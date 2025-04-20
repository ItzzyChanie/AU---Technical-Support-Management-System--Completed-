<?php
require_once "config.php";
include ("session-checker.php");

if (isset($_GET['AssetNumber'])) {
    $assetNumber = $_GET['AssetNumber'];

    // Prepare the DELETE statement
    $sql = "DELETE FROM tblequipments WHERE AssetNumber = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $assetNumber);
        if (mysqli_stmt_execute($stmt)) {
            // Log the deletion
            $log_sql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($log_stmt = mysqli_prepare($link, $log_sql)) {
                $datelog = date("Y-m-d");
                $timelog = date("H:i:s");
                $action = "DELETE";
                $module = "Equipment";
                $performedby = $_SESSION['username'];
                mysqli_stmt_bind_param($log_stmt, "ssssss", $datelog, $timelog, $action, $module, $assetNumber, $performedby);
                mysqli_stmt_execute($log_stmt);
            }

            // Redirect with success message
            header("Location: equipment-management.php?success=true");
            exit();
        } else {
            echo "Error deleting equipment.";
        }
    } else {
        echo "Error preparing delete statement.";
    }
} else {
    echo "No Asset Number specified.";
}
?>
