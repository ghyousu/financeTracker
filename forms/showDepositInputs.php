<div id='div_deposit_form'>

<form id='form_deposit'  method='POST' action='/post/deposit_post.php'>
   <b>Deposits</b>
   <table id='table_deposit_form' border=1>
      <tr>
         <th>Bank</th>
         <th>Amount</th>
         <th>Date</th>
         <th>Notes</th>
      </tr>

      <?php foreach ($banks as $bank): ?>
         <tr>
            <td class='td_deposit_bank_alias'> <?php echo $bank->bank_alias; ?> </td>

            <td class='td_deposit_amount'>
               <input class='input_deposit_amount' type="number" step='any' <?php echo "name='" . $bank->bank_alias . "@amount'"; ?> />
            </td>

            <td class='td_deposit_date'>
               <input class='input_deposit_date_picker' type="date" <?php echo "name='" . $bank->bank_alias . "@date'"; ?> />
            </td>

            <td class='td_deposit_notes'>
               <textarea class='textarea_deposit_notes' wrap='soft' rows='3' cols='30' <?php echo "name='" . $bank->bank_alias . "@notes'"; ?> ></textarea>
            </td>
         </tr>
      <?php endforeach; ?>

      <!-- the submit button -->
      <tr>
         <td colspan=4 id='td_deposit_submit'>
            <input id='input_deposit_submit' type="submit" value="Submit">
         </td>
      </tr>

   </table>
</form>

</div>
