<div id='transaction_history_div'>

<ul>

<?php foreach ($banks as $bank): ?>
<li>
      <b>
         <?php echo $bank->bank_alias;  ?>
      </b>
      <?php echo '(*' . substr($bank->bank_account_num, -4) . ')';  ?>

      <table class='trans_table' border=1>
          <?php $recent_trans = getRecentTransctions($bank->bank_db_id); ?>

         <?php foreach ($recent_trans as $trans): ?>  <!-- inner loop of transactions -->
            <tr class='trans_table_tr'>
               <td class='trans_date_td'>   <?php echo substr($trans->trans_date, 0, 10); ?> </td>
               <td class='trans_amount_td'> <?php echo $trans->amount; ?>     </td>
               <td class='trans_notes_td'>  <?php echo $trans->notes; ?>      </td>
            </tr>
         <?php endforeach; ?>  <!-- inner loop of transactions -->
      </table>
</li>
<?php endforeach; ?>

</ul>

</div>
