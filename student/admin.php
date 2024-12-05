<!DOCTYPE html> 
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Info</title>
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
    <h1>Admin Form</h1>
    <form method="post" onsubmit="return confirmInsert();">
      <label for="admin_id">Admin ID:</label>
      <input type="number" id="admin_id" name="admin_id" required><br><br>

      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required><br><br>

      <label for="email">Email:</label>
      <input type="text" id="email" name="email" required><br><br>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required><br><br>

      <input type="submit" name="add" value="Insert"><br><br>

      <a href="./home.html">Go Back to Home</a>
    </form>

    <?php
    include('database_connection.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
      $admin_id = $_POST['admin_id'];
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = $_POST['password'];

      // Ensure $connection is initialized before running queries
      if ($connection) {
        $stmt = $connection->prepare("INSERT INTO admin (admin_id, name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $admin_id, $name, $email, $password);

        if ($stmt->execute()) {
          echo "New record has been added successfully.<br><br><a href='admin.php'>Back to Form</a>";
        } else {
          echo "Error inserting data: " . $stmt->error;
        }

        $stmt->close();
      } else {
        echo "Database connection failed.";
      }
    }
    ?>

    <section>
      <h2>Admin Details</h2>
      <table>
        <tr>
          <th>Admin ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Password</th>
          <th>Delete</th>
          <th>Update</th>
        </tr>
        <?php
        // Ensure $connection is initialized before running queries
        if ($connection) {
          $sql = "SELECT * FROM admin";
          $result = $connection->query($sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
                <td>{$row['admin_id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['password']}</td>
                <td><a style='padding:4px' href='delete_admin.php?admin_id={$row['admin_id']}'>Delete</a></td> 
                <td><a style='padding:4px' href='update_admin.php?admin_id={$row['admin_id']}'>Update</a></td> 
              </tr>";
            }
          } else {
            echo "<tr><td colspan='6'>No data found</td></tr>";
          }
        } else {
          echo "Failed to retrieve data from the database.";
        }
        ?>
      </table>
    </section>

    <footer>
      <h2>UR CBE BIT &copy; 2024 &reg; Designed by: @samuel HAZAJYABERA</h2>
    </footer>
  </body>
</html>
