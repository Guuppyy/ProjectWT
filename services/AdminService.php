<?php

namespace project\services;

use Exception;

class AdminService
{
    private string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = realpath($basePath);
        if ($this->basePath === false) {
            throw new Exception("Base path does not exist");
        }
    }

    public function getFullPath(string $relativePath = ''): string
    {
        $relativePath = trim($relativePath, '/\\');
        $fullPath = $this->basePath . '\\' . $relativePath;
        $fullPath = realpath($fullPath) ?: $this->basePath . '\\' . $relativePath;

        if (strpos($fullPath, $this->basePath) !== 0) {
            throw new Exception("Access denied: trying to access outside base directory");
        }

        return $fullPath;
    }

    public function listItems(string $path = ''): array
    {
        $fullPath = $this->getFullPath($path);
        $items = [];

        if (is_dir($fullPath)) {
            foreach (scandir($fullPath) as $item) {
                if ($item !== '.' && $item !== '..') {
                    $itemPath = $fullPath . '\\' . $item;
                    $items[] = [
                        'name' => $item,
                        'is_dir' => is_dir($itemPath),
                        'size' => is_dir($itemPath) ? '-' : $this->formatSize(filesize($itemPath)),
                        'modified' => date('Y-m-d H:i:s', filemtime($itemPath)),
                    ];
                }
            }
        }

        return $items;
    }

    public function createDirectory(string $path, string $dirName): bool
    {
        $dirName = preg_replace('/[^a-zA-Z0-9\-_]/', '', $dirName);
        $fullPath = $this->getFullPath($path) . '\\' . $dirName;

        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755);
            return true;
        }
        return false;
    }

    public function deleteFile(string $path, string $fileName): bool
    {
        $fullPath = $this->getFullPath($path) . '\\' . $fileName;

        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }

    public function deleteDirectory(string $path, string $dirName): bool
    {
        $fullPath = $this->getFullPath($path) . '\\' . $dirName;

        if (file_exists($fullPath) && is_dir($fullPath)) {
            $isEmpty = count(scandir($fullPath)) == 2;
            if ($isEmpty) {
                return rmdir($fullPath);
            }
        }
        return false;
    }

    public function uploadFile(string $path, array $file): bool
    {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $fileName = basename($file['name']);
            $targetPath = $this->getFullPath($path) . '\\' . $fileName;

            if (!file_exists($targetPath)) {
                return move_uploaded_file($file['tmp_name'], $targetPath);
            }
        }
        return false;
    }

    public function getBreadcrumbs(string $path = ''): array
    {
        $pathParts = explode('\\', trim($path, '\\'));
        $breadcrumbs = [];
        $accumulatedPath = '';

        foreach ($pathParts as $part) {
            if (!empty($part)) {
                $accumulatedPath .= $part . '\\';
                $breadcrumbs[] = [
                    'name' => $part,
                    'path' => $accumulatedPath,
                ];
            }
        }

        return $breadcrumbs;
    }

    private function formatSize(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return '1 byte';
        } else {
            return '0 bytes';
        }
    }
}
