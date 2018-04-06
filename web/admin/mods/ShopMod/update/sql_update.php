<?php 
/*************************************************************************** 
 *                              sql_update.php 
 *                            -------------------
 *   begin                : Wednesday, Sep 6th, 2006 [GMT+10]
 *   copyright            : (C) 2006 Zarath Technologies
 *   email                : N/A
 *   website		  : http://www.zarath.com
 *
 *   $Id: sql_update.php,v 3.0.0 2005/01/04 19:00:00 Zarath $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', true); 
$phpbb_root_path = './'; 

include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 

$i = 0;
while ( !file_exists($phpbb_root_path . 'extension.inc') && ($i++ < 4) )
{
	$phpbb_root_path .= '../';
}
if ( $i > 4 )
{
	message_die(GENERAL_MESSAGE, 'Unable to find extension.inc, terminating. Please move this file into your main/"root" phpbb directory and try again.'); 
}


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
	redirect(append_sid("login.$phpEx?redirect=sql_install.$phpEx", true));
} 

if( $userdata['user_level'] != ADMIN ) 
{ 
	message_die(GENERAL_MESSAGE, 'You are not authorised to access this page'); 
} 

$page_title = 'Shop Mod Update v:3.0.0'; 
include($phpbb_root_path . 'includes/page_header.'.$phpEx); 

echo '
<table width="100%" cellspacing="1" cellpadding="2" border="0" class="forumline">
	<tr>
	  <th class="thHead">Installing Shop Mod Database Updates</th>
	</tr>
	<tr>
	  <td>
		<span class="genmed">
		<ul type="square">';

$current_time = time();

$sql = array();

$sql[] = "ALTER TABLE `" . $table_prefix . "shopitems` ADD `special_link` varchar(255) NOT NULL default ''";
$sql[] = "ALTER TABLE `" . $table_prefix . "shopitems` ADD `synth` text NOT NULL";

$sql[] = "ALTER TABLE `" . $table_prefix . "shops` DROP `amountearnt`";
$sql[] = "ALTER TABLE `" . $table_prefix . "shops` ADD `d_type` tinyint(3) NOT NULL default '0'";
$sql[] = "ALTER TABLE `" . $table_prefix . "shops` ADD `district` tinyint(3) NOT NULL default '0'";
$sql[] = "ALTER TABLE `" . $table_prefix . "shops` ADD `url` varchar(255) default NULL";
$sql[] = "ALTER TABLE `" . $table_prefix . "shops` ADD `shop_owner` varchar(32) NOT NULL default ''";
$sql[] = "ALTER TABLE `" . $table_prefix . "shops` ADD `template` varchar(32) NOT NULL default ''";
$sql[] = "ALTER TABLE `" . $table_prefix . "shops` ADD `item_template` varchar(32) NOT NULL default ''";

$sql[] = "CREATE TABLE `" . $table_prefix . "transactions` (
  `id` int(10) NOT NULL auto_increment,
  `user_id` int(5) NOT NULL default '0',
  `target_id` int(5) NOT NULL default '0',
  `target_name` varchar(32) NOT NULL default '',
  `type` varchar(32) NOT NULL default '',
  `action` varchar(32) NOT NULL default '',
  `value` varchar(255) NOT NULL default '',
  `misc` text NOT NULL,
  `ip` varchar(16) NOT NULL default '',
  `timestamp` int(32) default NULL,
  UNIQUE KEY `id` (`id`)
)";

$sql[] = "CREATE TABLE `" . $table_prefix . "user_items` (
  `id` int(20) NOT NULL auto_increment,
  `user_id` int(20) NOT NULL default '0',
  `item_id` int(20) NOT NULL default '0',
  `item_name` varchar(32) NOT NULL default '',
  `item_l_desc` text NOT NULL,
  `item_s_desc` varchar(100) NOT NULL default '',
  `worn` tinyint(1) NOT NULL default '0',
  `die_time` int(20) NOT NULL default '0',
  `special` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `item_id` (`item_id`),
  KEY `user_id` (`user_id`)
)";

$sql[] = "DELETE FROM " . CONFIG_TABLE . " WHERE config_value = 'specialshop'";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('shop_discard', 'on')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('shop_owners', 'on')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('shop_districts', 'off')";


$sql[] = "ALTER TABLE " . USERS_TABLE . " DROP `user_effects`";
$sql[] = "ALTER TABLE " . USERS_TABLE . " DROP `user_privs`";
$sql[] = "ALTER TABLE " . USERS_TABLE . " DROP `user_custitle`";
$sql[] = "ALTER TABLE " . USERS_TABLE . " DROP `user_specmsg`";


if ( empty($board_config['points_name']) )
{
   $sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('points_name', 'Gold')";
}


foreach ( $sql AS $query ) 
{
	if ( !($result = $db->sql_query($query)) )
	{ 
		$error = $db->sql_error();
		echo '<li>' . nl2br($query) . '<br /> !!! <font color="#FF0000"><b>Error:</b></font> ' . $error['message'] . '</li><br />';
		$errored = TRUE;
	} 
	else 
	{ 
		echo '<li>' . nl2br($query) . '<br /> +++ <font color="#00AA00"><b>Successfull</b></font></li><br />';
	} 
}

$forum_url = append_sid($phpbb_root_path . "index.$phpEx");
$phpbb_url = "http://forums.knightsofchaos.com";
$my_url = "http://www.zarath.com";


#
# Script to update previous user items to the new system...
#
$sql = "SELECT user_id, username, user_items
	FROM " . USERS_TABLE . "
	WHERE user_items != ''";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error checking employed users!', '', __LINE__, __FILE__, $sql);
}

$sql_count = $db->sql_numrows($result);

for ( $i = 0; $i < $sql_count; $i++ )
{
	if (!( $row = $db->sql_fetchrow($result) ))
	{
		message_die(GENERAL_ERROR, "Error checking employed users!", '', __LINE__, __FILE__, $sql);
	}

	$itempurge = str_replace("Þ", "", $row['user_items']);
	$itemarray = explode('ß',$itempurge);
	$itemcount = count($itemarray);

	for ( $ii = 0; $ii < $itemcount; $ii++ )
	{
		$sql = "SELECT *
			FROM " . SHOP_ITEMS_TABLE . "
			WHERE name = '" . addslashes($itemarray[$ii]) . "'";

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
	echo '<li>' . nl2br($query) . '<br /> +++ <font color="#00AA00"><b>Successfully Updated :: </b> ' . $row['username'] . '\'s items...</li><br />';

	$sql = "UPDATE " . USERS_TABLE . "
		SET user_items = ''
		WHERE user_id = '{$row['user_id']}'";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error clearing user\'s items!', '', __LINE__, __FILE__, $sql);
	}
}

echo '
		</ul>
		</span>
	  </td>
	</tr>
	<tr>
	  <td class="catBottom" colspan="2" align="center"><span class="gen">Completed ' . ( ( $errored ) ? 'with' : 'without' ) . ' errors!</span></td>
	</tr>
</table>

<br />
<br />

<table width="100%" cellspacing="1" cellpadding="2" border="0" class="forumline">
  <tr>
    <th class="thHead">SQL Installation complete</th>
  </tr>
  <tr>
    <td class="row1">
      <span class="genmed">Please delete this file (sql_install.' . $phpEx . ').<br />
      If you have any problems, please visit <a href="' . $phpbb_url . '" target="_new">http://forums.knightsofchaos.com</a> and ask for help.</span>
    </td>
  </tr>
  <tr>
    <td class="row2" height="28" align="center">
      <span class="genmed"><a href="' . $my_url . '">Click here to visit my mod site.</a>
      </span>
    </td>
  </tr>

  <tr>
    <td class="catBottom" height="28" align="center">
      <span class="genmed"><a href="' . $forum_url . '">Click Here to return to your forum.</a>
      </span>
    </td>
  </tr>
</table>';

include($phpbb_root_path . 'includes/page_tail.'.$phpEx); 

?>
