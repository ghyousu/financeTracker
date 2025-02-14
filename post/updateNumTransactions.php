
<?php
   if ($_SERVER['REQUEST_METHOD'] == 'POST')
   {
      require_once("../common.php");

      session_start();

      // var_dump($_POST);
      // array(1) { ["num_transactions"]=> string(2) "34" }

      $new_max_entries = $_POST['num_transactions'];

      updateMaxTransactions($new_max_entries);

      header("location: /index.php");
   }
?>
