<?php
session_start();
if ($_SESSION['adminLogin'] != 1) {
    header("location:index.php");
    exit();
}

// Database connection
$con = mysqli_connect("localhost", "root", "", "voting");
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle form submission
if (isset($_POST['add'])) {

    $pos_name = trim($_POST['position']); // Remove extra spaces

    // Check if position already exists
    $check_query = "SELECT * FROM can_position WHERE position_name='$pos_name'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>
                alert('Position already exists!');
                window.location.href='position.php';
              </script>";
        exit();
    } else {
        // Insert new position
        $insert_query = "INSERT INTO can_position (position_name) VALUES ('$pos_name')";
        $insert_result = mysqli_query($con, $insert_query);

        if ($insert_result) {
            echo "<script>
                    alert('Position added successfully');
                    window.location.href='position.php';
                  </script>";
            exit();
        } else {
            echo "<script>
                    alert('Failed to add position!');
                    window.location.href='position.php';
                  </script>";
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System - Positions</title>
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>
    <div class="container">
        <div class="heading">
            <h1>Online Voting System</h1>
        </div>

        <!-- Add Position Form -->
        <div class="form">
            <h4>Add Positions</h4>
            <form action="" method="POST">
                <label class="label">Position Name:</label>
                <input type="text" name="position" class="input" placeholder="Enter position" required>
                <button class="button" name="add">Add</button>
            </form>
        </div>
    </div>
</body>

</html>