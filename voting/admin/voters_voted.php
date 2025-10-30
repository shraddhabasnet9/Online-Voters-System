<?php
session_start();
error_reporting(0);
if ($_SESSION['adminLogin'] != 1) {
    header("location:index.php");
    exit();
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
    <style>
        .del,
        .edit,
        .verify {
            display: block;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .verify {
            background-color: royalblue;
        }

        td {
            padding: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <span class="menu-bar" id="show" onclick="showMenu()">&#9776;</span>
            <span class="menu-bar" id="hide" onclick="hideMenu()">&#9776;</span>
            <span class="logo">Voting System</span>
            <span class="profile" onclick="showProfile()"><img src="../res/user3.jpg" alt=""><label><?php echo $_SESSION['name']; ?></label></span>
        </div>
        <?php include '../includes/menu.php'; ?>
        <div id="profile-panel">
            <i class="fa-solid fa-circle-xmark" onclick="hidePanel()"></i>
            <div class="dp"><img src="../res/user3.jpg" alt=""></div>
            <div class="info">
                <h2><?php echo $_SESSION['name']; ?></h2>
                <h5>Admin</h5>
            </div>
            <div class="link"><a href="../includes/admin-logout.php" class="del"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a></div>
        </div>
        <div id="main">
            <div class="heading">
                <h2 style="background:royalblue;">Voted Voters</h2>
            </div>
            <table class="table">
                <thead>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Id Name</th>
                    <th>Id number</th>
                    <th>ID Card</th>
                    <th>DOB</th>
                    <th>Gender</th>
                    <th>Phone No</th>
                    <th>Email Address</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php
                    $con = mysqli_connect('localhost', 'root', '', 'voting');

                    // ✅ Only show voters who are verified and have voted
                    $query = "SELECT * FROM register WHERE verify='yes' AND status='voted'";

                    $data = mysqli_query($con, $query);

                    while ($result = mysqli_fetch_assoc($data)) {
                        echo "<tr>
                        <td>" . $result['name'] . "</td>
                        <td>" . $result['address'] . "</td>
                        <td><h4>" . $result['idname'] . "</h4></td>
                        <td><h4>" . $result['idnum'] . "</h4></td>
                        <td><a href='../" . $result['idcard'] . "'><img src='../" . $result['idcard'] . "'></a></td>
                        <td>" . $result['dob'] . "</td>
                        <td>" . $result['gender'] . "</td>
                        <td>" . $result['phone'] . "</td>
                        <td>" . $result['email'] . "</td>
                        <td>" . $result['status'] . "</td>
                        <td>
                            <a href='user-update.php?n=$result[name]&ad=$result[address]&idname=$result[idname]&idno=$result[idnum]&ph=$result[phone]&em=$result[email]' class='edit'><i class='fa-solid fa-pen-to-square'></i> Edit</a>
                            <a href='user-delete.php?ph=$result[phone]&file_path=$result[idcard]' class='del' onClick='return delconfirm()'><i class='fa-solid fa-trash-can'></i> Delete</a>
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
            return confirm('Delete this Voter?');
        }

        function validconfirm() {
            return confirm('Validate this Voter?');
        }
    </script>
</body>

</html>