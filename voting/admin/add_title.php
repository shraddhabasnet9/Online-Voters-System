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

    $voting_title = trim($_POST['voting_title']); // Remove extra spaces

    // Check if position already exists
    $check_query = "SELECT * FROM vote_title WHERE voting_title='$voting_title'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>
                alert('vote title already exists!');
                window.location.href='voting-title.php';
              </script>";
        exit();
    } else {
        // Insert new position
        $insert_query = "INSERT INTO vote_title (voting_title) VALUES ('$voting_title')";
        $insert_result = mysqli_query($con, $insert_query);

        if ($insert_result) {
            echo "<script>
                    alert('vote title added successfully');
                    window.location.href='voting-title.php';
                  </script>";
            exit();
        } else {
            echo "<script>
                    alert('Failed to add vote title!');
                    window.location.href='voting-title.php';
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
    <title>Online Voting System vote_titles</title>
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>
    <div class="container">
        <div class="heading">
            <h1>Online Voting System</h1>
        </div>

        <!-- Add vote_title Form -->
        <div class="form">
            <h4>Add vote title</h4>
            <form action="" method="POST">
                <label class="label">vote_title Name:</label>
                <input type="text" name="voting_title" class="input" placeholder="Enter vote_title" required>
                <button class="button" name="add">Add</button>
            </form>
        </div>
    </div>
</body>

</html>