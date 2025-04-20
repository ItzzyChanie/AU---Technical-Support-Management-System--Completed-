<?php
require_once "config.php";
include "session-checker.php";

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Prepare the DELETE statement
    $sql = "DELETE FROM tblaccounts WHERE username = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        if (mysqli_stmt_execute($stmt)) {
            // Log the deletion
            $log_sql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($log_stmt = mysqli_prepare($link, $log_sql)) {
                $datelog = date("Y-m-d");
                $timelog = date("H:i:s");
                $action = "DELETE";
                $module = "Account";
                $performedby = $_SESSION['username'];
                mysqli_stmt_bind_param($log_stmt, "ssssss", $datelog, $timelog, $action, $module, $username, $performedby);
                mysqli_stmt_execute($log_stmt);
            }

            // Redirect with success message
            header("Location: accounts-management.php?success=true");
            exit();
        } else {
            echo "Error deleting account.";
        }
    } else {
        echo "Error preparing delete statement.";
    }
} else {
    echo "No username specified.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delete Account</title>
</head>
<body>
</body>
</html>
