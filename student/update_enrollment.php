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

// Check if enrollment_id is set
if (isset($_REQUEST['enrollment_id'])) {
    $enrollment_id = $_REQUEST['enrollment_id'];
    
    $stmt = $connection->prepare("SELECT * FROM enrollment WHERE enrollment_id=?");
    $stmt->bind_param("i", $enrollment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $student_id = $row['student_id'];
        $course_id = $row['course_id'];
        $enrollment_date = $row['enrollment_date'];
    } else {
        echo "Enrollment not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Enrollment</title>
    <!-- JavaScript validation and content load for update or modify data-->
    <script>
        function confirmUpdate() {
            return confirm('Are you sure you want to update this record?');
        }
    </script>
</head>
<body>
    <center>
    <!-- Update Enrollment form -->
    <h2><u>Update Form of Enrollment</u></h2>
    <form method="POST" onsubmit="return confirmUpdate();">
        <input type="hidden" name="enrollment_id" value="<?php echo $enrollment_id; ?>">
        
        <label for="student_id">Student ID:</label>
        <input type="number" name="student_id" value="<?php echo $student_id; ?>" required>
        <br><br>

        <label for="course_id">Course ID:</label>
        <input type="number" name="course_id" value="<?php echo $course_id; ?>" required>
        <br><br>

        <label for="enrollment_date">Enrollment Date:</label>
        <input type="date" name="enrollment_date" value="<?php echo $enrollment_date; ?>" required>
        <br><br>

        <input type="submit" name="up" value="Update">
    </form>
    </center>
</body>
</html>

<?php
if (isset($_POST['up'])) {
    // Retrieve updated values from form
    $enrollment_id = $_POST['enrollment_id'];
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $enrollment_date = $_POST['enrollment_date'];

    // Update the enrollment record in the database
    $stmt = $connection->prepare("UPDATE enrollment SET student_id=?, course_id=?, enrollment_date=? WHERE enrollment_id=?");
    $stmt->bind_param("iisi", $student_id, $course_id, $enrollment_date, $enrollment_id);
    $stmt->execute();
    
    // Redirect to enrollments.php or any other page displaying enrollment records
    header('Location: enrollments.php');
    exit(); // Ensure that no other content is sent after the header redirection
}
?>
