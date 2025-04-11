<?php
// Определяем базовую директорию, с которой разрешены операции
$baseDir = realpath(__DIR__ . '/../view');

// Получаем действие и путь к файлу/директории
$action = $_GET['action'] ?? $_POST['action'] ?? '';
$file = $_GET['file'] ?? $_POST['file'] ?? '';
$path = $_GET['path'] ?? $_POST['path'] ?? '';

/**
 * Функция для получения абсолютного пути относительно $baseDir
 */
function getFullPath($relativePath) {
    global $baseDir;
    $fullPath = realpath($baseDir . '/' . $relativePath);
    if ($fullPath === false || strpos($fullPath, $baseDir) !== 0) {
        die("Access denied");
    }
    return $fullPath;
}

switch ($action) {
    // Просмотр файла
    case 'view':
        if (empty($file)) {
            die("No file specified.");
        }
        $fullPath = getFullPath($file);
        if (!is_file($fullPath)) {
            die("Not a valid file.");
        }
        // Определяем MIME-тип (для простоты выводим как текст)
        header("Content-Type: text/plain");
        readfile($fullPath);
        break;

    // Редактирование файла
    case 'edit':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (empty($file)) {
                die("No file specified.");
            }
            $fullPath = getFullPath($file);
            if (!is_file($fullPath)) {
                die("Not a valid file.");
            }
            $content = file_get_contents($fullPath);
            ?>
            <!DOCTYPE html>
            <html lang="ru">
            <head>
                <meta charset="UTF-8">
                <title>Редактирование <?= htmlspecialchars($file) ?></title>
            </head>
            <body>
            <h2>Редактирование файла: <?= htmlspecialchars($file) ?></h2>
            <form action="file_manager.php" method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="file" value="<?= htmlspecialchars($file) ?>">
                <textarea name="content" rows="20" cols="80"><?= htmlspecialchars($content) ?></textarea>
                <br>
                <button type="submit">Сохранить</button>
            </form>
            <a href="index.php?path=<?= urlencode(dirname($file)) ?>">Назад</a>
            </body>
            </html>
            <?php
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($file)) {
                die("No file specified.");
            }
            $fullPath = getFullPath($file);
            if (file_put_contents($fullPath, $_POST['content']) === false) {
                die("Не удалось сохранить файл.");
            }
            header("Location: index.php?path=" . urlencode(dirname($file)));
        }
        break;

    // Скачивание файла
    case 'download':
        if (empty($file)) {
            die("No file specified.");
        }
        $fullPath = getFullPath($file);
        if (!is_file($fullPath)) {
            die("Not a valid file.");
        }
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($fullPath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fullPath));
        readfile($fullPath);
        exit;

    // Удаление файла или папки
    case 'delete':
        if (empty($file)) {
            die("No file specified.");
        }
        $fullPath = getFullPath($file);
        if (is_dir($fullPath)) {
            // Рекурсивное удаление каталога
            function deleteDir($dir) {
                if (!file_exists($dir)) return true;
                if (!is_dir($dir)) return unlink($dir);
                foreach (scandir($dir) as $item) {
                    if ($item == '.' || $item == '..') continue;
                    if (!deleteDir($dir . DIRECTORY_SEPARATOR . $item)) return false;
                }
                return rmdir($dir);
            }
            if (deleteDir($fullPath)) {
                header("Location: index.php?path=" . urlencode(dirname($file)));
            } else {
                die("Не удалось удалить папку.");
            }
        } else {
            if (unlink($fullPath)) {
                header("Location: index.php?path=" . urlencode(dirname($file)));
            } else {
                die("Не удалось удалить файл.");
            }
        }
        break;

    // Загрузка файла
    case 'upload':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Получаем директорию для загрузки
            $uploadDir = getFullPath($_POST['path'] ?? '');
            if (!is_dir($uploadDir)) {
                die("Некорректная директория для загрузки.");
            }
            if ($_FILES['upload']['error'] == UPLOAD_ERR_OK) {
                $tmpName = $_FILES['upload']['tmp_name'];
                $name = basename($_FILES['upload']['name']);
                $dest = $uploadDir . DIRECTORY_SEPARATOR . $name;
                if (move_uploaded_file($tmpName, $dest)) {
                    header("Location: index.php?path=" . urlencode($_POST['path']));
                } else {
                    die("Ошибка при загрузке файла.");
                }
            } else {
                die("Ошибка загрузки файла.");
            }
        }
        break;

    default:
        die("Неизвестное действие.");
}
