<?php
   if ($_SERVER['REQUEST_METHOD'] == 'POST')
   {
      require_once("../common.php");

      session_start();
      // var_dump($_POST);
      // array(27) {
      //  ["bank_alias@amount"]=> string(4) "1234"
      //  ["bank_alias@date"]=> string(10) "2025-02-27"
      //  ["bank_alias@notes"]=> string(16) "testing boa_myou"

      $banks = getAllBanksArray();
      $transactions = array();

      foreach ($banks as $bank)
      {
         $post_amount_var_name = $bank->bank_alias . '@amount';
         $post_date_var_name   = $bank->bank_alias . '@date';
         $post_notes_var_name  = $bank->bank_alias . '@notes';

         if (!isset($_POST[$post_amount_var_name]))
         {
            die('no amount field for bank ' . $bank->bank_alias . '. Please update the code');
         }
         else if ($_POST[$post_amount_var_name] != '' )
         {
            $trans = new SQLTransaction();

            $trans->user_id    = $_SESSION['sql_user_info']->user_id;
            $trans->bank_db_id = $bank->bank_db_id;
            $trans->amount     = $_POST[$post_amount_var_name];
            $trans->trans_date = $_POST[$post_date_var_name];
            $trans->notes      = str_replace("'", "''", $_POST[$post_notes_var_name]);

            array_push($transactions, $trans);
         }
      }

      addTransactions($transactions);

      header("location: /index.php");
   }
?>
