<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Include database configuration
include_once "config.php";

if (isset($_GET['close_id'])) {
    $id = $_GET['close_id'];
    
    $ins_sql = "UPDATE `rides` SET `ride_status` = 'completed' WHERE `rides`.`id` = '$id'";
    if (mysqli_query($conn, $ins_sql)) {
        $message = "Ride successfully completed!";
        header("Location: dashboard.php");
    } else {
        $message = "Error creating ride: " . mysqli_error($conn);
    }
}