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
           header("location: /login.php");
        }
     ?>
  </head>

  <body onload="on_page_loaded()">
     <?php
         require_once("forms/top_panel.php");
     ?>

     <hr/>

     <?php
        require_once("forms/showMainSummary.php");
     ?>

     <hr/>

     <div id='div_transactions'>
          <ul id='ul_transactions'>
             <?php $banks = getAllBanksArray(); ?>
             <li>
                <?php require_once("forms/addNewTransactions.php"); ?>
             </li>

             <li>
                <?php require_once("forms/showRecentTransactions.php"); ?>
             </li>
          </ul>
     </div>

  </body>
</html>
