<?php 
/*************************************************************************** 
 *                              sql_install.php 
 *                            -------------------
 *   copyright            : (C) 2006 Zarath Technologies
 *   website		  : http://www.zarath.com/
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

$page_title = 'Bar Shop for Shop Mod v:3 Installation'; 
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

$sql[] = "INSERT INTO `phpbb_shops` (`shopname`, `shoptype`, `url`) VALUES('Bar', 'Alcohol', 'shop_bar.php')"; # This may be renamed.
$sql[] = "ALTER TABLE `phpbb_users` ADD user_drunk INT (10) DEFAULT '0'";

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
