<?php
session_start();

include 'database_connection.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {
    $username = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    $sql = "SELECT user_id, email, password FROM users WHERE email=?";
    $stmt = $connection->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $row['id'];
                header("Location: home.html");
                exit();
            } else {
                $error = "Invalid email or password. Please try again.";
            }
        } else {
            $error = "registration is successfull. Please fill again or register.";
        }
    } else {
        $error = "Database error: " . $connection->error;
    }

    $stmt->close();
} else {
    $error = "PLEASE FILL OUT THE LOGIN FORM";
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            background-image: url('images/C.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            text-align: center;
            padding: 50px;
            border: 0px solid #ccc;
            border-radius: 40px;
            background-color: #f9f9f9;
        }

        .error {
            color: red; /* Changed to red color */
            margin-top: 20px;
        }
    </style>
</head>
<body bgcolor="skyblue">
    <div class="container">
        <h2>User Login Form</h2>
        <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="email">email:</label><br>
            <input type="text" id="email" name="email" required><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Login">
        </form>
        <p class="error"><?php echo $error; ?></p>
        <p>Not registered yet? <a href="register.php">Register here</a></p>
        <p>Do you want to logout? <a href="logout.php">Logout here</a></p>
        <p>Do you want to reach on home? <a href="home.html">home here</a></p>
    </div>

    <script>
        // JavaScript confirmation prompt before submitting the form
        document.getElementById("loginForm").addEventListener("submit", function(event) {
            var confirmLogin = confirm("Do you want to login?");
            if (!confirmLogin) {
                event.preventDefault(); // Prevent form submission if user cancels
            }
        });
    </script>
</body>
</html>
