<?php
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
        <header class="top-bar">
            <button class="sidebar-toggle" type="button" aria-label="Open sidebar" aria-expanded="false" aria-controls="sidebar"><img src="resources/expand-close.jpg" alt=""></button>
            <input class="search-input" type="search" placeholder="search" aria-label="Search">
            <button class="create-project-button" type="button">+ Create Project</button>
            <button class="profile-button" type="button" aria-label="Profile"><img src="resources/profile.png" alt=""></button>
        </header>
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
            <p class="aorna">Welcome to the For You page! This page is accessible to all users.</p>
            <p class="aorna"><a class="a" href="dashboard.php">Go to Dashboard</a></p>
            <p class="aorna"><a class="a" href="logout.php">Logout</a></p>
        </main>
        <script src="script.js"></script>
    </body>
</html>
