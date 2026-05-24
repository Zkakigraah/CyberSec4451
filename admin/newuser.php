<?php
session_start();

// --- SECURE CHANGE 1: PREVENT UNPRIVILEGED REGISTRATION (ATT-2) ---
// Admin files must have strict authorization logic verifying that only logged-in users 
// (or users with an administrative role) can trigger user additions.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: /socialnet/signin.php");
    exit;
}

// Database configuration
$host = 'localhost';
$db   = 'socialnet';
$user = 'user1'; 
$pass = 'password1'; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- SECURE CHANGE 2: PREVENT CROSS-SITE REQUEST FORGERY (ATT-2 CSRF) ---
    // Validate the cryptographically secure token passed from the active session.
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("<span style='color: red; font-family: Arial;'>CSRF token verification failed. Unauthorized action blocked.</span>");
    }

    $username = $_POST['username'];
    $fullname = $_POST['fullname']; 
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare statement to prevent SQL Injection
    $stmt = $conn->prepare("INSERT INTO account (username, fullname, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $fullname, $hashed_password);

    if ($stmt->execute()) {
        $message = "<span style='color: green;'>User account created successfully! <a href='/socialnet/signin.php'>Go to Sign In</a></span>";
    } else {
        $message = "<span style='color: red;'>Error: " . $stmt->error . "</span>";
    }
    
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin: Create New User</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #2c3e50; color: white; padding: 10px; width: 100%; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #1a252f; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add New User</h2>
        <?php if ($message) echo "<p>$message</p>"; ?>
        <form method="POST" action="">
            <!-- --- SECURE CHANGE 3: INJECT HIDDEN CSRF TOKEN --- -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Full Name:</label>
            <input type="text" name="fullname" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Create Account</button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="/socialnet/signin.php" style="color: #04AA6D; text-decoration: none; font-weight: bold; font-size: 14px;">← Back to Sign In</a>
        </div>
    </div>
</body>
</html>
