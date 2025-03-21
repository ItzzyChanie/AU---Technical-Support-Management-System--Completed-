<?php
require_once "config.php";

if (isset($_GET['ticketNumber'])) {
    $ticketNumber = $_GET['ticketNumber'];

    // Prepare SQL query
    $sql = "SELECT * FROM tbltickets WHERE TicketNumber = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $ticketNumber);
        
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            
            if ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                // Send ticket details as JSON
                echo json_encode($row);
            } else {
                error_log("Ticket not found: " . $ticketNumber);
                echo json_encode(["error" => "Ticket not found"]);
            }
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
