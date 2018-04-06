<?php
define('IN_PHPBB', true);
$phpbb_root_path='./';
include($phpbb_root_path.'extension.inc');
include($phpbb_root_path.'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

if( !$userdata['session_logged_in'] )
{
	header('Location: ' . append_sid("login.$phpEx?redirect=itemfinder_db_update.$phpEx", true));
}

if( $userdata['user_level'] != ADMIN )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}
$sql = array();

// Version 1.0.0
$sql[] = "INSERT INTO `".CONFIG_TABLE."` ( `config_name`, `config_value` ) VALUES ( 'itemfinder_enabled', '1' )";
$sql[] = "INSERT INTO `".CONFIG_TABLE."` ( `config_name`, `config_value` ) VALUES ( 'itemfinder_odds_items', '0' )";
$sql[] = "INSERT INTO `".CONFIG_TABLE."` ( `config_name`, `config_value` ) VALUES ( 'itemfinder_odds_money', '0' )";
$sql[] = "INSERT INTO `".CONFIG_TABLE."` ( `config_name`, `config_value` ) VALUES ( 'itemfinder_items', '' )";
$sql[] = "INSERT INTO `".CONFIG_TABLE."` ( `config_name`, `config_value` ) VALUES ( 'itemfinder_money_min', '0' )";
$sql[] = "INSERT INTO `".CONFIG_TABLE."` ( `config_name`, `config_value` ) VALUES ( 'itemfinder_money_max', '0' )";

$sql_count = count($sql);

echo "<html>\n";
echo "<body>\n";

for($i = 0; $i < $sql_count; $i++)
{
	echo "Running :: " . $sql[$i];
	flush();

	if ( !$db->sql_query($sql[$i]) )
	{
		$errored = true;
		$error = $db->sql_error();
		echo " -> <b>FAILED</b> ---> <u>" . $error['message'] . "</u><br /><br />\n\n";
	}
	else
	{
		echo " -> <b>COMPLETED</b><br /><br />\n\n";
	}
}
echo "\n<br />\n<b>Finished!</b><br />\n";
echo $message . "<br />\n";
echo "</body>\n";
echo "</html>\n";
exit();

?>
