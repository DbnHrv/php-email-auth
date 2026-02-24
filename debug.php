<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Panel - Email Authentication System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        h1 {
            color: #4ec9b0;
            margin-bottom: 20px;
            border-bottom: 2px solid #4ec9b0;
            padding-bottom: 10px;
        }
        
        .debug-section {
            background: #252526;
            border: 1px solid #3e3e42;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .debug-section h2 {
            color: #569cd6;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .status {
            padding: 10px;
            border-radius: 3px;
            margin: 10px 0;
            font-weight: bold;
        }
        
        .status.ok {
            background: #0d47a1;
            color: #4fc3f7;
        }
        
        .status.error {
            background: #b71c1c;
            color: #ff8a80;
        }
        
        .status.warning {
            background: #f57f17;
            color: #ffe082;
        }
        
        .code-block {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 3px;
            overflow-x: auto;
            border: 1px solid #3e3e42;
            margin: 10px 0;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        
        .btn {
            background: #4ec9b0;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin: 10px 5px 10px 0;
        }
        
        .btn:hover {
            background: #3fa399;
        }
        
        .btn-danger {
            background: #d16969;
        }
        
        .btn-danger:hover {
            background: #c15858;
        }
        
        .file-content {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 3px;
            border: 1px solid #3e3e42;
            margin-top: 10px;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .back-link {
            color: #569cd6;
            text-decoration: none;
            margin-bottom: 20px;
            display: inline-block;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Back to Application</a>
        <h1>🔧 Debug Panel - Email Authentication System</h1>

        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // Check PHP Version
        echo '<div class="debug-section">';
        echo '<h2>📋 PHP Information</h2>';
        echo '<div class="status ok">PHP Version: ' . phpversion() . '</div>';
        echo '</div>';

        // Check Database Connection
        echo '<div class="debug-section">';
        echo '<h2>🗄️ Database Connection</h2>';
        
        require_once 'config/db.php';
        
        if ($conn && !$conn->connect_error) {
            echo '<div class="status ok">✓ Database Connected Successfully</div>';
            
            // Check users table
            $result = $conn->query("SHOW TABLES LIKE 'users'");
            if ($result && $result->num_rows > 0) {
                echo '<div class="status ok">✓ Users Table Exists</div>';
                
                // Count users
                $count = $conn->query("SELECT COUNT(*) as total FROM users");
                if ($count) {
                    $row = $count->fetch_assoc();
                    echo '<div class="status ok">✓ Total Users: ' . $row['total'] . '</div>';
                }
            } else {
                echo '<div class="status error">✗ Users Table NOT FOUND - Please run DATABASE_SETUP.sql</div>';
            }
        } else {
            echo '<div class="status error">✗ Database Connection Failed</div>';
            echo '<div class="code-block">' . $conn->connect_error . '</div>';
        }
        echo '</div>';

        // Check File Permissions and Logs
        echo '<div class="debug-section">';
        echo '<h2>📁 File System & Logs</h2>';
        
        $base_path = __DIR__;
        $logs_dir = $base_path . '/logs';
        $logs_file = $logs_dir . '/emails.log';
        
        // Check logs directory
        if (is_dir($logs_dir)) {
            echo '<div class="status ok">✓ Logs Directory Exists</div>';
            
            if (is_writable($logs_dir)) {
                echo '<div class="status ok">✓ Logs Directory is Writable</div>';
            } else {
                echo '<div class="status warning">⚠ Logs Directory is NOT Writable</div>';
            }
        } else {
            echo '<div class="status error">✗ Logs Directory Does NOT Exist</div>';
            echo '<p>Path: ' . $logs_dir . '</p>';
        }
        
        // Check logs file
        if (file_exists($logs_file)) {
            echo '<div class="status ok">✓ Logs File Exists</div>';
            $file_size = filesize($logs_file);
            echo '<div class="status ok">Logs File Size: ' . number_format($file_size) . ' bytes</div>';
            
            if (is_writable($logs_file)) {
                echo '<div class="status ok">✓ Logs File is Writable</div>';
            } else {
                echo '<div class="status warning">⚠ Logs File is NOT Writable</div>';
            }
            
            echo '<h3 style="margin-top: 20px; color: #569cd6;">Logs File Content:</h3>';
            $content = file_get_contents($logs_file);
            if (!empty($content)) {
                echo '<div class="file-content">' . htmlspecialchars($content) . '</div>';
                echo '<form method="POST" style="margin-top: 10px;">';
                echo '<button type="submit" name="clear_logs" value="1" class="btn btn-danger">🗑️ Clear Logs File</button>';
                echo '</form>';
            } else {
                echo '<div class="status warning">⚠ Logs File is EMPTY</div>';
            }
        } else {
            echo '<div class="status error">✗ Logs File Does NOT Exist</div>';
            echo '<p>Expected at: ' . $logs_file . '</p>';
            
            if (is_writable($logs_dir)) {
                echo '<p><strong>The logs file should be created automatically on first registration.</strong></p>';
            }
        }
        echo '</div>';

        // Check Required Files
        echo '<div class="debug-section">';
        echo '<h2>✓ Required Files Status</h2>';
        
        $files_to_check = [
            'config/db.php' => 'Database Configuration',
            'config/config.php' => 'App Configuration',
            'controllers/AuthController.php' => 'Auth Controller',
            'models/User.php' => 'User Model',
            'views/register.php' => 'Register View',
            'views/login.php' => 'Login View',
            'views/verify.php' => 'Verify View',
            'views/home.php' => 'Home View',
            'public/style.css' => 'Stylesheet',
            'logs.php' => 'Logs Viewer'
        ];
        
        foreach ($files_to_check as $file => $name) {
            $full_path = $base_path . '/' . $file;
            if (file_exists($full_path)) {
                echo '<div class="status ok">✓ ' . $name . ' (' . $file . ')</div>';
            } else {
                echo '<div class="status error">✗ ' . $name . ' (' . $file . ') - MISSING</div>';
            }
        }
        echo '</div>';

        // Test Email Logging Function
        echo '<div class="debug-section">';
        echo '<h2>📧 Test Email Logging</h2>';
        
        echo '<form method="POST">';
        echo '<label for="test_email">Test Email:</label><br>';
        echo '<input type="email" id="test_email" name="test_email" value="test@example.com" required><br>';
        echo '<button type="submit" name="test_log" value="1" class="btn">🧪 Test Email Logging</button>';
        echo '</form>';
        
        if (isset($_POST['test_log']) && $_POST['test_log'] == 1) {
            $test_email = $_POST['test_email'] ?? 'test@example.com';
            $test_link = 'http://localhost/php-email-auth/index.php?action=verify&code=TEST123456789';
            
            $log_content = "
================================================================================
[" . date('Y-m-d H:i:s') . "] TEST EMAIL - Sent to: $test_email
================================================================================
Subject: Test Email

VERIFICATION LINK:
$test_link

================================================================================

";
            
            if (is_writable($logs_dir)) {
                file_put_contents($logs_file, $log_content, FILE_APPEND);
                echo '<div class="status ok">✓ Test email logged successfully!</div>';
                echo '<p>Check the <a href="logs.php" target="_blank">logs page</a> to verify.</p>';
            } else {
                echo '<div class="status error">✗ Cannot write to logs directory</div>';
            }
        }
        echo '</div>';

        // Clear logs functionality
        if (isset($_POST['clear_logs']) && $_POST['clear_logs'] == 1) {
            if (file_exists($logs_file)) {
                file_put_contents($logs_file, '');
                echo '<script>alert("Logs cleared!"); location.reload();</script>';
            }
        }
        
        // Database Query Test
        echo '<div class="debug-section">';
        echo '<h2>🔍 Recent Registrations</h2>';
        
        $result = $conn->query("SELECT email, is_verified, created_at FROM users ORDER BY created_at DESC LIMIT 5");
        if ($result && $result->num_rows > 0) {
            echo '<table style="width: 100%; border-collapse: collapse;">';
            echo '<tr style="border-bottom: 1px solid #3e3e42; padding: 10px;">';
            echo '<th style="text-align: left; padding: 10px; color: #569cd6;">Email</th>';
            echo '<th style="text-align: left; padding: 10px; color: #569cd6;">Verified</th>';
            echo '<th style="text-align: left; padding: 10px; color: #569cd6;">Created</th>';
            echo '</tr>';
            
            while ($row = $result->fetch_assoc()) {
                echo '<tr style="border-bottom: 1px solid #3e3e42;">';
                echo '<td style="padding: 10px;">' . htmlspecialchars($row['email']) . '</td>';
                echo '<td style="padding: 10px;">' . ($row['is_verified'] ? '✓ Yes' : '✗ No') . '</td>';
                echo '<td style="padding: 10px;">' . $row['created_at'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<div class="status warning">No registrations found yet</div>';
        }
        echo '</div>';
        ?>
    </div>
</body>
</html>
