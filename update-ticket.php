<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: login-intro.php");
    exit();
}

require_once "config.php";

if (!isset($_GET['ticketNumber']) && !isset($_POST['ticketNumber'])) {
    echo "ERROR: Ticket number not provided.";
    exit();
}

$ticketNumber = isset($_GET['ticketNumber']) ? $_GET['ticketNumber'] : $_POST['ticketNumber'];
$problem = '';
$details = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $ticketNumber = $_POST['ticketNumber'];
    $problem = $_POST['problem'];
    $details = $_POST['details'];

    $sql = "UPDATE tbltickets SET Problem = ?, Details = ? WHERE TicketNumber = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $problem, $details, $ticketNumber);

        if (mysqli_stmt_execute($stmt)) 
        {
            // Insert log
            $logSql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) VALUES (?, ?, ?, ?, ?, ?)";

            if ($logStmt = mysqli_prepare($link, $logSql)) 
            {
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $action = "UPDATE";
                $module = "ticket";
                $username = $_SESSION['username'];
                mysqli_stmt_bind_param($logStmt, "ssssss", $date, $time, $action, $module, $ticketNumber, $username);
                mysqli_stmt_execute($logStmt);
                mysqli_stmt_close($logStmt);
            }
            echo "<script>
                    window.onload = function() {
                        document.getElementById('successModalMessage').innerText = 'Ticket Successfully Updated!';
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

} else {
    $sql = "SELECT Problem, Details FROM tbltickets WHERE TicketNumber = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $ticketNumber);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $problem, $details);
            mysqli_stmt_fetch($stmt);

        } else {
            echo "ERROR: Could not execute query: $sql. " . mysqli_error($link);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "ERROR: Could not prepare query: $sql. " . mysqli_error($link);
    }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Ticket - AU Equipment Management System</title>
    <link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
    <div class = "form-container">
        <div class = "header">
            <h1>Update Ticket</h1>
        </div>

        <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">
            <div class = "form-group">
                <label>Your Ticket Number:</label>
                <input type = "text" class = "form-control" name = "ticketNumber" value = "<?php echo $ticketNumber; ?>" readonly>
            </div>

            <div class = "form-group">
                <label>Problem:</label>
                <select name = "problem" class = "form-control" required>
                    <option value = "">--Select Problem--</option>
                    <option value = "Hardware" <?php echo $problem == 'Hardware' ? 'selected' : ''; ?>>Hardware</option>
                    <option value = "Software" <?php echo $problem == 'Software' ? 'selected' : ''; ?>>Software</option>
                    <option value = "Connection" <?php echo $problem == 'Connection' ? 'selected' : ''; ?>>Connection</option>
                </select>
            </div>

            <div class = "form-group">
                <label>Details:</label>
                <textarea name = "details" class = "form-control" rows = "5"><?php echo $details; ?></textarea>
            </div>

            <button type = "submit" class = "btn btn-primary">Save</button>
            <a href = "ticket-management.php" class = "btn btn-secondary">Cancel</a>
        </form>
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