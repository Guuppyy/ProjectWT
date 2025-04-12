<?php
$baseDir = realpath(__DIR__ . '/../view'); // Только папка /view
$requestedPath = realpath($baseDir . '/' . (isset($_GET['path']) ? $_GET['path'] : ''));

if (strpos($requestedPath, $baseDir) !== 0 || !is_dir($requestedPath)) {
    die("Access denied.");
}

$files = scandir($requestedPath); // Список файлов и папок в текущей директории
$current = str_replace($baseDir, '', $requestedPath); // Относительный путь к текущей папке
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Файловый менеджер</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Файловый менеджер</h1>
</header>

<main>
    <section class="navigation">
        <h2>📁 Директория: <?= htmlspecialchars($current) ?></h2>
        <?php if ($current !== ''): ?>
            <a class="btn" href="?path=<?= urlencode(dirname($current)) ?>">⬅ Назад</a>
        <?php endif; ?>
    </section>

    <section class="file-list-section">
        <ul class="file-items-list">
            <?php foreach ($files as $file): ?>
                <?php if ($file === '.') continue; ?>
                <li class="file-item">
                    <?php
                    $fullPath = $requestedPath . '/' . $file;
                    $relativePath = trim($current . '/' . $file, '/');
                    ?>
                    <?php if (is_dir($fullPath)): ?>
                        📁 <a href="?path=<?= urlencode($relativePath) ?>"><?= $file ?></a>
                    <?php else: ?>
                        📄 <?= $file ?>
                        [<a href="file_manager.php?action=view&file=<?= urlencode($relativePath) ?>">Просмотр</a>]
                                                                                                                 [<a href="file_manager.php?action=edit&file=<?= urlencode($relativePath) ?>">Редактировать</a>]
                                                                                                                                                                                                               [<a href="file_manager.php?action=download&file=<?= urlencode($relativePath) ?>">Скачать</a>]
                                                                                                                                                                                                                                                                                                           [<a href="file_manager.php?action=delete&file=<?= urlencode($relativePath) ?>" onclick="return confirm('Удалить?')">Удалить</a>]
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="upload-section">
        <form action="file_manager.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="path" value="<?= htmlspecialchars($current) ?>">
            <input type="file" name="upload">
            <button class="btn" type="submit" name="action" value="upload">Загрузить файл</button>
        </form>
    </section>
</main>

</body>
</html>
