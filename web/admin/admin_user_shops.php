<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                            admin_user_shops.php
 *                            --------------------
 *   Version              : 2.0.1
 *   website              : http://www.zarath.com
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   Copyright (C) 2004-2006   Zarath
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['General']['User_Shops'] = $file;
	return;
}

//
// Load default header
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_admin.'.$phpEx);
if (!(@include($phpbb_root_path . 'language/lang_' . $userdata['user_lang'] . '/lang_shop.' . $phpEx))) { include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_shop.' . $phpEx); }

#
# Register action var
#
if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) { $action = ( isset($HTTP_POST_VARS['action']) ) ? htmlspecialchars($HTTP_POST_VARS['action']) : htmlspecialchars($HTTP_GET_VARS['action']); }
else { $action = ''; }
#
# Done
#

if ( empty($action) )
{
	$template->set_filenames(array(
		'body' => 'admin/user_shops_body.tpl')
	);

	# Get a list of user shops!
	$sql = "SELECT a.*, b.username
		FROM " . USER_SHOPS_TABLE . " a, " . USERS_TABLE . " as b
		WHERE b.user_id = a.user_id
		ORDER BY b.username";
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

		$sql = "SELECT *
			FROM " . USER_SHOP_ITEMS_TABLE . " 
			WHERE shop_id = '{$row['id']}'";

		if ( !($result2 = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
		}
		$count = $db->sql_numrows($result2);

		$template->assign_block_vars('list_users', array(
			'SHOP_ID' => $row['id'],
			'STRING' => $row['username']
		));


		$string = $row['shop_name'] .' [' . $count . ' items]';

		$template->assign_block_vars('list_shops', array(
			'SHOP_ID' => $row['id'],
			'STRING' => $string
		));
	}

	if ( $sql_num_rows )
	{
		$template->assign_block_vars('switch_are_shops', array());

		$sql = "SELECT *
			FROM " . USER_SHOP_ITEMS_TABLE;

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
		}
		$total_items = $db->sql_numrows($result);

		$sql = "SELECT SUM(amount_holding) as total_amount, SUM(amount_earnt) as total_earnt
			FROM " . USER_SHOPS_TABLE;

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
		}
		if ( !($row = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
		}
	}

	$shop_open = ( $board_config['u_shops_enabled'] ) ? 'SELECTED' : '';
	$shop_closed = ( $board_config['u_shops_enabled'] ) ? '' : 'SELECTED';


	$template->assign_vars(array(
		'TOTAL_SHOPS' => $sql_num_rows,
		'TOTAL_ITEMS' => $total_items,
		'TOTAL_HOLDING' => $row['total_amount'],
		'TOTAL_EARNT' => $row['total_earnt'],

		'OPEN_COST' => $board_config['u_shops_open_cost'],
		'TAX_PERCENT' => $board_config['u_shops_tax_percent'],
		'MAX_ITEMS' => $board_config['u_shops_max_items'],
		'SHOP_OPEN' => $shop_open,
		'SHOP_CLOSED' => $shop_closed,

		'S_CONFIG_ACTION' => append_sid('admin_user_shops.' . $phpEx),

		'L_TITLE' => $lang['ushop_index_table1'],
		'L_EXPLAIN' => $lang['ushop_index_explain'],
		'L_TABLE_TITLE_1' => $lang['ushop_atitle_1'],
		'L_TABLE_TITLE_2' => $lang['ushop_atitle_2'],
		'L_TABLE_TITLE_3' => $lang['ushop_atitle_3'],
		'L_TABLE_TITLE_4' => $lang['ushop_atitle_4'],
		'L_TOTAL_SHOPS' => $lang['ushop_total_shops'],
		'L_TOTAL_ITEMS' => $lang['ushop_total_items'],
		'L_TOTAL_POINTS' => sprintf($lang['ushop_total_points'], $board_config['points_name']),
		'L_TOTAL_EARNT' => sprintf($lang['ushop_total_earnt'], $board_config['points_name']),
		'L_USER_SHOPS' => $lang['user_shops'],
		'L_OPEN' => $lang['ushop_open'],
		'L_CLOSED' => $lang['ushop_closed'],
		'L_PERCENT_TAKEN' => $lang['ushop_percent_taken'],
		'L_MAX_ITEMS' => $lang['ushop_max_items'],
		'L_OPEN_COST' => $lang['ushop_open_cost'],
		'L_UPDATE_CONFIG' => $lang['ushop_update_config'],
		'L_SHOP_NAME' => $lang['shop_name'],
		'L_RETURN_ITEMS' => $lang['ushop_return_items'],
		'L_ON' => $lang['ashop_on'],
		'L_OFF' => $lang['ashop_off'],
		'L_CLOSE_SHOP' => $lang['ushop_close_shop'],
		'L_SHOP_OWNER' => $lang['shop_owner']
	));
}

