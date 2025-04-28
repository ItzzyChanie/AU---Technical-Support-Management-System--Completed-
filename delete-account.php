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
                $module = "Account Management";
                $performedby = $_SESSION['username'];
                mysqli_stmt_bind_param($log_stmt, "ssssss", $datelog, $timelog, $action, $module, $username, $performedby);
                mysqli_stmt_execute($log_stmt);
            }

            // Show success modal
            echo "<script>
                    window.onload = function() {
                        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                        successModal.show();
                    };
                  </script>";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white"> <!-- Green background for header -->
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Account Deleted Successfully!</p>
            </div>
            <div class="modal-footer">
                <a href="accounts-management.php" class="btn btn-success">Okay</a> <!-- Green "Okay" button -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Example script to trigger the modal (replace with actual logic as needed)
    function showSuccessModal() {
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    }

    // Uncomment the line below to test the modal
    // showSuccessModal();
</script>
</body>
</html>
