<?php

$id = $_GET['id'];
$con = mysqli_connect('localhost', 'root', '', 'voting');
$query = "DELETE FROM voting WHERE id='$id'";
$data = mysqli_query($con, $query);

if ($data) {
    echo "<script> 
            alert('Schedule deleted!');
    history.back()</script>";
}
