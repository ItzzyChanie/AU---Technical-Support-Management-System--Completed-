<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: login-intro.php");
    exit();
}

$usertype = $_SESSION['usertype'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Select Management System</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <style>
    /* Add new header styles */
    .header {
      background-color: #004ea8;
      padding: 15px 20px;
      display: flex;
      align-items: center;
      width: 100%;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 100;
    }
    .header img {
      width: 60px;
      height: auto;
      margin-right: 15px;
    }
    .header h1 {
      color: white;
      margin: 0;
      font-size: 24px;
      font-weight: bold;
    }
    /* Adjust body to account for fixed header */
    body {
      height: 100vh;
      margin: 0;
      background-color: rgb(255, 255, 255);
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      font-family: Arial, sans-serif;
      padding-top: 90px;
      padding-bottom: 60px;
    }
    .welcome-section {
      text-align: center;
      color: #004ea8;
      padding: 20px;
      margin-top: 30px;
      z-index: 10;
    }
    .welcome-section h2 {
      font-size: 28px;
      margin-bottom: 10px;
      font-weight: bold;
    }
    .welcome-section h3 {
      font-size: 18px;
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
    /* Ticket Management (default positioning) */
    .ticket-management {
      bottom: 280px;
      left: 50%;
      transform: translateX(-50%);
      background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100"><text x="50" y="50" text-anchor="middle" dy=".3em" font-size="50">🎫</text></svg>');
    }
    .ticket-management:hover {
      transform: translateX(-50%) scale(1.02);
    }
    /* For TECHNICAL and USER accounts: alternate positioning */
    .ticket-management.left {
      bottom: 280px;
      left: 50px;
      transform: none;
    }
    .ticket-management.right {
      bottom: 280px;
      right: 50px;
      transform: none;
    }
    .ticket-management.left:hover {
      transform: scale(1.02);
    }
    /* Equipment Management: positioned to the right (or center) */
    .equipment-management {
      bottom: 280px;
      right: 50px;
      background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100"><text x="50" y="50" text-anchor="middle" dy=".3em" font-size="50">🛠️</text></svg>');
    }
    .equipment-management.center {
      bottom: 280px;
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
    /* New separate CSS for ADMINISTRATOR Ticket Management */
    .admin-ticket-management {
      bottom: 200px; /* Adjust this value to move the link closer to the bottom */
      left: 50%;
      transform: translateX(-50%);
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
    /* Add footer styles */
    .footer {
      background-color: rgb(4, 54, 111);
      color: white;
      text-align: center;
      padding: 15px;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      font-size: 14px;
    }
  </style>
</head>

<body>
  <!-- Add header -->
  <div class="header">
    <img src="pictures/au-logo.png" alt="AU Logo">
    <h1>AU - Technical Support Management System</h1>
  </div>

  <div class="welcome-section">
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <h3>Usertype, <?php echo $_SESSION['usertype']; ?></h3>
  </div>

  <?php if ($usertype === 'ADMINISTRATOR'): ?>
    <div class="management-link account-management" onclick="location.href='accounts-management.php'">
      <span>Account Management System</span>
    </div>

    <div class="management-link equipment-management" onclick="location.href='equipment-management.php'">
      <span>Equipment Management System</span>
    </div>

    <!-- ADMINISTRATOR now uses a separate CSS class for Ticket Management -->
    <div class="management-link ticket-management admin-ticket-management" onclick="location.href='ticket-management.php'">
      <span>Ticket Management System</span>
    </div>

  <?php elseif ($usertype === 'TECHNICAL'): ?>
    <!-- TECHNICAL sees Equipment and Ticket Management -->
    <div class="management-link equipment-management right" onclick="location.href='equipment-management.php'">
      <span>Equipment Management System</span>
    </div>

    <div class="management-link ticket-management left" onclick="location.href='ticket-management.php'">
      <span>Ticket Management System</span>
    </div>

  <?php elseif ($usertype === 'STAFF'): ?>
    <!-- USER sees Equipment and Ticket Management like TECHNICAL -->
    <div class="management-link equipment-management right" onclick="location.href='equipment-management.php'">
      <span>Equipment Management System</span>
    </div>

    <div class="management-link ticket-management left" onclick="location.href='ticket-management.php'">
      <span>Ticket Management System</span>
    </div>
  <?php endif; ?>

  <a href="login-intro.php" class="back-button">Logout</a>

  <!-- Add footer -->
  <div class="footer">
    <p>&copy; Copyright 2025, Jefferson B. Palceso.</p>
  </div>
</body>
</html>
