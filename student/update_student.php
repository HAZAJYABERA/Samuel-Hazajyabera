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

// Check if student_id is set
if(isset($_REQUEST['student_id'])) {
    $student_id = $_REQUEST['student_id'];
    
    $stmt = $connection->prepare("SELECT * FROM student WHERE student_id=?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $email = $row['email'];
        $contact = $row['contact'];
        $address = $row['address'];
        $profile_photo = $row['profile_photo'];
    } else {
        echo "Student not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Student</title>
    <!-- JavaScript validation and content load for update or modify data-->
    <script>
        function confirmUpdate() {
            return confirm('Are you sure you want to update this record?');
        }
    </script>
</head>
<body>
    <center>
    <!-- Update Student form -->
    <h2><u>Update Form of Student</u></h2>
    <form method="POST" enctype="multipart/form-data" onsubmit="return confirmUpdate();">
        <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
        
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo $name; ?>" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $email; ?>" required>
        <br><br>

        <label for="contact">Contact:</label>
        <input type="text" name="contact" value="<?php echo $contact; ?>" required>
        <br><br>

        <label for="address">Address:</label>
        <textarea name="address" required><?php echo $address; ?></textarea>
        <br><br>

        <label for="profile_photo">Profile Photo:</label>
        <input type="file" name="profile_photo">
        <br><br>

        <input type="submit" name="up" value="Update">
    </form>
    </center>
</body>
</html>

<?php
if(isset($_POST['up'])) {
    // Retrieve updated values from form
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    // Check if a new profile photo is uploaded
    if(isset($_FILES['profile_photo']) && $_FILES['profile_photo']['size'] > 0) {
        $profile_photo = $_FILES['profile_photo']['name'];
        $temp_name = $_FILES['profile_photo']['tmp_name'];
        $upload_dir = "uploads/";
        move_uploaded_file($temp_name, $upload_dir . $profile_photo);
    } else {
        // If no new photo is uploaded, keep the existing one
        $profile_photo = $_POST['existing_photo'] ?? $profile_photo;
    }
    
    // Update the student record in the database
    $stmt = $connection->prepare("UPDATE student SET name=?, email=?, contact=?, address=?, profile_photo=? WHERE student_id=?");
    $stmt->bind_param("sssssi", $name, $email, $contact, $address, $profile_photo, $student_id);
    $stmt->execute();
    
    // Redirect to student.php or any other page displaying student records
    header('Location: student.php');
    exit(); // Ensure that no other content is sent after the header redirection
}
?>
