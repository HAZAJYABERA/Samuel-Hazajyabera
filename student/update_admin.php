<?php
// Connection details
$host = "localhost";
$user = "root"; 
$pass = ""; 
$database = "student_management_system"; 

// Creating connection
$connection = new mysqli($host, $user, $pass, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
<?php
// Include connection details
include('database_connection.php');

// Check if admin_id is set
if (isset($_REQUEST['admin_id'])) {
    $admin_id = $_REQUEST['admin_id'];
    
    $stmt = $connection->prepare("SELECT * FROM admin WHERE admin_id=?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $email = $row['email'];
        $password = $row['password'];
    } else {
        echo "Admin not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Admin</title>
    <!-- JavaScript validation and content load for update or modify data-->
    <script>
        function confirmUpdate() {
            return confirm('Are you sure you want to update this record?');
        }
    </script>
</head>
<body>
    <center>
    <!-- Update Admin form -->
    <h2><u>Update Form of Admin</u></h2>
    <form method="POST" onsubmit="return confirmUpdate();">
        <input type="hidden" name="admin_id" value="<?php echo $admin_id; ?>">
        
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo $name; ?>" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $email; ?>" required>
        <br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" value="<?php echo $password; ?>" required>
        <br><br>

        <input type="submit" name="up" value="Update">
    </form>
    </center>
</body>
</html>

<?php
if (isset($_POST['up'])) {
    // Retrieve updated values from form
    $admin_id = $_POST['admin_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Update the admin record in the database
    $stmt = $connection->prepare("UPDATE admin SET name=?, email=?, password=? WHERE admin_id=?");
    $stmt->bind_param("sssi", $name, $email, $password, $admin_id);
    $stmt->execute();
    
    // Redirect to admin.php or any other page displaying admin records
    header('Location: admin.php');
    exit(); // Ensure that no other content is sent after the header redirection
}
?>
