<?php

function printDebug($str, $debug = 0)
{
   if ($debug == 1)
   {
      echo "Debug: $str <br/>";
   }
}

function printInfo($str, $enabled = 0)
{
   if ($enabled == 1)
   {
      echo "Info: $str <br/>";
   }
}

function printError($str)
{
   echo "Error: $str <br/>";
}

function getCommonSchemaName() { return 'pbt'; }
function getUsersTableName()   { return getCommonSchemaName() . ".pbt_user"; }
function getBankTableName()    { return getCommonSchemaName() . "." . "bank"; }
function getTransTableName()   { return getCommonSchemaName() . "." . "transaction"; }

class SQLUserInfo
{
   public $user_id = -1;
   public $user_name = '';
   public $fname = '';
   public $lname = '';
   public $max_trans = 50;

   function toString()
   {
      return "SQLUserInfo: $this->user_id '$this->user_name' '$this->fname $this->lname' $this->max_trans";
   }
}

class SQLBank
{
   public $bank_db_id = -1;
   public $owner_name =  '';
   public $bank_name = '';
   public $bank_alias = '';
   public $min_balance = 0;
   public $bank_routing_num = '';
   public $bank_account_num = '';
   public $total_balance = 0;
   public $statement_date = 0;

   function toString()
   {
      return "SQLBank: $this->bank_db_id, '$this->owner_name', '$this->bank_name', '$this->bank_alias', $this->min_balance, $this->bank_routing_num, $this->bank_account_num, $this->statement_date, $this->total_balance";
   }
}

class SQLTransaction
{
   public $trans_id = 0;
   public $bank_db_id = 0;
   public $bank_alias = '';
   public $user_id = 0;
   public $amount = 0;
   public $notes = '';
   public $trans_date = '';

   function toString()
   {
      return "SQLTransaction: trans_id = $trans_id, bank_db_id = $bank_db_id, user_id = $user_id, amount = $amount, trans_date = $trans_date, notes = '$notes'";
   }
}


// return a working connection, caller is responsible to close
// connection when done
function getDBConnection()
{
   $db_url = getenv("DATABASE_URL");

   if ($db_url == "") { die("Unable to get database URL!"); }

   $conn = pg_connect($db_url);

   if ($conn)
   {
      return $conn;
   }
   else
   {
      die("Failed to connect to database<br/>");
   }
}

function fetchQueryResults($query)
{
   $conn = getDBConnection();

   printDebug("query: '$query'<br/>");

   $result = pg_query($conn, $query);

   if ($result == false)
   {
      die('Failed to execute query. query str: "' . $query . '". <br/>' .
          'Error str: "' . pg_last_error($conn) . '"');

      pg_close($conn);
   }

   pg_close($conn);
   return $result;
}

function authenticateUser($username, $pw)
{
   $query = 'SELECT user_id, user_name, fname, lname, max_trans ' .
            'FROM ' . getUsersTableName() .
            " WHERE user_name = '$username' AND pw = '" . sha1($pw) . "'";

   printDebug("query: '$query'");

   $result = fetchQueryResults($query);

   $row  = pg_fetch_row($result);
   $user_info = new SQLUserInfo();
   $user_info->user_id   = $row[0];
   $user_info->user_name = $row[1];
   $user_info->fname     = $row[2];
   $user_info->lname     = $row[3];
   $user_info->max_trans = $row[4];

   $_SESSION['sql_user_info'] = $user_info;

   return true;
}

function getUniqueBankOwnersArray()
{
   $query = 'SELECT DISTINCT(owner_name) FROM ' . getBankTableName();

   printDebug("query: '$query'");

   $owners_array = array();

   $result = fetchQueryResults($query);

   while ( $row = pg_fetch_row($result) )
   {
      array_push( $owners_array, $row[0] );
   }

   return $owners_array;
}

