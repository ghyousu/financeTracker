function bank_filter_changed()
{
   // debugger;
   var url = window.location.origin + '/post/applyBankNameFilter.php';

   var bank_drop_down_sel = document.getElementById('bank_dropdown_filter');

   var selected_bank_id = bank_drop_down_sel.value;

   var new_url = url + '?bank_id=' + selected_bank_id;

   window.location.replace( new_url );
}
