<?php
session_start();
require_once './db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: student_login.php");
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Check if the user is registered for any bus
$sql_check_registration = "SELECT * FROM bus_registrations WHERE user_id_student = '$user_id'";
$result_check_registration = mysqli_query($conn, $sql_check_registration);

if ($result_check_registration && mysqli_num_rows($result_check_registration) > 0) {
    // User is registered, redirect to dashboard
    header("Location: student_dashboard.php");
    exit();
} else {
    // User is not registered, redirect to registration page
    header("Location: student_registration.php");
    exit();
}
?>
