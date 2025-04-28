<?php
require_once "config.php";
include ("session-checker.php");

$error_message = "";
$success_message = "";

if (isset($_POST['btnsubmit'])) {
    $username = $_POST['txtusername'];
    $password = $_POST['txtpassword'];
    $usertype = $_POST['cmbtype'];

    // Check if the username already exists
    $sql = "SELECT * FROM tblaccounts WHERE username = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 0) {
                // Add account
                $sql = "INSERT INTO tblaccounts (username, password, usertype, status, createdby, datecreated) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    $status = 'ACTIVE';
                    $date = date("d/m/Y");
                    mysqli_stmt_bind_param($stmt, "ssssss", $username, $password, $usertype, $status, $_SESSION['username'], $date);

                    if (mysqli_stmt_execute($stmt)) {
                        // Log the creation
                        $log_sql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) 
                                    VALUES (?, ?, ?, ?, ?, ?)";
                        if ($log_stmt = mysqli_prepare($link, $log_sql)) {
                            $datelog = date("Y-m-d");
                            $timelog = date("H:i:s");
                            $action = "CREATE";
                            $module = "Account Management";
                            $performedto = $username;
                            $performedby = $_SESSION['username'];
                            mysqli_stmt_bind_param($log_stmt, "ssssss", $datelog, $timelog, $action, $module, $performedto, $performedby);
                            mysqli_stmt_execute($log_stmt);
                        }

                        $success_message = "Account Successfully Added!";
                    } else {
                        $error_message = "ERROR on adding new account.";
                    }
                }
            } else {
                $error_message = "Username is already in use.";
            }
        }
    } else {
        $error_message = "ERROR on validating if username exists.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Account Page - AU Technical Support Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <style>
        body {
            background-color: rgb(223, 225, 228);
        }
        .container {
            margin-top: 50px;
        }
        .form-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .password-container {
            position: relative;
        }
        .password-container input {
            padding-right: 40px; /* space for the eye icon */
        }
        .password-container .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 10; /* ensure it's on top */
            color: #6c757d; /* optional: icon color */
        }
        .button-container {
            display: flex;
            justify-content: flex-end; /* Align both buttons to the right */
            gap: 10px; /* Add spacing between buttons */
        }
        /* Style for the close button without square border */
        .modal-header .btn-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            cursor: pointer;
        }
        .modal-header .btn-close:hover {
            color: #ccc;
        }
    </style>
</head>

<body>
    <?php if (!empty($success_message)): ?>
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel"><i class="fas fa-check-circle"></i> Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="mb-0"><?php echo $success_message; ?></p>
                </div>
                <div class="modal-footer justify-content-end">
                    <a href="accounts-management.php" class="btn btn-success">Okay</a>
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

    <?php if (!empty($error_message)): ?>
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="errorModalLabel"><i class="fas fa-exclamation-circle"></i> Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="mb-0"><?php echo $error_message; ?></p>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#errorModal").modal("show");
        });
    </script>
    <?php endif; ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 form-box">
                <h2 class="text-center text-primary">Create New Account</h2>

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <div class="mb-3">
                        <label for="txtusername" class="form-label">Username</label>
                        <input type="text" name="txtusername" placeholder="Enter Username" class="form-control" id="txtusername" value="<?php echo isset($_POST['txtusername']) ? htmlspecialchars($_POST['txtusername']) : ''; ?>" required>
                    </div>

                    <div class="mb-3 password-container">
                        <label for="txtpassword" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="txtpassword" placeholder="Enter Password" class="form-control" id="txtpassword" value="<?php echo isset($_POST['txtpassword']) ? htmlspecialchars($_POST['txtpassword']) : ''; ?>" required>
                            <span class="input-group-text toggle-password" id="togglePassword">
                                <i class="fa-solid fa-eye" id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="cmbtype" class="form-label">Account Type</label>
                        <select name="cmbtype" id="cmbtype" class="form-select" required>
                            <option value="">--Select Account Type--</option>
                            <option value="ADMINISTRATOR" <?php echo (isset($_POST['cmbtype']) && $_POST['cmbtype'] == "ADMINISTRATOR") ? 'selected' : ''; ?>>Administrator</option>
                            <option value="TECHNICAL" <?php echo (isset($_POST['cmbtype']) && $_POST['cmbtype'] == "TECHNICAL") ? 'selected' : ''; ?>>Technical</option>
                            <option value="STAFF" <?php echo (isset($_POST['cmbtype']) && $_POST['cmbtype'] == "STAFF") ? 'selected' : ''; ?>>Staff</option>
                        </select>
                    </div>

                    <div class="button-container">
                    <button type="submit" name="btnsubmit" class="btn btn-primary">Save</button>
                        <a href="accounts-management.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.querySelector('.toggle-password').addEventListener('click', function () {
        const passwordInput = document.getElementById('txtpassword');
        const icon = document.getElementById('toggleIcon');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
</script>

</body>
</html>
