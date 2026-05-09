<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: signin.php");
    exit;
}

// Database configuration
$host = 'localhost';
$db   = 'socialnet';
$user = 'user1'; 
$pass = 'password1'; 

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// The Core Logic: Determine whose profile to show
// If "?owner=" exists in the URL, use that. Otherwise, use the logged-in user.
if (isset($_GET['owner']) && !empty($_GET['owner'])) {
    $profile_owner = $_GET['owner'];
} else {
    $profile_owner = $_SESSION['username'];
}

// Fetch the user's details from the database
$stmt = $conn->prepare("SELECT fullname, description FROM account WHERE username = ?");
$stmt->bind_param("s", $profile_owner);
$stmt->execute();
$stmt->store_result();

$profile_fullname = "User not found";
$profile_description = "This user does not exist.";

if ($stmt->num_rows > 0) {
    $stmt->bind_result($profile_fullname, $profile_description);
    $stmt->fetch();
    
    // If the user hasn't set a description yet, give a default message
    if (empty($profile_description)) {
        $profile_description = "This user hasn't written a description yet.";
    }
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile: <?php echo htmlspecialchars($profile_owner); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center; }
        .desc-box { background: #eee; padding: 20px; border-radius: 5px; text-align: left; margin-top: 20px; white-space: pre-wrap; }
	.avatar { width: 80px; height: 80px; background-color: #04AA6D; color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 40px; font-weight: bold; margin: 0 auto 15px auto; }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container">
	<div class="avatar">
            <?php echo htmlspecialchars(strtoupper(substr($profile_fullname, 0, 1))); ?>
        </div>
        
        <h1><?php echo htmlspecialchars($profile_fullname); ?></h1>
        <p style="color: gray;">@<?php echo htmlspecialchars($profile_owner); ?></p>
        
        <div class="desc-box">
            <?php echo htmlspecialchars($profile_description); ?>
        </div>
    </div>
</body>
</html>
