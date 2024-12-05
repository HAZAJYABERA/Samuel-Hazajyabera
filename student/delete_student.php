<?php
// Connection details
include('database_connection.php');

// Check if student_id is set
if(isset($_REQUEST['student_id'])) {
    $student_id = $_REQUEST['student_id'];

    // Delete dependent records from other tables if needed (optional, based on your DB structure)
    // $stmt = $connection->prepare("DELETE FROM other_table WHERE student_id=?");
    // $stmt->bind_param("i", $student_id);
    // $stmt->execute();
    // $stmt->close();

    // Prepare and execute the DELETE statement for the student table
    $stmt = $connection->prepare("DELETE FROM student WHERE student_id=?");
    $stmt->bind_param("i", $student_id);

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Delete Record</title>
        <script>
            function confirmDelete() {
                return confirm("Are you sure you want to delete this record?");
            }
        </script>
    </head>
    <body>
        <form method="post" action="delete_student.php" onsubmit="return confirmDelete();">
            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
            <input type="submit" value="Delete">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($stmt->execute()) {
                echo "Record deleted successfully.<br><br>";
                echo "<a href='students.php'>OK</a>"; // Assuming students.php is the page displaying student records
            } else {
                echo "Error deleting data: " . $stmt->error;
            }
        }
        ?>
    </body>
    </html>
    <?php

    $stmt->close();
} else {
    echo "student_id is not set.";
}

$connection->close();
?>
