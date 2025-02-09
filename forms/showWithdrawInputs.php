<div id='div_withdraw_form'>

<form id='form_withdraw' action='/post/withdraw_post.php'>
   <b>Withdraws/Payments</b>
   <table id='table_withdraw_form' border=1>
      <tr>
         <th>Bank</th>
         <th>Amount</th>
         <th>Date</th>
         <th>Notes</th>
      </tr>

      <?php foreach ($banks as $bank): ?>
         <tr>
            <td class='td_withdraw_bank_alias'> <?php echo $bank->bank_alias; ?> </td>

            <td class='td_withdraw_amount'>
               <input class='input_withdraw_amount' type="number" step='any' <?php echo "name='" . $bank->bank_alias . "@amount'"; ?> />
            </td>

            <td class='td_withdraw_date'>
               <input class='input_withdraw_date_picker' type="date" <?php echo "name='" . $bank->bank_alias . "@date'"; ?> />
            </td>

            <td class='td_withdraw_notes'>
               <textarea class='textarea_withdraw_notes' wrap='soft' rows='3' cols='30' <?php echo "name='" . $bank->bank_alias . "@notes'"; ?> ></textarea>
            </td>
         </tr>
      <?php endforeach; ?>

      <!-- the submit button -->
      <tr>
         <td colspan=4 id='td_withdraw_submit'>
            <input id='input_withdraw_submit' type="submit" value="Submit">
         </td>
      </tr>

   </table>
</form>

</div>
