<?php
// 1. Start the session so PHP remembers the user
session_start();

// Database configuration
$host = 'localhost';
$db   = 'app_db';
$user = 'user1';
$pass = 'userpass1';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

// 2. Check if the login form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 3. Search for the username in the database
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    // Store the result so we can check if a row was found
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // 4. Username exists! Grab their ID, Name, and Hashed Password from the DB
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        // 5. Verify the password typed in matches the hash in the DB
        // 5. Verify the password typed in matches the hash in the DB
        if (password_verify($password, $hashed_password)) {
            
            // Success! Store their info in the Session array
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $id;
            $_SESSION['name'] = $name;
            
            // Redirect them to the homepage instantly!
            header("Location: index.php");
            exit;
            
        } else {
            $message = "<span style='color: red;'>Error: Incorrect password.</span>";
        }
    } else {
        $message = "<span style='color: red;'>Error: No account found with that username.</span>";
    }
    
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .container { max-width: 300px; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; margin: 8px 0; }
        input[type="submit"] { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        
        <?php if ($message) echo "<p><strong>$message</strong></p>"; ?>
        
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
