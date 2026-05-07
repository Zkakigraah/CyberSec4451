<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: signin.php");
    exit;
}

$host = 'localhost';
$db   = 'socialnet';
$user = 'user1'; 
$pass = 'password1'; 

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed");

$current_user = $_SESSION['username'];
$message = '';

// Handle the form submission to update the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_description = $_POST['description'];
    
    $stmt_update = $conn->prepare("UPDATE account SET description = ? WHERE username = ?");
    $stmt_update->bind_param("ss", $new_description, $current_user);
    
    if ($stmt_update->execute()) {
        $message = "<p style='color: green;'>Profile description updated successfully!</p>";
    } else {
        $message = "<p style='color: red;'>Error updating profile.</p>";
    }
    $stmt_update->close();
}

// Fetch the current description to pre-fill the text box
$stmt_fetch = $conn->prepare("SELECT description FROM account WHERE username = ?");
$stmt_fetch->bind_param("s", $current_user);
$stmt_fetch->execute();
$stmt_fetch->bind_result($current_description);
$stmt_fetch->fetch();
$stmt_fetch->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-bottom: 15px; }
        button { background-color: #2c3e50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #1a252f; }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container">
        <h2>Edit Profile Description</h2>
        <?php echo $message; ?>
        
        <form method="POST" action="">
            <label>Write something about yourself:</label><br><br>
            <textarea name="description" rows="6" required><?php echo htmlspecialchars($current_description); ?></textarea>
            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
