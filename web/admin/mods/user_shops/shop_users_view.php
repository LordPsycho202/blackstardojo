<?php
/***************************************************************************
 *                            shop_users_view.php
 *                            -------------------
 *   Version              : 2.0.1
 *   website              : http://www.zarath.com
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   Copyright (C) 2004-2006   Zarath
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

#
# Start session management
#
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
#
# End session management
#
if (!(@include($phpbb_root_path . 'language/lang_' . $userdata['user_lang'] . '/lang_shop.' . $phpEx))) { include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_shop.' . $phpEx); }

#
# Register main action variable!
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


//start of shop list page
if ( empty($action) )
{
	if ( isset($HTTP_GET_VARS['shop']) || isset($HTTP_POST_VARS['shop']) ) { $shop = ( isset($HTTP_POST_VARS['shop']) ) ? intval($HTTP_POST_VARS['shop']) : intval($HTTP_GET_VARS['shop']); }
	else { $shop = ''; }

	$template->set_filenames(array(
		'body' => 'shop/shop_users_view.tpl')
	);

	$sql = "SELECT *
		FROM " . USER_SHOPS_TABLE . "
		WHERE id = '$shop'
			AND shop_status != '1'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
	}

	if ( !($db->sql_numrows($result)) )
	{
		message_die(GENERAL_MESSAGE, $lang['ushop_does_not_exist']);
	}



	$sql = "SELECT b.id as items_id, c.*, b.cost, b.seller_notes, a.shop_name, a.id as shop_id
		FROM " . USER_SHOPS_TABLE . " as a, " . USER_SHOP_ITEMS_TABLE . " as b, " . USER_ITEMS_TABLE . " as c
		WHERE a.id = '$shop'
			AND a.shop_status != '1'
			AND b.shop_id = a.id
			AND c.id = b.real_id
		ORDER BY c.item_name";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	if ( !($sql_num_rows) )
	{
		message_die(GENERAL_MESSAGE, $lang['ushop_no_items'] . '<br /><br />' . sprintf($lang['ushop_u_index'], '<a href="' . append_sid("shop_users.php") . '" class="nav">', '</a>'));
	}

	$template->assign_block_vars('switch_main_list', array());

	for ($i = 0; $i < $sql_num_rows; $i++)
	{
		if ( !($row = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
		}

		$rownum = ( $i % 2 ) ? "row1" : "row2";

		$s_desc = ( substr_count($row['item_s_desc'], 'scatter') ) ? 'N/A' : $row['item_s_desc'];

		if ( $row['wrapped'] )
		{
			$row['item_name'] = $row['wrapped'];
			$s_desc = 'A large wrapped box.';
		}

		$template->assign_block_vars('list_items', array(
			'ROW_CLASS' => $rownum,
			'ITEM_URL' => append_sid('shop_users_view.' . $phpEx . '?action=display_item&item=' . $row['items_id']),
			'ITEM_NAME' => $row['item_name'],
			'ITEM_S_DESC' => $s_desc,
			'ITEM_COST' => $row['cost'],
			'ITEM_NOTES' => $row['seller_notes']
		));
	}

	$page_title = $row['shop_name'];
	$shoplocation = ' -> <a href="'.append_sid('shop_users.'.$phpEx, true).'" class="nav">' . $lang['ushop_shop_list'] . '</a> -> <a href="' . append_sid('shop_users_view.' . $phpEx . '?shop=' . $row['shop_id'], true) . '" class="nav">' . $row['shop_name'] . '</a>';

	$template->assign_vars(array(
		'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=" . $userdata['user_id']),

		'USER_POINTS' => $userdata['user_points'],
		'SHOPLOCATION' => $shoplocation,
		'L_PERSONAL_INFO' => $lang['shop_personal_info'],

		'L_INVENTORY' => $lang['your_inv'],
		'L_POINTS_NAME' => $board_config['points_name'],
		'L_COST' => $lang['item_cost'],
		'L_S_DESC' => $lang['item_s_desc'],
		'L_ITEM_NAME' => $lang['name'],
		'L_ICON' => $lang['icon'],
		'L_OWNED' => $lang['owned'],
		'L_DESCRIPTION' => $lang['ushop_description'],
		'L_BUY' => $lang['buy'],
		'L_NOTES' => $lang['ushop_seller_notes'],
		'L_SHOP_TITLE' => $row['shop_name'] . ' ' . $lang['inventory']
	));
	$template->assign_block_vars('', array());
}

//start of item info page
elseif ( $action == 'display_item' ) 
{
	if ( isset($HTTP_GET_VARS['item']) || isset($HTTP_POST_VARS['item']) ) { $item = ( isset($HTTP_POST_VARS['item']) ) ? intval($HTTP_POST_VARS['item']) : intval($HTTP_GET_VARS['item']); }
	else { $item = ''; }

	$template->set_filenames(array(
		'body' => 'shop/shop_users_view.tpl')
	);

	$sql = "SELECT a.user_id, a.username, a.shop_name, a.shop_status, c.*, b.shop_id, b.cost, b.seller_notes
		FROM " . USER_SHOPS_TABLE . " as a, " . USER_SHOP_ITEMS_TABLE . " as b, " . USER_ITEMS_TABLE . " as c
		WHERE a.id = b.shop_id
			AND a.shop_status <> 1
			AND b.id = '$item'
			AND c.id = b.real_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	if ( !($sql_num_rows) )
	{
		message_die(GENERAL_MESSAGE, $lang['ushop_no_such_item']);
	}

	if ( !($row = $db->sql_fetchrow($result)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
	}

	if (file_exists("shop/images/" . $row['item_name'] . ".jpg")) { $item_image = $row['item_name'] . '.jpg'; }
	elseif (file_exists("shop/images/" . $row['item_name'] . ".png")) { $item_image = $row['item_name'] . '.png'; }
	else { $item_image = $row['item_name'] . '.gif'; }

	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
		WHERE user_id = '{$userdata['user_id']}'
			AND item_name = '" . str_replace("'", "''", $row['item_name']) . "'
			AND worn = 0";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
	}

	$count = $db->sql_numrows($result);

	if ( $row['wrapped'] )
	{
		$row['item_name'] = $row['wrapped'];
		$row['item_l_desc'] = $lang['ushop_wrapped'];
		$item_image = 'wrapped/' . $row['wrapped'] . '.jpg';
	}

	$decay = ( $row['die_time'] ) ? '<br /><span class="gensmall"><i>*' . $lang['shop_will_decay'] . ' ' . duration($row['die_time'] - time()) . '!</i></span>' : '';

	$template->assign_block_vars('switch_item_info', array(
		'ROW_CLASS' => $rownum,
		'ITEM_URL' => append_sid('shop_users_view.'.$phpEx.'?action=buy_item&item=' . $item),
		'ITEM_IMAGE' => $item_image,
		'ITEM_NAME' => $row['item_name'],
		'ITEM_L_DESC' => $row['item_l_desc'] . $decay,
		'ITEM_COST' => $row['cost'],
		'ITEM_NOTES' => $row['seller_notes'],
		'ITEM_OWNED' => $count
	));
	
	$page_title = $row['name'] . ' ' . $lang['information'];
	$shoplocation = ' -> <a href="'.append_sid('shop_users.'.$phpEx, true).'" class="nav">' . $lang['ushop_shop_list'] . '</a> -> <a href="' . append_sid('shop_users_view.' . $phpEx . '?shop=' . $row['shop_id'], true) . '" class="nav">' . $row['shop_name'] . '</a> -> <a href="' . append_sid('shop_users_view.' . $phpEx . '?action=display_item&item=' . $item, true) . '" class="nav">' . $row['name'] . ' ' . $lang['information'] . '</a>';

	$template->assign_vars(array(
		'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=" . $userdata['user_id']),

		'USER_POINTS' => $userdata['user_points'],

		'SHOPLOCATION' => $shoplocation,
		'L_INVENTORY' => $lang['your_inv'],
		'L_POINTS_NAME' => $board_config['points_name'],
		'L_PERSONAL_INFO' => $lang['shop_personal_info'],

		'L_TABLE_TITLE' => $lang['ushop_item_display'],
		'L_NOTES' => $lang['ushop_seller_notes'],
		'L_ICON' => $lang['icon'],
		'L_ITEM_NAME' => $lang['name'],
		'L_DESCRIPTION' => $lang['item_desc'],
		'L_COST' => $lang['item_cost'],
		'L_OWNED' => $lang['owned'],
		'L_BUY' => $lang['buy']
	));
	$template->assign_block_vars('', array());
}

#Start buy item script
elseif ( $action == 'buy_item' )
{
	if ( isset($HTTP_GET_VARS['item']) || isset($HTTP_POST_VARS['item']) ) { $item = ( isset($HTTP_POST_VARS['item']) ) ? intval($HTTP_POST_VARS['item']) : intval($HTTP_GET_VARS['item']); }
	else { $item = ''; }

	if ( !$userdata['session_logged_in'] )
	{
		$redirect = 'shop_users_view.'.$phpEx.'&action=buy_item&item=' . $item;
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}
	
	# Make sure item is still in user's shop!
	$sql = "SELECT a.*, b.*, c.*, a.user_id as shop_owner
		FROM " . USER_SHOPS_TABLE . " as a, " . USER_SHOP_ITEMS_TABLE . " as b, " . USER_ITEMS_TABLE . " as c
		WHERE a.id = b.shop_id
			AND c.id = b.real_id
			AND b.id = '$item'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users/user shops/user shop items'), '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	if ( !($sql_num_rows) )
	{
		message_die(GENERAL_MESSAGE, $lang['ushop_no_such_item'] . '<br /><br />' . sprintf($lang['ushop_u_index'], '<a href="' . append_sid("shop_users.php") . '" class="nav">', '</a>'));
	}
	if ( !($row = $db->sql_fetchrow($result)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users/user shops/user shop items'), '', __LINE__, __FILE__, $sql);
	}

	#
	# Check if shop is closed for editting!
	if ( $row['shop_status'] == '2' )
	{
		message_die(GENERAL_MESSAGE, $lang['ushop_shop_restocking'] . '<br /><br />' . sprintf($lang['ushop_u_shop'], '<a href="' . append_sid("shop_users_view.php?shop=" . $row['shop_id']) . '" class="nav">','</a>', $row['shop_name']) . '<br /><br />' . sprintf($lang['ushop_u_index'], '<a href="' . append_sid("shop_users.php") . '" class="nav">', '</a>'));
	}
	if ( $row['shop_status'] == '1' )
	{
		message_die(GENERAL_MESSAGE, $lang['ushop_shop_closed'] . '<br /><br />' . sprintf($lang['ushop_u_index'], '<a href="' . append_sid("shop_users.php") . '" class="nav">', '</a>'));
	}

	#
	# Check currency & if has item
	if ( $board_config['multibuys'] == "off" ) 
	{
		$sql = "SELECT *
			FROM " . USER_ITEMS_TABLE . "
			WHERE user_id = '{$userdata['user_id']}'
				AND item_id = '{$row['item_id']}'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users/user shops/user shop items'), '', __LINE__, __FILE__, $sql);
		}

		if ( $db->sql_numrows($result) )
		{
			message_die(GENERAL_MESSAGE, $lang['ushop_already_owned']);
		}
	}

	#Specific code for a special version of the shop, won't affect all shops due to the previous check!
	$item_limit = ( empty($board_config['shop_invlimit']) ) ? 0 : $board_config['shop_invlimit'];

	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
		WHERE user_id = '{$userdata['user_id']}'
			AND worn = 0
				OR worn = 1";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
	}

	$count = $db->sql_numrows($result);

	if ( ( $count >= $item_limit) && ($item_limit != 0) )
	{
		message_die(GENERAL_MESSAGE, $lang['inv_full']);
	}

	if ( $userdata['user_points'] < $row['cost'] )
	{
		message_die(GENERAL_MESSAGE, sprintf($lang['not_enough_currency'], $board_config['points_name']));
	}
	# End of check for currency and is has item
	#
	#start of table updates

	# Code to update purchaser's new currency & items
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_points = user_points - {$row['cost']}
		WHERE user_id = '{$userdata['user_id']}'";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
	}

	$sql = "UPDATE " . USER_ITEMS_TABLE . "
		SET worn = 0,
			user_id = '{$userdata['user_id']}'
		WHERE id = '{$row['real_id']}'
			AND worn = 2";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
	}


	# Code to remove the item from the shop
	$sql = "DELETE
		FROM " . USER_SHOP_ITEMS_TABLE . "
		WHERE id = '$item'";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_deleting'], 'users shops items'), '', __LINE__, __FILE__, $sql);
	}

	# Check if $cost needs to be set back, if tax is set
	if ( $board_config['u_shops_tax_percent'] > 0 )
	{
		$cost = $row['cost'] - ($row['cost'] / 100 * $row['u_shops_tax_percent']);
	}
	else
	{
		$cost = $row['cost'];
	}
	# Code to update left items, items sold, holding amount, amount earnt!
	$sql = "UPDATE " . USER_SHOPS_TABLE . "
		set items_sold = items_sold + 1, 
			items_holding = items_holding - 1,
			amount_holding = amount_holding + $cost,
			amount_earnt = amount_earnt + $cost
		WHERE user_id = '{$row['user_id']}'";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users shops'), '', __LINE__, __FILE__, $sql);
	}


	// Transaction Code!
	$sql = "INSERT
		INTO `".$table_prefix."transactions`
		(user_id, type, action, value, timestamp, ip)
		values('{$userdata['user_id']}', 'shop', 'buy', '" . addslashes($row['item_name']) . " :: " . $item . "', '".time()."', '{$_SERVER['REMOTE_ADDR']}')";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'transactions'), '', __LINE__, __FILE__, $sql);
	}
	#end of table updates
	#
	# Send PM to shop owner!
	#
	$sql = "SELECT *
		FROM " . USERS_TABLE . "
		WHERE user_id = '{$row['shop_owner']}'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
	}
	$owner_row = $db->sql_fetchrow($result);

	$privmsg_subject = $lang['ushop_pm_title'];
	$message = '[b]' . $lang['ushop_pm_automated'] . ':[/b]
		' . sprintf($lang['ushop_pm_sold'], $row['item_name'], $cost, $board_config['points_name'], $userdata['username']);
	cash_pm($owner_row, $privmsg_subject, $message);
	#
	# PM sent, final output
	#

	if ( $row['wrapped'] )
	{
		$row['item_name'] = $row['wrapped'];
		$row['item_l_desc'] = $lang['ushop_wrapped'];
		$item_image = 'wrapped/' . $row['wrapped'] . '.jpg';
	}

	$page_title = $lang['buy'] . ' ' . $row['item_name'];

	message_die(GENERAL_MESSAGE, sprintf($lang['ushop_bought'], $row['item_name'], $row['cost'], $board_config['points_name'], $row['shop_name'], $row['username']) . '<br /><br />' . sprintf($lang['ushop_u_shop'], '<a href="' . append_sid("shop_users_view.php?shop=" . $row['shop_id']) . '" class="nav">','</a>', $row['shop_name']) . '<br /><br />' . sprintf($lang['ushop_u_index'], '<a href="' . append_sid("shop_users.php") . '" class="nav">', '</a>'));
}

elseif ( ( $action == 'clear_error' ) && ( $userdata['user_level'] == 1 ) )
{
	$sql = "SELECT *
		FROM " . USER_SHOP_ITEMS_TABLE;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'user shop items'), '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	for ( $i = 0; $i < $sql_num_rows; $i++ )
	{
		$row = $db->sql_fetchrow($result);

		$sql = "SELECT *
			FROM " . USER_ITEMS_TABLE . "
			WHERE id = '{$row['real_id']}'";
		if ( !($result2 = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'user items'), '', __LINE__, __FILE__, $sql);
		}

		if ( !($db->sql_numrows($result2)) )
		{
			$msg .= 'Failed to find item for ' . $row['real_id'] . ' in shop ' . $row['shop_id'] . '<br />';
			$sql = "DELETE
				FROM " . USER_SHOP_ITEMS_TABLE . "
				WHERE real_id = '{$row['real_id']}'";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['shop_error_deleting'], 'user shop items'), '', __LINE__, __FILE__, $sql);
			}
			else
			{
				$msg .= 'Item successfully removed...<br /><br />';
				$z++;
			}
		}

	}
	$msg .= 'Total of ' . $z . ' items removed from user shops.';

	message_die(GENERAL_MESSAGE, $msg);
}

else 
{
	message_die(GENERAL_MESSAGE, 'This is not a valid command!');
}

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