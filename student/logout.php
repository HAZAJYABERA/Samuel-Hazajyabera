<?php
session_start();

// Check if the user is logged in
if(isset($_SESSION['user_id'])) {
    // If logged in, destroy the session
    session_destroy();
    $message = "You have been successfully logged out.";
} else {
    // If not logged in, display a message
    $message = "You are already logged out.";
}

// Redirect to login page after 3 seconds
header("refresh:3;url=login.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>
<body bgcolor="chocolate">
    <h2>Logout</h2>
    <p><?php echo $message; ?></p>
    <p>Redirecting to login page...</p>
</body>
</html>