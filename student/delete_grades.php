<?php
// Connection details
include('database_connection.php');

// Check if grade_id is set
if(isset($_REQUEST['grade_id'])) {
    $grade_id = $_REQUEST['grade_id'];

    // Delete dependent records from other tables if needed (optional, based on your DB structure)
    // Example:
    // $stmt = $connection->prepare("DELETE FROM other_table WHERE grade_id=?");
    // $stmt->bind_param("i", $grade_id);
    // $stmt->execute();
    // $stmt->close();

    // Prepare and execute the DELETE statement for the grade table
    $stmt = $connection->prepare("DELETE FROM grades WHERE grade_id=?");
    $stmt->bind_param("i", $grade_id);

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
        <form method="post" action="delete_grade.php" onsubmit="return confirmDelete();">
            <input type="hidden" name="grade_id" value="<?php echo $grade_id; ?>">
            <input type="submit" value="Delete">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($stmt->execute()) {
                echo "Record deleted successfully.<br><br>";
                echo "<a href='grade.php'>OK</a>"; // Assuming grade.php is the page displaying grade records
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
    echo "grade_id is not set.";
}

$connection->close();
?>
