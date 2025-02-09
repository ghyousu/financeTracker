<div id='top_panel'>
   <ul id='ul_top_panel'>
       <li>
           <h1>Hi,
              <span id='user_name'>
                 <?php
                    $user_info = $_SESSION['sql_user_info'];

                    echo $user_info->fname . ' ' . $user_info->lname;
                 ?>
              </span>
           </h1>
       </li>

       <li>
           <a style='font-size: 1.5em' href="/logout.php">Log Out</a>
       </li>
   </ul>
</div>
