<?php
session_start();

require_once 'config/db.php';
require_once 'config/email.php';
require_once 'controllers/AuthController.php';

$auth = new AuthController($conn);
$action = $_GET['action'] ?? 'login';
$message = null;

switch ($action) {
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $auth->register($_POST['email'] ?? '', $_POST['password'] ?? '');
        }
        include 'views/register.php';
        break;

    case 'verify':
        $message = null;
        $show_resend_form = false;
        
        // Handle POST form submission for verification code
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['code'])) {
                // User submitting verification code
                $code = $_POST['code'] ?? '';
                $message = $auth->verify($code);
            } elseif (isset($_POST['email'])) {
                // User requesting resend
                $email = $_POST['email'] ?? '';
                $message = 'Resend verification email functionality not yet implemented';}
        }
        // Handle GET from email link
        elseif (isset($_GET['code'])) {
            $code = $_GET['code'];
            $message = $auth->verify($code);
            // If verified from link, show success and auto-refresh
            if (strpos($message, 'successful') !== false) {
                // Auto-redirect to login after 3 seconds
                header("refresh:3;url=index.php?action=login");
            }
        } else {
            // Show form for email input (to get resend option)
            $show_resend_form = true;
        }
        
        include 'views/verify.php';
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $auth->login($_POST['email'] ?? '', $_POST['password'] ?? '');
        }
        include 'views/login.php';
        break;

    case 'home':
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit();
        }
        include 'views/home.php';
        break;

    case 'logout':
        $auth->logout();
        break;

    default:
        header("Location: index.php?action=login");
        exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/style.css">
    <title>Document</title>
</head>
<body>
    
</body>
</html>