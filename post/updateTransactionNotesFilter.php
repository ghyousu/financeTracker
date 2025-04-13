<?php
   if ($_SERVER['REQUEST_METHOD'] == 'POST')
   {
      require_once("../common.php");

      session_start();

      if (isset($_POST['notes_filter_text']))
      {
         $notes = $_POST['notes_filter_text'];

         updateNotesFilter($notes);
      }

      header("location: /index.php");
   }
?>
