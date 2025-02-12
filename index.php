<html>
  <head>
     <title>yoU Finance Tracker</title>

     <link rel="stylesheet" href="style/index.css">
     <link rel="stylesheet" href="style/top_panel.css">
     <link rel="stylesheet" href="style/showMainSummary.css">
     <link rel="stylesheet" href="style/showDepositInputs.css">
     <link rel="stylesheet" href="style/showWithdrawInputs.css">

     <?php
        require_once("common.php");

        session_start();

        if (!isset($_SESSION['LOGGED_IN']))
        {
           header("location: /login.php");
        }
     ?>
  </head>

  <body>
     <?php
         require_once("forms/top_panel.php");
     ?>

     <hr/>

     <?php
        require_once("forms/showMainSummary.php");
     ?>

     <hr/>

     <div id='div_deposit_withdraw'>
          <ul id='ul_deposit_withdraw'>
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
