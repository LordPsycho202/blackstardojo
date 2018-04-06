<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                             admin_bank.php
 *                            -------------------
 *   Version              : 1.5.0
 *   began                : Tuesday, December 17th, 2002
 *   released             : Wednesday, December 18th, 2002
 *   last updated         : Monday, June 30th, 2003
 *   email                : zarath@knightsofchaos.com
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

define('IN_PHPBB', 1);

if(	!empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Users']['Bank_Configuration'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = '../';
require($phpbb_root_path . 'extension.inc');
require('pagestart.' . $phpEx);
include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_bank.' . $phpEx);

//
//check for userlevel
//
if( !$userdata['session_logged_in'] )
{
	header('Location: ' . append_sid("login.$phpEx?redirect=admin/admin_bank.$phpEx", true));
}

if( $userdata['user_level'] != ADMIN )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}
//end check

//bank pages

$template->set_filenames(array(
	'body' => 'admin/bank_config_body.tpl')
);

if (empty($_REQUEST['action']))
{
	$sql = "select * from phpbb_bank";
	if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error Getting Total Users!'); }
	$bankusers = mysql_num_rows($result);
	$bankholdings = 0;
	for ($x = 0;$x < mysql_num_rows($result);$x++) {
		$bhrow = mysql_fetch_array($result);
		$bankholdings = $bankholdings + $bhrow['holding'];
	}
	if ($board_config['bankopened'] != off) { $bankstatus1 = "on"; $bankstatus2 = "off"; } else { $bankstatus1 = "off"; $bankstatus2 = "on"; }
	$banksettings = '<form method="post" action="'.append_sid("admin_bank.$phpEx").'"><input type="hidden" name="action" value="updatebank"><tr><td class="row2"><span class="gensmall">'.$lang['bank_name'].' </span></td><td class="row2"><input type="text" class="post" name="name" size="32" value="'.$board_config['bankname'].'" maxlength="32"></td></tr><tr><td class="row2"><span class="gensmall">'.$lang['interest_rate'].' </span></td><td class="row2"><input type="text" class="post" name="interestrate" size="5" value="'.$board_config['bankinterest'].'" maxlength="3">%</td></tr><tr><td class="row2"><span class="gensmall">'.$lang['withdraw_rate'].' </span></td><td class="row2"><input type="text" class="post" name="withdrawfee" size="5" value="'.$board_config['bankfees'].'" maxlength="3">%</td></tr><tr><td class="row2"><span class="gensmall">'.$lang['interest_pay_time'].'</span></td><td class="row2"><input type="text" class="post" name="paymenttime" value="'.$board_config['bankpayouttime'].'" size="20" maxlength="20"> '.$lang['seconds'].'</td></tr>
	<tr><td class="row2"><span class="gensmall">'.$lang['bank_status'].' </span></td><td class="row2"><span class="gen"><select name="status"><option value="'.$bankstatus1.'">'.ucfirst($bankstatus1).'</option><option value="'.$bankstatus2.'">'.ucfirst($bankstatus2).'</option></select></span></td></tr><tr><td class="row2"><span class="gensmall">'.$lang['holding'].' </span></td><td class="row2"><span class="gen">'.$bankholdings.'</span></td></tr><tr><td class="row2"><span class="gensmall">'.$lang['total_withdrawn'].' </span></td><td class="row2"><span class="gen">'.$board_config['banktotalwithdrew'].'</span></td></tr><tr><td class="row2"><span class="gensmall">'.$lang['total_deposited'].' </span></td><td class="row2"><span class="gen">'.$board_config['banktotaldeposits'].'</span></td></tr><tr><td class="row2"><span class="gensmall">'.$lang['total_accounts'].' </span></td><td class="row2"><span class="gen">'.$bankusers.'</span></td></tr><tr><td class="row2" colspan="2" align="center">
	<input type="submit" class="liteoption" value="'.$lang['button_update'].'" name="Update"></td></tr><tr><td colspan="2" class="row2"><br></td></tr></form><form method="post" action="'.append_sid("admin_bank.$phpEx").'" name="post"><input type="hidden" name="action" value="editaccount"><tr><td class="row2" colspan="2" align="center"><b><span class="gensmall">Edit User\'s Account</span></b></td></tr><tr><td class="row2"><input type="text" class="post" class="post" name="username" maxlength="25" size="25"> <input type="submit" value="'.$lang['button_edit_account'].'"></td><td class="row2"><input type="submit" name="usersubmit" value="'.$lang['button_find'].'" class="liteoption" onClick="window.open(\'./../search.php?mode=searchuser\', \'_phpbbsearch\', \'HEIGHT=250,resizable=yes,WIDTH=400\');return false;" /></td></tr></form>';
	
	$template->assign_vars(array(
		'BANKCONFIGINFO' => "$banksettings",
		'BANKTABLETITLE' => $lang['bank_modify'],
		'S_CONFIG_ACTION' => append_sid('admin_bank.' . $phpEx),
		'BANKTITLE' => $lang['bank_settings'],
		'BANKEXPLAIN' => $lang['bank_settings_explain'])
	);
	
}
elseif ($_REQUEST['action'] == "updatebank")
{
	$banktime = time();
	$usql = array();
	$name = addslashes($name);
	if ($_REQUEST['name'] != $board_config['bankname']) { $usql[] = "update ". CONFIG_TABLE . " set config_value='{$_REQUEST['name']}' where config_name='bankname'"; }
	if ($_REQUEST['interestrate'] != $board_config['bankinterest']) { $usql[] = "update ". CONFIG_TABLE . " set config_value='{$_REQUEST['interestrate']}' where config_name='bankinterest'"; }
	if ($_REQUEST['withdrawfees'] != $board_config['bankfees']) { $usql[] = "update ". CONFIG_TABLE . " set config_value='{$_REQUEST['withdrawfee']}' where config_name='bankfees'"; }
	if ($_REQUEST['paymenttime'] != $board_config['bankpayouttime']) { $usql[] = "update ". CONFIG_TABLE . " set config_value='{$_REQUEST['paymenttime']}' where config_name='bankpayouttime'"; }
	if ((strtolower($_REQUEST['status']) == off) && ($board_config['bankopened'] != off)) { $usql[] = "update ". CONFIG_TABLE . " set config_value='off' where config_name='bankopened'"; }
	if ((strtolower($_REQUEST['status']) == on) && ($board_config['bankopened'] == off)) { $usql[] = "update ". CONFIG_TABLE . " set config_value='$banktime' where config_name='bankopened'"; }
	$sql_count = count($usql);
	for($i = 0; $i < $sql_count; $i++) 
	{ 
		if ( !($db->sql_query($usql[$i])) ) { message_die(GENERAL_MESSAGE, 'Fatal Error Updating Bank Information!'); } 
	}
	message_die(GENERAL_MESSAGE, $lang['bank_updated'].'!<br /><br />'.sprintf($lang['click_return_bank_admin'], '<a href="' . append_sid("admin_bank.$phpEx") . '">', '</a>').'<br /><br />'.sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") .'">', '</a>').'<br /><br />');
}
elseif ($_REQUEST['action'] == "editaccount")
{
	//check username & get account information
	$sql = "select user_id from " . USERS_TABLE . " where username='{$_REQUEST['username']}'";
	if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error Getting User Item!'); }
	$row = mysql_fetch_array($result);
	$sql = "select * from phpbb_bank where name='{$row['user_id']}'";
	if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error Getting User Item!'); }
	$row = mysql_fetch_array($result);

	if ($row['fees'] == "on") { $otherfees = "off"; }
	else { $otherfees = "on"; }

	$banksettings = '
<form method="post" action="'.append_sid("admin_bank.$phpEx").'">
<input type="hidden" name="action" value="updateaccount">
<input type="hidden" name="id" value="'.$row['name'].'">
<tr>
<td class="row2">'.$lang['bank_balance'].'</td>
<td class="row2"><input type="text" class="post" name="holding" size="10" maxlength="10" value="'.$row['holding'].'" /></td>
</tr>
<tr>
<td class="row2">'.$lang['total_deposited'].'</td>
<td class="row2"><input type="text" class="post" name="withdrawn" size="10" maxlength="10" value="'.$row['totalwithdrew'].'" /></td>
</tr>
<tr>
<td class="row2">'.$lang['total_withdrawn'].'</td>
<td class="row2"><input type="text" class="post" name="deposited" size="10" maxlength="10" value="'.$row['totaldeposit'].'" /></td>
</tr>
<tr>
<td class="row2">'.$lang['withdraw_fees'].'</td>
<td class="row2"><select name="fees"><option value="'.$row['fees'].'">'.ucfirst($row['fees']).'</option><option value="'.$otherfees.'">'.ucfirst($otherfees).'</option></select></td>
</tr>
<tr>
<td class="row2" align="center"><input class="liteoption" type="submit" name="update" value="'.$lang['button_update'].'" /></td>
<td class="row2" align="center"><input class="liteoption" type="submit" name="close" value="'.$lang['button_close'].'" /></td>
</tr>';

	$template->assign_vars(array(
		'BANKCONFIGINFO' => "$banksettings",
		'BANKTABLETITLE' => $lang['bank_modify'],
		'S_CONFIG_ACTION' => append_sid('admin_bank.' . $phpEx),
		'BANKTITLE' => $lang['bank_settings'],
		'BANKEXPLAIN' => $lang['bank_settings_explain'])
	);
}
elseif ($_REQUEST['action'] == "updateaccount")
{
	$sql = "select * from phpbb_bank where name='{$_REQUEST['id']}'";
	if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error: '.mysql_error()); }
	$row = mysql_fetch_array($result);


	if (!is_numeric($_REQUEST['holding']) || $_REQUEST['holding'] < 1) { $request = $row['holding']; }
	else { $holding = $_REQUEST['holding']; }
	if (!is_numeric($_REQUEST['withdrawn']) || $_REQUEST['withdrawn'] < 1) { $totalwithdrew = $row['totalwithdrew']; }
	else { $totalwithdrew = $_REQUEST['withdrawn']; }
	if (!is_numeric($_REQUEST['deposited']) || $_REQUEST['deposited'] < 1) { $totaldeposited = $row['totaldeposited']; }
	else { $totaldeposited = $_REQUEST['deposited']; }
	if ($_REQUEST['fees'] != "on" && $_REQUEST['fees'] != "off") { $request = $row['fees']; }
	else { $request = $_REQUEST['fees']; }

	$sql = "update `phpbb_bank` set holding='$holding', totalwithdrew='$totalwithdrew', totaldeposit='$totaldeposited', fees='$fees' where name='{$_REQUEST['id']}'";
	if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error: '.mysql_error()); }

	message_die(GENERAL_MESSAGE, $lang['user_updated'].'!<br /><br />'.sprintf($lang['click_return_bank_admin'], '<a href="' . append_sid("admin_bank.$phpEx") . '">', '</a>').'<br /><br />'.sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") .'">', '</a>').'<br /><br />');
}
else
{
	message_die(GENERAL_MESSAGE, "Invalid Command!"); 
}


//
// Generate the page
//
$template->pparse('body');

include('page_footer_admin.' . $phpEx);


?>
