<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Equipment Management - AU Equipment Management System</title>
    <!-- Font Awesome & Bootstrap -->
    <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

    <script src = "https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <style>
        /* Make HTML and body span the full browser window, no overflow. */
        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            display: flex; /* Lay out sidebar and main content side by side */
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #000;
            transition: background-color 0.3s, color 0.3s;
        }
        /* Sidebar fixed at 260px width on the left */
        .sidebar {
            width: 260px;
            background-color: #004ea8;
            color: #fff;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .sidebar .logo-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .sidebar img {
            width: 60px;
            height: 60px;
        }
        .sidebar h2 {
            font-size: 20px;
            font-weight: bold;
            margin-left: 10px;
            position: relative;
            top: 4px;
        }
        .sidebar hr {
            width: 100%;
            border: 1px solid rgb(192, 192, 191);
            margin: 20px 0;
            margin-bottom: 20px;
            position: relative;
            bottom: 5px;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 12px;
            padding-bottom: 8px;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            transition: 0.3s;
            margin-bottom: 5px;
        }
        .sidebar a i {
            margin-right: 10px;
            font-size: 18px;
        }
        .sidebar a:hover,
        .sidebar a.selected {
            background-color: rgb(7, 114, 228);
        }
        .sidebar .logout-btn {
            margin-top: auto;
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            background-color: #d9534f;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            transition: 0.3s;
            text-align: center; /* Center the text and icon */
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
        }
        .sidebar .logout-btn:hover {
            background-color: #c9302c;
        }
        .sidebar .logout-btn i {
            margin-right: 4px; /* Space between icon and text */
            font-size: 16px; /* Icon size */
        }
        /* Main Content - uses flex to fill remaining horizontal space */
        .main-content {
            flex: 1; /* Fill the space not used by the sidebar */
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-sizing: border-box;
        }

        /* Header: full width in the main content */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #004ea8;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            margin-bottom: 10px;
            width: 100%; /* occupy full width of .main-content */
            box-sizing: border-box;
        }
        .header .welcome-container {
            display: flex;
            flex-direction: column;
        }
        .header .welcome-container h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .header .welcome-container h2 {
            margin: 0;
            font-size: 1rem;
            font-weight: normal;
        }
        .search-container {
            display: flex;
            align-items: center;
        }
        .search-container input[type="text"] {
            width: 187px;
            height: 33px;
            font-size: 14px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 4px;
            margin-top: 3px;
        }
        .search-container button {
            padding: 6px 10px;
            margin-left: 5px;
            background-color:rgb(2, 78, 160);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-container button:hover {
            background-color: #003d80;
        }

        /* Title and Actions Container below header */
        .title-and-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%; /* fill .main-content */
            margin-bottom: 10px;
        }
        .table-title {
            font-size: 1.7rem;
            font-weight: normal;
            position: relative;
            top: 13px;
            color: #282828;
        }
        .actions-container {
            display: flex;
            align-items: center;
        }
        .index-btn {
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            padding: 7px 12px;
            display: inline-flex;
            align-items: center;
            margin-right: 20px;
            transition: background-color 0.2s;
            position: relative;
            top: 8px;
            left: 8px;
            padding-right: 13px;
        }
        .index-btn i {
            margin-right: 5px;
        }
        .index-btn:hover {
            background-color: #0056b3;
            text-decoration: none;
            color: #fff;
        }

        /* Table Container - can simply remain flexible in height */
        .table-container {
            margin-top: 10px;
            overflow-x: auto; /* horizontal scroll if needed */
        }
        table {
            width: 100%; /* fill container horizontally */
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 7px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: none;
        }
        th {
            background-color: grey;
            color: #fff;
            padding: 10px;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: inherit; /* Remove hover effect */
        }
        td {
            background-color:rgb(243, 242, 242); /* Light background color */
            border-bottom: 1px solid #ddd; /* Add lines between rows */
        }
        .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
        }
        .icon-btn i {
            font-size: 18px;
        }
        .icon-btn.disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        /* Dark mode: Only the body changes background & text color */
        body.dark-mode {
            background-color: #282828;
            color: #ffffff;
        }

        /* Dark Mode Toggle Switch Styles */
        .dark-mode-toggle {
            display: inline-flex;
            align-items: center;
            cursor: default; /* Default cursor for the container */
        }
        
        .dark-mode-toggle .fa-moon {
            color:rgb(167, 165, 165); /* Change icon color to grey */
            margin: 0 8px;
            font-size: 19px; /* Match size with the slider */
            position: relative;
            top: 9px; /* Align with the slider */
            left: 8px;
            right: 8px;
        }
        .dark-mode-toggle .fa-sun {
            color:rgb(167, 165, 165); /* Change icon color to grey */
            margin: 8px;
            font-size: 19px; /* Match size with the slider */
            position: relative;
            top: 10px; /* Align with the slider */
            left: 6px;
        }
        .dark-mode-toggle label {
            display: inline-block;
            position: relative;
            left: 7px;
            width: 60px; /* Increase width */
            height: 30px; /* Increase height */
            top: 13px; /* Adjust alignment */
            cursor: pointer; /* Pointer cursor for the slider */
        }
        .dark-mode-toggle label input {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
        }
        .dark-mode-toggle label .slider {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            border-radius: 34px;
            transition: background-color 0.4s;
        }
        .dark-mode-toggle label .slider:before {
            position: absolute;
            content: "";
            height: 22px; /* Increase height */
            width: 22px; /* Increase width */
            left: 3px; /* Adjust position */
            bottom: 4px; /* Adjust position */
            background-color: white;
            transition: transform 0.4s;
            border-radius: 50%;
        }
        .dark-mode-toggle label input:checked + .slider {
            background-color: #007bff;
        }
        .dark-mode-toggle label input:checked + .slider:before {
            transform: translateX(31px); /* Adjust translation */
        }

        /* Add specific styling for the table title in dark mode */
        body.dark-mode .table-title {
            color: #ffffff; /* Ensure text is visible in dark mode */
        }

        body.dark-mode td {
            background-color: #3a3a3a; /* Dark background color for dark mode */
            color: #ffffff; /* Ensure text is visible in dark mode */
            border-bottom: 1px solid #555; /* Adjust border color for dark mode */
        }

        /* Add styles for update and delete buttons */
        .update-btn {
            background-color: #007bff; /* Match update button color */
            color: #ffffff;
            padding: 7px 8px; /* Adjust padding for size */
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px; /* Add spacing between buttons */
        }
        .update-btn i {
            font-size: 16px; /* Match icon size */
        }
        .update-btn:hover {
            background-color:rgb(5, 100, 201); /* Hover effect */
            color: #ffffff; /* Keep font color unchanged */
        }
        .delete-btn {
            background-color: #d9534f; /* Match delete button color */
            color: #ffffff;
            padding: 7px 9px; /* Adjust padding for size */
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .delete-btn i {
            font-size: 16px; /* Match icon size */
        }
        .delete-btn:hover {
            background-color: #c82333; /* Hover effect */
            color: #ffffff; /* Keep font color unchanged */
        }
        
        /* Simple modal styles */
        .modal-simple .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        
        .modal-simple .modal-footer {
            border-top: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }
    </style>

    <script>
        function confirmDelete(username) {
            // Open the modal and set the username dynamically
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            confirmDeleteBtn.href = 'delete-account.php?username=' + encodeURIComponent(username);
            $('#confirmDeleteModal').modal({ backdrop: 'static', keyboard: false });
        }

        // Removed the "localhost says" alert message
        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            if (params.get('success') === 'true') {
                // Automatically show the success modal
                $("#successModal").modal("show");
                // Remove the query param to prevent re-triggering on refresh
                history.replaceState(null, '', window.location.pathname);
            }
        });

        function openAddAccountModal() {
            $('#addAccountModal').modal('show');
        }

        // Function to open the Update Account modal and populate fields
        function openUpdateAccountModal(username, usertype) {
            document.getElementById('updateUsername').value = username;
            document.getElementById('updateUsertype').value = usertype;
            $('#updateAccountModal').modal('show');
        }
        function confirmLogout(event) {
            event.preventDefault();
            // Show the logout confirmation modal
            $('#logoutConfirmModal').modal({ backdrop: 'static', keyboard: false });
        }
        // Toggle dark mode: Only toggles body style
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            const isDarkMode = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDarkMode ? 'enabled' : 'disabled');
        }
        // Load dark mode preference on page load
        document.addEventListener('DOMContentLoaded', () => {
            const darkModePreference = localStorage.getItem('darkMode');
            const toggleSwitch = document.getElementById('darkModeToggle');
            if (darkModePreference === 'enabled') {
                toggleDarkMode();
                if (toggleSwitch) toggleSwitch.checked = true;
            }
        });

        // Ensure dark mode persists across pages
        document.addEventListener('DOMContentLoaded', () => {
            const darkModePreference = localStorage.getItem('darkMode');
            if (darkModePreference === 'enabled') {
                document.body.classList.add('dark-mode');
                const toggleSwitch = document.getElementById('darkModeToggle');
                if (toggleSwitch) toggleSwitch.checked = true;
            }
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
                    localStorage.setItem('darkMode', 'false');
                }

                // Synchronize dark mode across pages
                localStorage.setItem('darkModeSync', Date.now());
            });

            // Listen for changes in dark mode synchronization
            window.addEventListener('storage', (event) => {
                if (event.key === 'darkModeSync') {
                    const darkModePreference = localStorage.getItem('darkMode');
                    if (darkModePreference === 'true') {
                        document.body.classList.add('dark-mode');
                        toggleInput.checked = true;
                    } else {
                        document.body.classList.remove('dark-mode');
                        toggleInput.checked = false;
                    }
                }
            });
        });

        $(document).ready(function() {
            const params = new URLSearchParams(window.location.search);

            if (params.get('success') === 'true') {
                $("#successModal").modal("show");
                history.replaceState(null, '', window.location.pathname);
            }

            if (params.get('update_success') === 'true') {
                $("#updateSuccessModal").modal("show");
                history.replaceState(null, '', window.location.pathname);
            }
        });
    </script>
