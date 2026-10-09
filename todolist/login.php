<?php
session_start();
$error = '';
$email = '';

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=dotolist;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        error_log('[todolist login] Sign-in request received.');
        $email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';
        $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

        if ($email !== '' && $password !== '') {
            $stmt = $pdo->prepare('SELECT id, name, password, role FROM users WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log('[todolist login] User lookup completed: ' . ($user ? 'account found.' : 'account not found.'));

            if ($user && password_verify($password, $user['password'])) {
                error_log('[todolist login] Password verified.');
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                error_log('[todolist login] Session created; redirecting to foryou.php.');
                header('Location: foryou.php');
                exit;
            }

            if ($user) {
                error_log('[todolist login] Password verification failed.');
            }
            $error = 'Invalid email or password.';
        } else {
            error_log('[todolist login] Email or password field was empty.');
            $error = 'Please fill in all fields.';
        }
    }
} catch (PDOException $exception) {
    error_log('Login database error: ' . $exception->getMessage());
    $error = 'We could not connect to the database. Please try again later.';
}

$registered = isset($_GET['registered']);
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
            <?php if ($registered): ?>
                <p class="form-message">Your account has been created. Please log in.</p>
            <?php endif; ?>
            <?php if ($error !== ''): ?>
                <p class="form-message" role="alert"><?= htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <?php endif; ?>
            <form action="login.php" method="POST" class="signup-form">
                <div class="input-group">
                    <input type="email" id="email" name="email" class="input-field" placeholder="Enter email" value="<?= htmlspecialchars($email, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" required>
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