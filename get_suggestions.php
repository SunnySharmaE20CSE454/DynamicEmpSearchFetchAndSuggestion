<?php
include 'connection.php';

if (isset($_GET['term'])) {
    $term = $_GET['term'];
    $query = "SELECT empid FROM hr_dump WHERE empid LIKE '$term%'";
    $result = mysqli_query($con, $query);

    $suggestions = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $suggestions[] = $row['empid'];
    }

    echo json_encode($suggestions);
}
?>
