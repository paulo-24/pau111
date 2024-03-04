<?php
include("php/database.php");

if (isset($_POST['action']) && $_POST['action'] === 'deleteAttendance' && isset($_POST['id'])) {
    $recordId = $_POST['id'];

    // Perform deletion query
    $sqlDelete = "DELETE FROM attendance WHERE id = '$recordId'";
    
    if (mysqli_query($connection, $sqlDelete)) {
        echo json_encode(array('success' => true, 'message' => 'Record deleted successfully'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error deleting record'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request'));
}

mysqli_close($connection);
?>
