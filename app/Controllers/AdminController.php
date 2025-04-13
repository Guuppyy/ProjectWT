<?php
namespace project\app\Controllers;

use project\services\AdminService;
use project\template\MyTemplate;

class AdminController
{
    private AdminService $adminService;
    private MyTemplate $template;
    private const BASE_PATH = '/project';

    public function __construct()
    {
        $this->adminService = new AdminService(__DIR__.'/../../public');
        $this->template = new MyTemplate();
    }

    public function index(): void
    {
        $currentDir = $_GET['path'] ?? '';
        $items = $this->adminService->listItems($currentDir);
        $breadcrumbs = $this->adminService->getBreadcrumbs($currentDir);

        echo $this->template->view('D:\WebData\project\admin\html\file_manager.html', [
            'items' => $items,
            'currentDir' => $currentDir,
            'breadcrumbs' => $breadcrumbs,
            'basePath' => self::BASE_PATH
        ]);
    }

    public function uploadFile(): void
    {
        $currentDir = $_POST['currentDir'] ?? '';

        if (isset($_FILES['file'])) {
            $this->adminService->uploadFile($currentDir, $_FILES['file']);
        }

        header("Location: ".self::BASE_PATH."/admin?path=".urlencode($currentDir));
        exit;
    }

    public function deleteFile(): void
    {
        $currentDir = $_GET['currentDir'] ?? '';
        $fileName = $_GET['fileName'] ?? '';

        $this->adminService->deleteFile($currentDir, $fileName);
        header("Location: ".self::BASE_PATH."/admin?path=".urlencode($currentDir));
        exit;
    }

    public function createDirectory(): void
    {
        $currentDir = $_POST['currentDir'] ?? '';
        $dirName = $_POST['dirName'] ?? '';

        $this->adminService->createDirectory($currentDir, $dirName);
        header("Location: ".self::BASE_PATH."/admin?path=".urlencode($currentDir));
        exit;
    }

    public function deleteDirectory(): void
    {
        $currentDir = $_GET['currentDir'] ?? '';
        $dirName = $_GET['dirName'] ?? '';

        $this->adminService->deleteDirectory($currentDir, $dirName);
        header("Location: ".self::BASE_PATH."/admin?path=".urlencode($currentDir));
        exit;
    }
}