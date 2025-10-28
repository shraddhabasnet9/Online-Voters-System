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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Candidate Registration - Online Voting System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <div class="heading">
                <h1>Candidate Registration</h1>
            </div>

            <div class="form">
                <!-- Candidate Name -->
                <label class="label">Candidate Name:</label>
                <input type="text" name="cname" class="input" placeholder="Enter Candidate Name" required>

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
                        echo "<option value='{$row['election_id']}'>{$title} (From: {$start} - To: {$end})</option>";
                    }
                    ?>
                </select>

                <!-- Party/Symbol Name -->
                <label class="label">Party/Symbol Name:</label>
                <input type="text" name="csymbol" class="input" placeholder="Enter Party or Symbol Name" required>

                <!-- Symbol Photo -->
                <label class="label">Upload Party Symbol Image:</label>
                <input type="file" accept="image/*" name="cphoto" class="input" required>

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

                <button class="button" name="register">Register Candidate</button>
            </div>
        </form>
    </div>
</body>

</html>

<?php
if (isset($_POST['register'])) {
    $cname = mysqli_real_escape_string($con, trim($_POST['cname']));
    $election_id = mysqli_real_escape_string($con, $_POST['election_id']);
    $csymbol = mysqli_real_escape_string($con, trim($_POST['csymbol']));
    $position = mysqli_real_escape_string($con, $_POST['position']);

    // Handle file upload
    $filename = $_FILES["cphoto"]["name"];
    $tempname = $_FILES["cphoto"]["tmp_name"];
    $folder = "symbol/" . time() . "_" . basename($filename);

    if (!is_dir("symbol")) {
        mkdir("symbol", 0777, true);
    }

    // Step 1: Check for duplicate candidate (same name, election, party, position)
    $dup_check = mysqli_query($con, "
        SELECT * FROM candidate 
        WHERE cname = '$cname' 
          AND election_id = '$election_id' 
          AND symbol = '$csymbol' 
          AND position = '$position'
    ");

    if (mysqli_num_rows($dup_check) > 0) {
        echo "<script>alert('Duplicate entry! Same candidate, party, election, and position already exist.');</script>";
        exit();
    }

    // Step 2 (Optional): prevent same party in same election+position
    $party_check = mysqli_query($con, "
        SELECT * FROM candidate 
        WHERE symbol = '$csymbol' 
          AND election_id = '$election_id' 
          AND position = '$position'
    ");

    if (mysqli_num_rows($party_check) > 0) {
        echo "<script>alert('This party already has a candidate for this position in this election.');</script>";
        exit();
    }

    // Step 3: Upload image and insert candidate
    if (move_uploaded_file($tempname, $folder)) {
        $query = "
            INSERT INTO candidate (election_id, cname, symbol, symphoto, position, tvotes)
            VALUES ('$election_id', '$cname', '$csymbol', '$folder', '$position', 0)
        ";

        if (mysqli_query($con, $query)) {
            echo "<script>
                    alert('Candidate registered successfully!');
                    window.location.href='candidates.php';
                  </script>";
        } else {
            echo "<script>alert('Database error: " . mysqli_error($con) . "');</script>";
        }
    } else {
        echo "<script>alert('Failed to upload symbol image.');</script>";
    }
}
?>