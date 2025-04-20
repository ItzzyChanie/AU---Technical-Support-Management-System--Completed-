<?php
session_start();
require_once "config.php";

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "Unauthorized access."]);
    exit();
}

if (isset($_GET['ticketNumber'])) {
    $ticketNumber = $_GET['ticketNumber'];
    $username = $_SESSION['username'];
    $usertype = $_SESSION['usertype'];

    // 1) Check if the ticket status is PENDING or CLOSED and belongs to the user or is an admin
    $sqlCheck = "SELECT Status, CreatedBy FROM tbltickets WHERE TicketNumber = ?";

    if ($stmtCheck = mysqli_prepare($link, $sqlCheck)) 
    {
        mysqli_stmt_bind_param($stmtCheck, "s", $ticketNumber);
        mysqli_stmt_execute($stmtCheck);
        $resultCheck = mysqli_stmt_get_result($stmtCheck);
        $ticketInfo = mysqli_fetch_assoc($resultCheck);
        mysqli_stmt_close($stmtCheck);

        // Check if ticket exists and is PENDING or CLOSED
        if (!$ticketInfo || !in_array($ticketInfo['Status'], ['Pending', 'CLOSED'])) {
            echo json_encode(["error" => "Cannot delete this ticket. It must be in PENDING or CLOSED status."]);
            exit();
        }

        // Check if user is the ticket creator or an administrator
        if ($usertype !== 'ADMINISTRATOR' && $ticketInfo['CreatedBy'] !== $username) {
            echo json_encode(["error" => "You are not authorized to delete this ticket."]);
            exit();
        }

        // Start a transaction
        mysqli_begin_transaction($link);

        try {
            // 2) Delete the ticket
            $sqlDelete = "DELETE FROM tbltickets WHERE TicketNumber = ?";
            $stmtDelete = mysqli_prepare($link, $sqlDelete);
            mysqli_stmt_bind_param($stmtDelete, "s", $ticketNumber);
            
            if (!mysqli_stmt_execute($stmtDelete)) {
                throw new Exception("Failed to delete ticket: " . mysqli_error($link));
            }
            mysqli_stmt_close($stmtDelete);

            // 3) Insert a log record
            $sqlLog = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) 
                       VALUES (CURDATE(), CURTIME(), 'DELETE', 'ticket', ?, ?)";

            $stmtLog = mysqli_prepare($link, $sqlLog);
            mysqli_stmt_bind_param($stmtLog, "ss", $ticketNumber, $username);
            
            if (!mysqli_stmt_execute($stmtLog)) {
                throw new Exception("Failed to insert log: " . mysqli_error($link));
            }
            mysqli_stmt_close($stmtLog);

            // Commit the transaction
            mysqli_commit($link);

            echo json_encode(["success" => true]);
            
        } catch (Exception $e) {
            // Rollback the transaction
            mysqli_rollback($link);
            error_log("Ticket Deletion Error: " . $e->getMessage());
            echo json_encode(["error" => "Failed to delete ticket. Please try again."]);
        }
    } else {
        error_log("Ticket check query preparation failed: " . mysqli_error($link));
        echo json_encode(["error" => "Failed to process your request."]);
    }
} else {
    echo json_encode(["error" => "Invalid request: No ticket number provided"]);
}

mysqli_close($link);
