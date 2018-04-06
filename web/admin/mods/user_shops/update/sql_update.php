<?php
/***************************************************************************
 *                              mod_install.php
 *                            -------------------
 *   Version              : 1.0.0
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   copyright (C) 2002/2003  IcE-RaiN/Zarath
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
DEFINE('USER_SHOPS_TABLE', $table_prefix . 'user_shops');
DEFINE('USER_SHOP_ITEMS_TABLE', $table_prefix . 'user_shops_items');

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
	header('Location: ' . append_sid("login.$phpEx?redirect=shop_install.$phpEx", true));
}

if( $userdata['user_level'] != ADMIN )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}

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

$sql = "ALTER TABLE `" . $table_prefix . "user_shop_items` ADD `real_id` INT( 10 ) NOT NULL";

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

#
# This is the bulk of the update script, to remove all items from shops -- no need to close and delete them, just remove items!
#
$sql = "SELECT *
	FROM " . TABLE_USER_SHOPS;
if ( !($shop_result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
}

$sql_num_rows = $db->sql_numrows($shop_result);

for ( $iz = 0; $iz < $sql_num_rows; $iz++ )
{
	$shop_row = $db->sql_fetchrow($shop_result);

	$sql = "SELECT *
		FROM " . USER_SHOP_ITEMS_TABLE . "
		WHERE shop_id = '{$shop_row['id']}'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	for ( $i = 0; $i < $sql_num_rows; $i++ )
	{
		if ( !($row = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
		}

		if ( !defined('SHOP_TABLE') )
		{
			$append_items .= 'ß' . $row['name'] . 'Þ';
		}
		else
		{
			$sql = "SELECT *
				FROM " . SHOP_ITEMS_TABLE . "
				WHERE name = '" . addslashes($row['name']) . "'";

			if ( !($result2 = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Error checking user information!', '', __LINE__, __FILE__, $sql);
			}

			$item_data = $db->sql_fetchrow($result2);

			$sql = "INSERT INTO " . USER_ITEMS_TABLE . "
				(user_id, item_id, item_name, item_l_desc, item_s_desc)
				VALUES ('{$row['user_id']}', '{$item_data['id']}', '" . addslashes($item_data['name']) . "', '" . addslashes($item_data['ldesc']) . "', '" . addslashes($item_data['sdesc']) . "')";

			if ( !empty($item_data['name']) )
			{
				if ( !($db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Error checking user information!', '', __LINE__, __FILE__, $sql);
				}

			}
		}
	}

	$sql = "SELECT *
		FROM " . USERS_TABLE . "
		WHERE user_id = '{$row['user_id']}'"; 
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
	}

	if ( $db->sql_numrows($result) )
	{
		if ( !($row = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
		}

		if ( !defined('SHOP_TABLE') )
		{
			$new_items = addslashes($row['user_items'] . $append_items);

			$sql = "UPDATE " . USERS_TABLE . "
				SET user_items = '$new_items'
				WHERE user_id = '{$row['user_id']}'";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
			}
		}
	}
	echo '<br />All items have successfully been returned to ' . $row['username'] . '.';


	# Delete Shop Items
	$sql = "DELETE
		FROM " . USER_SHOP_ITEMS_TABLE . "
		WHERE shop_id = '$id'";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_deleting'], 'users shops'), '', __LINE__, __FILE__, $sql);
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