<html>
  <head>
     <title>yoU Finance Tracker</title>
     <link rel="stylesheet" href="style/listTransactions.css">

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
         require_once("forms/listTransactions.php");
     ?>

     <hr/>

  </body>
</html>
