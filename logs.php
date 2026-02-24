<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Logs - Verification Codes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .back-link {
            color: white;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .main-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .instructions {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            color: #1565c0;
        }
        
        .instructions h2 {
            margin-bottom: 10px;
            color: #1565c0;
        }
        
        .instructions ol {
            margin-left: 20px;
        }
        
        .instructions li {
            margin: 8px 0;
        }
        
        .no-logs {
            text-align: center;
            padding: 50px 20px;
            color: #999;
        }
        
        .verification-item {
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .email-address {
            color: #666;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .email-address strong {
            color: #333;
        }
        
        .timestamp {
            color: #999;
            font-size: 12px;
            margin-bottom: 15px;
        }
        
        .code-display {
            background: #667eea;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 15px;
        }
        
        .code-value {
            font-size: 24px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            letter-spacing: 3px;
            word-break: break-all;
            margin: 10px 0;
        }
        
        .copy-btn {
            background: #4CAF50;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
        }
        
        .copy-btn:hover {
            background: #45a049;
        }
        
        .link-section {
            background: #f0f4ff;
            border: 1px dashed #667eea;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        
        .verification-link {
            background: white;
            padding: 10px;
            border-radius: 4px;
            word-break: break-all;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            color: #667eea;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            border: 1px solid #ddd;
        }
        
        .action-buttons {
            text-align: center;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #e0e0e0;
        }
        
        .btn {
            background: #667eea;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            margin: 5px;
        }
        
        .btn-danger {
            background: #d16969;
        }
        
        .btn-secondary {
            background: #757575;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Back to Application</a>
        <h1>📧 Verification Codes - Email Logs</h1>

        <div class="main-card">
            <div class="instructions">
                <h2>📋 How to Use This Page</h2>
                <ol>
                    <li>Find your email address in the list below</li>
                    <li>Copy the verification code (click the code or "Copy" button)</li>
                    <li>Go to the <a href="index.php?action=verify" style="color: #0066cc; font-weight: 600;">Verification Page</a></li>
                    <li>Paste the code and click "Verify Code"</li>
                    <li>Your account will be verified!</li>
                </ol>
            </div>

            <?php
            $log_file = __DIR__ . '/logs/emails.log';

            if (file_exists($log_file) && filesize($log_file) > 0) {
                $content = file_get_contents($log_file);
                $entries = array_filter(array_map('trim', explode('================================================================================', $content)));
                
                // Reverse to show newest first
                $entries = array_reverse($entries);
                
                echo '<div style="margin-bottom: 20px;" id="entries">';
                
                foreach ($entries as $entry) {
                    if (empty(trim($entry))) continue;
                    
                    // Extract email
                    preg_match('/Email Sent to:\s*(.+?)(?:\n|$)/', $entry, $email_match);
                    $email = $email_match[1] ?? 'Unknown';
                    
                    // Extract timestamp
                    preg_match('/\[(.+?)\]/', $entry, $time_match);
                    $timestamp = $time_match[1] ?? 'Unknown';
                    
                    // Extract code - look for the highlighted code section
                    preg_match('/📌\s*VERIFICATION\s+CODE[^\n]*:\s*(\S+)/', $entry, $code_match);
                    if (!isset($code_match[1])) {
                        preg_match('/>>>?\s*(.+?)\s*<<?/', $entry, $code_match);
                    }
                    $code = isset($code_match[1]) ? trim($code_match[1]) : 'N/A';
                    
                    // Extract link
                    preg_match('/🔗\s*VERIFICATION\s+LINK[^\n]*:\s*(https?:\/\/.+?)(?:\n|$)/', $entry, $link_match);
                    $link = $link_match[1] ?? '';
                    
                    echo '<div class="verification-item">';
                    echo '<div class="email-address">📧 <strong>' . htmlspecialchars($email) . '</strong></div>';
                    echo '<div class="timestamp">🕐 ' . htmlspecialchars($timestamp) . '</div>';
                    
                    // Code display
                    echo '<div class="code-display">';
                    echo '<div style="font-size: 12px; opacity: 0.9; margin-bottom: 8px;">Your Verification Code:</div>';
                    echo '<div class="code-value">' . htmlspecialchars($code) . '</div>';
                    
                    if ($code !== 'N/A') {
                        echo '<button class="copy-btn" onclick="copyCode(this, \'' . addslashes($code) . '\')">📋 Copy Code</button>';
                    }
                    echo '</div>';
                    
                    // Link display
                    if ($link) {
                        echo '<div class="link-section">';
                        echo '<p style="margin-bottom: 8px;"><strong>Or click this link to verify directly:</strong></p>';
                        echo '<a href="' . htmlspecialchars($link) . '" class="verification-link" target="_blank">' . htmlspecialchars($link) . '</a>';
                        echo '</div>';
                    }
                    
                    echo '</div>';
                }
                
                echo '</div>';
                
                echo '<div class="action-buttons">';
                echo '<button class="btn btn-secondary" onclick="location.reload()">🔄 Refresh</button>';
                echo '<form method="POST" style="display: inline;">';
                echo '<button type="submit" name="clear_logs" value="1" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to clear all logs?\')">🗑️ Clear Logs</button>';
                echo '</form>';
                echo '</div>';
                
            } else {
                echo '<div class="no-logs">';
                echo '<p style="font-size: 24px;">📭 No verification codes yet</p>';
                echo '<p>When you register a new account, the verification code will appear here.</p>';
                echo '<p style="margin-top: 20px;"><a href="index.php?action=register" style="color: #667eea; font-weight: 600;">← Register now</a></p>';
                echo '</div>';
            }
            
            // Handle clear logs
            if (isset($_POST['clear_logs']) && $_POST['clear_logs'] == 1) {
                if (file_exists($log_file)) {
                    file_put_contents($log_file, '');
                    echo '<script>alert("Logs cleared!"); setTimeout(() => location.reload(), 500);</script>';
                }
            }
            ?>
        </div>
    </div>

    <script>
        function copyCode(button, code) {
            navigator.clipboard.writeText(code).then(() => {
                const originalText = button.textContent;
                button.textContent = '✓ Copied!';
                setTimeout(() => {
                    button.textContent = originalText;
                }, 2000);
            }).catch(err => {
                const textArea = document.createElement('textarea');
                textArea.value = code;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                button.textContent = '✓ Copied!';
                setTimeout(() => {
                    button.textContent = '📋 Copy Code';
                }, 2000);
            });
        }
    </script>
</body>
</html>
