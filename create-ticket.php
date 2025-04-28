<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: login-intro.php");
    exit();
}

// Generate ticket number only once when the page is first loaded
if (!isset($_SESSION['current_ticket_number'])) {
    $_SESSION['current_ticket_number'] = date('YmdHis'); // Format: YearMonthDayHourMinuteSecond
}

$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    require_once "config.php";
    // Use the stored ticket number instead of generating a new one
    $ticketNumber = $_SESSION['current_ticket_number'];
    $problem = trim($_POST['problem']);
    $details = trim($_POST['details']);
    $createdBy = $username;
    $status = "Pending";

    $sql = "INSERT INTO tbltickets (TicketNumber, Problem, Details, CreatedBy, Status, DateCreated) 
            VALUES (?, ?, ?, ?, ?, NOW())";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssss", $ticketNumber, $problem, $details, $createdBy, $status);

        if (mysqli_stmt_execute($stmt)) {
            // Clear the stored ticket number after successful insertion
            unset($_SESSION['current_ticket_number']);
            // Insert log
            $logSql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) VALUES (?, ?, ?, ?, ?, ?)";

            if ($logStmt = mysqli_prepare($link, $logSql)) {
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $action = "CREATE";
                $module = "Ticket Management";
                mysqli_stmt_bind_param($logStmt, "ssssss", $date, $time, $action, $module, $ticketNumber, $username);
                mysqli_stmt_execute($logStmt);
                mysqli_stmt_close($logStmt);
            }
            header("location: ticket-management.php");
            exit();
        } else {
            echo "Error: Could not execute the query.";
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Ticket - AU Equipment Management System</title>
    <link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 13px rgba(0, 0, 0, 0.2);
            width: 500px;
            margin: auto;
            margin-bottom: 50px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 1.5em;
            font-weight: bold;
            color: #004ea8;
        }
        label {
            font-weight: bold;
            color:rgb(60, 61, 61);
        }
    </style>
</head>

<body>
    <div class = "container mt-5">
        <div class = "form-container">
            <div class = "header">
                <h1>Create New Ticket</h1>
            </div>

            <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">
                <div class = "form-group">
                    <label>Your Ticket Number:</label>
                    <input type = "text" class = "form-control" value = "<?php echo $_SESSION['current_ticket_number']; ?>" readonly>
                </div>

                <div class = "form-group">
                    <label>Problem:</label>
                    <select name = "problem" class = "form-control" required>
                        <option value = "">--Select Problem--</option>
                        <option value = "Hardware">Hardware</option>
                        <option value = "Software">Software</option>
                        <option value = "Connection">Connection</option>
                    </select>
                </div>

                <div class = "form-group">
                    <label>Details:</label>
                    <textarea name = "details" class = "form-control" rows = "5" required></textarea>
                </div>

                <button type = "submit" name = "submit" class = "btn btn-primary">Save</button>
                <a href = "ticket-management.php" class = "btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <script src = "https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>