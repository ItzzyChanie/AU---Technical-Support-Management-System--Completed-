<?php
$error_message = "";

if (isset($_POST['btnlogin'])) {
    require_once "config.php";

    $sql = "SELECT * FROM tblaccounts WHERE username = ? AND password = ? AND status = 'ACTIVE'";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $_POST['txtusername'], $_POST['txtpassword']);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) 
            {
                $accounts = mysqli_fetch_array($result, MYSQLI_ASSOC);
                session_start();
                $_SESSION['username'] = $accounts['username'];
                $_SESSION['usertype'] = $accounts['usertype']; // e.g. "USER", "ADMINISTRATOR", "TECHNICAL"
                header("location:index.php");
                exit();

            } else {
                $error_message = "Incorrect login details or account is inactive.";
            }
        }
    } else {
        $error_message = "Error on the select statement.";
    }
}
?>

<script>
    window.onload = function() {
        var errorMessage = "<?php echo $error_message; ?>";
        if (errorMessage !== "") {
            document.getElementById("loginModal").style.display = "block";
            document.getElementById("errorModalMessage").innerText = errorMessage;
            document.getElementById("errorModal").style.display = "block";
        }
    };
</script>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <title>AU - Technical Support Management System</title>

    <link rel = "stylesheet" href = "styleforlogin.css">
    <link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src = "https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #e02c1c;
            padding: 15px 40px;
            display: flex;
            align-items: center;
            position: fixed;
            width: 100%;
            z-index: 3;
            height: 50px;
        }

        .header-blue {
            background-color: #004ea8;
            padding: 15px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            z-index: 2;
            height: 150px;
            margin-top: 50px;
        }

        .header-blue img {
            height: 110px;
            margin-right: 15px;
            padding-bottom: 30px;
        }

        .header-blue h1 {
            margin: 0;
            color: #fff;
            font-weight: lighter;
            font-family: "Times New Roman", Times, serif; /* Apply Times New Roman */
            padding-bottom: 30px;
        }

        .header-links {
            display: flex;
            gap: 15px;
            align-items: flex-end;
            margin-bottom: 70px;
        }

        .header-links a {
            color: white;
            text-decoration: none;
            font-size: 17px;
            font-family: "Arial", sans-serif; /* Apply Arial font */
            position: relative;
            padding-top: 183px;
            margin-right: 30px;
        }

        .header-links a::after {
            content: "";
            position: absolute;
            width: 100%;
            height: 4px;
            background-color: white;
            bottom: -5px; /* Position at the bottom edge of header-blue */
            left: 0;
            transform: scaleX(0);
            transform-origin: bottom right;
            transition: transform 0.25s ease-out;
        }

        .header-links a:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        .buttons {
            margin-left: auto; /* Pushes buttons to the right */
            display: flex;
            gap: 15px;
        }

        .buttons button {
            padding: 5px 20px;
            border: none;
            cursor: pointer;
        }

        .login {
            background-color: transparent;
            color: white;
        }

        .login:hover {
            color: #004ea8;
        }

        .signup {
            background-color: #004ea8;
            color: white;
        }

        .signup:hover {
            background-color: darkblue;
            color: white;
        }

        .background {
            flex: 1;
            display: flex;
            justify-content: flex-start; /* Align to the left */
            align-items: center;
            overflow: hidden;
            position: absolute;
            top: 200px; /* Adjusted to place below the header-blue */
            left: 0;
            width: 100%;
            height: calc(100% - 220px); /* Adjust height to cover remaining body */
            background: url('pictures/au-background.jpg') no-repeat center center fixed;
            background-size: cover;
            z-index: 1;
        }

        .background::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(blue, red); /* Blue at top, red at bottom */
            opacity: 0.7; /* Slightly low opacity */
            z-index: 1;
        }

        .text {
            position: relative;
            padding-left: 110px; /* Move text to the left */
            z-index: 2;
            font-size: 4em; /* Make the font size bigger */
            font-weight: bold;
            color: white;
            white-space: pre-line;
            padding-bottom: 25px;
        }

        .text::after {
            content: "___________________";
            display: block;
            margin-top: -75px;
        }

        .background img {
            position: absolute;
            top: 0;
            right: 50px;
            height: 350px;
            z-index: 2;
            margin-right: 60px;
            margin-top: 60px;
        }

        footer {
            background-color: rgb(11, 44, 87);
            padding: 10px 20px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 50px;
            z-index: 3;
            color: #fff;
        }

        /* Styles for the login modal */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 5; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            background-color: rgba(0,0,0,0.8); /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto; /* 10% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 90%; /* Adjusted width */
            max-width: 500px; /* Maximum width */
            box-shadow: 0 5px 15px rgba(0,0,0,.5);
            border-radius: 10px;
            text-align: center; /* Center the text */
        }

        .modal-content h2 {
            margin-bottom: 20px; /* Space below the heading */
        }

        .modal-header h2 {
            padding-left: 30px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Styles for the error modal */
        .error-modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 6; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            background-color: rgba(0,0,0,0.8); /* Black w/ opacity */
        }

        .error-modal-content {
            background-color: #fefefe;
            margin: 20% auto; /* 20% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Adjusted width */
            max-width: 400px; /* Maximum width */
            box-shadow: 0 5px 15px rgba(0,0,0,.5);
            border-radius: 10px;
            text-align: center; /* Center the text */
        }

        .error-modal-content h2 {
            margin-bottom: 20px; /* Space below the heading */
        }

        .error-modal .close-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .error-modal .close-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
