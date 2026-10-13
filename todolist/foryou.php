<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$hasProjects = false;
$projectCheckError = false;

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=dotolist;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $stmt = $pdo->prepare(
        'SELECT 1 FROM project_assignments WHERE user_id = :user_id LIMIT 1'
    );
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $hasProjects = $stmt->fetchColumn() !== false;
} catch (PDOException $exception) {
    error_log('For You project lookup error: ' . $exception->getMessage());
    $projectCheckError = true;
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>For You</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="for-you-page sidebar-collapsed">
        <?php require __DIR__ . '/topbar.php'; ?>
        <aside class="sidebar" id="sidebar" aria-label="Main navigation" inert>
            <nav>
                <a class="sidebar-link active" href="foryou.php">For you</a>
                <a class="sidebar-link" href="#">Starred</a>
                <a class="sidebar-link" href="#">Projects</a>
                <a class="sidebar-link" href="#">Calendar</a>
            </nav>
        </aside>
        <main class="page-content">
            <h1 class="title">For You</h1>
            <?php if ($projectCheckError): ?>
                <p class="aorna" role="alert">We could not check your projects. Please try again later.</p>
            <?php elseif (!$hasProjects): ?>
                <p class="aorna">You have no projects ;,(</p>
            <?php else: ?>
                <p class="aorna">Welcome to the For You page!</p>
            <?php endif; ?>
        </main>
        <script src="script.js"></script>
    </body>
</html>
