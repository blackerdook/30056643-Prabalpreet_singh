<?php
session_start();
include "credentials.php"; 

$error = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $app_username && $password === $app_password) {
        $_SESSION['loggedin'] = true;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0a0a0a;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #d0d0d0;
        }
        .login-container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .header-title {
            color: #8b2f2b;
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            background-color: #2b2d2f;
            color: #e0e0e0;
            border: none;
            border-radius: 5px;
        }
        .btn-submit {
            background-color: #8b2f2b;
            color: white;
            font-weight: bold;
            border-radius: 30px;
            width: 100%;
            padding: 10px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            border: none;
        }
        .btn-submit:hover {
            background-color: #732724;
            box-shadow: 0 0 10px rgba(139, 47, 43, 0.7);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1 class="header-title">Login</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-submit">Login</button>
        </form>
    </div>
</body>
</html>
