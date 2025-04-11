<?php
$baseDir = realpath(__DIR__ . '/../view'); // Только папка /view
$requestedPath = realpath($baseDir . '/' . (isset($_GET['path']) ? $_GET['path'] : ''));


if (strpos($requestedPath, $baseDir) !== 0 || !is_dir($requestedPath)) {
    die("Access denied.");
}

$files = scandir($requestedPath);
$current = str_replace($baseDir, '', $requestedPath);
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
    <section class="hero">
        <h2>📁 Файлы: <?= htmlspecialchars($current) ?></h2>
        <?php if ($current !== ''): ?>
            <a class="btn" href="?path=<?= urlencode(dirname($current)) ?>">⬅ Назад</a>
        <?php endif; ?>
    </section>

    <section class="Movies">
        <ul class="Movie-list">
            <?php foreach ($files as $file): ?>
                <?php if ($file === '.') continue; ?>
                <li class="Movie">
                    <?php
                    $fullPath = $requestedPath . '/' . $file;
                    $relativePath = trim($current . '/' . $file, '/');
                    ?>
                    <?php if (is_dir($fullPath)): ?>
                        📁 <a href="?path=<?= urlencode($relativePath) ?>"><?= $file ?></a>
                    <?php else: ?>
                        📄 <?= $file ?>
                        [<a href="file_manager.php?action=view&file=<?= urlencode($relativePath) ?>">просмотр</a>]
                                                                                                                 [<a href="file_manager.php?action=edit&file=<?= urlencode($relativePath) ?>">редактировать</a>]
                                                                                                                                                                                                               [<a href="file_manager.php?action=download&file=<?= urlencode($relativePath) ?>">скачать</a>]
                                                                                                                                                                                                                                                                                                           [<a href="file_manager.php?action=delete&file=<?= urlencode($relativePath) ?>" onclick="return confirm('Удалить?')">удалить</a>]
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="about">
        <form action="file_manager.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="path" value="<?= htmlspecialchars($current) ?>">
            <input type="file" name="upload">
            <button class="btn" type="submit" name="action" value="upload">Загрузить</button>
        </form>
    </section>
</main>

</body>
</html>

