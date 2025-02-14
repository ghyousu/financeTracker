<?php

   if ($_SERVER['REQUEST_METHOD'] == 'POST')
   {
      require_once("../common.php");

      session_start();

      // var_dump($_POST);
      // array(1) { ["td_trans_checkbox"]=> array(1) { [0]=> string(1) "5" } } test

      $num_trans_ids = count($_POST['td_trans_checkbox']);

      $trans_id_array = array();

      for ($i=0; $i<$num_trans_ids; ++$i)
      {
         $trans_id = $_POST['td_trans_checkbox'][$i];

         printDebug('trans_id: ' . $trans_id, 0);

         array_push($trans_id_array, $trans_id);
      }

      deleteTransactions($trans_id_array);

      header("location: /index.php");
   }
?>
