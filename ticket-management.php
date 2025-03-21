<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tickets Management Page - AU Equipment Management System</title>
    <link rel = "stylesheet" href = "styleforticket.css">
    <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

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
            background-color:rgb(7, 114, 228);
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
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .table-header h3 {
            position: relative;
            top: 5px;
        }
        .table-header .btn-create {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: auto;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .table-header .btn-create i {
            margin-right: 5px;
        }
        .table-header .btn-create:hover {
            background-color: #0056b3;
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
        .action-btns button {
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
            color: #fff !important; /* Ensures text remains white */
            text-decoration: none !important;
            outline: none;
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
        <li><a href = "#" class = "<?php echo $current_page == '#' ? 'selected' : ''; ?>"><i class = "bi bi-person-circle"></i> Account Profile</a></li>
            <li><a href = "ticket-management.php" class = "<?php echo $current_page == 'ticket-management.php' ? 'selected' : ''; ?>"><i class = "fas fa-ticket-alt"></i> Ticket Table</a></li>
            <li><a href = "#" class = "<?php echo $current_page == '#' ? 'selected' : ''; ?>"><i class = "bi bi-person-circle"></i> Account Table</a></li>
            <li><a href = "#" class = "<?php echo $current_page == '#' ? 'selected' : ''; ?>"><i class = "bi bi-tools"></i> Equipment Table</a></li>
        </ul>
        <a href = "login-intro.php" class = "logout-btn" onclick = "confirmLogout(event)"><i class = "fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class = "main-content">
        <?php
        if (isset($_SESSION['username'])) {
            echo "<div class='header'>";
            echo "<div>";
            echo "<h1>Welcome, " . $_SESSION['username'] . "!" . "</h1>";
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
            <div class = "table-header">
                <h3>Table of Ticket Management</h3>
                <a href = "create-ticket.php" class = "btn-create"><i class = "fas fa-plus"></i> Create Ticket</a>
            </div>

            <?php
            function buildtable($result) 
            {
                if (mysqli_num_rows($result) > 0) {
                    echo "<table class='styled-table'>";
                    echo "<thead><tr>
                        <th><i class='fas fa-ticket-alt' style='margin-right: 10px;'></i> TICKET NUMBER</th>
                        <th>PROBLEM</th>
                        <th>DATE</th>
                        <th>STATUS</th>
                        <th class='action'>ACTION</th>
                    </tr></thead><tbody>";
            
                    while ($row = mysqli_fetch_array($result)) 
                    {
                        echo "<tr>";
                        echo "<td><i class='fas fa-ticket-alt' style='margin-right: 20px;'></i>" . $row['TicketNumber'] . "</td>";
                        echo "<td>" . $row['Problem'] . "</td>";
                        echo "<td>" . (isset($row['DateCreated']) ? date('Y-m-d', strtotime($row['DateCreated'])) : 'N/A') . "</td>";
                        echo "<td>" . $row['Status'] . "</td>";
                        echo "<td class='action-btns'>
                                <button class='btn btn-warning btn-sm' onclick='showDetails(" . $row['TicketNumber'] . ")'>
                                    <i class='fas fa-info-circle'></i>
                                </button>
                                <a href='update-ticket.php?ticketNumber=" . $row['TicketNumber'] . "' class='btn btn-primary btn-sm'><i class='fas fa-edit'></i></a>
                                <button class='btn btn-danger btn-sm' onclick='confirmDelete(" . $row['TicketNumber'] . ")'>
                                    <i class='fas fa-trash'></i>
                                </button>
                              </td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "No record(s) found.";
                }
            }
            
            require_once "config.php";
            if (isset($_POST['btnsearch'])) {
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
            } else {
                $sql = "SELECT * FROM tbltickets ORDER BY DateCreated DESC";

                if ($stmt = mysqli_prepare($link, $sql)) 
                {
                    if (mysqli_stmt_execute($stmt)) {
                        $result = mysqli_stmt_get_result($stmt);
                        buildtable($result);
                    }
                } else {
                    echo "ERROR on loading data.";
                }
            }
            ?>

    <!-- Details Modal -->
    <div id = "detailsModal" class = "modal fade" tabindex = "-1" role = "dialog">
        <div class = "modal-dialog" role = "document">
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

    <!-- Delete Confirmation Modal -->
    <div id = "deleteModal" class = "modal fade" tabindex = "-1" role = "dialog">
        <div class = "modal-dialog" role = "document">
            <div class = "modal-content">
                <div class = "modal-header">
                    <h5 class = "modal-title">Delete Confirmation</h5>
                    <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
                        <span aria-hidden = "true">&times;</span>
                    </button>
                </div>

                <div class = "modal-body">
                    <p>Are you sure you want to delete this ticket?</p>
                </div>

                <div class = "modal-footer">
                    <button type = "button" class = "btn btn-secondary" data-dismiss = "modal">Cancel</button>
                    <button type = "button" class = "btn btn-danger" id = "confirmDeleteBtn">Delete</button>
                </div>
            </div>
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
                    <p>Ticket Successfully Deleted!</p>
                </div>

                <div class = "modal-footer">
                    <button type = "button" class = "btn btn-secondary" data-dismiss = "modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let ticketToDelete = null;

    function confirmDelete(ticketNumber) {
        ticketToDelete = ticketNumber;
        $('#deleteModal').modal('show');
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (ticketToDelete !== null) {
            fetch('delete-ticket.php?ticketNumber=' + encodeURIComponent(ticketToDelete), {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#deleteModal').modal('hide');
                    $('#successModal').modal('show');
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
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

    // show ticket details from modal
    function showDetails(ticketNumber) {
    fetch('get-ticket-details.php?ticketNumber=' + encodeURIComponent(ticketNumber))
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert("Error: " + data.error);
                return;
            }
            
            let details = `
                <strong>Ticket Number:</strong> ${data.TicketNumber}<br>
                <strong>Problem:</strong> ${data.Problem}<br>
                <strong>Details:</strong> ${data.Details || 'No details provided'}<br>
                <strong>Status:</strong> ${data.Status}<br>
                <strong>Created By:</strong> ${data.Createdby}<br>
                <strong>Date Created:</strong> ${data.DateCreated.split(' ')[0]}<br>
                <strong>Assigned To:</strong> ${data.AssignedTo || 'N/A'}<br>
                <strong>Date Assigned:</strong> ${data.DateAssigned || 'N/A'}<br>
                <strong>Date Completed:</strong> ${data.DateCompleted || 'N/A'}<br>
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
</script>
</body>
</html>