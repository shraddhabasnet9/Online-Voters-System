<?php
<<<<<<< HEAD
error_reporting(0);
$con = mysqli_connect("localhost", "root", "", "voting");
=======
// error_reporting(0);
$con = mysqli_connect("localhost","root","","voting");
>>>>>>> 2d8b5f8169caaef785bb73ed3f4d65dd61a85bfe

// candidate data
$can_query = "SELECT * FROM candidate";
$can_data = mysqli_query($con, $can_query);
$_SESSION["total_cand"] = mysqli_num_rows($can_data);
$total_cand = mysqli_num_rows($can_data);

// user register data
$voter_query = "SELECT * FROM register";
$voter_data = mysqli_query($con, $voter_query);
$_SESSION["total_voters"] = mysqli_num_rows($voter_data);

// candidate position data
$pos_query = "SELECT * FROM can_position";
$pos_data = mysqli_query($con, $pos_query);
$_SESSION["total_position"] = mysqli_num_rows($pos_data);
$total_pos = mysqli_num_rows($pos_data);

//title data
$vote_query = "SELECT * FROM vote_title";
$vote_data = mysqli_query($con, $vote_query);
$_SESSION["total_titles"] = mysqli_num_rows($vote_data);
$total_vote = mysqli_num_rows($vote_data);
