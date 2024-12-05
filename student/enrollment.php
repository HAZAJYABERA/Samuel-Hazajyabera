<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enrollment Info</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <style>
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
    .logout {
      position: absolute;
      top: 10px;
      right: 10px;
    }
    table {
      width: 75%;
      border-collapse: revert;
    }
    td:first-child {
      background-color: #333333;
      color: #ffffff;
    }
    td {
      padding: 8px;
      border-bottom: 1px solid #dddddd;
    }
    tr:hover {
      background-color: #e9e9e9;
    }
    th, td {
      border: 2px solid black;
      padding: 10px;
      text-align: left;
    }
    th {
      background-color: orange;
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
      <li style="display: inline; margin-right: 10px;">
        <ul style="list-style-type: none; padding: 0;">
          <li style="display: inline; margin-right: 8px;padding: 10px; color: white; background-color: greenyellow;"><a href="./home.html">HOME</a></li>
          <li style="display: inline; margin-right: 10px;padding: 10px; color: white; background-color: greenyellow;"><a href="./about.html">ABOUT</a></li>
          <li style="display: inline; margin-right: 10px;padding: 10px; color: white; background-color: greenyellow;"><a href="./contact.html">CONTACT</a></li>
          <li class="dropdown" style="display: inline;">
            <a href="#" style="padding: 10px; color: white; background-color: greenyellow;">TABLES</a>
            <div class="dropdown-contents">
              <a href="./users.php">USERS</a>
              <a href="./admin.php">ADMIN</a>
              <a href="./student.php">STUDENT</a>
              <a href="./courses.php">COURSES</a>
              <a href="./enrollment.php">ENROLLMENT</a>
              <a href="./grades.php">GRADES</a>
            </div>
          </li>
        </ul>
      </li>
    </ul>
    <a class="logout" href="logout.php" style="padding: 10px; color: white; background: red;">LOG OUT</a>
  </header>

  <h1>Enrollment Form</h1>
  <form method="post" onsubmit="return confirmInsert();">
    <label for="student_id">Student ID:</label>
    <input type="number" id="student_id" name="student_id" required><br><br>

    <label for="course_id">Course ID:</label>
    <input type="number" id="course_id" name="course_id" required><br><br>

    <label for="enrollment_date">Enrollment Date:</label>
    <input type="date" id="enrollment_date" name="enrollment_date" required><br><br>

    <input type="submit" name="add" value="Insert"><br><br>
    <a href="./home.html">Go Back to Home</a>
  </form>

  <?php
  // Include database connection
  include('database_connection.php');

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
      $student_id = intval($_POST['student_id']);
      $course_id = intval($_POST['course_id']);
      $enrollment_date = $connection->real_escape_string($_POST['enrollment_date']);

      // Check if student_id exists in the `student` table
      $check_student = $connection->prepare("SELECT * FROM student WHERE student_id = ?");
      $check_student->bind_param("i", $student_id);
      $check_student->execute();
      $student_result = $check_student->get_result();

      // Check if course_id exists in the `courses` table
      $check_course = $connection->prepare("SELECT * FROM courses WHERE course_id = ?");
      $check_course->bind_param("i", $course_id);
      $check_course->execute();
      $course_result = $check_course->get_result();

      if ($student_result->num_rows > 0 && $course_result->num_rows > 0) {
          // Proceed with the insertion if both student and course exist
          $stmt = $connection->prepare("INSERT INTO enrollment (student_id, course_id, enrollment_date) VALUES (?, ?, ?)");
          $stmt->bind_param("iis", $student_id, $course_id, $enrollment_date);

          if ($stmt->execute()) {
              echo "New record has been added successfully.";
          } else {
              echo "Error inserting data: " . $stmt->error;
          }
          $stmt->close();
      } else {
          if ($student_result->num_rows == 0) {
              echo "Error: Student ID {$student_id} does not exist.<br>";
          }
          if ($course_result->num_rows == 0) {
              echo "Error: Course ID {$course_id} does not exist.<br>";
          }
      }
      $check_student->close();
      $check_course->close();
  }
  ?>

  <section>
    <h2>Enrollment Details</h2>
    <table>
      <tr>
        <th>Enrollment ID</th>
        <th>Student ID</th>
        <th>Course ID</th>
        <th>Enrollment Date</th>
        <th>Delete</th>
        <th>Update</th>
      </tr>
      <?php
      $sql = "SELECT * FROM enrollment";
      $result = $connection->query($sql);

      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                  <td>{$row['enrollment_id']}</td>
                  <td>{$row['student_id']}</td>
                  <td>{$row['course_id']}</td>
                  <td>{$row['enrollment_date']}</td>
                  <td><a href='delete_enrollment.php?enrollment_id={$row['enrollment_id']}'>Delete</a></td>
                  <td><a href='update_enrollment.php?enrollment_id={$row['enrollment_id']}'>Update</a></td>
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
