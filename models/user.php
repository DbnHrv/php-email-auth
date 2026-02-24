<?php
require_once __DIR__ . '/../config/email.php';

class User
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /* REGISTER USER */
    public function register($email, $password)
    {
        // Validate email and password
        if (empty($email) || empty($password)) {
            return "Email and password are required!";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format!";
        }

        if (strlen($password) < 6) {
            return "Password must be at least 6 characters!";
        }

        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            return "Email already registered!";
        }
        $stmt->close();

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $verificationCode = bin2hex(random_bytes(16));
        $created_at = date('Y-m-d H:i:s');

        $stmt = $this->conn->prepare(
            "INSERT INTO users (email, password, verification_code, is_verified, created_at)
             VALUES (?, ?, ?, 0, ?)"
        );
        $stmt->bind_param("ssss", $email, $hashedPassword, $verificationCode, $created_at);

        if ($stmt->execute()) {
            $this->sendVerificationEmail($email, $verificationCode);
            return ["success" => true, "message" => "Registration successful! Check your email to verify your account."];
        } else {
            return "Registration failed.";
        }
    }

    /* VERIFY ACCOUNT */
    public function verify($code)
    {
        if (empty($code)) {
            return "Invalid verification code!";
        }

        $stmt = $this->conn->prepare(
            "SELECT id, email, is_verified FROM users WHERE verification_code = ?"
        );
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return "Invalid verification code!";
        }

        $user = $result->fetch_assoc();

        if ($user['is_verified']) {
            return "Email already verified! <a href='index.php?action=login'>Login here</a>";
        }

        $stmt = $this->conn->prepare(
            "UPDATE users SET is_verified = 1, verification_code = NULL WHERE id = ?"
        );
        $stmt->bind_param("i", $user['id']);

        if ($stmt->execute()) {
            return "Email verified successfully! <a href='index.php?action=login'>Login here</a>";
        } else {
            return "Error during verification!";
        }
    }

    /* RESEND VERIFICATION EMAIL */
    public function resendVerificationEmail($email)
    {
        // Validate email
        if (empty($email)) {
            return "Email is required!";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format!";
        }

        // Check if email exists
        $stmt = $this->conn->prepare(
            "SELECT id, email, is_verified, verification_code FROM users WHERE email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return "Email not found! Please register first.";
        }

        $user = $result->fetch_assoc();

        // Check if already verified
        if ($user['is_verified']) {
            return "Your email is already verified! You can login now.";
        }

        // Generate new verification code
        $newCode = bin2hex(random_bytes(16));

        // Update verification code in database
        $stmt = $this->conn->prepare(
            "UPDATE users SET verification_code = ? WHERE id = ?"
        );
        $stmt->bind_param("si", $newCode, $user['id']);

        if ($stmt->execute()) {
            // Send new verification email
            $this->sendVerificationEmail($email, $newCode);
            return ["success" => true, "message" => "Verification email sent! Check your email (including spam folder) for the verification code."];
        } else {
            return "Failed to resend verification email. Please try again.";
        }
    }

    /* LOGIN USER */
    public function login($email, $password)
    {
        if (empty($email) || empty($password)) {
            return "Email and password are required!";
        }

        $stmt = $this->conn->prepare("SELECT id, email, password, is_verified FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return "Email not found!";
        }

        $user = $result->fetch_assoc();

        if (!$user['is_verified']) {
            return "Please verify your email first!";
        }

        if (!password_verify($password, $user['password'])) {
            return "Incorrect password!";
        }

        return ["email" => $user['email'], "id" => $user['id']];
    }

    /* SEND VERIFICATION EMAIL */
    private function sendVerificationEmail($email, $code)
    {
        $verify_link = VERIFY_URL . $code;
        $subject = "Email Verification - " . APP_NAME;

        $message = "
        <html>
        <head><title>Email Verification</title></head>
        <body style='font-family: Arial, sans-serif; color: #333; background-color: #f5f5f5; margin: 0; padding: 20px;'>
            <div style='max-width: 600px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                <h2 style='color: #667eea; margin-top: 0;'>Welcome to " . APP_NAME . "!</h2>
                <p>Hello <strong>" . htmlspecialchars($email) . "</strong>,</p>
                <p>Thank you for registering. Please verify your email address to activate your account.</p>
                
                <div style='background: #f0f4ff; padding: 25px; border-radius: 8px; margin: 25px 0; border-left: 5px solid #667eea; text-align: center;'>
                    <p style='margin: 0; color: #999; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;'>Your Verification Code</p>
                    <p style='margin: 15px 0 0 0; font-size: 32px; font-weight: bold; letter-spacing: 3px; color: #667eea; font-family: monospace;'>" . strtoupper($code) . "</p>
                </div>
                
                <p style='margin: 25px 0 15px 0;'><strong>✅ Quickest Way to Verify:</strong></p>
                <div style='text-align: center; margin: 20px 0;'>
                    <a href='" . $verify_link . "' style='background-color: #667eea; color: white; padding: 14px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; font-size: 16px;'>Verify Email Now</a>
                </div>
                
                <p style='margin: 25px 0 15px 0;'><strong>🔐 Alternative Method (Manual Code Entry):</strong></p>
                <ol style='margin: 10px 0;'>
                    <li>Go to: <a href='" . APP_URL . "/index.php?action=verify' style='color: #667eea;'>" . APP_URL . "/index.php?action=verify</a></li>
                    <li>Enter your verification code: <strong style='font-family: monospace;'>" . strtoupper($code) . "</strong></li>
                    <li>Click the Verify button</li>
                </ol>
                
                <hr style='border: none; border-top: 1px solid #ddd; margin: 25px 0;'>
                <p style='color: #666; font-size: 13px;'>
                    <strong>Security Note:</strong> This verification link will expire in 24 hours. If you did not create this account, please ignore this email.
                </p>
                <p style='color: #999; font-size: 12px; margin: 15px 0 0 0;'>
                    " . APP_NAME . " | " . date('Y') . "<br>
                    If you have any questions, please contact our support team.
                </p>
            </div>
        </body>
        </html>
        ";

        // Send email via configured method
        if (SEND_EMAILS) {
            $this->sendEmailViaGmail($email, $subject, $message);
        }
        
        // Also log for reference
        try {
            $this->logEmailToFile($email, $subject, $message, $verify_link);
        } catch (Exception $e) {
            error_log("Email logging failed: " . $e->getMessage());
        }
    }

    /* SEND EMAIL VIA GMAIL SMTP */
    private function sendEmailViaGmail($to, $subject, $message)
    {
        if (EMAIL_METHOD === 'phpmailer') {
            return $this->sendEmailViaPhpMailer($to, $subject, $message);
        } else {
            return $this->sendEmailViaPhpMail($to, $subject, $message);
        }
    }

    /* SEND EMAIL VIA PHPMAILER */
    private function sendEmailViaPhpMailer($to, $subject, $message)
    {
        try {
            // Check if PHPMailer exists
            $phpmailer_path = __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
            
            if (!file_exists($phpmailer_path)) {
                error_log("PHPMailer not found at: " . $phpmailer_path);
                return $this->sendEmailViaPhpMail($to, $subject, $message);
            }

            require_once $phpmailer_path;
            require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
            require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            
            // Server settings
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USERNAME;
            $mail->Password = MAIL_PASSWORD;
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = MAIL_PORT;

            // Recipients
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            // Send
            $mail->send();
            error_log("Email sent successfully to: " . $to);
            return true;

        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("PHPMailer Error: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("General Error: " . $e->getMessage());
            return false;
        }
    }

    /* SEND EMAIL VIA PHP MAIL FUNCTION */
    private function sendEmailViaPhpMail($to, $subject, $message)
    {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM . ">\r\n";
        $headers .= "Reply-To: " . MAIL_FROM . "\r\n";

        if (mail($to, $subject, $message, $headers)) {
            error_log("Email sent successfully via mail() to: " . $to);
            return true;
        } else {
            error_log("Failed to send email via mail() to: " . $to);
            return false;
        }
    }

    /* LOG EMAIL TO FILE (for development) */
    private function logEmailToFile($email, $subject, $message, $verify_link)
    {
        // Extract code from link
        preg_match('/code=([a-f0-9]+)/', $verify_link, $matches);
        $code = $matches[1] ?? 'N/A';
        
        $log_dir = __DIR__ . '/../logs';
        
        // Create logs directory if it doesn't exist
        if (!is_dir($log_dir)) {
            if (!mkdir($log_dir, 0755, true)) {
                throw new Exception("Failed to create logs directory at: " . $log_dir);
            }
        }

        $log_file = $log_dir . '/emails.log';
        $timestamp = date('Y-m-d H:i:s');
        
        $log_content = "\n================================================================================\n";
        $log_content .= "[" . $timestamp . "] Email Sent to: $email\n";
        $log_content .= "================================================================================\n";
        $log_content .= "Subject: $subject\n\n";
        $log_content .= "📌 VERIFICATION CODE (For verification page input):\n";
        $log_content .= "   >>> " . $code . " <<<\n\n";
        $log_content .= "🔗 VERIFICATION LINK (Alternative - Click the link):\n";
        $log_content .= $verify_link . "\n\n";
        $log_content .= "Email Body:\n";
        $log_content .= $message . "\n";
        $log_content .= "================================================================================\n";

        // Append to log file
        $written = file_put_contents($log_file, $log_content, FILE_APPEND | LOCK_EX);
        
        if ($written === false) {
            throw new Exception("Failed to write to log file: " . $log_file);
        }
    }
}
?>
