<?php
session_start();
header('Content-Type: application/json');
require_once "config.php";

// Check if ticketNumber is provided
if (!isset($_GET['ticketNumber'])) {
    echo json_encode(['success' => false, 'error' => 'Ticket number not provided.']);
    exit();
}

$ticketNumber = $_GET['ticketNumber'];

// Ensure the user is logged in as an Administrator
if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'ADMINISTRATOR') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access.']);
    exit();
}

$adminUser = $_SESSION['username'];

// Verify that the ticket exists and its status is "FOR APPROVAL"
$sql_check = "SELECT Status FROM tbltickets WHERE TicketNumber = ?";

if ($stmt = mysqli_prepare($link, $sql_check)) 
{
    mysqli_stmt_bind_param($stmt, "s", $ticketNumber);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        echo json_encode(['success' => false, 'error' => 'Ticket not found.']);
        exit();
    }

    $row = mysqli_fetch_assoc($result);

    if ($row['Status'] !== "FOR APPROVAL") {
        echo json_encode(['success' => false, 'error' => 'Ticket cannot be approved.']);
        exit();
    }
    mysqli_stmt_close($stmt);

} else {
    echo json_encode(['success' => false, 'error' => 'Error preparing statement.']);
    exit();
}

// Update the ticket: set status to "CLOSED", update DateApproved and ApprovedBy
$dateApproved = date("Y-m-d H:i:s");
$sql_update = "UPDATE tbltickets SET Status = 'CLOSED', DateApproved = ?, ApprovedBy = ? WHERE TicketNumber = ?";

if ($stmt = mysqli_prepare($link, $sql_update)) {
    mysqli_stmt_bind_param($stmt, "sss", $dateApproved, $adminUser, $ticketNumber);

    if (mysqli_stmt_execute($stmt)) {
        // Insert log entry
        $logSql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) VALUES (?, ?, ?, ?, ?, ?)";
        
        if ($logStmt = mysqli_prepare($link, $logSql)) 
            {
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $action = "APPROVE";
                $module = "ticket";
                $username = $_SESSION['username'];
                mysqli_stmt_bind_param($logStmt, "ssssss", $date, $time, $action, $module, $ticketNumber, $username);
                mysqli_stmt_execute($logStmt);
                mysqli_stmt_close($logStmt);
            }
        
        echo json_encode(['success' => true]);
    } 
    else {
        echo json_encode(['success' => false, 'error' => mysqli_error($link)]);
    }
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'error' => 'Error preparing update statement.']);
}

mysqli_close($link);
?>
