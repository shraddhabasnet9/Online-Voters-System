<?php
error_reporting(0);
$con = mysqli_connect("localhost", "root", "", "voting");

if (isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $idname = $_POST['idname'];
    $idnum = trim($_POST['idnum']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // Calculate age
    $date1 = new DateTime($dob);
    $date2 = new DateTime("now");
    $dateDiff = $date1->diff($date2);

    // Server-side validation
    if (strlen($phone) != 10 || !is_numeric($phone)) {
        echo "<script>alert('Phone Number must be numeric and 10 digits'); history.back();</script>";
        exit;
    }

    if (strlen($idnum) > 13 || !is_numeric($idnum)) {
        echo "<script>alert('Enter valid numeric ID number with max 13 digits'); history.back();</script>";
        exit;
    }

    if ($dateDiff->days < 6570) {
        echo "<script>alert('You must be at least 18 years old'); history.back();</script>";
        exit;
    }

    // Check duplicates separately
    $errors = [];

    $checkName = mysqli_query($con, "SELECT * FROM register WHERE name='$name'");
    if (mysqli_num_rows($checkName) > 0) {
        $errors['name'] = "Name already exists";
    }

    $checkPhone = mysqli_query($con, "SELECT * FROM register WHERE phone='$phone'");
    if (mysqli_num_rows($checkPhone) > 0) {
        $errors['phone'] = "Phone number already exists";
    }

    $checkEmail = mysqli_query($con, "SELECT * FROM register WHERE email='$email'");
    if (mysqli_num_rows($checkEmail) > 0) {
        $errors['email'] = "Email already exists";
    }

    $checkIdnum = mysqli_query($con, "SELECT * FROM register WHERE idnum='$idnum'");
    if (mysqli_num_rows($checkIdnum) > 0) {
        $errors['idnum'] = "ID number already exists";
    }

    // If any duplicate exists, show individual error alerts
    if (!empty($errors)) {
        foreach ($errors as $field => $msg) {
            echo "<script>
                    alert('$msg');
                  </script>";
        }
        echo "<script>history.back();</script>";
        exit;
    }

    // Handle file upload
    $filename = $_FILES["idcard"]["name"];
    $tempname = $_FILES["idcard"]["tmp_name"];
    $idcard = "img/" . time() . "_" . $filename;
    move_uploaded_file($tempname, $idcard);

    // Insert into DB
    $query = "INSERT INTO register(name,address,idname,idnum,idcard,dob,gender,phone,email,verify,status) 
              VALUES('$name','$address','$idname','$idnum','$idcard','$dob','$gender','$phone','$email','no','not voted')";
    $data = mysqli_query($con, $query);

    if ($data) {
        echo "<script>alert('Registration Successful!'); location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error occurred while registering'); history.back();</script>";
    }
}
