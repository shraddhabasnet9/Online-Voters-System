<?php
session_start();
include("db_connection.php"); // Your DB connection

$phone = $_POST['phone'] ?? null;
$resend = $_POST['resend'] ?? 0;

if (!$phone) {
    echo "<script>alert('Phone number is required'); history.back();</script>";
    exit;
}

// Fetch user from DB
$query = "SELECT * FROM register WHERE phone='$phone'";
$result = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "<script>alert('Phone number not registered'); history.back();</script>";
    exit;
}

// Set session data
$_SESSION['name'] = $user['name'];
$_SESSION['idnum'] = $user['idnum'];
$_SESSION['phone'] = $user['phone'];
$_SESSION['idcard'] = $user['idcard'];
$_SESSION['verify'] = $user['verify'];
$_SESSION['password'] = $user['password'];
$_SESSION['status'] = $user['status'];
$_SESSION['otp'] = null;

if ($_SESSION['verify'] != "yes") {
    echo "<script>alert('You are not verified by Admin'); location.href='index.php';</script>";
    exit;
}

// Validate phone number
if (!preg_match("/^[0-9]{10}$/", $phone)) {
    echo "<script>alert('Invalid mobile number'); history.back();</script>";
    exit;
}

// Generate OTP
$otp = rand(1111, 9999);
$_SESSION['otp'] = $otp;
$_SESSION['userLogin'] = 1;

// === MSG91 Transactional SMS for Nepal ===
$authkey = "475250AdqbrCQI069007c7dP1"; // Replace with your key
$senderId = "OTPForOVS";             // Any approved sender ID
$country = "977";                  // Nepal country code

$message = "Your OTP for Online Voting System login is $otp. Do not share it.";

// Prepare POST data
$postData = [
    "sender" => $senderId,
    "route" => "4",        // Transactional SMS
    "country" => $country,
    "sms" => [
        [
            "message" => $message,
            "to" => [$phone]
        ]
    ]
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.msg91.com/api/v2/sms/send",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($postData),
    CURLOPT_HTTPHEADER => [
        "authkey: $authkey",
        "Content-Type: application/json"
    ],
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 30,
]);

$response = curl_exec($curl);
$err = curl_error($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

if ($err) {
    echo "<script>alert('Failed to send OTP! Connection error: " . addslashes($err) . "');</script>";
} elseif ($http_code != 200) {
    echo "<script>alert('Failed to send OTP! HTTP response code: $http_code'); console.log('Response: " . addslashes($response) . "');</script>";
} else {
    $data = json_decode($response, true);

    if (isset($data['type']) && strtolower($data['type']) == 'success') {
        echo "<script>alert('OTP sent successfully to your phone.');</script>";
    } else {
        echo "<script>alert('Failed to send OTP. Response: " . addslashes($response) . "');</script>";
    }
}
