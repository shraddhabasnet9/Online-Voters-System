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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/all.min.css">
</head>
<style>
    .form {
        position: absolute;
        background: #fff;
        border-radius: 0rem;
        box-shadow: none;
        margin: 1rem;
        height: 0rem;
    }

    .add-btn {
        text-decoration: none;
    }

    /* Small thumbnail for symbol images */
    .symbol-thumb {
        width: 50px;
        height: 50px;
        object-fit: contain;
        border: 1px solid #ccc;
        padding: 2px;
        border-radius: 4px;
    }
</style>

<body>
    <div class="container">
        <div class="header">
            <span class="menu-bar" id="show" onclick="showMenu()">&#9776;</span>
            <span class="menu-bar" id="hide" onclick="hideMenu()">&#9776;</span>
            <span class="logo">Voting System</span>
            <span class="profile" onclick="showProfile()">
                <img src="../res/user3.jpg" alt="">
                <label><?php echo htmlspecialchars($_SESSION['name']); ?></label>
            </span>
        </div>

        <div id="profile-panel">
            <i class="fa-solid fa-circle-xmark" onclick="hidePanel()"></i>
            <div class="dp"><img src="../res/user3.jpg" alt=""></div>
            <div class="info">
                <h2><?php echo htmlspecialchars($_SESSION['name']); ?></h2>
                <h5>Admin</h5>
            </div>
            <div class="link"><a href="../includes/admin-logout.php" class="del"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a></div>
        </div>

        <?php include '../includes/menu.php'; ?>

        <div id="main">
            <div class="heading">
                <a href="cand-register.php" class="add-btn" onclick="showForm()">+ Add</a>
                <h2>Candidates Information</h2>
            </div>

            <table class="table">
                <thead>
                    <th>Candidate Name</th>
                    <th>Candidate Party</th>
                    <th>Party Symbol Image</th>
                    <th>Position</th>
                    <th>Election Title</th>
                    <th>Total Votes</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php
                    // Fetch candidates with election info
                    $query = "
                        SELECT c.*, v.id AS election_id, vt.voting_title, v.vot_start_date, v.vot_end_date
                        FROM candidate c
                        INNER JOIN voting v ON c.election_id = v.id
                        INNER JOIN vote_title vt ON v.vote_title_id = vt.id
                        ORDER BY v.vot_start_date DESC, c.position ASC
                    ";
                    $can_data = mysqli_query($con, $query);
                    if (!$can_data) {
                        die("Error fetching candidates: " . mysqli_error($con));
                    }

                    while ($result = mysqli_fetch_assoc($can_data)) {
                        $cname = htmlspecialchars($result['cname']);
                        $symbol = htmlspecialchars($result['symbol']);
                        $position = htmlspecialchars($result['position']);
                        $tvotes = $result['tvotes'];
                        $symphoto = $result['symphoto'];
                        $election_title = htmlspecialchars($result['voting_title']);
                        $election_period = date('M d, Y', strtotime($result['vot_start_date'])) . " - " . date('M d, Y', strtotime($result['vot_end_date']));
                        $edit_url = "cand-update.php?cn=" . urlencode($result['cname']) . "&sy=" . urlencode($result['symbol']) . "&ps=" . urlencode($result['position']);
                        $delete_url = "can-delete.php?id=" . $result['id'];

                        echo "<tr>
                            <td>$cname</td>
                            <td>$symbol</td>
                            <td><a href='$symphoto'><img src='$symphoto' class='symbol-thumb'></a></td>
                            <td>$position</td>
                            <td>$election_title ($election_period)</td>
                            <td>$tvotes</td>
                            <td>
                                <a href='$edit_url' class='edit'><i class='fa-solid fa-pen-to-square'></i> Edit</a>
                                <a href='$delete_url' class='del' onclick='return delconfirm()'><i class='fa-solid fa-trash-can'></i> Delete</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../js/script.js"></script>
    <script>
        function delconfirm() {
            return confirm('Delete this Candidate?');
        }
    </script>
</body>

</html>