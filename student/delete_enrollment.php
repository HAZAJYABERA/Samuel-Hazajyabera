<?php
// Connection details
include('database_connection.php');

// Check if enrollment_id is set
if(isset($_REQUEST['enrollment_id'])) {
    $enrollment_id = $_REQUEST['enrollment_id'];

    // Delete dependent records from other tables if needed (optional, based on your DB structure)
    // Example:
    // $stmt = $connection->prepare("DELETE FROM other_table WHERE enrollment_id=?");
    // $stmt->bind_param("i", $enrollment_id);
    // $stmt->execute();
    // $stmt->close();

    // Prepare and execute the DELETE statement for the enrollment table
    $stmt = $connection->prepare("DELETE FROM enrollment WHERE enrollment_id=?");
    $stmt->bind_param("i", $enrollment_id);

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
        <form method="post" action="delete_enrollment.php" onsubmit="return confirmDelete();">
            <input type="hidden" name="enrollment_id" value="<?php echo $enrollment_id; ?>">
            <input type="submit" value="Delete">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($stmt->execute()) {
                echo "Record deleted successfully.<br><br>";
                echo "<a href='enrollment.php'>OK</a>"; // Assuming enrollment.php is the page displaying enrollment records
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
    echo "enrollment_id is not set.";
}

$connection->close();
?>
