<?php 
session_start();
include("php/database.php");

if(isset($_POST['name'], $_POST['employee_id'], $_POST['email'], $_POST['gender'], $_POST['department'], $_POST['salary'], $_POST['start_date'])) {

    $name = $_POST['name'];
    $employee_id = $_POST['employee_id'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $department = implode(',', (array)$_POST['department']); 
    $salary = $_POST['salary'];
    $start_date = $_POST['start_date'];

    $stmt = $connection->prepare("INSERT INTO employees (name, employee_id, email, gender, department, salary, start_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $employee_id, $email, $gender, $department, $salary, $start_date);

    if ($stmt->execute()) {
        header("Location: positionlist.php");
        exit();
    } else {
        echo "Error: " . $connection->error;
    }
    $stmt->close();
} else {
    echo "All fields are required";
}
$connection->close();
?>
