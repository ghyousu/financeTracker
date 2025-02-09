<!DOCTYPE html>
<html>

<head>
   <link rel="stylesheet" href="style/login.css">

  <title>Login</title>
   <?php
      require_once("common.php");

      session_start();

      $thisScriptWeb = $_SERVER["SCRIPT_NAME"];
      $main_page     = '/index.php';

      if (isset($_SESSION['LOGGED_IN']))
      {
         header("location: $main_page");
      }
      else if (isset($_POST['username']) && isset($_POST['password']))
      {
         $username = trim(strtolower($_POST['username']));
         $password = trim(strtolower($_POST['password']));

         if (authenticateUser($username, $password))
         {
            $_SESSION['LOGGED_IN'] = true;

            header("location: $main_page");
         }
         else
         {
            header("location: $thisScriptWeb");
            $_SESSION['msg'] = 'Bad username/password. Try again.<br/>';
         }
      }
      else
      {
         if (isset($_SESSION['msg']))
         {
            echo $_SESSION['msg'] . '<br/>';
            unset($_SESSION['msg']);
         }
      }
   ?>
</head>

<body>
   <h2>Please Login</h2>
   <div>
      <form method="POST" action="<?php echo $thisScriptWeb ?>">
         <ul>
            <li>
               <label>Username:</label>
               <input type="text" name="username" >
            </li>

            <li>
               <label>Password:</label>
               <input type="password" name="password">
            </li>

            <li>
               <button id='login_btn' type="submit" class="btn" name="login_user">Login</button>
            </li>
         </ul>
      </form>
   </div>
</body>

</html>
