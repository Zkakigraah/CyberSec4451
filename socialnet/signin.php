<?php
session_start();

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
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, fullname, password FROM account WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $fullname, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Success! Store info in Session
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['fullname'] = $fullname; // Required for the Home Page
            
            // Redirect to Home Page
            header("Location: /socialnet/index.php");
            exit;
        } else {
            $message = "<span style='color: red;'>Incorrect password.</span>";
        }
    } else {
        $message = "<span style='color: red;'>Username not found.</span>";
    }
    
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign In</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #04AA6D; color: white; padding: 10px; width: 100%; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #058f5c; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Sign In</h2>
        <?php if ($message) echo "<p>$message</p>"; ?>
        <form method="POST" action="">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Log In</button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <p style="color: #7f8c8d; font-size: 14px;">Don't have an account?</p>
            <a href="/admin/newuser.php" style="display: inline-block; padding: 10px 20px; background-color: #2c3e50; color: white; text-decoration: none; border-radius: 4px; font-size: 14px;">Create New Account</a>
        </div>
    </div>
</body>
</html>
