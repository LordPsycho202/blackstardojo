<?php
/***************************************************************************
 *                             partycracker.php
 *                            ------------------------
 *   Version              : 0.0.4
 *   forums               : http://www.blackstardojo.org
 *   
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   copyright (C) 2007  Blackstar RP Dojo
 *
 *   This program is free software; you can redistribute it and/or
 *   modify it under the terms of the GNU General Public License
 *   as published by the Free Software Foundation; either version 2
 *   of the License, or (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   http://www.gnu.org/copyleft/gpl.html
 *
 ***************************************************************************/

define('IN_PHPBB', true); 
$phpbb_root_path = './'; 
//Set output variable
$Yen = 250;
$item_name='Bronze Cracker';
$i = 0;
while ( !file_exists($phpbb_root_path . 'extension.inc') && ($i++ < 4) )
{
	$phpbb_root_path .= '../';
}
if ( $i > 4 )
{
	message_die(CRITICAL_ERROR, 'Unable to find extension.inc, terminating. Please move this file into your main/"root" phpbb directory and try again.'); 
}

include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

$gen_simple_header = TRUE;

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//
//
// Start page Variables
//
if (!(@include($phpbb_root_path . 'language/lang_' . $userdata['user_lang'] . '/lang_shop.' . $phpEx))) { include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_shop.' . $phpEx); }

//
// Start output of page
//
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

//
// Generate the page
//

echo "<b><center>Party Cracker</center></b>"; 
echo "You grab both ends of the Cracker and give a mighty tug... out comes ".$Yen." Yen, hurrah!"; 
echo "You toss away the remains of the paper Cracker."; 

$sql = "UPDATE " . USERS_TABLE . " SET user_points = (user_points + ".$Yen.") WHERE user_id = '{$userdata['user_id']}'"; 

mysql_query($sql); 

$sql = "SELECT *
	FROM " . USER_ITEMS_TABLE . "
	WHERE user_id = {$userdata['user_id']}
		AND item_name = '" . str_replace("'", "''", $item_name) . "'";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Error getting Cracker info!", '', __LINE__, __FILE__, $sql);
}
if ( !($db->sql_numrows($result)) )
{
	message_die(GENERAL_MESSAGE, "It appears you don't have one of these");
}
else
{
	$row = $db->sql_fetchrow($result);
}


$sql = "DELETE FROM " . USER_ITEMS_TABLE . " 
WHERE user_id = '".$userdata['user_id']."' 
AND id = '".$row['id']."' 
LIMIT 1"; 

mysql_query($sql); 


?> 
