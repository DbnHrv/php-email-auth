<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Registration - Email Auth</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #333; }
        .form-group {
            margin: 15px 0;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }
        button:hover {
            background: #45a049;
        }
        .status {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
            font-weight: bold;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #0066cc;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Back to Main Application</a>
        
        <h1>Test Registration System</h1>
        <p>Use this page to test the registration process step by step.</p>

        <?php
        // Enable error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // Include required files
        require_once 'config/db.php';
        require_once 'models/User.php';

        // Check if form was submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Validate input
            if (empty($email) || empty($password)) {
                echo '<div class="status error">❌ Email and password are required!</div>';
            } else {
                echo '<div class="status info">Testing registration for: ' . htmlspecialchars($email) . '</div>';
                
                // Create User instance and attempt registration
                $user = new User($conn);
                
                // Call the register method
                $result = $user->register($email, $password);
                
                // Display result
                if (is_array($result) && isset($result['success']) && $result['success']) {
                    echo '<div class="status success">✅ ' . htmlspecialchars($result['message']) . '</div>';
                    echo '<div class="status info">';
                    echo '<strong>Next Steps:</strong>';
                    echo '<ol>';
                    echo '<li>Check the <a href="logs.php" target="_blank" style="color: #0066cc;">email logs</a></li>';
                    echo '<li>Copy the verification link</li>';
                    echo '<li>Paste the link in your browser to verify</li>';
                    echo '</ol>';
                    echo '</div>';
                } else {
                    echo '<div class="status error">❌ ' . htmlspecialchars($result) . '</div>';
                }
            }
        }
        ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" placeholder="test@example.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="At least 6 characters" minlength="6" required>
            </div>
            
            <button type="submit">🧪 Test Registration</button>
        </form>

        <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">
        
        <h2>System Check</h2>
        
        <?php
        // Quick system checks
        echo '<h3>Database Status:</h3>';
        
        if ($conn && !$conn->connect_error) {
            echo '<div class="status success">✓ Database connected</div>';
            
            // Check users table
            $result = $conn->query("SHOW TABLES LIKE 'users'");
            if ($result && $result->num_rows > 0) {
                echo '<div class="status success">✓ Users table exists</div>';
                
                // List last 3 registrations
                $users = $conn->query("SELECT email, is_verified, created_at FROM users ORDER BY created_at DESC LIMIT 3");
                if ($users && $users->num_rows > 0) {
                    echo '<p><strong>Last registrations:</strong></p>';
                    while ($row = $users->fetch_assoc()) {
                        $status = $row['is_verified'] ? '✓ Verified' : '✗ Not Verified';
                        echo '<div style="padding: 10px; background: #f9f9f9; margin: 5px 0; border-radius: 4px;">';
                        echo htmlspecialchars($row['email']) . ' - ' . $status . ' (' . $row['created_at'] . ')';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="status info">ℹ No users registered yet</div>';
                }
            } else {
                echo '<div class="status error">✗ Users table not found - Run DATABASE_SETUP.sql</div>';
            }
        } else {
            echo '<div class="status error">✗ Database connection failed</div>';
        }
        
        // Check logs directory
        echo '<h3 style="margin-top: 20px;">Logs Directory Status:</h3>';
        $logs_dir = __DIR__ . '/logs';
        $logs_file = $logs_dir . '/emails.log';
        
        if (is_dir($logs_dir)) {
            echo '<div class="status success">✓ Logs directory exists</div>';
            if (is_writable($logs_dir)) {
                echo '<div class="status success">✓ Logs directory is writable</div>';
            } else {
                echo '<div class="status error">✗ Logs directory is not writable</div>';
            }
            
            if (file_exists($logs_file)) {
                $size = filesize($logs_file);
                echo '<div class="status success">✓ Logs file exists (' . number_format($size) . ' bytes)</div>';
            } else {
                echo '<div class="status info">ℹ Logs file will be created on first registration</div>';
            }
        } else {
            echo '<div class="status warning">⚠ Logs directory does not exist at: ' . $logs_dir . '</div>';
            echo '<p>It will be created automatically on first registration.</p>';
        }
        ?>
    </div>
</body>
</html>
