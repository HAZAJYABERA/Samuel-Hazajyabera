<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Grades Info</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <style>
    /* Dropdown menu style */
    .dropdown-contents {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
      z-index: 1;
    }
    .dropdown:hover .dropdown-contents {
      display: block;
    }
    .dropdown-contents a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }
    .logout {
      position: absolute;
      top: 10px;
      right: 10px;
    }
    table {
      width: 75%;
      border-collapse: collapse;
    }
    th, td {
      border: 2px solid black;
      padding: 10px;
      text-align: left;
    }
    th {
      background-color: orange;
    }
    tr:hover {
      background-color: #e9e9e9;
    }
  </style>
  <script>
    function confirmInsert() {
      return confirm("Are you sure you want to insert this record?");
    }
  </script>
</head>
<body style="background-color: lightblue;">
  <header>
    <button onclick="window.location.href='logout.php'" style="position: absolute; top: 10px; right: 10px;">Logout</button>
    <ul style="list-style-type: none; padding: 0;">
      <li style="display: inline; margin-right: 10px; background-color: greenyellow;">
        <a href="./home.html" style="text-decoration: none; padding: 10px; color: white;">HOME</a>
      </li>
      <li style="display: inline; margin-right: 10px; background-color: greenyellow;">
        <a href="./about.html" style="text-decoration: none; padding: 10px; color: white;">ABOUT</a>
      </li>
      <li style="display: inline; margin-right: 10px; background-color: greenyellow;">
        <a href="./contact.html" style="text-decoration: none; padding: 10px; color: white;">CONTACT</a>
      </li>
      <li class="dropdown" style="display: inline;">
        <a href="#" style="padding: 10px; background-color: greenyellow; text-decoration: none; color: white;">TABLES</a>
        <div class="dropdown-contents">
          <a href="./users.php">USERS</a>
          <a href="./admin.php">ADMIN</a>
          <a href="./students.php">STUDENTS</a>
          <a href="./courses.php">COURSES</a>
          <a href="./enrollments.php">ENROLLMENTS</a>
          <a href="./grades.php">GRADES</a>
        </div>
      </li>
    </ul>
  </header>

  <h1>Grades Form</h1>
  <form method="post" onsubmit="return confirmInsert();">
    <label for="enrollment_id">Enrollment ID:</label>
    <input type="number" id="enrollment_id" name="enrollment_id"><br><br>

    <label for="grade">Grade:</label>
    <input type="text" id="grade" name="grade" required><br><br>

    <input type="submit" name="add" value="Insert"><br><br>

    <a href="./home.html">Go Back to Home</a>
  </form>

  <?php
  // Enable error reporting
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  include('database_connection.php');

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    // Secure input handling
    $enrollment_id = isset($_POST['enrollment_id']) ? intval($_POST['enrollment_id']) : null;
    $grade = $connection->real_escape_string($_POST['grade']);

    $stmt = $connection->prepare("INSERT INTO grades (enrollment_id, grade) VALUES (?, ?)");
    $stmt->bind_param("is", $enrollment_id, $grade);

    if ($stmt->execute()) {
      echo "New record has been added successfully.<br><br><a href='grades.php'>Back to Form</a>";
    } else {
      echo "Error inserting data: " . $stmt->error;
    }

    $stmt->close();
  }

  // Display existing grades
  $sql = "SELECT * FROM grades";
  $result = $connection->query($sql);

  echo "<section><h2>Grades Details</h2><table><tr><th>Grade ID</th><th>Enrollment ID</th><th>Grade</th><th>Delete</th><th>Update</th></tr>";

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "<tr>
        <td>{$row['grade_id']}</td>
        <td>{$row['enrollment_id']}</td>
        <td>{$row['grade']}</td>
        <td><a href='delete_grade.php?grade_id={$row['grade_id']}'>Delete</a></td>
        <td><a href='update_grade.php?grade_id={$row['grade_id']}'>Update</a></td>
      </tr>";
    }
  } else {
    echo "<tr><td colspan='5'>No data found</td></tr>";
  }
  echo "</table></section>";
  ?>

  <footer>
    <h2>UR CBE BIT &copy; 2024 &reg; Designed by: @samuel HAZAJYABERA</h2>
  </footer>
</body>
</html>
