<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>System Status | AD-Meeting-Calendar</title>
  <link rel="stylesheet" href="/assets/css/status.css">
  <style>
    .header {
      background-color: #007bff;
      color: white;
      padding: 20px;
      font-size: 24px;
      text-align: center;
      font-weight: bold;
    }

    .container {
      max-width: 800px;
      margin: 40px auto;
      padding: 20px;
      background-color: #f9f9f9;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .status-row {
      display: flex;
      justify-content: space-between;
      gap: 20px;
      margin-top: 20px;
    }

    .status-card {
      flex: 1;
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 1px 5px rgba(0,0,0,0.1);
      text-align: center;
    }

    .btn {
      display: inline-block;
      padding: 12px 24px;
      background-color: #007bff;
      color: white;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      font-size: 16px;
      margin-top: 30px;
    }

    .btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

  <div class="header">
    AD Meeting Calendar
  </div>

  <div class="container">
    <h2>✅ System Status Check</h2>
    <p>Below are the results of your database connection checks:</p>

    <!-- ✅ Flex container for connection cards -->
    <div class="status-row">
      <div class="status-card">
        <?php include_once "handlers/mongodbChecker.handler.php"; ?>
      </div>
      <div class="status-card">
        <?php include_once "handlers/postgreChecker.handler.php"; ?>
      </div>
    </div>

    <div style="text-align: center;">
      <a href="/pages/login/index.php" class="btn">Go to Login Page</a>
    </div>
  </div>

</body>
</html>