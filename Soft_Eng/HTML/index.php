<?php
include("php/database.php");

if(isset($_POST['employeeSelect']) && isset($_POST['action'])) {
    $employeeName = $_POST['employeeSelect'];
    $action = $_POST['action']; // "Time In" or "Time Out"

    // I-save ang time stamp sa attendance table
    $sql = "INSERT INTO attendance (employee_name, action, datetime) VALUES ('$employeeName', '$action', NOW())";
    if(mysqli_query($connection, $sql)) {
        echo "Record added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
    }
}

mysqli_close($connection);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home Page</title>
  <link rel="stylesheet" href="css/index.css">
</head>
<body>
  <header class="header">
    <img src="images/bigbrewpic2.jpg" class="logo">
    <a class="text"><div class="br-name">BigBrew Zamboanga</div></a>
    <div class="selection">
      <a href="index.php">HOME</a>
      <a href="login_page.php" target="_self">SIGN UP</a>
    </div>
  </header>
  <img src="images/bigbrewpic3.jpg" class="img">
  
  <div class="container">
    <h2>Employee's Login</h2>
    <form id="loginForm" action="#" method="post">
      <div class="form-group">
        <label for="employeeSelect">Select Employee:</label>
        <select id="employeeSelect" name="employeeSelect" required>
          <option value="">--Select Employee--</option>
          <?php
          include("php/database.php");

          if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
          }
  
          $sql = "SELECT name FROM employees";
          $result = mysqli_query($connection, $sql);
  
          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              echo '<option value="' . $row["name"] . '">' . $row["name"] . '</option>';
            }
          } else {
            echo "<option value=''>No employees found</option>";
          }

          mysqli_close($connection);
          ?>
        </select>
      </div>
      <div class="form-group">
        <button id="time-in-btn" type="button">Time In</button>
        <button id="time-out-btn" type="button">Time Out</button>
      </div>
    </form>
  </div>
  <div class="bg-modal">
    <div class="modal-content">
      <div class="close">+</div>
      <h2 class="title-hed" id="modal-title">-Staff Attendance-</h2>
      <div class="camera-effect">
        <video id="video" class="video" crossorigin="anonymous"></video>
        <div class="camera-button" id="startCamera">
          <ion-icon name="camera-outline"></ion-icon>
        </div>
      </div>
      <canvas id="canvas" class="canvas" width="500" height="400"></canvas>
      <div class="clock" id="clock">00:00:00</div>
      <div class="modal-btns">
        <button id="modal-action-btn">Confirm</button>
        <button class="close-modal">Cancel</button>
      </div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="js/camera.js"></script>
  <script src="js/index.js"></script>
  <script src="js/realtimeclock.js"></script>
  
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
