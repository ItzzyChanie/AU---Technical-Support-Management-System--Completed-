<?php
require_once "config.php";
include "session-checker.php";

if (isset($_POST["btnsubmit"])) {
    $sql = "UPDATE tblaccounts SET password = ?, usertype = ?, status = ? WHERE username = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssss", $_POST['txtpassword'], $_POST['cmbtype'], $_POST['rbstatus'], $_GET['username']);

        if (mysqli_stmt_execute($stmt)) {
            $sql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) VALUE(?, ?, ?, ?, ?, ?)";

            if ($stmt = mysqli_prepare($link, $sql)) 
            {
                $date = date("d/m/Y");
                $time = date("h:i:sa");
                $action = "UPDATE";
                $module = "account-management";
                mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, $_GET['username'], $_SESSION['username']);

                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('Account Updated!'); window.location.href='accounts-management.php';</script>";
                    exit();
                }
            } else {
                echo "<font color='red'>Error on inserting logs.</font>";
            }
        }
    } else {
        echo "<font color='red'>Error on updating account.</font>";
    }
} else {
    if (isset($_GET["username"]) && !empty($_GET["username"])) {

        $sql = "SELECT * FROM tblaccounts WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) 
        {
            mysqli_stmt_bind_param($stmt, "s", $_GET["username"]);

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                $account = mysqli_fetch_array($result);
            }
        }
    } else {
        echo "<font color='red'>Error on loading account data.</font>";
    }
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <title>Update Account - AU Technical Support Management System</title>

    <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel = "stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
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
        .password-container input[type="password"] {
            padding-right: 40px;
        }
        .password-container .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>

<body>
<div class = "container">
    <div class = "row justify-content-center">
        <div class = "col-md-6 form-box">

            <h2 class = "text-center text-primary">Update Account</h2>
            <p class = "text-center">Change the value on this form and submit to update the account</p>

            <form action = "<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method = "POST">

                <div class = "mb-3">
                    <label for = "txtusername" class = "form-label">Username</label>
                    <input type = "text" name = "txtusername" class = "form-control" id = "txtusername" value = "<?php echo $account['username']; ?>" disabled>
                </div>
 
                <div class = "mb-3 password-container">
                    <label for = "txtpassword" class = "form-label">Password</label>

                    <div class = "input-group">
                        <input type = "password" name = "txtpassword" class = "form-control" id = "txtpassword" value = "<?php echo $account['password']; ?>" required>
                        <span class = "input-group-text toggle-password"><i class = "fas fa-eye"></i></span>
                    </div>
                </div>

                <div class = "mb-3">
                    <label for = "cmbtype" class = "form-label">Account Type</label>
            
                    <select name = "cmbtype" id = "cmbtype" class = "form-select" required>
                        <option value = "">--Select Account Type--</option>
                        <option value = "ADMINISTRATOR" <?php if ($account['usertype'] == 'ADMINISTRATOR') echo 'selected'; ?>>Administrator</option>
                        <option value = "TECHNICAL" <?php if ($account['usertype'] == 'TECHNICAL') echo 'selected'; ?>>Technical</option>
                        <option value = "STAFF" <?php if ($account['usertype'] == 'STAFF') echo 'selected'; ?>>Staff</option>
                    </select>
                </div>

                <div class = "mb-3">
                    <label class = "form-label">Status</label><br>

                    <div class = "form-check form-check-inline">
                        <input class = "form-check-input" type = "radio" name = "rbstatus" id = "active" value = "ACTIVE"
                        <?php if ($account['status'] == 'ACTIVE') echo 'checked'; ?>>
                        <label class = "form-check-label" for = "active">Active</label>
                    </div>

                    <div class = "form-check form-check-inline">
                        <input class = "form-check-input" type = "radio" name = "rbstatus" id = "inactive" value = "INACTIVE"
                        <?php if ($account['status'] == 'INACTIVE') echo 'checked'; ?>>
                        <label class = "form-check-label" for = "inactive">Inactive</label>
                    </div>
                </div>

                <div class = "d-flex justify-content-between">
                    <button type = "submit" name = "btnsubmit" class = "btn btn-success">Submit</button>
                    <a href = "accounts-management.php" class = "btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<script>
    document.querySelector('.toggle-password').addEventListener('click', function (e) {
        const passwordInput = document.getElementById('txtpassword');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    document.getElementById('cmbtype').value = "<?php echo $account['usertype']; ?>";
</script>

</body>
</html>
