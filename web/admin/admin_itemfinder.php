<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                           admin_itemfinder.php
 *                            -------------------
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   copyright (C) 2002-2007  RCTycooner
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

define('IN_PHPBB', 1);

if(	!empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['General']['Random Item or Money Finder'] = $file;
	return;
}

//
// Lets set the root dir for phpBB
//
$phpbb_root_path = '../';
require($phpbb_root_path . 'extension.inc');
require('pagestart.' . $phpEx);

//
//check for userlevel
//
if( !$userdata['session_logged_in'] )
{
	header('Location: ' . append_sid("login.$phpEx?redirect=admin/admin_itemfinder.$phpEx", true));
}

if( $userdata['user_level'] != ADMIN )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}
//end check




//retrieve the current configurations:
$cur_enabled = $board_config['itemfinder_enabled'];
$cur_odds_items = $board_config['itemfinder_odds_items'];
$cur_odds_money = $board_config['itemfinder_odds_money'];
$cur_items = $board_config['itemfinder_items'];
$cur_money_min = $board_config['itemfinder_money_min'];
$cur_money_max = $board_config['itemfinder_money_max'];


if(! isset($_REQUEST['Submit'] ) )
{
	$enabled_on = ($cur_enabled == 1) ? 'checked' : '';
	$enabled_off = ($cur_enabled != 1) ? 'checked' : '';
	
	$overall = '<form method="POST" action="'.append_sid("admin_itemfinder.$phpEx").'">
	<table width="100%" cellpadding="4" cellspacing="1" border="0">
	  	<tr>
			<th width="50%"  class="thHead" colspan="2"><center>'.$lang['admin_itemfinder_header'].'</center></th>
	  	</tr>
	  	<tr>
			<td width="75%" class="row1">'.$lang['admin_itemfinder_enabled'].'</td>
			<td width="25%" class="row2"><input type="radio" name="enabled" value="1" '.$enabled_on.'>On | Off<input type="radio" name="enabled" value="0" '.$enabled_off.'></td>
		</tr>
		<tr>
			<td width="75%" class="row1">'.$lang['admin_itemfinder_oddsItems'].'</td>
			<td width="25%" class="row2"><input class="post" type="text" name="odds_items" size="50" value="'.$cur_odds_items.'"></td>
		</tr>
		<tr>
			<td width="75%" class="row1">'.$lang['admin_itemfinder_items'].'</td>
			<td width="25%" class="row2"><input class="post" type="text" name="items" size="50" value="'.$cur_items.'"></td>
		</tr>
		<tr>
			<td width="75%" class="row1">'.$lang['admin_itemfinder_oddsMoney'].'</td>
			<td width="25%" class="row2"><input class="post" type="text" name="odds_money" size="50" value="'.$cur_odds_money.'"></td>
		</tr>
		<tr>
			<td width="75%" class="row1">'.$lang['admin_itemfinder_money_min'].'</td>
			<td width="25%" class="row2"><input class="post" type="text" name="money_min" size="50" value="'.$cur_money_min.'"></td>
		</tr>
		<tr>
			<td width="75%" class="row1">'.$lang['admin_itemfinder_money_max'].'</td>
			<td width="25%" class="row2"><input class="post" type="text" name="money_max" size="50" value="'.$cur_money_max.'"></td>
		</tr>
	  	<tr>
			<td width="50%"  class="catBottom" colspan="2"><center><input type="submit" value="'.$lang['admin_submit'].'" name="Submit" size="20" class="liteoption"></form></center></td>
	  	</tr>
	</table>
	';
}
if( isset($_REQUEST['Submit']) )
{
	if( $_REQUEST['enabled'] != $cur_enabled ) 
	{
		$sql = "update " . CONFIG_TABLE . " set config_value='{$_REQUEST['enabled']}' where config_name='itemfinder_enabled'";
		if ( !($db->sql_query($sql)) ) { message_die(GENERAL_ERROR, 'Fatal Error Updating Item/Money Finder Configuration!', '', __LINE__, __FILE__ ,$sql); }
	}
	if( $_REQUEST['odds_items'] != $cur_odds_items ) 
	{
		$sql = "update " . CONFIG_TABLE . " set config_value='{$_REQUEST['odds_items']}' where config_name='itemfinder_odds_items'";
		if ( !($db->sql_query($sql)) ) { message_die(GENERAL_ERROR, 'Fatal Error Updating Item/Money Finder Configuration!', '', __LINE__, __FILE__ ,$sql); }
	}
	if( $_REQUEST['items'] != $items ) 
	{
		$sql = "update " . CONFIG_TABLE . " set config_value='{$_REQUEST['items']}' where config_name='itemfinder_items'";
		if ( !($db->sql_query($sql)) ) { message_die(GENERAL_ERROR, 'Fatal Error Updating Item/Money Finder Configuration!', '', __LINE__, __FILE__ ,$sql); }
	}
	if( $_REQUEST['odds_money'] != $cur_odds_money ) 
	{
		$sql = "update " . CONFIG_TABLE . " set config_value='{$_REQUEST['odds_money']}' where config_name='itemfinder_odds_money'";
		if ( !($db->sql_query($sql)) ) { message_die(GENERAL_ERROR, 'Fatal Error Updating Item/Money Finder Configuration!', '', __LINE__, __FILE__ ,$sql); }
	}
	if( $_REQUEST['money_min'] != $cur_money_min ) 
	{
		$sql = "update " . CONFIG_TABLE . " set config_value='{$_REQUEST['money_min']}' where config_name='itemfinder_money_min'";
		if ( !($db->sql_query($sql)) ) { message_die(GENERAL_ERROR, 'Fatal Error Updating Item/Money Finder Configuration!', '', __LINE__, __FILE__ ,$sql); }
	}
	if( $_REQUEST['money_max'] != $cur_money_max ) 
	{
		$sql = "update " . CONFIG_TABLE . " set config_value='{$_REQUEST['money_max']}' where config_name='itemfinder_money_max'";
		if ( !($db->sql_query($sql)) ) { message_die(GENERAL_ERROR, 'Fatal Error Updating Item/Money Finder Configuration!', '', __LINE__, __FILE__ ,$sql); }
	}
		
	$overall = '
	<table width="100%" cellpadding="4" cellspacing="1" border="0">
	  <tr>
		<th width="50%"  class="thHead" colspan="2"><center>'.$lang['admin_itemfinder_header'].'</center></th>
	  </tr>
	  <tr>
		<td class="row1" width="100%">'.$lang['admin_itemfinder_updated'].'. | <b><a href="'.append_sid("admin_itemfinder.$phpEx").'">'.$lang['admin_return'].'</a></b></td>
	  </tr>
	</table>';

}




// END SLOT MACHINE ADMIN

$template->assign_vars(array(
	'OVERALL' => "$overall",
	'TTL' => $lang['admin_itemfinder_header'],
	'DESC' => $lang['admin_itemfinder_desc'],
));
$template->set_filenames(array(
	'body' => 'admin/itemfinder_config_body.tpl')
);
//
// Generate the page
//
$template->pparse('body');

include('page_footer_admin.' . $phpEx);


?>