</head>
<body>
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="logo-container">
            <img src="pictures/au-logo.png" alt="AU Logo">
            <h2>AU - TSMS</h2>
        </div>
        <hr>
        <a href="account-profile.php" class="<?php echo $current_page == 'account-profile.php' ? 'selected' : ''; ?>">
            <i class="bi bi-person-circle"></i> Account Profile
        </a>
        <a href="ticket-management.php" class="<?php echo $current_page == 'ticket-management.php' ? 'selected' : ''; ?>">
            <i class="fas fa-ticket-alt"></i> Ticket Table
        </a>
        <a href="accounts-management.php" class="<?php echo $current_page == 'accounts-management.php' ? 'selected' : ''; ?>">
            <i class="bi bi-person-circle"></i> Accounts Table
        </a>
        <a href="equipment-management.php" class="<?php echo $current_page == 'equipment-management.php' ? 'selected' : ''; ?>">
            <i class="bi bi-tools"></i> Equipment Table
        </a>
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>Logout
        </a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <?php if (isset($_SESSION['username'])): ?>
            <!-- HEADER -->
            <div class="header">
                <div class="welcome-container">
                    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
                    <h2>Account type: <?php echo strtoupper($_SESSION['usertype']); ?></h2>
                </div>
                <div class="search-container">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" style="display: flex;">
                        <input type="text" name="txtsearch" placeholder="Search Equipment..." />
                        <button type="submit" name="btnsearch">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Title & Actions Container -->
            <div class="title-and-actions">
                <div class="table-title">Table of Accounts Management</div>
                <div class="actions-container">
                    <!-- Add Account Button -->
                    <a href="create-account.php" class="index-btn">
                        <i class="fas fa-user-plus"></i> Add Account
                    </a>
                    <a href="index.php" class="index-btn">
                        <i class="fas fa-home"></i> Index Page
                    </a>
                    <div class="dark-mode-toggle">
                        <i class="fas fa-sun"></i>
                        <label>
                            <input type="checkbox" id="darkModeToggle" onclick="toggleDarkMode()">
                            <span class="slider"></span>
                        </label>
                        <i class="fas fa-moon"></i>
                    </div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-container">
                <?php
                    function buildtable($result) {
                        if (mysqli_num_rows($result) > 0) {
                            echo "<table>";
                            echo "<thead><tr>
                                <th>USERNAME</th>
                                <th>USER TYPE</th>
                                <th>STATUS</th>
                                <th>CREATED BY</th>
                                <th>DATE CREATED</th>
                                <th>ACTIONS</th>
                            </tr></thead><tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['username'] . "</td>";
                                echo "<td>" . $row['usertype'] . "</td>";
                                echo "<td>" . $row['status'] . "</td>";
                                echo "<td>" . $row['createdby'] . "</td>";
                                echo "<td>" . $row['datecreated'] . "</td>";
                                echo "<td>";
                                echo "<a href='update-account.php?username=" . $row['username'] . "&usertype=" . $row['usertype'] . 
                                    "' class='update-btn'><i class='fas fa-edit'></i></a>"; // Icon only
                                echo "<a href='#' onclick='confirmDelete(\"" . $row['username'] . "\")' 
                                class='delete-btn'><i class='fas fa-trash'></i></a>"; // Icon only
                                echo "</td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "<p>No records found.</p>";
                        }
                    }

                    require_once "config.php";
                    $sql = isset($_POST['btnsearch']) 
                        ? "SELECT * FROM tblaccounts 
                            WHERE username LIKE ? 
                                OR usertype LIKE ? 
                            ORDER BY username ASC" // Sort by datecreated in descending order
                        : "SELECT * FROM tblaccounts ORDER BY username ASC"; // Default sorting by datecreated DESC

                    if ($stmt = mysqli_prepare($link, $sql)) {
                        if (isset($_POST['btnsearch'])) {
                            $searchvalue = '%' . $_POST['txtsearch'] . '%';
                            mysqli_stmt_bind_param($stmt, "ss", $searchvalue, $searchvalue);
                        }
                        if (mysqli_stmt_execute($stmt)) {
                            $result = mysqli_stmt_get_result($stmt);
                            buildtable($result);
                        } else {
                            echo "<p>Error executing query.</p>";
                        }
                    } else {
                        echo "<p>Error preparing query.</p>";
                    }
                ?>
            </div>
        <?php else: header("location: login.php"); exit(); endif; ?>
    </div>

    <!-- Enhanced Confirm Delete Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="margin-top: 10px; border-radius: 5px;">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteModalLabel"><i class="fas fa-exclamation-triangle"></i> Delete Account Confirmation</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete this account?</p>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Simplified Add Account Modal -->
    <div class="modal fade modal-simple" id="addAccountModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="create-account.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                        </div>
                        <div class="form-group">
                            <label for="usertype">User Type</label>
                            <select class="form-control" id="usertype" name="usertype" required>
                                <option value="" disabled selected>Select user type</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"></div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'true'): ?>
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="margin-top: 10px; border-radius: 5px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Successful!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Account Deleted Successfully!</p>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn" style="background-color: #007bff; color: white;" data-dismiss="modal">Okay</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#successModal").modal("show");
        });
    </script>
    <?php endif; ?>

    <?php if (isset($_GET['update_success']) && $_GET['update_success'] === 'true'): ?>
    <div class="modal fade" id="updateSuccessModal" tabindex="-1" role="dialog" aria-labelledby="updateSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="margin-top: 10px; border-radius: 5px;">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="updateSuccessModalLabel"><i class="fas fa-check-circle"></i> Success</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Account Updated Successfully!</p>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Okay</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#updateSuccessModal").modal("show");
        });
    </script>
    <?php endif; ?>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutConfirmModal" tabindex="-1" role="dialog" aria-labelledby="logoutConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="margin-top: 10px; border-radius: 5px;">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="logoutConfirmModalLabel"><i class="fas fa-exclamation-circle"></i> Confirm Logout</h5>
                    <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to logout?</p>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a href="logout.php" class="btn btn-warning">Logout</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>