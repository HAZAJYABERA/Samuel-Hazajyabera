<?php
// Connection details
include('database_connection.php');

// Check if admin_id is set
if(isset($_REQUEST['admin_id'])) {
    $admin_id = $_REQUEST['admin_id'];

    // Delete dependent records from other tables if needed (optional, based on your DB structure)
    // $stmt = $connection->prepare("DELETE FROM other_table WHERE admin_id=?");
    // $stmt->bind_param("i", $admin_id);
    // $stmt->execute();
    // $stmt->close();

    // Prepare and execute the DELETE statement for the admin table
    $stmt = $connection->prepare("DELETE FROM admin WHERE admin_id=?");
    $stmt->bind_param("i", $admin_id);

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
        <form method="post" action="delete_admin.php" onsubmit="return confirmDelete();">
            <input type="hidden" name="admin_id" value="<?php echo $admin_id; ?>">
            <input type="submit" value="Delete">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($stmt->execute()) {
                echo "Record deleted successfully.<br><br>";
                echo "<a href='admin.php'>OK</a>"; // Assuming admin.php is the page displaying admin records
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
    echo "admin_id is not set.";
}

$connection->close();
?>
