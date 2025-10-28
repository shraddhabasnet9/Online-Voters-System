<?php
session_start();
if ($_SESSION['adminLogin'] != 1) {
    header("location:index.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Invalid request!");
}

$con = mysqli_connect('localhost', 'root', '', 'voting');
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// 1. Get the request details from phno_change table
$query1 = "SELECT old_phno, new_phno FROM phno_change WHERE id='$id'";
$result = mysqli_query($con, $query1);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $oldPhone = $row['old_phno'];
    $newPhone = $row['new_phno'];

    // 2. Update the register table
    $query2 = "UPDATE register SET phone='$newPhone' WHERE phone='$oldPhone'";
    $update = mysqli_query($con, $query2);

    if ($update) {
        // 3. Optionally delete the request from phno_change table
        $query3 = "DELETE FROM phno_change WHERE id='$id'";
        mysqli_query($con, $query3);

        echo "<script>
                alert('Phone number updated successfully.');
                window.location.href='voter_request.php'; // back to requests page
              </script>";
        exit();
    } else {
        echo "<script>alert('Failed to update phone number.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Request not found.'); window.history.back();</script>";
}
