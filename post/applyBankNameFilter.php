<?php
   if ($_SERVER['REQUEST_METHOD'] == 'GET')
   {
      require_once("../common.php");

      session_start();

      if (isset($_GET['bank_id']))
      {
         $bank_id = $_GET['bank_id'];

         applyBankIdFilter($bank_id);
      }

      header("location: /index.php");
   }
?>
