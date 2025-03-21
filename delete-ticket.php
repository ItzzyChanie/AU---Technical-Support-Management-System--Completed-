<?php
session_start();
require_once "config.php";

if (isset($_GET['ticketNumber'])) {
    $ticketNumber = $_GET['ticketNumber'];

    // Prepare SQL query
    $sql = "DELETE FROM tbltickets WHERE TicketNumber = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $ticketNumber);
        
        if (mysqli_stmt_execute($stmt)) 
        {
            // Insert log
            $logSql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) VALUES (?, ?, ?, ?, ?, ?)";

            if ($logStmt = mysqli_prepare($link, $logSql)) 
            {
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $action = "DELETE";
                $module = "ticket";
                $username = $_SESSION['username'];
                mysqli_stmt_bind_param($logStmt, "ssssss", $date, $time, $action, $module, $ticketNumber, $username);
                mysqli_stmt_execute($logStmt);
                mysqli_stmt_close($logStmt);
            }
            echo json_encode(["success" => true]);

        } else {
            error_log("Query execution failed: " . mysqli_error($link));
            echo json_encode(["error" => "Query execution failed"]);
        }

        mysqli_stmt_close($stmt);
        
    } else {
        error_log("Query preparation failed: " . mysqli_error($link));
        echo json_encode(["error" => "Query preparation failed"]);
    }
} else {
    error_log("Invalid request: No ticket number provided");
    echo json_encode(["error" => "Invalid request"]);
}

mysqli_close($link);
?>
