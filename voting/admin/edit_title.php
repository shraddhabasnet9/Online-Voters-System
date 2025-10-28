<?php
$voting_title = $_GET['voting_title'];
$id = $_GET['id'];
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
        <div class="heading">
            <h1>Online Voting System</h1>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form">
                <h4>Voting Title Update</h4>

                <label class="label">Voting Title:</label>
                <input type="text" name="voting_title" class="input"
                    value="<?php echo htmlspecialchars($voting_title); ?>" required>

                <button class="button" name="update">Update</button>
            </div>
        </form>
    </div>

    <?php
    if (isset($_POST['update'])) {
        $voting_title = trim($_POST['voting_title']);
        $con = mysqli_connect("localhost", "root", "", "voting");

        if (!$con) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        // 🔍 Check if same voting title already exists (excluding the current one)
        $check = "SELECT * FROM vote_title WHERE voting_title = '$voting_title' AND id != '$id'";
        $check_run = mysqli_query($con, $check);

        if (mysqli_num_rows($check_run) > 0) {
            echo "
            <script>
                alert('This voting title already exists! Please use another title.');
                location.href='voting-title.php';
            </script>
        ";
        } else {
            // ✅ Proceed with update if no duplicate found
            $query = "UPDATE vote_title SET voting_title = '$voting_title' WHERE id = '$id'";
            $data = mysqli_query($con, $query);

            if ($data) {
                echo "
                <script>
                    alert('Voting title updated successfully!');
                    location.href='voting-title.php';
                </script>
            ";
            } else {
                echo "
                <script>
                    alert('Error updating voting title.');
                    location.href='voting-title.php';
                </script>
            ";
            }
        }

        mysqli_close($con);
    }
    ?>
</body>

</html>