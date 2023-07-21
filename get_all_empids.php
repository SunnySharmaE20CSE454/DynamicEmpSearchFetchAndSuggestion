<?php
include 'connection.php';

$query = "SELECT empid FROM hr_dump";
$result = mysqli_query($con, $query);

$empids = array();
while ($row = mysqli_fetch_assoc($result)) {
    $empids[] = $row['empid'];
}

echo json_encode($empids);
?>
