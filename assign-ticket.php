<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'ADMINISTRATOR') {
    header("location: login-intro.php");
    exit();
}
require_once "config.php";

// Get ticket number from GET
if (!isset($_GET['ticketNumber'])) {
    header("location: ticket-management.php");
    exit();
}

$ticketNumber = $_GET['ticketNumber'];

// Fetch ticket details
$sql = "SELECT * FROM tbltickets WHERE TicketNumber = ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $ticketNumber);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        echo "Ticket not found.";
        exit();
    }
    $ticket = mysqli_fetch_array($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

} else {
    echo "Error preparing statement.";
    exit();
}

// Fetch all technician accounts
$sqlTech = "SELECT username FROM tblaccounts WHERE usertype = 'TECHNICAL' AND status = 'ACTIVE'";
$technicians = [];

if ($resultTech = mysqli_query($link, $sqlTech)) 
{
    while ($row = mysqli_fetch_assoc($resultTech)) {
        $technicians[] = $row['username'];
    }
    mysqli_free_result($resultTech);
} else {
    echo "Error fetching technicians.";
    exit();
}

// Get current assigned technician
$currentAssignee = '';
$sqlCurrent = "SELECT AssignedTo FROM tbltickets WHERE TicketNumber = ?";

if ($stmtCurrent = mysqli_prepare($link, $sqlCurrent)) {
    mysqli_stmt_bind_param($stmtCurrent, "s", $ticketNumber);

    if (mysqli_stmt_execute($stmtCurrent)) {
        $result = mysqli_stmt_get_result($stmtCurrent);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $currentAssignee = $row['AssignedTo'];
        }
    }
    mysqli_stmt_close($stmtCurrent);
}

$success = false; // Flag for successful assignment

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $assignedTo = $_POST['assignedTo'];
    $dateAssigned = date('Y-m-d H:i:s');
    
    // Update ticket with assignment details
    $updateSql = "UPDATE tbltickets SET Status = 'ONGOING', DateAssigned = ?, AssignedTo = ? WHERE TicketNumber = ?";

    if ($updateStmt = mysqli_prepare($link, $updateSql)) {
        mysqli_stmt_bind_param($updateStmt, "sss", $dateAssigned, $assignedTo, $ticketNumber);

        if (mysqli_stmt_execute($updateStmt)) {
            $success = true;

        } else {
            $error = "Error updating ticket: " . mysqli_error($link);
        }
        mysqli_stmt_close($updateStmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assign Ticket - AU Equipment Management System</title>
    <link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        .form-container {
            background-color: #fff;
            padding: 20px 30px;
            position: relative;
            top: 130px;
            border-radius: 5px;
            box-shadow: 0 0 13px rgba(0,0,0,0.2);
            max-width: 600px;
            margin: auto;
        }
        .form-container h2 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            color: #004ea8;
        }
        .form-label {
            font-weight: bold;
        }
        .ticket-info {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class = "form-container">
        <h2>Assign Ticket</h2>
        <?php if(isset($error)) { echo "<div class='alert alert-danger'>{$error}</div>"; } ?>

        <form method = "POST" action = "">
            <div class = "ticket-info">
                <label class = "form-label">Ticket Number:</label>
                <span><?php echo htmlspecialchars($ticket['TicketNumber']); ?></span>
            </div>

            <div class = "ticket-info">
                <label class = "form-label">Problem:</label>
                <span><?php echo htmlspecialchars($ticket['Problem']); ?></span>
            </div>

            <div class = "ticket-info">
                <label class = "form-label">Details:</label>
                <span><?php echo htmlspecialchars($ticket['Details']); ?></span>
            </div>

            <div class = "form-group">
                <label for = "assignedTo" class = "form-label">Assign to Technician:</label>
                <select name = "assignedTo" id = "assignedTo" class = "form-control" required>
                    <option value = "">--Select Technician--</option>
                    <?php foreach ($technicians as $tech): ?>
                        <option value = "<?php echo htmlspecialchars($tech); ?>" <?php echo ($tech === $currentAssignee) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tech); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <br>
            <button type = "submit" class = "btn btn-primary">Save</button>
            <a href = "ticket-management.php" class = "btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- Success Modal -->
    <div class = "modal fade" id = "successModal" tabindex = "-1" role = "dialog" aria-labelledby = "successModalLabel" aria-hidden = "true">
      <div class = "modal-dialog" role = "document">
        <div class = "modal-content">
          <div class = "modal-header">
            <h5 class = "modal-title" id = "successModalLabel">Success</h5>
          </div>

          <div class = "modal-body">
            Ticket Assigned Successfully!
          </div>

          <div class = "modal-footer">
            <button type = "button" class = "btn btn-primary" id = "okButton">OK</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS and dependencies (jQuery and Popper.js) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <?php if ($success): ?>
    <script>
        $(document).ready(function(){
            $('#successModal').modal('show');
            $('#okButton').on('click', function(){
                window.location.href = "ticket-management.php";
            });
        });
    </script>
    <?php endif; ?>

</body>
</html>
