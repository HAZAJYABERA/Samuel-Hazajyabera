<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Info</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <style>
    /* CSS styles */
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
      border-collapse: collapse;
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
          <li style="display: inline; margin-right: 8px; padding: 10px; color: white; background-color: greenyellow; text-decoration: none; margin-right: 15px;"><a href="./home.html">HOME</a></li>
          <li style="display: inline; margin-right: 10px; padding: 10px; color: white; background-color: greenyellow; text-decoration: none; margin-right: 15px;"><a href="./about.html">ABOUT</a></li>
          <li style="display: inline; margin-right: 10px; padding: 10px; color: white; background-color: greenyellow; text-decoration: none; margin-right: 15px;"><a href="./contact.html">CONTACT</a></li>
          <li class="dropdown" style="display: inline;">
            <a href="#" style="padding: 10px; color: white; background-color: greenyellow; text-decoration: none; margin-right: 15px;">TABLES</a>
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

  <h1>Student Form</h1>
  <form method="post" onsubmit="return confirmInsert();">
    <label for="student_id">Student ID:</label>
    <input type="number" id="student_id" name="student_id" required><br><br>

    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="text" id="email" name="email" required><br><br>

    <label for="contact">Contact:</label>
    <input type="text" id="contact" name="contact" required><br><br>

    <label for="adress">Address:</label>
    <input type="text" id="address" name="adress" required><br><br>

    <label for="profle_photo">Profile Photo:</label>
    <input type="text" id="profile_photo" name="profile_photo" required><br><br>

    <input type="submit" name="add" value="Insert"><br><br>
    <a href="./home.html">Go Back to Home</a>
  </form>

  <?php
  include('database_connection.php');
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $student_id = $_POST['student_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $adress = $_POST['address'] ?? '';
    $profle_photo = $_POST['profile_photo'] ?? '';

    $stmt = $connection->prepare("INSERT INTO student (student_id, name, email, contact, address, profile_photo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $student_id, $name, $email, $contact, $adress, $profle_photo);

    if ($stmt->execute()) {
      echo "New record has been added successfully.<br><br><a href='student.php'>Back to Form</a>";
    } else {
      echo "Error inserting data: " . $stmt->error;
    }
    $stmt->close();
  }
  ?>

  <section>
    <h2>Student Detail</h2>
    <table>
      <tr>
        <th>Student ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Contact</th>
        <th>Address</th>
        <th>Profile Photo</th>
        <th>Delete</th>
        <th>Update</th>
      </tr>
      <?php
      $sql = "SELECT * FROM student";
      $result = $connection->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
            <td>{$row['student_id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['contact']}</td>
            <td>{$row['address']}</td>
            <td>{$row['profile_photo']}</td>
            <td><a style='padding:4px' href='delete_student.php?student_id={$row['student_id']}'>Delete</a></td>
            <td><a style='padding:4px' href='update_student.php?student_id={$row['student_id']}'>Update</a></td>
          </tr>";
        }
      } else {
        echo "<tr><td colspan='8'>No data found</td></tr>";
      }
      ?>
    </table>
  </section>

  <footer>
    <h2>UR CBE BIT &copy; 2024 &reg; Designed by: @samuel HAZAJYABERA</h2>
  </footer>
</body>
</html>
