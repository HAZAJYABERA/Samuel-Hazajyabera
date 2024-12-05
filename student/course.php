<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Courses Info</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <style>
    /* Your existing CSS styles */
    /* Dropdown menu style */
    .dropdown-contents {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
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
    /* Positioning for LOG OUT */
    .logout {
      position: absolute;
      top: 10px;
      right: 10px;
    }
    table {
      width: 75%; /* Set table to full width */
      border-collapse: revert; /* Merge borders */
    }

    /* Special Styling for First Column */
    td:first-child {
      background-color: #333333;
      color: #ffffff;
    }

    /* Table Cells */
    td {
      padding: 8px;
      border-bottom: 1px solid #dddddd;
    }

    /* Hover Effect */
    tr:hover {
      background-color: #e9e9e9;
    }

    th, td {
      border: 2px solid black; /* Table borders */
      padding: 10px; /* Padding for readability */
      text-align: left;
    }

    th {
      background-color: orange; /* Header row color */
    }
  </style>
  <!-- JavaScript function for insert confirmation -->
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
      <li style="display: inline; margin-right: 10px;">
        <ul style="list-style-type: none; padding: 0;">
          <li style="display: inline; margin-right: 8px;padding: 10px; color: white; background-color: greenyellow; text-decoration: none; margin-right: 15px;"><a href="./home.html">HOME</a></li>
          <li style="display: inline; margin-right: 10px;padding: 10px; color: white; background-color: greenyellow; text-decoration: none; margin-right: 15px;"><a href="./about.html">ABOUT</a></li>
          <li style="display: inline; margin-right: 10px;padding: 10px; color: white; background-color: greenyellow; text-decoration: none; margin-right: 15px;"><a href="./contact.html">CONTACT</a></li>
          <li class="dropdown" style="display: inline;">
            <a href="#" style="padding: 10px; color: white; background-color: greenyellow; text-decoration: none; margin-right: 15px;"> TABLES </a>
            <div class="dropdown-contents">
            <a href="./users.php">USERS</a>
          <a href="./admin.php">ADMIN</a>
          <a href="./student.php.php">STUDENT</a>
          <a href="./courses.php">COURSES</a>
          <a href="./enrollment.php">ENROLLMENT</a>
          <a href="./grades.php">GRADES</a>
          
            </div>
          </li>
        </ul>
      </li>
    </ul>
    <a class="logout" href="logout.php" style="padding: 10px; color: white; background: red; text-decoration: none;">LOG OUT</a>
  </header>

  <body style="background-color: yellowgreen;">
    <h1>Courses Form</h1>
    <form method="post" onsubmit="return confirmInsert();">
      <label for="course_id">Course ID:</label>
      <input type="number" id="course_id" name="course_id" required><br><br>

      <label for="course_name">Course Name:</label>
      <input type="text" id="course_name" name="course_name" required><br><br>

      <label for="course_code">Course Code:</label>
      <input type="text" id="course_code" name="course_code" required><br><br>

      <label for="description">Description:</label>
      <input type="text" id="description" name="description" required><br><br>

      <input type="submit" name="add" value="Insert"><br><br>

      <a href="./home.html">Go Back to Home</a>
    </form>

    <?php
    include('database_connection.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
      $course_id = $_POST['course_id'];
      $course_name = $_POST['course_name'];
      $course_code = $_POST['course_code'];
      $description = $_POST['description'];

      $stmt = $connection->prepare("INSERT INTO courses (course_id, course_name, course_code, description) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("isss", $course_id, $course_name, $course_code, $description);

      if ($stmt->execute()) {
        echo "New record has been added successfully.<br><br><a href='courses.php'>Back to Form</a>";
      } else {
        echo "Error inserting data: " . $stmt->error;
      }

      $stmt->close();
    }
    ?>

    <section>
      <h2>Courses Details</h2>
      <table>
        <tr>
          <th>Course ID</th>
          <th>Course Name</th>
          <th>Course Code</th>
          <th>Description</th>
          <th>Delete</th>
          <th>Update</th>
        </tr>
        <?php
        $sql = "SELECT * FROM courses";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<tr>
              <td>{$row['course_id']}</td>
              <td>{$row['course_name']}</td>
              <td>{$row['course_code']}</td>
              <td>{$row['description']}</td>
              <td><a style='padding:4px' href='delete_course.php?course_id={$row['course_id']}'>Delete</a></td> 
              <td><a style='padding:4px' href='update_course.php?course_id={$row['course_id']}'>Update</a></td> 
            </tr>";
          }
        } else {
          echo "<tr><td colspan='6'>No data found</td></tr>";
        }
        ?>
      </table>
    </section>

    <footer>
      <h2>UR CBE BIT &copy; 2024 &reg; Designed by: @samuel HAZAJYABERA</h2>
    </footer>
  </body>
</html>