<header>
        <div class = "buttons">
            <button class = "btn login" id = "loginBtn"><i class = "fas fa-sign-in-alt"></i> LOGIN</button>
            <button class = "btn btn-secondary signup">Sign Up</button>
        </div>
    </header>

    <div class = "header-blue">
        <div style = "display: flex; align-items: center;">
            <img src = "pictures/au-logo.png" alt = "AU Logo">
            <h1>ARELLANO UNIVERSITY</h1>
        </div>

        <div class="header-links">
            <a href = "#">Account Management</a>
            <a href = "#">Equipment Management</a>
            <a href = "#">Arellano FB Page</a>
            <a href = "#">Ticket Management</a>
        </div>
    </div>

    <div class = "background">
        <div class = "text">AU -<br>Technical Support<br>Management System</div>
        <img src = "pictures/au-chiefs.png" alt = "AU Chiefs">
    </div>

    <footer>
        &copy; Copyright 2025, Jefferson B. Palceso.
    </footer>

    <!-- The Login Modal -->
    <div id = "loginModal" class = "modal">
        <div class = "modal-dialog modal-dialog-centered">
            <div class = "modal-content p-4" style = "border-radius: 10px;">

                <div class = "modal-header">
                    <h2 class = "modal-title w-100 text-center font-weight-bold">Ticket Management System</h2>
                    <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
                        <span>&times;</span>
                    </button>
                </div>

                <div class = "modal-body">
                    <form method = "post" action = "login-intro.php">

                        <div class = "form-group text-left">
                            <label for = "txtusername" class = "font-weight-bold">Username:</label>
                            <input type = "text" class = "form-control" id = "txtusername" name = "txtusername" placeholder = "Enter your username" required>
                        </div>

                        <div class="form-group text-left">
                            <label for = "txtpassword" class = "font-weight-bold">Password:</label>
                            <input type = "password" class = "form-control" id ="txtpassword" name = "txtpassword" placeholder = "Enter your password" required>
                        </div>

                        <button type = "submit" class = "btn btn-primary btn-block" name = "btnlogin">Login</button>
                    </form>

                    <div class = "text-center mt-3">
                        <a href = "#" class = "text-secondary">Forgot password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id = "errorModal" class = "error-modal">
        <div class = "error-modal-content">
            <h2>Oops!!</h2>
            <p id = "errorModalMessage"></p>
            <button class = "close-btn" onclick = "document.getElementById('errorModal').style.display='none'">Close</button>
        </div>
    </div>

    <script>
        var modal = document.getElementById("loginModal");
        var btn = document.getElementById("loginBtn");
        var closeBtn = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
