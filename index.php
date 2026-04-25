<?php
// 1. ALWAYS start the session first on any protected page!
session_start();

// 2. The Security Check: If they are not logged in, kick them out
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Secure Dashboard</title>
    <style>
        /* Basic CSS Reset */
        body, html { margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; }
        
        /* The Top Navigation Bar */
        .navbar {
            background-color: #2c3e50;
            display: flex;
            justify-content: center; /* Centers the links at the top */
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .navbar a {
            color: white;
            padding: 16px 24px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .navbar a:hover { background-color: #34495e; }
        .navbar a.logout:hover { background-color: #e74c3c; } /* Make logout button turn red on hover */

        /* The Main Content Area */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh; /* Takes up most of the screen */
        }
        .card {
            background: white;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            text-align: center;
        }
        h1 { color: #2c3e50; margin-bottom: 10px; }
        p { color: #7f8c8d; font-size: 18px; }
    </style>
</head>
<body>

    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="settings.php">Settings</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <div class="container">
        <div class="card">
            <h1>Hello, <?php echo htmlspecialchars($_SESSION['name']); ?>! 👋</h1>
            <p>Welcome to your secure homepage.</p>
        </div>
    </div>

</body>
</html>
