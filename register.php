<?php
// Database configuration
$host = '127.0.0.1';
$db   = 'app_db';
$user = 'user1';    // Update this to your MySQL username
$pass = 'userpass1'; // Update this to your MySQL password

// 1. Connect to the database using mysqli
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

// 2. Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 3. Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 4. Use Prepared Statements to prevent SQL Injection
    $stmt = $conn->prepare("INSERT INTO users (name, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $username, $hashed_password);

    if ($stmt->execute()) {
        $message = "Success! Account registered for " . htmlspecialchars($username);
    } else {
        $message = "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .container { max-width: 300px; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; margin: 8px 0; }
        input[type="submit"] { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        
        <?php if ($message) echo "<p style='color: green;'><strong>$message</strong></p>"; ?>
        
        <form method="POST" action="">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
