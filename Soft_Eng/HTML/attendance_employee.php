<?php
include("php/database.php");

// Existing code for handling Time In/Time Out actions
if (isset($_POST['action']) && isset($_POST['employeeName']) && isset($_POST['time']) && isset($_POST['image'])) {
    $action = $_POST['action'];
    $employeeName = $_POST['employeeName'];
    $time = $_POST['time']; 
    $image = $_POST['image'];

    date_default_timezone_set('Asia/Manila');
    $currentDateTime = date('Y-m-d H:i:s');
    echo "Action: " . $action . "<br>";
    echo "Employee Name: " . $employeeName . "<br>";
    echo "Current Time: " . $currentDateTime . "<br>";

    $sqlEmployee = "SELECT id FROM employees WHERE name = '$employeeName'";
    $resultEmployee = mysqli_query($connection, $sqlEmployee);

    if (mysqli_num_rows($resultEmployee) > 0) {
        $rowEmployee = mysqli_fetch_assoc($resultEmployee);
        $employeeId = $rowEmployee['id'];

        if ($action === 'Time In') {
            $status = 'Time In';
            $sqlInsert = "INSERT INTO attendance (employee_id, name, time_in, image, status) VALUES ('$employeeId', '$employeeName', '$currentDateTime', '$image', '$status')";
            
            if (mysqli_query($connection, $sqlInsert)) {
                echo "Attendance recorded successfully for Time In";
            } else {
                echo "Error: " . $sqlInsert . "<br>" . mysqli_error($connection);
            }
        } else if ($action === 'Time Out') {
            $status = 'Time Out';
            $sqlUpdate = "UPDATE attendance SET time_out = '$currentDateTime', total_hours = TIMEDIFF('$currentDateTime', time_in), status = '$status' WHERE employee_id = '$employeeId' AND time_out IS NULL";
            
            if (mysqli_query($connection, $sqlUpdate)) {
                echo "Attendance updated successfully for Time Out";
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
    $sql = "SELECT * FROM attendance";
    $result = mysqli_query($connection, $sql);

    $attendanceData = array(); // Initialize an empty array

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
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
