<?php
// Start PHP and handle form submission
error_reporting(0);
$con = mysqli_connect("localhost", "root", "", "voting");
$errors = [];

if (isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $idname = $_POST['idname'];
    $idnum = trim($_POST['idnum']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $idcardFile = $_FILES['idcard'];

    // Server-side validation
    if (strlen($name) < 6 || preg_match('/\d/', $name)) {
        $errors['name'] = "Name must be at least 6 letters and cannot contain numbers";
    }

    if (strlen($address) < 6 || preg_match('/\d/', $address)) {
        $errors['address'] = "Address must be at least 6 letters and cannot contain numbers";
    }

    if (empty($idname)) {
        $errors['idname'] = "Please select ID proof";
    }

    if (!is_numeric($idnum) || strlen($idnum) < 4) {
        $errors['idnum'] = "ID number must be numeric and at least 4 digits";
    }

    if (!isset($idcardFile) || $idcardFile['error'] != 0) {
        $errors['idcard'] = "Please upload ID card photo";
    } elseif ($idcardFile['size'] > 1024 * 1024) {
        $errors['idcard'] = "Photo size must be less than 1 MB";
    }

    if (empty($dob)) {
        $errors['dob'] = "Please select your date of birth";
    } else {
        $dobDate = new DateTime($dob);
        $today = new DateTime();
        $age = $today->diff($dobDate)->y;
        if ($age < 18) {
            $errors['dob'] = "You must be at least 18 years old";
        }
    }

    if (empty($gender)) {
        $errors['gender'] = "Please select your gender";
    }

    if (!preg_match('/^98\d{8}$/', $phone) || preg_match('/^0+$/', $phone)) {
        $errors['phone'] = "Phone must be 10 digits starting with 98 and not all zeros";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address";
    }

    // Server-side duplicate checks
    if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM register WHERE name='$name'")) > 0) {
        $errors['name'] = "Name already exists";
    }
    if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM register WHERE phone='$phone'")) > 0) {
        $errors['phone'] = "Phone already exists";
    }
    if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM register WHERE email='$email'")) > 0) {
        $errors['email'] = "Email already exists";
    }
    if (mysqli_num_rows(mysqli_query($con, "SELECT * FROM register WHERE idnum='$idnum'")) > 0) {
        $errors['idnum'] = "ID number already exists";
    }

    // If no errors, insert the data
    if (empty($errors)) {
        $filename = "img/" . time() . "_" . basename($idcardFile['name']);
        move_uploaded_file($idcardFile['tmp_name'], $filename);

        $insertQuery = "INSERT INTO register(name,address,idname,idnum,idcard,dob,gender,phone,email,verify,status) 
                        VALUES('$name','$address','$idname','$idnum','$filename','$dob','$gender','$phone','$email','no','not voted')";
        if (mysqli_query($con, $insertQuery)) {
            echo "<script>alert('Registration Successful!'); window.location='index.php';</script>";
            exit();
        } else {
            $errors['general'] = "Database error occurred!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Online Voting System</title>
    <link rel="stylesheet" href="css/all_style.css">
    <style>
        .error {
            color: red;
            font-size: 0.9rem;
            margin-top: 2px;
            display: block;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="heading">
            <h1>Online Voting System</h1>
        </div>
        <form id="voterForm" action="" method="POST" enctype="multipart/form-data" novalidate>
            <div class="form">
                <h4>Voter Registration</h4>

                <label class="label"><sup class="req_symbol">*</sup>Voter Name:</label>
                <input type="text" name="name" id="fullname" class="input" placeholder="Enter Full Name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                <span class="error" id="nameError"><?= $errors['name'] ?? '' ?></span>

                <label class="label"><sup class="req_symbol">*</sup>Address:</label>
                <input type="text" name="address" id="address" class="input" placeholder="Enter Address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
                <span class="error" id="addressError"><?= $errors['address'] ?? '' ?></span>

                <label class="label"><sup class="req_symbol">*</sup>Choose ID Proof:</label>
                <select name="idname" id="myselect" class="input" onchange="idproof()">
                    <option value="">--Select ID Proof--</option>
                    <option value="Citizenship Card" <?= ($_POST['idname'] ?? '') == 'Citizenship Card' ? 'selected' : '' ?>>Citizenship Card</option>
                    <option value="National ID Card" <?= ($_POST['idname'] ?? '') == 'National ID Card' ? 'selected' : '' ?>>National ID Card</option>
                    <option value="ID Card" <?= ($_POST['idname'] ?? '') == 'ID Card' ? 'selected' : '' ?>>ID Card</option>
                    <option value="Vote Card" <?= ($_POST['idname'] ?? '') == 'Vote Card' ? 'selected' : '' ?>>Vote Card</option>
                </select>
                <span class="error" id="idnameError"><?= $errors['idname'] ?? '' ?></span>

                <label class="label" id="myid1"><sup class="req_symbol">*</sup>ID No:</label>
                <input type="text" name="idnum" id="idnum" placeholder="Enter ID Number" class="input" value="<?= htmlspecialchars($_POST['idnum'] ?? '') ?>">
                <span class="error" id="idnumError"><?= $errors['idnum'] ?? '' ?></span>

                <label class="label" id="myid"><sup class="req_symbol">*</sup>ID Card Photo:</label>
                <input type="file" accept="image/*" name="idcard" id="myfile" class="input">
                <span class="error" id="idcardError"><?= $errors['idcard'] ?? '' ?></span>

                <label class="label"><sup class="req_symbol">*</sup>Date of Birth:</label>
                <input type="date" name="dob" id="dob" class="input" value="<?= $_POST['dob'] ?? '' ?>">
                <span class="error" id="dobError"><?= $errors['dob'] ?? '' ?></span>

                <label class="label"><sup class="req_symbol">*</sup>Gender:</label>
                <input type="radio" value="male" name="gender" id="male" class="radio" <?= ($_POST['gender'] ?? '') == 'male' ? 'checked' : '' ?>>Male
                <input type="radio" value="female" name="gender" id="female" class="radio" <?= ($_POST['gender'] ?? '') == 'female' ? 'checked' : '' ?>>Female
                <input type="radio" value="other" name="gender" id="other" class="radio" <?= ($_POST['gender'] ?? '') == 'other' ? 'checked' : '' ?>>Other
                <span class="error" id="genderError"><?= $errors['gender'] ?? '' ?></span>

                <label class="label"><sup class="req_symbol">*</sup>Phone Number:</label>
                <input type="text" name="phone" id="phone" class="input" placeholder="Enter Phone Number" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                <span class="error" id="phoneError"><?= $errors['phone'] ?? '' ?></span>

                <label class="label"><sup class="req_symbol">*</sup>Email Address:</label>
                <input type="text" name="email" id="email" class="input" placeholder="Enter Email Address" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <span class="error" id="emailError"><?= $errors['email'] ?? '' ?></span>

                <button class="button" type="submit" name="register">Register</button>
                <div class="link1">Already have account? <a href="index.php">Login here</a></div>
            </div>
        </form>
    </div>

    <script>
        function idproof() {
            var x = document.getElementById("myselect").value;
            document.getElementById("myid").innerHTML = x + ":";
            document.getElementById("myid1").innerHTML = x + " No:";
        }
    </script>
</body>

</html>