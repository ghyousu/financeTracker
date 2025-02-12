// this function updates currency formatting to include a dollar sign and separator at the thousands place
function updateCurrencyFormatting(html_class)
{
   // Create our number formatter. (copied from stackoverflow)
   const formatter = new Intl.NumberFormat('en-US', {
     style: 'currency',
     currency: 'USD',

     // These options can be used to round to whole numbers.
     trailingZeroDisplay: 'stripIfInteger'   // This is probably what most people
                                             // want. It will only stop printing
                                             // the fraction when the input
                                             // amount is a round number (int)
                                             // already. If that's not what you
                                             // need, have a look at the options
                                             // below.
     //minimumFractionDigits: 0, // This suffices for whole numbers, but will
                                 // print 2500.10 as $2,500.1
     //maximumFractionDigits: 0, // Causes 2500.99 to be printed as $2,501
   });

   var objs = document.getElementsByClassName(html_class);

   for (let i=0; i < objs.length; i++)
   {
      var obj_val = objs[i].innerText;

      objs[i].innerText = formatter.format(obj_val);
   }

}

function updateDatePickerValue(html_class)
{
   var objs = document.getElementsByClassName(html_class);

   for (let i=0; i < objs.length; i++)
   {
      objs[i].valueAsDate = new Date();
   }
}

function on_page_loaded()
{
   // this is at the top summary window
   updateCurrencyFormatting('td_balance');
   updateCurrencyFormatting('total_balance');

   updateDatePickerValue('input_deposit_date_picker');

   // this is in the transaction history
   updateCurrencyFormatting('td_trans_amount');
}

