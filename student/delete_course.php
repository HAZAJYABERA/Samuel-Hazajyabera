<?php
// Connection details
include('database_connection.php');

// Check if course_id is set
if(isset($_REQUEST['course_id'])) {
    $course_id = $_REQUEST['course_id'];

    // Delete dependent records from other tables if needed (optional, based on your DB structure)
    // Example:
    // $stmt = $connection->prepare("DELETE FROM other_table WHERE course_id=?");
    // $stmt->bind_param("i", $course_id);
    // $stmt->execute();
    // $stmt->close();

    // Prepare and execute the DELETE statement for the course table
    $stmt = $connection->prepare("DELETE FROM courses WHERE course_id=?");
    $stmt->bind_param("i", $course_id);

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
        <form method="post" action="delete_course.php" onsubmit="return confirmDelete();">
            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
            <input type="submit" value="Delete">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($stmt->execute()) {
                echo "Record deleted successfully.<br><br>";
                echo "<a href='course.php'>OK</a>"; // Assuming course.php is the page displaying course records
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
    echo "course_id is not set.";
}

$connection->close();
?>
