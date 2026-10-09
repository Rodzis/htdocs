<?php
session_start();

if (!isset($_SESSION['signup_token'])) {
    $_SESSION['signup_token'] = bin2hex(random_bytes(32));
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$firstName = '';
$lastName = '';
$email = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = is_string($_POST['first_name'] ?? null) ? trim($_POST['first_name']) : '';
    $lastName = is_string($_POST['last_name'] ?? null) ? trim($_POST['last_name']) : '';
    $email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
    $passwordConfirm = is_string($_POST['password_confirm'] ?? null) ? $_POST['password_confirm'] : '';
    $token = is_string($_POST['signup_token'] ?? null) ? $_POST['signup_token'] : '';

    if (!hash_equals($_SESSION['signup_token'], $token)) {
        $error = 'Your session expired. Please refresh the page and try again.';
    } elseif ($firstName === '' || $lastName === '' || $email === '' || $password === '' || $passwordConfirm === '') {
        $error = 'Please fill in all fields.';
    } elseif (strlen($firstName) > 100 || strlen($lastName) > 100) {
        $error = 'Names must be 100 characters or fewer.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8 || strlen($password) > 72) {
        $error = 'Password must be between 8 and 72 bytes.';
    } elseif ($password !== $passwordConfirm) {
        $error = 'Passwords do not match.';
    } else {
        try {
            $pdo = new PDO(
                'mysql:host=localhost;dbname=dotolist;charset=utf8mb4',
                'root',
                '',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

            $check = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
            $check->execute(['email' => $email]);
            if ($check->fetchColumn() !== false) {
                $error = 'An account with that email already exists.';
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)'
                );
                $stmt->execute([
                    'name' => $firstName . ' ' . $lastName,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role' => 'user',
                ]);
                unset($_SESSION['signup_token']);
                header('Location: login.php?registered=1');
                exit;
            }
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000' && ($exception->errorInfo[1] ?? null) === 1062) {
                $error = 'An account with that email already exists.';
            } else {
                error_log('Signup database error: ' . $exception->getMessage());
                $error = 'We could not create your account right now. Please try again later.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign Up</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="dark-theme">
        <div class="container">
            <h1 class="title">Sign Up</h1>
            <?php if ($error !== ''): ?>
                <p class="form-message" role="alert"><?= escape($error) ?></p>
            <?php endif; ?>
            <form action="signup.php" method="POST" class="signup-form">
                    <input type="hidden" name="signup_token" value="<?= escape($_SESSION['signup_token']) ?>">
                    <div class="form-row-double">
                        <div class="input-group">
                            <input type="text" name="first_name" class="input-field" placeholder="Your name" maxlength="100" autocomplete="given-name" value="<?= escape($firstName) ?>" required>
                        </div>
                        <div class="input-group">
                            <input type="text" name="last_name" class="input-field" placeholder="Last name" maxlength="100" autocomplete="family-name" value="<?= escape($lastName) ?>" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <input type="email" name="email" class="input-field" placeholder="Enter email" maxlength="254" autocomplete="email" value="<?= escape($email) ?>" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" class="input-field" placeholder="Password (8-72 bytes)" minlength="8" maxlength="72" autocomplete="new-password" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password_confirm" class="input-field" placeholder="Rewrite your password" minlength="8" maxlength="72" autocomplete="new-password" required>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="submit-btn">Create account</button>
                    </div>
            </form>
            <p class="aorna">Already have an account? <a href="login.php">Log in here</a>.</p>
        </div>
    </body>
</html>
