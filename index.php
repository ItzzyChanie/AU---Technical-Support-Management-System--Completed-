<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: login-intro.php");
    exit();
}

$usertype = $_SESSION['usertype'];
?>

<!DOCTYPE html>
<html lang = "en">
<head>
  <meta charset = "UTF-8">
  <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
  <title>Select Management System</title>
  <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <style>
    body {
      height: 100vh;
      margin: 0;
      background-color: #004ea8;
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      font-family: Arial, sans-serif;
    }
    .welcome-section {
      text-align: center;
      color: white;
      padding: 20px;
      margin-top: 30px;
      z-index: 10;
    }
    .welcome-section h2 {
      font-size: 28px;
      margin-bottom: 10px;
      font-weight: bold;
    }
    .management-link {
      position: absolute;
      width: 450px;
      height: 300px;
      background-color: #f8f9fa;
      border: 1px solid #e9ecef;
      border-radius: 15px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      box-shadow: 0 8px 15px rgba(0,0,0,0.2);
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      overflow: hidden;
      transition: transform 0.3s ease;
      transform-origin: center;
      cursor: pointer;
      z-index: 1;
    }
    .management-link::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.4);
      z-index: 1;
    }
    /* Global hover: scales up all containers */
    .management-link:hover {
      transform: scale(1.02);
    }
    .management-link span {
      text-decoration: none;
      color: white;
      font-size: 24px;
      font-weight: bold;
      z-index: 2;
      position: relative;
      padding: 15px;
      background: rgba(255,255,255,0.2);
      border-radius: 10px;
      backdrop-filter: blur(5px);
    }
    /* Ticket Management (default for ADMINISTRATOR/USER): centered */
    .ticket-management {
      bottom: 280px;
      left: 50%;
      transform: translateX(-50%);
      background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100"><text x="50" y="50" text-anchor="middle" dy=".3em" font-size="50">🎫</text></svg>');
    }
    .ticket-management:hover {
      transform: translateX(-50%) scale(1.02);
    }
    /* For TECHNICAL: move Ticket Management to the left */
    .ticket-management.left {
      bottom: 280px;
      left: 50px;
      transform: none;
    }
    .ticket-management.left:hover {
      transform: scale(1.02);
    }
    /* Equipment Management for ADMINISTRATOR (default): positioned to the right */
    .equipment-management {
      top: 150px;
      right: 50px;
      background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100"><text x="50" y="50" text-anchor="middle" dy=".3em" font-size="50">🛠️</text></svg>');
    }
    /* For TECHNICAL: center Equipment Management */
    .equipment-management.center {
      top: 150px;
      left: 50%;
      right: auto;
      transform: translateX(-50%);
    }
    .equipment-management.center:hover {
      transform: translateX(-50%) scale(1.02);
    }
    /* Account Management: used only for ADMINISTRATOR */
    .account-management {
      top: 150px;
      left: 50px;
      background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100"><text x="50" y="50" text-anchor="middle" dy=".3em" font-size="50">👥</text></svg>');
    }
    .back-button {
      position: absolute;
      bottom: 100px;
      left: 50%;
      transform: translateX(-50%);
      background-color: rgb(11,106,222);
      color: #fff;
      padding: 10px 20px;
      border-radius: 5px;
      text-decoration: none;
      transition: background-color 0.1s ease-in-out;
      z-index: 10;
    }
    .back-button:hover {
      background-color: #fff;
      color: rgb(11,106,222);
      border: 1px solid rgb(11,106,222);
    }
  </style>
</head>

<body>
  <div class = "welcome-section">
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <p>Please Select your System</p>
  </div>

  <?php if ($usertype === 'ADMINISTRATOR'): ?>
    <div class = "management-link account-management" onclick = "location.href='account-management.php'">
      <span>Account Management System</span>
    </div>

    <div class = "management-link equipment-management" onclick = "location.href='equipment-management.php'">
      <span>Equipment Management System</span>
    </div>

    <div class = "management-link ticket-management" onclick = "location.href='ticket-management.php'">
      <span>Ticket Management System</span>
    </div>

  <?php elseif ($usertype === 'TECHNICAL'): ?>
    <!-- TECHNICAL sees Equipment and Ticket Management -->
    <div class = "management-link equipment-management right" onclick = "location.href='equipment-management.php'">
      <span>Equipment Management System</span>
    </div>

    <div class = "management-link ticket-management left" onclick = "location.href='ticket-management.php'">
      <span>Ticket Management System</span>
    </div>

  <?php elseif ($usertype === 'USER'): ?>
    <!-- USER sees only Ticket Management -->
    <div class = "management-link ticket-management" onclick = "location.href='ticket-management.php'">
      <span>Ticket Management System</span>
    </div>
  <?php endif; ?>

  <a href = "login-intro.php" class = "back-button">Logout</a>
</body>
</html>
