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

// Check if grade_id is set
if (isset($_REQUEST['grade_id'])) {
    $grade_id = $_REQUEST['grade_id'];
    
    $stmt = $connection->prepare("SELECT * FROM grades WHERE grade_id=?");
    $stmt->bind_param("i", $grade_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $enrollment_id = $row['enrollment_id'];
        $grade = $row['grade'];
    } else {
        echo "Grade not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Grade</title>
    <!-- JavaScript validation and content load for update or modify data -->
    <script>
        function confirmUpdate() {
            return confirm('Are you sure you want to update this record?');
        }
    </script>
</head>
<body>
    <center>
    <!-- Update Grade form -->
    <h2><u>Update Form of Grade</u></h2>
    <form method="POST" onsubmit="return confirmUpdate();">
        
        
        <label for="enrollment_id">Enrollment ID:</label>
        <input type="number" name="enrollment_id" value="<?php echo $enrollment_id; ?>" required>
        <br><br>

        <label for="grade">Grade:</label>
        <input type="text" name="grade" value="<?php echo $grade; ?>" required>
        <br><br>

        <input type="submit" name="up" value="Update">
    </form>
    </center>
</body>
</html>

<?php
if (isset($_POST['up'])) {
    // Retrieve updated values from for
    $enrollment_id = $_POST['enrollment_id'];
    $grade = $_POST['grade'];

    // Update the grade record in the database
    $stmt = $connection->prepare("UPDATE grades SET enrollment_id=?, grade=? WHERE enrollment_id=?");
    $stmt->bind_param("is", $enrollment_id, $grade);
    $stmt->execute();
    
    // Redirect to grades.php or any other page displaying grade records
    header('Location: grades.php');
    exit(); // Ensure that no other content is sent after the header redirection
}
?>
