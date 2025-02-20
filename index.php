<html>
  <head>
     <title>yoU Finance Tracker</title>

     <script type="text/javascript" src="scripts/index.js"></script>

     <link rel="stylesheet" href="style/index.css">
     <link rel="stylesheet" href="style/top_panel.css">
     <link rel="stylesheet" href="style/showMainSummary.css">
     <link rel="stylesheet" href="style/addNewTransactions.css">
     <link rel="stylesheet" href="style/showRecentTransactions.css">

     <?php
        require_once("common.php");

        session_start();

        if (!isset($_SESSION['LOGGED_IN']))
        {
           // header("location: /login.php");
           /* use auto login */
           $username = 'myou';
           $password = '1234';
           authenticateUser($username, $password);
        }
     ?>
  </head>

  <body onload="on_page_loaded()">
     <?php
         // use auto-login require_once("forms/top_panel.php");
     ?>

     <!-- <hr/> -->

     <?php
        require_once("forms/showMainSummary.php");
     ?>

     <hr/>

     <?php $banks = getAllBanksArray(); ?>

     <div id='div_deposits'>
        <?php  require_once("forms/addNewTransactions.php"); ?>
     </div>

     <hr/>

     <div id='div_transactions'>
        <?php require_once("forms/showRecentTransactions.php"); ?>
     </div>

  </body>
</html>
