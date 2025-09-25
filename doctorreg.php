<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
var_dump($_POST);

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dmw";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $First_Name      = trim($_POST['firstName']);
    $Last_Name       = trim($_POST['lastName']);
    $Email           = trim($_POST['email']);
    $Contact         = trim($_POST['phone']);
    $Speciality      = $_POST['specialty'];
    $working_Yrs     = $_POST['experience'];
    $WorkingHospital = trim($_POST['hospital']);
    $Doctor_Id       = trim($_POST['doctorId']);
    $Password        = $_POST['password'];
    $Re_password     = $_POST['confirmPassword'];

    // Password validation
    if ($Password !== $Re_password) {
        die("<script>alert('Passwords do not match'); window.history.back();</script>");
    }

    // Hash the password
    $passwordHash = password_hash($Password, PASSWORD_BCRYPT);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO Doctor (First_Name, Last_Name, Email, Contact, Speciality, working_Yrs, WorkingHospital, Doctor_Id, Password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $First_Name, $Last_Name, $Email, $Contact, $Speciality, $working_Yrs, $WorkingHospital, $Doctor_Id, $passwordHash);

    // Execute and check
    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='doctorlogin.html';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>
