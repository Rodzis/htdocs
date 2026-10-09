<?php
session_start();
$host     = 'localhost';
$db_name  = 'dotolist';
$db_user  = 'root';
$db_pass  = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs and trim whitespace
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        // Fetch the user by email using a prepared statement to prevent SQL Injection
        $stmt = $pdo->prepare("SELECT id, name, password, role FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 4. Verify password against the securely stored hash
        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID for security against session fixation
            session_regenerate_id(true);

            // 5. Store user information in the session
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role']; // Stores 'admin' or 'user'

            // 6. Redirect based on their role
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="dark-theme">
        <div class="container">
            <h1 class="title">Login</h1>
            <form action="login.php" method="POST" class="signup-form">
                <div class="input-group">
                    <input type="email" id="email" name="email" class="input-field" placeholder="Enter email" required>
                </div>

                <div class="input-group">
                    <input type="password" id="password" name="password" class="input-field" placeholder="Password" required>
                </div>

                <div class="button-container">
                    <button type="submit" class="submit-btn">Login</button>
                </div>
            </form>
            <p class="aorna">Don't have an account? <a href="signup.php">Sign up here</a>.</p>
        </div>
    </body>
</html>