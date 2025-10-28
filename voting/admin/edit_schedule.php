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
if (!$con) die("Database connection failed: " . mysqli_connect_error());

// Fetch voting schedule details
$query = "SELECT * FROM voting WHERE id='$id'";
$result = mysqli_query($con, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Schedule not found!");
}

$schedule = mysqli_fetch_assoc($result);

// Fetch all voting titles for dropdown
$titles = mysqli_query($con, "SELECT * FROM vote_title");

// Handle form submission
if (isset($_POST['update_schedule'])) {
    $vote_title_id = $_POST['vote_title_id'];
    $start = $_POST['vot_start_date'];
    $end = $_POST['vot_end_date'];

    $update = "UPDATE voting 
               SET vote_title_id='$vote_title_id', vot_start_date='$start', vot_end_date='$end' 
               WHERE id='$id'";

    if (mysqli_query($con, $update)) {
        echo "<script>
                alert('Voting schedule updated successfully!');
                window.location.href='voting_schedule.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('Failed to update schedule: " . mysqli_error($con) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Voting Schedule</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <div class="heading">
            <h1>Edit Voting Schedule</h1>
        </div>
        <div class="form">
            <form method="POST">
                <label class="label">Voting Title:</label>
                <select name="vote_title_id" class="input" required>
                    <option value="">--Select Voting Title--</option>
                    <?php while ($row = mysqli_fetch_assoc($titles)) { ?>
                        <option value="<?= $row['id'] ?>" <?= ($row['id'] == $schedule['vote_title_id']) ? 'selected' : '' ?>>
                            <?= $row['voting_title'] ?>
                        </option>
                    <?php } ?>
                </select>

                <label class="label">Valid From:</label>
                <input type="datetime-local" name="vot_start_date" class="input"
                    value="<?= date('Y-m-d\TH:i', strtotime($schedule['vot_start_date'])) ?>" required>

                <label class="label">Valid To:</label>
                <input type="datetime-local" name="vot_end_date" class="input"
                    value="<?= date('Y-m-d\TH:i', strtotime($schedule['vot_end_date'])) ?>" required>

                <button class="button" name="update_schedule">Update Schedule</button>
            </form>
        </div>
    </div>
</body>

</html>