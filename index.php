<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <title>Select Management System</title>

    <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-size: cover; /* Cover the whole body */
            background-color: #004ea8;
            position: relative;
        }

        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
            position: relative;
            z-index: 2; /* Ensure form is above the gradient */
        }

        .form-container h2 {
            margin-bottom: 20px;
            font-size: 22px;
            font-weight: bold;
        }

        .form-container a {
            text-decoration: none;
            font-size: 18px;
            color: #007bff;
            font-weight: bold;
            display: block;
            margin-top: 15px;
            transition: 0.3s ease-in-out;
        }

        .form-container a:hover {
            color: #0056b3;
            text-decoration: none;
        }

        .form-container .back-button {
            background-color:rgb(11, 106, 222); /* Background color for the back button */
            color:rgb(255, 255, 255);
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 15px;
            transition: background-color 0.1s ease-in-out;
        }

        .form-container .back-button:hover {
            background-color:rgb(255, 255, 255); /* Hover effect color */
        }
    </style>
</head>

<body>
    <div class = "form-container">
        <h2>WELCOME!<br>Please select your System</h2>
        <a href = "ticket-management.php">Ticket Management</a>
        <br>
        <a href = "login-intro.php" class = "back-button">Back</a>
    </div>
</body>
</html>
