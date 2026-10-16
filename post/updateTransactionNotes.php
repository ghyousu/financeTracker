<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
   require_once("../common.php");

   session_start();

   header('Content-Type: application/json');

   $trans_id = isset($_POST['trans_id']) ? (int)$_POST['trans_id'] : 0;
   $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

   if ($trans_id <= 0) {
       echo json_encode(['success' => false, 'error' => 'Invalid transaction ID']);
       exit;
   }

   $curr_user = $_SESSION['sql_user_info'];
   $query = 'UPDATE ' . getTransTableName() . " SET notes = '" . pg_escape_string($notes) . "'" .
            ' WHERE trans_id = ' . $trans_id . ' AND user_id = ' . $curr_user->user_id;

   printDebug("query: '$query'");

   $result = fetchQueryResults($query);

   if ($result) {
       echo json_encode(['success' => true]);
   } else {
       echo json_encode(['success' => false, 'error' => 'Failed to update notes']);
   }
}