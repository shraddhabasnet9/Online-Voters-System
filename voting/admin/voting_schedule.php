<?php
session_start();
if ($_SESSION['adminLogin'] != 1) {
    header("location:index.php");
    exit();
}
$con = mysqli_connect("localhost", "root", "", "voting");
if (!$con) die("Database connection failed: " . mysqli_connect_error());

// Handle form submission
if (isset($_POST['add_schedule'])) {
    $vote_title_id = $_POST['vote_title_id'];
    $start = $_POST['vot_start_date'];
    $end = $_POST['vot_end_date'];

    $query = "INSERT INTO voting (vote_title_id, vot_start_date, vot_end_date) 
              VALUES ('$vote_title_id', '$start', '$end')";

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Voting schedule added successfully!'); window.location.href='';</script>";
        exit;
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}

// Fetch voting titles for dropdown
$titles = mysqli_query($con, "SELECT * FROM vote_title");

// Fetch all voting schedules for table
$schedules = mysqli_query($con, "
    SELECT voting.id, voting.vot_start_date, voting.vot_end_date, vote_title.voting_title
    FROM voting
    INNER JOIN vote_title ON voting.vote_title_id = vote_title.id
    ORDER BY voting.vot_start_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Voting Schedule</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .button-small {
            padding: 5px 10px;
            margin: 2px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <span class="menu-bar" id="show" onclick="showMenu()">&#9776;</span>
            <span class="menu-bar" id="hide" onclick="hideMenu()">&#9776;</span>
            <span class="logo">Voting System</span>
            <span class="profile" onclick="showProfile()"><img src="../res/user3.jpg" alt=""><label for=""><?php echo $_SESSION['name']; ?></label></span>
        </div>
        <div id="profile-panel">
            <i class="fa-solid fa-circle-xmark" onclick="hidePanel()"></i>
            <div class="dp"><img src="../res/user3.jpg" alt=""></div>
            <div class="info">
                <h2><?php echo $_SESSION['name']; ?></h2>
                <h5>Admin</h5>
            </div>
            <div class="link"><a href="../includes/admin-logout.php" class="del"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a></div>
        </div>
        <?php include '../includes/menu.php'; ?>
        <div class="heading">
            <h1>Online Voting System</h1>
        </div>
        <div class="form">
            <h4>Voting Schedule</h4>
            <form method="POST">
                <label class="label">Voting Title:</label>
                <select name="vote_title_id" class="input" required>
                    <option value="">--Select Voting Title--</option>
                    <?php while ($row = mysqli_fetch_assoc($titles)) { ?>
                        <option value="<?= $row['id'] ?>"><?= $row['voting_title'] ?></option>
                    <?php } ?>
                </select>

                <label class="label">Valid From:</label>
                <input type="datetime-local" name="vot_start_date" class="input" required>

                <label class="label">Valid To:</label>
                <input type="datetime-local" name="vot_end_date" class="input" required>

                <button class="button" name="add_schedule">Set</button>
            </form>
        </div>

        <h4>Existing Voting Schedules</h4>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Voting Title</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($sched = mysqli_fetch_assoc($schedules)) { ?>
                    <tr>
                        <td><?= $sched['id'] ?></td>
                        <td><?= $sched['voting_title'] ?></td>
                        <td><?= $sched['vot_start_date'] ?></td>
                        <td><?= $sched['vot_end_date'] ?></td>
                        <td>
                            <a href="edit_schedule.php?id=<?= $sched['id'] ?>" class="edit"><i class="fa fa-pencil-square-o"></i>Edit</a>
                            <a href="delete_schedule.php?id=<?= $sched['id'] ?>" class="del" onclick="return confirm('Are you sure?')"><i class='fa-solid fa-trash-can'></i>Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="../js/script.js"></script>
</body>

</html>