elseif ( $action == 'update_vars' )
{
	#
	# Register Vars
	#
	if ( isset($HTTP_GET_VARS['status']) || isset($HTTP_POST_VARS['status']) ) { $status = ( isset($HTTP_POST_VARS['status']) ) ? intval($HTTP_POST_VARS['status']) : intval($HTTP_GET_VARS['status']); }
	else { $status = 0; }
	if ( isset($HTTP_GET_VARS['tax_percent']) || isset($HTTP_POST_VARS['tax_percent']) ) { $tax_percent = ( isset($HTTP_POST_VARS['tax_percent']) ) ? intval($HTTP_POST_VARS['tax_percent']) : intval($HTTP_GET_VARS['tax_percent']); }
	else { $tax_percent = 0; }
	if ( isset($HTTP_GET_VARS['max_items']) || isset($HTTP_POST_VARS['max_items']) ) { $max_items = ( isset($HTTP_POST_VARS['max_items']) ) ? intval($HTTP_POST_VARS['max_items']) : intval($HTTP_GET_VARS['max_items']); }
	else { $max_items = 100; }
	if ( isset($HTTP_GET_VARS['open_cost']) || isset($HTTP_POST_VARS['open_cost']) ) { $open_cost = ( isset($HTTP_POST_VARS['open_cost']) ) ? intval($HTTP_POST_VARS['open_cost']) : intval($HTTP_GET_VARS['open_cost']); }
	else { $open_cost = 0; }
	#
	# Vars Registered
	#
	# Begin checks on vars
	#
	$open_cost = ( $open_cost < 0 ) ? 0 : $open_cost;
	$status = ( $status != 0 && $status != 1 ) ? 0 : $status;
	$max_items = ( $max_items < 0 ) ? 100 : $max_items;
	$tax_percent = ( $tax_percent < 0 || $tax_percent > 100 ) ? 0 : $tax_percent;

	#
	# Checks Done
	#
	# Begin SQL Query Setup
	#
	$sql = array();

	if ( $status != $board_config['u_shops_enabled'] )
	{
		$sql[] = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '$status'
			WHERE config_name = 'u_shops_enabled'";
	}
	if ( $tax_percent != $board_config['u_shops_tax_percent'] )
	{
		$sql[] = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '$tax_percent'
			WHERE config_name = 'u_shops_tax_percent'";
	}

	if ( $max_items != $board_config['u_shops_max_items'] )
	{
		$sql[] = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '$max_items'
			WHERE config_name = 'u_shops_max_items'";
	}

	if ( $open_cost != $board_config['u_shops_open_cost'] )
	{
		$sql[] = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '$open_cost'
			WHERE config_name = 'u_shops_open_cost'";
	}

	$count = count($sql);

	for ( $i = 0; $i < $count; $i++ )
	{
		if ( !($db->sql_query($sql[$i])) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_updating'], 'users shops'), '', __LINE__, __FILE__, $sql);
		}
	}

	message_die(GENERAL_MESSAGE, $lang['ushop_vars_updated'] . '<br /><br />' . sprintf($lang['ushop_click_back'], '<a href="' . append_sid('admin_user_shops.php') . '" class="nav">', '</a>'));
}

elseif ( $action == 'close_shop' )
{
	#
	# Register Vars
	#
	if ( isset($HTTP_GET_VARS['id']) || isset($HTTP_POST_VARS['id']) ) { $id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']); }
	else { $id = -1; }
	if ( isset($HTTP_GET_VARS['items']) || isset($HTTP_POST_VARS['items']) ) { $items = ( isset($HTTP_POST_VARS['items']) ) ? intval($HTTP_POST_VARS['items']) : intval($HTTP_GET_VARS['items']); }
	else { $items = 1; }
	#
	# Vars Registered
	# 

	$sql = "SELECT *
		FROM " . USER_SHOPS_TABLE . "
		WHERE id = '$id'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	if ( !($sql_num_rows) ) { message_die(GENERAL_MESSAGE, $lang['ushop_does_not_exist'] . '<br /><br />' . sprintf($lang['ushop_click_back'], '<a href="' . append_sid('admin_user_shops.php') . '" class="nav">', '</a>')); }
	else
	{
		# If set, return user items
		if ( $items )
		{
			$sql = "SELECT *
				FROM " . USER_SHOP_ITEMS_TABLE . "
				WHERE shop_id = '$id'";
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

				$sql = "UPDATE " . USER_ITEMS_TABLE . "
					SET worn = 0
					WHERE item_id = '{$row['i_id']}'
						AND id = '{$row['real_id']}'
						AND worn = 2";
				if ( !($db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, sprintf($lang['shop_error_deleting'], 'users shop items'), '', __LINE__, __FILE__, $sql);
				}
			}

			$msg .= '<br />' . sprintf($lang['ushop_items_returned'], $row['username']);
		}

		# DELETE SHOP
		$sql = "DELETE
			FROM " . USER_SHOPS_TABLE . "
			WHERE id = '$id'";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_deleting'], 'users shops'), '', __LINE__, __FILE__, $sql);
		}

		# Delete Shop Items
		$sql = "DELETE
			FROM " . USER_SHOP_ITEMS_TABLE . "
			WHERE shop_id = '$id'";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_deleting'], 'users shops'), '', __LINE__, __FILE__, $sql);
		}

		message_die(GENERAL_MESSAGE, $lang['ushop_shop_deleted'] . $msg . '<br /><br />' . sprintf($lang['ushop_click_back'], '<a href="' . append_sid('admin_user_shops.php') . '" class="nav">', '</a>'));
	}
}

else { header("Location: admin_user_shops.php"); }

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
