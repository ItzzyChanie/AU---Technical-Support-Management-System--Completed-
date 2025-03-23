<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: login-intro.php");
    exit();
}

date_default_timezone_set('UTC');
$ticketNumber = date('YmdHis');
$username = $_SESSION['username'];
$dateCreated = date('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once "config.php";
    $problem = $_POST['problem'];
    $details = $_POST['details'];
    $status = 'Pending';

    $sql = "INSERT INTO tbltickets (TicketNumber, Problem, Details, Status, CreatedBy, DateCreated) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssssss", $ticketNumber, $problem, $details, $status, $username, $dateCreated);

        if (mysqli_stmt_execute($stmt)) 
        {
            // Insert log
            $logSql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) VALUES (?, ?, ?, ?, ?, ?)";

            if ($logStmt = mysqli_prepare($link, $logSql)) 
            {
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $action = "CREATE";
                $module = "ticket";
                mysqli_stmt_bind_param($logStmt, "ssssss", $date, $time, $action, $module, $ticketNumber, $username);
                mysqli_stmt_execute($logStmt);
                mysqli_stmt_close($logStmt);
            }
            echo "<script>
                    window.onload = function() {
                        document.getElementById('successModalMessage').innerText = 'Ticket Successfully Created!';
                        $('#successModal').modal('show');
                    };
                  </script>";
        } else {
            echo "ERROR: Could not execute query: $sql. " . mysqli_error($link);
        }
    } else {
        echo "ERROR: Could not prepare query: $sql. " . mysqli_error($link);
    }
    mysqli_stmt_close($stmt);
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
                    <input type = "text" class = "form-control" value = "<?php echo $ticketNumber; ?>" readonly>
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

                <button type = "submit" class = "btn btn-primary">Save</button>
                <a href = "ticket-management.php" class = "btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    <div id = "successModal" class = "modal fade" tabindex = "-1" role = "dialog">
        <div class = "modal-dialog" role = "document">
            <div class = "modal-content">
                <div class = "modal-header">
                    <h5 class = "modal-title">Success</h5>
                    <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
                        <span aria-hidden = "true">&times;</span>
                    </button>
                </div>

                <div class = "modal-body">
                    <p id = "successModalMessage"></p>
                </div>

                <div class = "modal-footer">
                    <a href = "ticket-management.php" class = "btn btn-secondary">Close</a>
                </div>
            </div>
        </div>
    </div>

    <script src = "https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>