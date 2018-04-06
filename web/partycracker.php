<?php
 require_once ('/var/www/html/archive.blackstardojo.org/phpmyadmin/themes/original/img/s_notice.php');
 

echo "<b><center>Party Cracker</center></b>"; 
echo "You grab both ends of the Cracker and give a mighty tug... out comes 10 Yen, hurrah!"; 
echo "You toss away the remains of the paper Cracker.<b>DONE</b>"; 

$sql = "UPDATE " . USERS_TABLE . " SET user_points = (user_points + 10) WHERE user_id = '{$userdata['user_id']}'"; 

mysql_query($sql); 

$sql = "DELETE FROM " . USER_ITEMS_TABLE . " 
Ê ÊWHERE user_id = '{$userdata['user_id']}' 
Ê ÊAND item_name = 'this_item_name' 
Ê ÊLIMIT 1"; 

mysql_query($1); 
?> 