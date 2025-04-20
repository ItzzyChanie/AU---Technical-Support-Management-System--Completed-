<?php
require_once "config.php";
include ("session-checker.php");

if (isset($_POST['btnsubmit']))
{
    //check if the username is existing on the table
    $sql = "SELECT * FROM tblaccounts WHERE username = ?";

    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "s", $_POST['txtusername']);
        if (mysqli_stmt_execute($stmt))
        {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 0)
            {
                //add accounts
                $sql = "INSERT INTO tblaccounts (username, password, usertype, status, createdby, datecreated) 
                VALUES (?, ?, ?, ?, ?, ?)";
                if ($stmt = mysqli_prepare($link, $sql))
                {
                    $status = 'ACTIVE';
                    $date = date("d/m/Y");
                    mysqli_stmt_bind_param($stmt, "ssssss", $_POST['txtusername'], $_POST['txtpassword'], 
                    $_POST['cmbtype'], $status, $_SESSION['username'], $date);

                    if (mysqli_stmt_execute($stmt))
                    {
                        // Log the creation
                        $log_sql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) 
                                    VALUES (?, ?, ?, ?, ?, ?)";
                        if ($log_stmt = mysqli_prepare($link, $log_sql)) {
                            $datelog = date("Y-m-d");
                            $timelog = date("H:i:s");
                            $action = "CREATE";
                            $module = "Account";
                            $performedto = $_POST['txtusername'];
                            $performedby = $_SESSION['username'];
                    
                            mysqli_stmt_bind_param($log_stmt, "ssssss", $datelog, $timelog, $action, $module, $performedto, $performedby);
                            mysqli_stmt_execute($log_stmt);
                        }
                    
                        echo "<script>alert('Account created Successfully!');</script>";
                        echo "<script>window.location.href='accounts-management.php';</script>";
                        exit();
                    }
                    
                }
                else
                {
                    echo "<div class='alert alert-danger'>ERROR on adding new account.</div>";
                }
            }
            else
            {
                echo "<div class='alert alert-warning'>Username is already in use.</div>";
            }
        }
    }
    else
    {
        echo "<div class='alert alert-danger'>ERROR on validating if username exists.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <title>Create New Account Page - AU Technical Support Management System</title>
    <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel = "stylesheet">

    <style>
        body {
            background-color:rgb(223, 225, 228);
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
            <h2 class = "text-center text-primary">Create New Account</h2>

            <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">

                <div class = "mb-3">
                    <label for = "txtusername" class = "form-label">Username</label>
                    
                    <input type = "text" name = "txtusername" placeholder = "Enter Username" class = "form-control" id = "txtusername" required>
                </div>

                <div class = "mb-3 password-container">
                    <label for = "txtpassword" class = "form-label">Password</label>

                    <div class = "input-group">
                        <input type = "password" name = "txtpassword" placeholder = "Enter Password" class = "form-control" id = "txtpassword" required>
                        <span class = "input-group-text toggle-password"><i class="fas fa-eye"></i></span>
                    </div>
                </div>

                <div class = "mb-3">
                    <label for = "cmbtype" class = "form-label">Account Type</label>

                    <select name = "cmbtype" id = "cmbtype" class = "form-select" required>
                        <option value = "">--Select Account Type--</option>
                        <option value = "ADMINISTRATOR">Administrator</option>
                        <option value = "TECHNICAL">Technical</option>
                        <option value = "STAFF">Staff</option>
                    </select>
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
</script>

</body>
</html>
