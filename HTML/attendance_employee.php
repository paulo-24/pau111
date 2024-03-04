<?php
include("php/database.php");

// Existing code for handling Time In/Time Out actions
if (isset($_POST['action']) && isset($_POST['employeeName']) && isset($_POST['time']) && isset($_POST['image'])) {
    $action = $_POST['action'];
    $employeeName = $_POST['employeeName'];
    $time = $_POST['time']; 

    date_default_timezone_set('Asia/Manila');
    $currentDateTime = date('Y-m-d H:i:s');
    echo "Employee Name: " . $employeeName;
    echo "         
";
    echo "Current Time: " . $currentDateTime;
    echo "         
";

    $sqlEmployee = "SELECT id FROM employees WHERE name = '$employeeName'";
    $resultEmployee = mysqli_query($connection, $sqlEmployee);

    if (mysqli_num_rows($resultEmployee) > 0) {
        $rowEmployee = mysqli_fetch_assoc($resultEmployee);
        $employeeId = $rowEmployee['id'];

        if ($action === 'Time In') {
            $status = 'Time In';
            $sqlInsert = "INSERT INTO attendance (employee_id, name, time_in, status) VALUES ('$employeeId', '$employeeName', '$currentDateTime', '$status')";
            
            if (mysqli_query($connection, $sqlInsert)) {
                echo "   TIME - IN ✔️ ";
            } else {
                echo "Error: " . $sqlInsert . "<br>" . mysqli_error($connection);
            }
        } else if ($action === 'Time Out') {
            $status = 'Time Out';
            $sqlUpdate = "UPDATE attendance SET time_out = '$currentDateTime', total_hours = TIMEDIFF('$currentDateTime', time_in), status = '$status' WHERE employee_id = '$employeeId' AND time_out IS NULL";
            
            if (mysqli_query($connection, $sqlUpdate)) {
                echo "  TIME - OUT ✔️ ";
            } else {
                echo "Error updating attendance: " . mysqli_error($connection);
            }
        }
    } else {
        echo "Employee not found";
    }
} 
// New code for fetching attendance data
else if (isset($_POST['action']) && $_POST['action'] === 'getAttendance') {
   // $sql = "SELECT * FROM attendance";
    $sql = "SELECT *, TIME_FORMAT(time_in, '%h:%i %p') AS formatted_time_in, TIME_FORMAT(time_out, '%h:%i %p') AS formatted_time_out, TIMEDIFF(time_out, time_in) AS total_time FROM attendance";
    $result = mysqli_query($connection, $sql);

    $attendanceData = array(); // Initialize an empty array

    //if (mysqli_num_rows($result) > 0) {
    //    while ($row = mysqli_fetch_assoc($result)) {
      //      $attendanceData[] = $row;
        //}

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $row['total_hours'] = $row['total_time']; // Store total time difference in total_hours
                $row['time_in'] = $row['formatted_time_in']; // Format time in to 12-hour format
                $row['time_out'] = $row['formatted_time_out']; // Format time out to 12-hour format
                unset($row['formatted_time_in'], $row['formatted_time_out'], $row['total_time']); // Remove extra fields
                $attendanceData[] = $row;
            }

    } else {
        echo "No attendance records found.";
    }

    // Send the data back as JSON
    echo json_encode($attendanceData);
} else {
    echo "Invalid request";
}

mysqli_close($connection);
?>