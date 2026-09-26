<?php
   if ($_SERVER['REQUEST_METHOD'] == 'POST')
   {
      require_once("../common.php");

      session_start();

      // var_dump($_POST);
      // array(2) { ["min_amount_filter_text"]=> string(4) "-500" ["max_amount_filter_text"]=> string(3) "100" }

      // a missing box means the same thing as an empty one: no bound on that end
      $min_amount = isset($_POST['min_amount_filter_text']) ? $_POST['min_amount_filter_text'] : '';
      $max_amount = isset($_POST['max_amount_filter_text']) ? $_POST['max_amount_filter_text'] : '';

      // blanking both boxes is how the user turns this one filter back off, the
      // clear all button in the panel header resets every filter at once
      updateAmountFilter($min_amount, $max_amount);

      header("location: /index.php");
   }
?>
