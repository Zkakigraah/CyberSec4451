<?php
session_start();

// Security Check: If not logged in, redirect to Sign In page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: signin.php");
    exit;
}
 
// Database configuration
$host = 'localhost';
$db   = 'socialnet';
$user = 'user1'; 
$pass = 'password1'; // Using your exact password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Social Network Home</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h1, h2 { color: #2c3e50; }
        .user-list { list-style-type: none; padding: 0; }
        .user-list li { background: #eee; margin: 5px 0; padding: 10px; border-radius: 5px; }
        .user-list a { text-decoration: none; color: #04AA6D; font-weight: bold; }
        .user-list a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <?php include 'menu.php'; ?>

    <div class="container">
        <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h1>
        <p>Your Username: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>

        <hr>
        
        <?php
        // Extra Vulnerability (Reflected XSS): Echoing GET input without sanitization
        if (isset($_GET['search'])) {
            echo "<div style='background: #ffcccc; padding: 10px;'>Search results for: " . $_GET['search'] . "</div>";
        }
        ?>

        <h2>Other Users in the Network</h2>
        <ul class="user-list">
            <?php
            // Fetch all users EXCEPT the currently logged-in user
            $current_user = $_SESSION['username'];
            $stmt = $conn->prepare("SELECT username, fullname FROM account WHERE username != ?");
            $stmt->bind_param("s", $current_user);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Loop through the results and create a list item for each user
                while ($row = $result->fetch_assoc()) {
                    $other_username = htmlspecialchars($row['username']);
                    $other_fullname = htmlspecialchars($row['fullname']);
                    
                    // Create the link to the Profile Page with the ?owner= query string
                    echo "<li>$other_fullname (@$other_username) - <a href='profile.php?owner=$other_username'>View Profile</a></li>";
                }
            } else {
                echo "<li>You are the only user in the network!</li>";
            }
            
            $stmt->close();
            $conn->close();
            ?>
        </ul>
    </div>

</body>
</html>
