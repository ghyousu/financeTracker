<div id='div_transactions_form'>

<form id='form_deposit'  method='POST' action='/post/postNewTransactions.php'>
   <b>New Transactions</b>

   <table id='table_transactions_form' border=1>
      <tr>
         <th>Bank</th>
         <th>Amount</th>
         <th>Date</th>
         <th>Notes</th>
      </tr>

      <?php foreach ($banks as $bank): ?>
         <tr>
            <td class='td_transactions_bank_alias'> <?php echo $bank->bank_alias; ?> </td>

            <td class='td_transactions_amount'>
               <input class='input_transactions_amount' type="number" step='any' <?php echo "name='" . $bank->bank_alias . "@amount'"; ?> />
            </td>

            <td class='td_transactions_date'>
               <input class='input_transactions_date_picker' type="date" <?php echo "name='" . $bank->bank_alias . "@date'"; ?> />
            </td>

            <td class='td_transactions_notes'>
               <textarea class='textarea_transactions_notes' wrap='soft' rows='3' cols='30' <?php echo "name='" . $bank->bank_alias . "@notes'"; ?> ></textarea>
            </td>
         </tr>
      <?php endforeach; ?>

      <!-- the submit button -->
      <tr>
         <td colspan=4 id='td_transactions_submit'>
            <input id='input_transactions_submit' type="submit" value="Submit">
         </td>
      </tr>

   </table>
</form>

</div>
