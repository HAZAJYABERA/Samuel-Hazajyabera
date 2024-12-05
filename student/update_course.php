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

// Check if course_id is set
if (isset($_REQUEST['course_id'])) {
    $course_id = $_REQUEST['course_id'];
    
    $stmt = $connection->prepare("SELECT * FROM courses WHERE course_id=?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $course_name = $row['course_name'];
        $course_code = $row['course_code'];
        $description = $row['description'];
    } else {
        echo "Course not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Course</title>
    <!-- JavaScript validation and content load for update or modify data-->
    <script>
        function confirmUpdate() {
            return confirm('Are you sure you want to update this record?');
        }
    </script>
</head>
<body>
    <center>
    <!-- Update Course form -->
    <h2><u>Update Form of Course</u></h2>
    <form method="POST" onsubmit="return confirmUpdate();">
        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
        
        <label for="course_name">Course Name:</label>
        <input type="text" name="course_name" value="<?php echo $course_name; ?>" required>
        <br><br>

        <label for="course_code">Course Code:</label>
        <input type="text" name="course_code" value="<?php echo $course_code; ?>" required>
        <br><br>

        <label for="description">Description:</label>
        <textarea name="description" required><?php echo $description; ?></textarea>
        <br><br>

        <input type="submit" name="up" value="Update">
    </form>
    </center>
</body>
</html>

<?php
if (isset($_POST['up'])) {
    // Retrieve updated values from form
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];
    $description = $_POST['description'];

    // Update the course record in the database
    $stmt = $connection->prepare("UPDATE courses SET course_name=?, course_code=?, description=? WHERE course_id=?");
    $stmt->bind_param("sssi", $course_name, $course_code, $description, $course_id);
    $stmt->execute();
    
    // Redirect to courses.php or any other page displaying course records
    header('Location: courses.php');
    exit(); // Ensure that no other content is sent after the header redirection
}
?>
