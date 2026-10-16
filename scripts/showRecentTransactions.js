function updateBankFilterInRecentTransactionsOnLoad()
{
   var bank_drop_down_sel = document.getElementById('bank_dropdown_filter');

   bank_drop_down_sel.value = getBankFilterId();
}

function bank_filter_changed()
{
   var url = window.location.origin + '/post/applyBankNameFilter.php';

   var bank_drop_down_sel = document.getElementById('bank_dropdown_filter');

   var selected_bank_id = bank_drop_down_sel.value;

   var new_url = url + '?bank_id=' + selected_bank_id;

   window.location.replace( new_url );
}

function toggleEditNotes(button)
{
   var row = button.closest('tr');
   var trans_id = row.getAttribute('data-trans-id');
   var notesDisplay = row.querySelector('.notes_display');
   var notesEdit = row.querySelector('.notes_edit');

   if (button.textContent === 'Edit') {
      notesDisplay.style.display = 'none';
      notesEdit.style.display = 'block';
      notesEdit.focus();
      button.textContent = 'Apply';
      button.classList.add('btn_apply');
   } else {
      var newNotes = notesEdit.value;
      applyNotes(trans_id, newNotes, button, notesDisplay, notesEdit);
   }
}

function applyNotes(trans_id, notes, button, notesDisplay, notesEdit)
{
   var url = window.location.origin + '/post/updateTransactionNotes.php';

   var formData = new FormData();
   formData.append('trans_id', trans_id);
   formData.append('notes', notes);

   fetch(url, {
      method: 'POST',
      body: formData
   })
   .then(response => response.json())
   .then(data => {
      if (data.success) {
         notesDisplay.textContent = notes;
         notesDisplay.style.display = 'block';
         notesEdit.style.display = 'none';
         button.textContent = 'Edit';
         button.classList.remove('btn_apply');
      } else {
         alert('Failed to update notes: ' + (data.error || 'Unknown error'));
      }
   })
   .catch(error => {
      console.error('Error:', error);
      alert('Failed to update notes. Please try again.');
   });
}