<script type="text/javascript">

function getBankFilterId()
{
   return <?php echo $_SESSION["sql_user_info"]->bank_id_filter; ?>;
}

</script>

<div id='div_recent_transactions'>

   <?php
      $banks = getAllBanksArray();
      $active_filters = getActiveFilterDescriptions($banks);
      $num_active_filters = count($active_filters);
   ?>

   <div id='filter_panel'>

      <div class='filter_panel_head'>
         <span class='filter_title'>Filters</span>

         <?php if ($num_active_filters > 0) : ?>
            <span class='filter_active_count'><?php echo $num_active_filters; ?> active</span>
         <?php endif; ?>

         <form id='form_clear_all_filters' class='filter_clear_form' method='POST' action='/post/clearAllFilters.php'>
            <input id='clear_all_filters_btn' class='filter_btn filter_btn_quiet' type='submit' value='Clear all' <?php echo ($num_active_filters > 0) ? '' : 'disabled'; ?> />
         </form>
      </div>

      <!-- an applied filter is easy to forget about, so spell out what is
           currently narrowing the list -->
      <?php if ($num_active_filters > 0) : ?>
         <ul class='filter_chips'>
            <?php foreach ($active_filters as $active_filter) : ?>
               <li class='filter_chip'><?php echo htmlspecialchars($active_filter, ENT_QUOTES); ?></li>
            <?php endforeach; ?>
         </ul>
      <?php endif; ?>

      <div class='filter_fields'>

         <div class='filter_field' id='filter_field_bank'>
            <label for='bank_dropdown_filter'>Bank</label>
            <div class='filter_control'>
               <select name='bank_filter' id='bank_dropdown_filter' onchange='bank_filter_changed()'>
                  <option value='0'>All</option>
                  <?php
                     foreach ($banks as $bank_obj)
                     {
                        echo '<option value="' . $bank_obj->bank_db_id . '">' . htmlspecialchars($bank_obj->bank_alias, ENT_QUOTES) . '</option>';
                     }
                  ?>
               </select>
            </div>
         </div>

         <form id='form_num_transactions' class='filter_field' method='POST' action='/post/updateNumTransactions.php'>
            <label for='input_num_trans'>Rows to show</label>
            <div class='filter_control'>
               <input id='input_num_trans' type='number' step='1' min='1' <?php echo "value='" . $_SESSION['sql_user_info']->max_trans . "'"; ?> name='num_transactions' />
               <input class='filter_btn' type='submit' value='Apply' />
            </div>
         </form>

         <form id='form_notes_filter' class='filter_field' method='POST' action='/post/updateTransactionNotesFilter.php'>
            <label for='input_notes_filter'>Notes contains</label>
            <div class='filter_control'>
               <input id='input_notes_filter' type='text' <?php echo "value='" . htmlspecialchars($_SESSION['sql_user_info']->notes_filter, ENT_QUOTES) . "'"; ?> name='notes_filter_text' placeholder='anything' />
               <input class='filter_btn' type='submit' value='Apply' />
            </div>
         </form>

         <!-- either end of the range may be left blank for an open ended range -->
         <form id='form_amount_filter' class='filter_field' method='POST' action='/post/updateAmountFilter.php'>
            <span class='filter_label'>Amount</span>
            <div class='filter_control'>
               <span class='filter_range'>
                  <span class='filter_currency'>$</span><input id='input_min_amount_filter' class='filter_amount' type='number' step='any' aria-label='Amount from' <?php echo "value='" . getAmountBoundForInput($_SESSION['sql_user_info']->min_amount_filter) . "'"; ?> name='min_amount_filter_text' placeholder='no min' title='Show transactions at or above this amount' />
                  <span class='filter_range_sep'>to</span>
                  <span class='filter_currency'>$</span><input id='input_max_amount_filter' class='filter_amount' type='number' step='any' aria-label='Amount to' <?php echo "value='" . getAmountBoundForInput($_SESSION['sql_user_info']->max_amount_filter) . "'"; ?> name='max_amount_filter_text' placeholder='no max' title='Show transactions at or below this amount' />
               </span>
               <input class='filter_btn' type='submit' value='Apply' />
            </div>
         </form>

      </div>
   </div>


<form id='form_delete_trans'  method='POST' action='/post/deleteTransactions.php'>
   <table id='table_recent_transactions' border=1>
      <tr>
         <th/>
         <th>Bank</th>
         <th>Date</th>
         <th>Amount</th>
         <th>Notes</th>
<!--          <th/> -->
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
            <td class='td_trans_row_index'>
               <?php echo $row_index++; ?>
               <input class='td_trans_checkbox' type='checkbox' name='td_trans_checkbox[]' <?php echo "value='" . $trans->trans_id . "'"; ?> />
            </td>

            <td class='td_trans_bank_alias'> <?php echo htmlspecialchars($trans->bank_alias, ENT_QUOTES); ?> </td>

            <td class='td_trans_date'> <?php echo substr($trans->trans_date, 0, 10); ?> </td>

            <td class='td_trans_amount'> <?php echo $trans->amount; ?> </td>

            <td class='td_trans_notes'> <?php echo htmlspecialchars($trans->notes, ENT_QUOTES); ?> </td>

<!--            <td class='td_edit_trans'>
               <?php
                  echo '<a href="/form/editTransaction.php?trans_id=' . $trans->trans_id. '">Edit</a>';
               ?>
            </td> -->
         </tr>
      <?php endforeach; ?>

      <tr>
          <td>
              <input id='input_delete_transactions_submit' type="submit" value="Delete">
          </td>
      </tr>
   </table>
</form>

</div>
