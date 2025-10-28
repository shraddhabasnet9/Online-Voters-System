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

$cn = $_GET['cn'] ?? '';
$sy = $_GET['sy'] ?? '';
$ps = $_GET['ps'] ?? '';

// Fetch current candidate data
$can_query = mysqli_query($con, "SELECT * FROM candidate WHERE cname='$cn' AND symbol='$sy' AND position='$ps'");
if (!$can_query || mysqli_num_rows($can_query) == 0) {
    die("Candidate not found.");
}
$can_data = mysqli_fetch_assoc($can_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Candidate - Online Voting System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <div class="heading">
                <h1>Update Candidate</h1>
            </div>

            <div class="form">
                <!-- Candidate Name -->
                <label class="label">Candidate Name:</label>
                <input type="text" name="cname" class="input" value="<?php echo htmlspecialchars($can_data['cname']); ?>" required>

                <!-- Election Dropdown -->
                <label class="label">Election (Voting Schedule):</label>
                <select name="election_id" class="input" required>
                    <option value="">-- Select Election --</option>
                    <?php
                    $query = "
                        SELECT v.id AS election_id, vt.voting_title, v.vot_start_date, v.vot_end_date
                        FROM voting v
                        INNER JOIN vote_title vt ON v.vote_title_id = vt.id
                        ORDER BY v.vot_start_date DESC
                    ";
                    $elections = mysqli_query($con, $query);
                    if (!$elections) {
                        die('Error fetching elections: ' . mysqli_error($con));
                    }

                    while ($row = mysqli_fetch_assoc($elections)) {
                        $title = htmlspecialchars($row['voting_title']);
                        $start = date('M d, Y h:i A', strtotime($row['vot_start_date']));
                        $end = date('M d, Y h:i A', strtotime($row['vot_end_date']));
                        $selected = ($row['election_id'] == $can_data['election_id']) ? 'selected' : '';
                        echo "<option value='{$row['election_id']}' $selected>{$title} (From: {$start} - To: {$end})</option>";
                    }
                    ?>
                </select>

                <!-- Party/Symbol Name -->
                <label class="label">Party/Symbol Name:</label>
                <input type="text" name="symbol" class="input" value="<?php echo htmlspecialchars($can_data['symbol']); ?>" required>

                <!-- Position -->
                <label class="label">Select Position:</label>
                <select name="position" class="input" required>
                    <?php
                    include "../includes/all-select-data.php";
                    while ($result = mysqli_fetch_assoc($pos_data)) {
                        $pos = htmlspecialchars($result['position_name']);
                        echo "<option value='{$pos}'>{$pos}</option>";
                    }
                    ?>
                </select>


                <button class="button" name="update">Update Candidate</button>
            </div>
        </form>
    </div>

    <?php
    if (isset($_POST['update'])) {
        $cname = mysqli_real_escape_string($con, $_POST['cname']);
        $election_id = mysqli_real_escape_string($con, $_POST['election_id']);
        $symbol = mysqli_real_escape_string($con, $_POST['symbol']);
        $position = mysqli_real_escape_string($con, $_POST['position']);

        // Update candidate in database
        $update_query = "
        UPDATE candidate 
        SET cname='$cname', symbol='$symbol', position='$position', election_id='$election_id'
        WHERE cname='$cn' AND symbol='$sy' AND position='$ps'
    ";

        $data = mysqli_query($con, $update_query);
        if ($data) {
            echo "<script>
                alert('Candidate updated successfully!');
                window.location.href='candidates.php';
              </script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
        }
    }
    ?>
</body>

</html>