<div id='div_main_summary'>

<ul id='ul_main_summary'>
   <?php
      require_once("common.php");
      $bank_owners = getUniqueBankOwnersArray();
   ?>

   <?php foreach ($bank_owners as $owner): ?>
      <li>
         <?php $banks = getBanksPerOwner($owner); ?>

         <b>
            <?php echo $owner;  ?>
         </b>

         <table class='table_bank_summary' border=1>
            <?php foreach ($banks as $bank): ?>
              <?php if ($bank->is_joint_account) : ?>
                <tr class='tr_is_joint_account'>
              <?php else : ?>
                <tr class='tr_not_joint_account'>
              <?php endif; ?>

                  <td><?php echo $bank->bank_alias; ?> </td>
                  <td class='td_balance'><?php echo $bank->total_balance; ?>     </td>
<!--                  <td class='td_link'>
                      <?php
                         echo "<a href='/forms/showTransactions.php?bank_id=$bank->bank_db_id'>";
                         echo 'Details';
                         echo '</a>';
                      ?>
                  </td> -->
               </tr>
            <?php endforeach; ?>
         </table>
      </li>
   <?php endforeach; ?>
</ul>

<ul id='ul_total'>
   <li>
      <b>Grand Total:</b>
   </li>
   <li>
      <span class='total_balance'>
         <?php echo getTotalBalance(); ?>
      </span>
   </li>
</ul>

</div>
