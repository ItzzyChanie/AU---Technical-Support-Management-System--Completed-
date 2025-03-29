<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']);
$usertype = $_SESSION['usertype'];
$current_user = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tickets Management Page - AU Equipment Management System</title>
    <link rel = "stylesheet" href = "styleforticket.css">
    <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel = "stylesheet" href = "darkmode.css">
    
    <script src = "https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <style>
        body {
            display: flex;
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            overflow: hidden;
            background-color: #f8f9fa;
        }
        .sidebar {
            background-color: #004ea8;
            color: #fff;
            width: 260px;
            padding: 20px;
            position: fixed;
            height: 100%;
            overflow: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .sidebar img {
            width: 60px;
            margin-right: 10px;
        }
        .sidebar .logo-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .sidebar .logo-container h2 {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
            width: 100%;
            margin-top: 10px;
        }
        .sidebar ul li {
            width: 100%;
        }
        .sidebar ul li a {
            display: flex;
            align-items: center;
            padding: 12px;
            font-size: 16px;
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
        }
        .sidebar ul li a i {
            margin-right: 10px;
            font-size: 18px;
        }
        .sidebar ul li a:hover, .sidebar ul li a.selected {
            background-color: rgb(7, 114, 228);
            border-radius: 0;
        }
        .logout-btn {
            margin-top: auto;
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: #e02c1c;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            transition: 0.3s;
        }
        .logout-btn:hover {
            background-color: #c9302c;
        }
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
            margin-left: 260px;
            overflow: auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #004ea8;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 1.5em;
            font-weight: bold;
        }
        .header h2 {
            margin: 0;
            font-size: 1em;
        }
        .search-container {
            display: flex;
            align-items: center;
        }
        .search-container input[type="text"] {
            padding: 5px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        .search-container button {
            padding: 10px;
            background-color: #004ea8;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-container button:hover {
            background-color: #0056b3;
        }
        /* Table header container */
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .table-header h3 {
            position: relative;
            top: 5px;
            margin: 0;
        }
        /* Create Ticket button for regular users */
        .btn-create {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: background-color 0.2s;
        }
        .btn-create i {
            position: relative;
            right: 9px;
            margin-left: 5px;
        }
        .btn-create:hover {
            background-color: #0056b3;
            color: #fff;
            text-decoration: none;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 14px;
        }
        th {
            background-color: grey;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        td {
            background-color: #f2f2f2;
            padding: 10px;
            border-bottom: 1px solid #ccc;
            text-align: center;
            word-wrap: break-word;
        }
        .action-btns button, .action-btns a {
            margin-right: 5px;
            margin-left: 5px;
        }
        .logout-btn {
            margin-top: auto;
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: #d9534f;
            color: #fff !important;
            text-decoration: none !important;
            font-size: 16px;
            border-radius: 5px;
            transition: 0.3s;
            display: inline-block;
        }
        .logout-btn:hover,
        .logout-btn:focus,
        .logout-btn:active {
            background-color: #c9302c;
            color: #fff !important;
            text-decoration: none !important;
            outline: none;
        }
        /* Details Modal custom width */
        #detailsModal .modal-dialog {
            width: 50%;
        }
    </style>
</head>

<body>
    <div class = "sidebar">
        <div class = "logo-container">
            <img src = 'pictures/au-logo.png' alt = 'Logo'>
            <h2>AU - TSMS</h2>
        </div>

        <hr style = "width: 100%; border: 1px solid #bfbbbb;">

        <ul>
            <!-- Account Profile is always visible -->
            <li><a href = "#" class = "<?php echo $current_page == '#' ? 'selected' : ''; ?>"><i class = "bi bi-person-circle"></i> Account Profile</a></li>
    
            <!-- Ticket Table is always visible -->
            <li><a href = "ticket-management.php" class = "<?php echo $current_page == 'ticket-management.php' ? 'selected' : ''; ?>"><i class = "fas fa-ticket-alt"></i> Ticket Table</a></li>
    
            <?php if ($usertype === "TECHNICAL"): ?>
            <!-- For Technical accounts, add Equipment Table -->
            <li><a href = "#" class = "<?php echo $current_page == '#' ? 'selected' : ''; ?>"><i class = "bi bi-tools"></i> Equipment Table</a></li>
            <?php endif; ?>
    
            <?php if ($usertype === "ADMINISTRATOR"): ?>
            <!-- For Administrator accounts, keep all existing menu items -->
            <li><a href = "#" class = "<?php echo $current_page == '#' ? 'selected' : ''; ?>"><i class = "bi bi-person-circle"></i> Account Table</a></li>
            <li><a href = "#" class = "<?php echo $current_page == '#' ? 'selected' : ''; ?>"><i class = "bi bi-tools"></i> Equipment Table</a></li>
            <?php endif; ?>
        </ul>

        <a href = "login-intro.php" class = "logout-btn" onclick = "confirmLogout(event)"><i class = "fas fa-sign-out-alt"></i> Logout</a>
    </div>
    
    <div class="main-content">

        <?php
        if (isset($_SESSION['username'])) 
        {
            echo "<div class='header'>";
            echo "<div>";
            echo "<h1>Welcome, " . $_SESSION['username'] . "!</h1>";
            echo "<h2>Account type: " . $_SESSION['usertype'] . "</h2>";
            echo "</div>";
            echo "<div class='search-container'>";
            echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>";
            echo "<input type='text' name='txtsearch' placeholder='Search Ticket...'>";
            echo "<button type='submit' name='btnsearch'><i class='fas fa-search'></i></button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
        } else {
            header("location: login-intro.php");
            exit();
        }
        ?>
    
        <!-- Table header -->
        <?php if($usertype === "TECHNICAL"): ?>
        <div class = "table-header">
        <h3>Tickets Assigned to You</h3>
            <div class = "dark-mode-toggle-container">
                    <i class = "fas fa-sun light-icon"></i>
                <label class = "dark-mode-toggle">
                    <input type = "checkbox" id = "darkModeToggle">
                    <span class = "slider"></span>
                </label>
                <i class = "fas fa-moon dark-icon"></i>
            </div>
        </div>

        <?php elseif($usertype === "ADMINISTRATOR"): ?>
            <div class = "table-header">
                <h3>Table of Ticket Management</h3>
                <div class = "dark-mode-toggle-container">
                    <i class = "fas fa-sun light-icon"></i>
                    <label class = "dark-mode-toggle">
                        <input type = "checkbox" id = "darkModeToggle">
                        <span class = "slider"></span>
                    </label>
                    <i class = "fas fa-moon dark-icon"></i>
                </div>
            </div>

        <?php else: // Regular User ?>
        <div class = "table-header" style = "justify-content: space-between; align-items: center;">
            <h3>Table of Ticket Management</h3>
            <div class = "d-flex align-items-center">
                <div class = "dark-mode-toggle-container mr-4">
                    <i class = "fas fa-sun light-icon"></i>
                    <label class = "dark-mode-toggle">
                        <input type ="checkbox" id="darkModeToggle">
                        <span class = "slider"></span>
                    </label>
                    <i class = "fas fa-moon dark-icon"></i>
                </div>
                <a href = "create-ticket.php" class = "btn-create"><i class = "fas fa-plus"></i> Create Ticket</a>
            </div>
        </div>
        <?php endif; ?>
    
        <?php
        function buildtable($result) {
            global $usertype, $current_user;

            if (mysqli_num_rows($result) > 0) 
            {
                echo "<table class='styled-table'>";
                echo "<thead><tr>
                        <th><i class='fas fa-ticket-alt' style='margin-right: 10px;'></i> TICKET NUMBER</th>
                        <th>PROBLEM</th>
                        <th>DATE</th>
                        <th>TIME CREATED</th>
                        <th>STATUS</th>
                        <th class='action'>ACTION</th>
                      </tr></thead><tbody>";

                while ($row = mysqli_fetch_array($result)) 
                {
                    // Escape TicketNumber for safe JavaScript usage.
                    $ticketId = addslashes($row['TicketNumber']);
                    echo "<tr>";
                    echo "<td><i class='fas fa-ticket-alt' style='margin-right: 20px;'></i>" . $row['TicketNumber'] . "</td>";
                    echo "<td>" . $row['Problem'] . "</td>";
                    echo "<td>" . (isset($row['DateCreated']) ? date('Y-m-d', strtotime($row['DateCreated'])) : 'N/A') . "</td>";
                    echo "<td>" . (isset($row['DateCreated']) ? date('H:i:s', strtotime($row['DateCreated'])) : 'N/A') . "</td>";
                    echo "<td>" . $row['Status'] . "</td>";
                    echo "<td class='action-btns'>";
                    
                    if ($usertype === "TECHNICAL") {
                        // For technical account: add Details button and Complete button
                        echo "<button class='btn btn-warning btn-sm' onclick='showDetails(\"$ticketId\")'><i class='fas fa-info-circle'></i></button>";
                        
                        if ($row['Status'] === "ONGOING") {
                            echo "<button class='btn btn-primary btn-sm' onclick='confirmComplete(\"$ticketId\")'>Complete</button>";
                        } else {
                            echo "<button class='btn btn-secondary btn-sm' disabled>Complete</button>";
                        }
                    } 
                    else if ($usertype === "ADMINISTRATOR") { 
                        // For administrator account: display icon-only buttons.
                        echo "<button class='btn btn-warning btn-sm' onclick='showDetails(\"$ticketId\")'><i class='fas fa-info-circle'></i></button>";
                        
                        // Enable assign button only if status is PENDING or ON-GOING, disable if FOR APPROVAL or CLOSED.
                        if ($row['Status'] === "Pending" || $row['Status'] === "ONGOING") {
                            echo "<a href='assign-ticket.php?ticketNumber=$ticketId' class='btn btn-primary btn-sm'><i class='fas fa-user-check'></i></a>";
                        } else {
                            echo "<a href='#' class='btn btn-primary btn-sm disabled' tabindex='-1' aria-disabled='true'><i class='fas fa-user-check'></i></a>";
                        }
                        
                        // Approve button: enabled only if status is "FOR APPROVAL"
                        if ($row['Status'] === "FOR APPROVAL") {
                            echo "<button class='btn btn-success btn-sm' onclick='showApproveConfirm(\"$ticketId\")'><i class='fas fa-check'></i></button>";
                        } else {
                            echo "<button class='btn btn-success btn-sm' disabled><i class='fas fa-check'></i></button>";
                        }
    
                        // Delete button: enabled only if status is "CLOSED" for ADMINISTRATOR
                        if ($row['Status'] === "CLOSED") {
                            echo "<button class='btn btn-danger btn-sm' onclick='showDeleteConfirm(\"$ticketId\")'><i class='fas fa-trash'></i></button>";
                        } else {
                            echo "<button class='btn btn-danger btn-sm' disabled><i class='fas fa-trash'></i></button>";
                        }
                    } 
                    else {
                        // For regular user account: display icon-only buttons for Details, Update, and Delete.
                        echo "<button class='btn btn-warning btn-sm' onclick='showDetails(\"$ticketId\")'><i class='fas fa-info-circle'></i></button>";
                        echo "<a href='update-ticket.php?ticketNumber=$ticketId' class='btn btn-primary btn-sm'><i class='fas fa-edit'></i></a>";
                        
                        // Delete button for regular users: enabled only if status is "CLOSED"
                        if ($row['Status'] === "CLOSED") {
                            echo "<button class='btn btn-danger btn-sm' onclick='confirmDelete(\"$ticketId\")'><i class='fas fa-trash'></i></button>";
                        } else {
                            echo "<button class='btn btn-danger btn-sm' disabled><i class='fas fa-trash'></i></button>";
                        }
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "No record(s) found.";
            }
        }
        
        require_once "config.php";
        // Use different queries based on the user type.
        if ($usertype === "TECHNICAL") {
            if (isset($_POST['btnsearch'])) 
            {
                $sql = "SELECT * FROM tbltickets WHERE AssignedTo = ? AND (TicketNumber LIKE ? OR Problem LIKE ? OR Status LIKE ?) ORDER BY DateCreated DESC";
                
                if ($stmt = mysqli_prepare($link, $sql)) {
                    $searchvalue = '%' . $_POST['txtsearch'] . '%';
                    mysqli_stmt_bind_param($stmt, "ssss", $_SESSION['username'], $searchvalue, $searchvalue, $searchvalue);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $result = mysqli_stmt_get_result($stmt);
                        buildtable($result);

                    } else {
                        echo "ERROR on search.";
                    }
                }
            } 
            else {
                $sql = "SELECT * FROM tbltickets WHERE AssignedTo = ? ORDER BY DateCreated DESC";

                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);

                    if (mysqli_stmt_execute($stmt)) {
                        $result = mysqli_stmt_get_result($stmt);
                        buildtable($result);
                    }
                } else {
                    echo "ERROR on loading data.";
                }
            }
        } 
        else if ($usertype === "ADMINISTRATOR") {
            if (isset($_POST['btnsearch'])) 
            {
                $sql = "SELECT * FROM tbltickets WHERE TicketNumber LIKE ? OR Problem LIKE ? OR Status LIKE ? ORDER BY DateCreated DESC";
                
                if ($stmt = mysqli_prepare($link, $sql)) {
                    $searchvalue = '%' . $_POST['txtsearch'] . '%';
                    mysqli_stmt_bind_param($stmt, "sss", $searchvalue, $searchvalue, $searchvalue);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $result = mysqli_stmt_get_result($stmt);
                        buildtable($result);
                        
                    } else {
                        echo "ERROR on search.";
                    }
                }
            } 
            else {
                $sql = "SELECT * FROM tbltickets ORDER BY DateCreated DESC";

                if ($stmt = mysqli_prepare($link, $sql)) {
                    if (mysqli_stmt_execute($stmt)) {
                        $result = mysqli_stmt_get_result($stmt);
                        buildtable($result);
                    }
                } else {
                    echo "ERROR on loading data.";
                }
            }
        } 
        else { // Regular user: show only tickets created by the user.
            if (isset($_POST['btnsearch'])) {
                $sql = "SELECT * FROM tbltickets WHERE CreatedBy = ? AND (TicketNumber LIKE ? OR Problem LIKE ? OR Status LIKE ?) ORDER BY DateCreated DESC";
                
                if ($stmt = mysqli_prepare($link, $sql)) {
                    $searchvalue = '%' . $_POST['txtsearch'] . '%';
                    mysqli_stmt_bind_param($stmt, "ssss", $_SESSION['username'], $searchvalue, $searchvalue, $searchvalue);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $result = mysqli_stmt_get_result($stmt);
                        buildtable($result);

                    } else {
                        echo "ERROR on search.";
                    }
                }
            } else {
                $sql = "SELECT * FROM tbltickets WHERE CreatedBy = ? ORDER BY DateCreated DESC";

                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $result = mysqli_stmt_get_result($stmt);
                        buildtable($result);
                    }
                } else {
                    echo "ERROR on loading data.";
                }
            }
        }
        ?>
    
    <!-- Complete Confirmation Modal (for technical accounts) -->
    <div id = "completeConfirmModal" class = "modal fade" tabindex = "-1" role = "dialog">
        <div class = "modal-dialog" role = "document">

            <div class = "modal-content">
                <div class = "modal-header">
                    <h5 class = "modal-title">Complete Ticket Confirmation</h5>
                    <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
                        <span aria-hidden = "true">&times;</span>
                    </button>
                </div>

                <div class = "modal-body">
                    <p>Are you sure you want to complete this ticket?</p>
                </div>

                <div class = "modal-footer">
                    <button type = "button" class = "btn btn-secondary" data-dismiss = "modal">Cancel</button>
                    <button type = "button" class = "btn btn-primary" id = "confirmCompleteBtn">Yes</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ticket Completed Modal -->
    <div id = "ticketCompletedModal" class = "modal fade" tabindex = "-1" role = "dialog">
        <div class = "modal-dialog" role = "document">

            <div class = "modal-content">
                <div class = "modal-header">
                    <h5 class = "modal-title">Ticket Completed!</h5>
                    <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
                        <span aria-hidden = "true">&times;</span>
                    </button>
                </div>

                <div class = "modal-body">
                    <p>Ticket Completed!</p>
                </div>

                <div class = "modal-footer">
                    <button type = "button" class = "btn btn-primary" id = "completedOkBtn">OK</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Approve Confirmation Modal (for admin) -->
    <div id = "approveConfirmModal" class = "modal fade" tabindex = "-1" role = "dialog">
        <div class = "modal-dialog" role = "document">

            <div class = "modal-content">
                <div class = "modal-header">
                    <h5 class = "modal-title">Approve Ticket Confirmation</h5>
                    <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
                        <span aria-hidden = "true">&times;</span>
                    </button>
                </div>

                <div class = "modal-body">
                    <p>Are you sure you want to Approve this Ticket?</p>
                </div>

                <div class = "modal-footer">
                    <button type = "button" class = "btn btn-secondary" data-dismiss = "modal">Cancel</button>
                    <button type = "button" class = "btn btn-primary" id = "confirmApproveBtn">Yes</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ticket Approved Modal -->
    <div id = "ticketApprovedModal" class = "modal fade" tabindex = "-1" role = "dialog">
        <div class = "modal-dialog" role = "document">

            <div class = "modal-content">
                <div class = "modal-header">
                    <h5 class = "modal-title">Ticket Approved!</h5>
                    <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
                        <span aria-hidden = "true">&times;</span>
                    </button>
                </div>

                <div class = "modal-body">
                    <p>Ticket Approved!</p>
                </div>

                <div class = "modal-footer">
                    <button type = "button" class = "btn btn-primary" id = "approvedOkBtn">OK</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal (for admin) -->
    <div id = "deleteConfirmModal" class = "modal fade" tabindex = "-1" role = "dialog">
        <div class = "modal-dialog" role  = "document">

            <div class = "modal-content">
                <div class = "modal-header">
                    <h5 class = "modal-title">Delete Ticket Confirmation</h5>
                    <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
                        <span aria-hidden = "true">&times;</span>
                    </button>
                </div>

                <div class = "modal-body">
                    <p>Are you sure you want to delete this Ticket now?</p>
                </div>

                <div class = "modal-footer">
                    <button type = "button" class = "btn btn-secondary" data-dismiss = "modal">Cancel</button>
                    <button type = "button" class = "btn btn-primary" id = "confirmDeleteBtn">Yes</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ticket Deleted Modal (for admin) -->
    <div id = "ticketDeletedModal" class = "modal fade" tabindex = "-1" role = "dialog">
        <div class = "modal-dialog" role = "document">

            <div class = "modal-content">
                <div class = "modal-header">
                    <h5 class = "modal-title">Ticket Successfully Deleted!</h5>
                    <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
                        <span aria-hidden = "true">&times;</span>
                    </button>
                </div>

                <div class = "modal-body">
                    <p>Ticket Successfully Deleted!</p>
                </div>

                <div class = "modal-footer">
                    <button type = "button" class = "btn btn-primary" id = "deletedOkBtn">OK</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Details Modal -->
    <div id = "detailsModal" class = "modal fade" tabindex = "-1" role = "dialog">
      <div class = "modal-dialog" role = "document" style = "max-width: 50%;">
        <div class = "modal-content">
          <div class = "modal-header">
            <h5 class = "modal-title">Ticket Details</h5>   
            <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
              <span aria-hidden = "true">&times;</span>
            </button>
          </div>

          <div class = "modal-body">
            <p id = "ticketDetails"></p>
          </div>

          <div class = "modal-footer">
            <button type = "button" class = "btn btn-secondary" data-dismiss = "modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    
    <script>
        let ticketToComplete = null;
        let adminTicketToApprove = null;
        let adminTicketToDelete = null;
    
        // For technical account - Complete Ticket
        function confirmComplete(ticketNumber) {
            ticketToComplete = ticketNumber;
            $('#completeConfirmModal').modal('show');
        }
        document.getElementById('confirmCompleteBtn').addEventListener('click', function() {
            if (ticketToComplete !== null) {
                fetch('complete-ticket.php?ticketNumber=' + encodeURIComponent(ticketToComplete), {
                    method: 'GET'
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        $('#completeConfirmModal').modal('hide');
                        $('#ticketCompletedModal').modal('show');
                    } else {
                        alert("Error: " + data.error);
                    }
                })
                .catch(error => {
                    alert("Failed to complete ticket. Please try again.");
                    console.error("Error:", error);
                });
            }
        });
        document.getElementById('completedOkBtn').addEventListener('click', function() {
            window.location.reload();
        });
    
        // For Approve action (admin)
        function showApproveConfirm(ticketNumber) {
            adminTicketToApprove = ticketNumber;
            $('#approveConfirmModal').modal('show');
        }
        document.getElementById('confirmApproveBtn').addEventListener('click', function() {
            if (adminTicketToApprove !== null) {
                fetch('approve-ticket.php?ticketNumber=' + encodeURIComponent(adminTicketToApprove), {
                    method: 'GET'
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        $('#approveConfirmModal').modal('hide');
                        $('#ticketApprovedModal').modal('show');
                    } else {
                        alert("Error: " + data.error);
                    }
                })
                .catch(error => {
                    alert("Failed to approve ticket. Please try again.");
                    console.error("Error:", error);
                });
            }
        });
        document.getElementById('approvedOkBtn').addEventListener('click', function() {
            window.location.reload();
        });
    
        // For Delete action (admin)
        function showDeleteConfirm(ticketNumber) {
            adminTicketToDelete = ticketNumber;
            $('#deleteConfirmModal').modal('show');
        }
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (adminTicketToDelete !== null) {
                fetch('delete-ticket.php?ticketNumber=' + encodeURIComponent(adminTicketToDelete), {
                    method: 'GET'
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        $('#deleteConfirmModal').modal('hide');
                        $('#ticketDeletedModal').modal('show');
                    } else {
                        alert("Error: " + data.error);
                    }
                })
                .catch(error => {
                    alert("Failed to delete ticket. Please try again.");
                    console.error("Error:", error);
                });
            }
        });
        document.getElementById('deletedOkBtn').addEventListener('click', function() {
            window.location.reload();
        });
    
        // showDetails function for both admin and regular users.
        function showDetails(ticketNumber) {
            fetch('get-ticket-details.php?ticketNumber=' + encodeURIComponent(ticketNumber))
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        alert("Error: " + data.error);
                        return;
                    }
                    let dateCreated = data.DateCreated ? data.DateCreated.split(' ') : ['N/A','N/A'];
                    let dateAssigned = data.DateAssigned ? data.DateAssigned.split(' ')[0] : 'N/A';
                    let dateCompleted = data.DateCompleted ? data.DateCompleted.split(' ')[0] : 'N/A';
                    let details = `
                        <strong>Ticket Number:</strong> ${data.TicketNumber}<br>
                        <strong>Problem:</strong> ${data.Problem}<br>
                        <strong>Details:</strong> ${data.Details || 'No details provided'}<br>
                        <strong>Status:</strong> ${data.Status}<br>
                        <strong>Created By:</strong> ${data.Createdby}<br>
                        <strong>Date Created:</strong> ${dateCreated[0]}<br>
                        <strong>Time Created:</strong> ${dateCreated[1] || 'N/A'}<br>
                        <strong>Assigned To:</strong> ${data.AssignedTo || 'N/A'}<br>
                        <strong>Date Assigned:</strong> ${dateAssigned}<br>
                        <strong>Date Completed:</strong> ${dateCompleted}<br>
                        <strong>Approved By:</strong> ${data.ApprovedBy || 'N/A'}<br>
                        <strong>Date Approved:</strong> ${data.DateApproved || 'N/A'}
                    `;
                    document.getElementById('ticketDetails').innerHTML = details;
                    $('#detailsModal').modal('show');
                })
                .catch(error => {
                    alert("Failed to fetch ticket details. Please try again.");
                    console.error("Error:", error);
                });
        }
    
        // ConfirmDelete function for regular users
        function confirmDelete(ticketNumber) {
            $('#deleteConfirmModal').modal('show');
            $('#confirmDeleteBtn').off('click').on('click', function() {
                fetch('delete-ticket.php?ticketNumber=' + encodeURIComponent(ticketNumber), {
                    method: 'GET'
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        $('#deleteConfirmModal').modal('hide');
                        $('#ticketDeletedModal').modal('show');
                    } else {
                        alert("Error: " + data.error);
                    }
                })
                .catch(error => {
                    alert("Failed to delete ticket. Please try again.");
                    console.error("Error:", error);
                });
            });
        }

        // deletedOkBtn event listener
        document.getElementById('deletedOkBtn').addEventListener('click', function() {
            window.location.reload();
        });
        
        document.addEventListener('DOMContentLoaded', () => {
            const toggleInput = document.getElementById('darkModeToggle');
    
        // Check and set initial state from localStorage
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
            toggleInput.checked = true;
        }

        // Toggle dark mode
        toggleInput.addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'true');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.removeItem('darkMode');
            }
        });
    });
    </script>
</body>
</html>
