<?php
   if ($_SERVER['REQUEST_METHOD'] == 'POST')
   {
      require_once("../common.php");

      session_start();

      // var_dump($_POST);
      // array(2) { ["min_date_filter"]=> string(10) "2025-02-01" ["max_date_filter"]=> string(10) "2025-02-28" }

      // a missing box means the same thing as an empty one: no bound on that end
      $min_date = isset($_POST['min_date_filter']) ? $_POST['min_date_filter'] : '';
      $max_date = isset($_POST['max_date_filter']) ? $_POST['max_date_filter'] : '';

      // blanking both boxes is how the user turns this one filter back off, the
      // clear all button in the panel header resets every filter at once
      updateDateFilter($min_date, $max_date);

      header("location: /index.php");
   }
?>
