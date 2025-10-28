<?php

$id = $_GET['id'];
$con = mysqli_connect('localhost', 'root', '', 'voting');
$query = "DELETE FROM vote_title WHERE id='$id'";
$data = mysqli_query($con, $query);

if ($data) {
    echo "<script> 
            alert('title deleted!');
    window.location.href='voting-title.php';</script>";
}