function getBanksPerOwner($owner)
{
   $query = 'SELECT bank_db_id, owner_name, bank_name, bank_alias, bank_account_num, total_balance ' .
            'FROM ' . getBankTableName() .
            " WHERE owner_name = '" . $owner . "' ORDER BY total_balance DESC";

   printDebug("query: '$query'");

   $bank_array = array();

   $result = fetchQueryResults($query);

   while ( $row = pg_fetch_row($result) )
   {
      $bank = new SQLBank();

      $bank->bank_db_id       = $row[0];
      $bank->owner_name       = $row[1];
      $bank->bank_name        = $row[2];
      $bank->bank_alias       = $row[3];
      $bank->bank_account_num = $row[4];
      $bank->total_balance    = $row[5];

      array_push( $bank_array, $bank);
   }

   return $bank_array;
}

function getAllBanksArray()
{
   $query = 'SELECT bank_db_id, owner_name, bank_name, bank_alias, bank_account_num ' .
            'FROM ' . getBankTableName() . ' ORDER BY display_order';

   printDebug("query: '$query'");

   $bank_array = array();

   $result = fetchQueryResults($query);

   while ( $row = pg_fetch_row($result) )
   {
      $bank = new SQLBank();

      $bank->bank_db_id       = $row[0];
      $bank->owner_name       = $row[1];
      $bank->bank_name        = $row[2];
      $bank->bank_alias       = $row[3];
      $bank->bank_account_num = $row[4];

      array_push( $bank_array, $bank);
   }

   return $bank_array;
}

function getTotalBalance()
{
   $query = 'SELECT sum(total_balance) FROM ' . getBankTableName();

   printDebug("query: '$query'");

   $grand_total = 0.0;

   $result = fetchQueryResults($query);

   while ( $row = pg_fetch_row($result) )
   {
      $grand_total = $row[0];
      break;
   }

   return $grand_total;
}

function getRecentTransctions($bank_db_id)
{
   // TODO: add WHERE clause for specific number of days
   $query = 'SELECT t.trans_id, t.bank_db_id, b.bank_alias, t.user_id, t.amount, t.notes, t.trans_date FROM ' .
            getTransTableName() .  ' t, ' .
            getBankTableName() . ' b ' .
            'WHERE t.bank_db_id = b.bank_db_id ' .
            'ORDER BY trans_date DESC LIMIT ' . $_SESSION['sql_user_info']->max_trans;

   if ($bank_db_id > 0)
   {
      $query = $query . ' WHERE bank_db_id = ' . $bank_db_id;
   }

   printDebug("query: '$query'");

   $recent_trans = array();

   $result = fetchQueryResults($query);

   while ( $row = pg_fetch_row($result) )
   {
      $trans = new SQLBank();

      $trans->trans_id         = $row[0];
      $trans->bank_db_id       = $row[1];
      $trans->bank_alias       = $row[2];
      $trans->user_id          = $row[3];
      $trans->amount           = $row[4];
      $trans->notes            = $row[5];
      $trans->trans_date       = $row[6];

      array_push( $recent_trans, $trans);
   }

   return $recent_trans;
}

function addTransactions($transactions)
{
   if (count($transactions) == 0)
   {
      return ;
   }

   $query = 'INSERT INTO ' . getTransTableName() . ' (user_id, bank_db_id, amount, trans_date, notes) VALUES ';

   foreach ($transactions as $trans)
   {
      $query = $query . "($trans->user_id, $trans->bank_db_id, $trans->amount, '$trans->trans_date', '$trans->notes'),";
   }

   // remove the last ","
   $query = substr($query, 0, -1);

   fetchQueryResults($query);
}

function deleteTransactions($trans_id_array)
{
   if (count($trans_id_array) == 0)
   {
      return ;
   }

   $query = 'DELETE FROM ' . getTransTableName() . ' WHERE trans_id IN (';

   foreach ($trans_id_array as $trans_id)
   {
      $query = $query . $trans_id . ',';
   }

   // remove the last "," and add a ")"
   $query = substr($query, 0, -1) . ')';

   fetchQueryResults($query);
}

function updateMaxTransactions($new_max_entries)
{
   $curr_user = $_SESSION['sql_user_info'];

   $query = 'UPDATE ' . getUsersTableName() . ' SET max_trans = ' . $new_max_entries .
            ' WHERE user_id = ' . $curr_user->user_id;

   fetchQueryResults($query) && $curr_user->max_trans = $new_max_entries;
}

?>
