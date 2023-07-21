<?php
include 'connection.php';

if (isset($_GET['term'])) {
    $empId = $_GET['term']; // Use 'term' as the parameter name for empId
    $query = "SELECT * FROM hr_dump WHERE empid = '$empId'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    } else {
        echo json_encode(null);
    }
}
?>
