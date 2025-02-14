<div id='div_recent_transactions'>

   <ul id='ul_recent_trans'>
      <li>
         Recent Transactions
      </li>

      <li>
         <form id='form_num_transactions' method='POST' action='/post/updateNumTransactions.php'>
            <label for='input_num_trans'>Max Entries:</label>
            <input  id='input_num_trans' type='number' step='1' <?php echo "value=" . $_SESSION['sql_user_info']->max_trans; ?> name='num_transactions' />
         </form>
      </li>
   </ul>

   <table id='table_recent_transactions' border=1>
      <tr>
         <th/>
         <th>Bank</th>
         <th>Date</th>
         <th>Amount</th>
         <th>Notes</th>
      </tr>

      <?php
          $transactions = getRecentTransctions(0); // 0 for bank_db_id, means all banks
          $row_index = 1;
      ?>
      <?php foreach ($transactions as $trans): ?>
         <?php if ($trans->amount > 0) : ?>
         <tr class='tr_deposits'>
         <?php else : ?>
         <tr class='tr_withdrawals'>
         <?php endif; ?>
            <td class='td_deposit_row_index'> <?php echo $row_index++; ?> </td>

            <td class='td_trans_bank_alias'> <?php echo $trans->bank_alias; ?> </td>

            <td class='td_trans_date'> <?php echo substr($trans->trans_date, 0, 10); ?> </td>

            <td class='td_trans_amount'> <?php echo $trans->amount; ?> </td>

            <td class='td_trans_notes'> <?php echo $trans->notes; ?> </td>
         </tr>
      <?php endforeach; ?>
   </table>

</div>
