<?php
   if ($_SERVER['REQUEST_METHOD'] == 'POST')
   {
      require_once("../common.php");

      session_start();

      clearAllFilters();

      header("location: /index.php");
   }
?>
