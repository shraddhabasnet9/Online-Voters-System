<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <canvas id="myChart"></canvas>
    <script src="../js/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
        });
    </script>
</body>

</html>
<?php

$con = mysqli_connect("localhost", "root", "", "voting");
if (isset($_POST['set'])) {
    $starting = $_POST['start'];
    $ending = $_POST['end'];
    $query = "UPDATE voting SET reg_start_date='$starting', reg_end_date='$ending'";
    $data = mysqli_query($con, $query);

    if (!$data) {
        echo "<script> alert('something went wrong!') </script>";
    } else {
        echo "<script> alert('Successfully update') </script>";
    }
}
?>
<?php
include 'db_connect.php'; // your connection file

$voter_id = $_SESSION['voter_id'];   // the logged-in voter
$candidate_id = $_POST['candidate']; // from the form

// Step 1: Insert vote
mysqli_query($conn, "INSERT INTO votes (voter_id, candidate_id) VALUES ('$voter_id', '$candidate_id')");

// Step 2: Increment candidate vote count
mysqli_query($conn, "UPDATE candidate SET tvotes = tvotes + 1 WHERE id = '$candidate_id'");

// Step 3: Mark voter as voted
mysqli_query($conn, "UPDATE register SET status = 'voted' WHERE id = '$voter_id'");

echo "Vote submitted successfully!";
?>