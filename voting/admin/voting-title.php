<?php
session_start();
if ($_SESSION['adminLogin'] != 1) {
    header("location:index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="heading">
                <h1>Online Voting System</h1>
            </div>
            <div class="form">
                <h4>Title for Voting</h4>
                <label class="label">Title:</label>
                <input type="text" name="title" id="" class="input" placeholder="Enter voting title" required>

                <button class="button" name="set">Set</button>
            </div>
        </form>
        </form>
    </div>
</body>

</html>

<?php

if (isset($_POST['set'])) {
    $con = mysqli_connect("localhost", "root", "", "voting");
    $vote_title = mysqli_real_escape_string($con, $_POST['title']);

    // Check if title already exists
    $check_query = "SELECT * FROM vote_title WHERE voting_title='$vote_title'";
    $result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Voting title already exists!')</script>";
    } else {
        // Insert new title
        $insert_query = "INSERT INTO vote_title(voting_title) VALUES('$vote_title')";
        if (mysqli_query($con, $insert_query)) {
            echo "<script>alert('Title set successfully!')</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($con) . "')</script>";
        }
    }
}

?>