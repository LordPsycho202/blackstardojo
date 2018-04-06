<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                              admin_store_list.php
 *                            -------------------
 *   begin                : Thursday, July 31, 2003
 *   copyright            : (C) 2003 wGEric
 *   email                : eric@best-1.biz
 *
 *   $Id: admin_store_users.php,v 1.1 2004/01/03 00:55:33 wgeric Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', 1);
define('IN_CASHMOD', true);

if ( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Store_MOD']['Store_users'] = "$file";
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_cash.'.$phpEx);
include($phpbb_root_path . 'includes/functions_store.'.$phpEx);
include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_store.'.$phpEx);

//
// Mode setting
//
if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
}
else
{
	$mode = '';
}
if ( isset($HTTP_POST_VARS['cancel']) || isset($HTTP_GET_VARS['cancel']) )
{
  $mode = '';
}
//
//submit
//
if ( isset($HTTP_POST_VARS['submit']) || isset($HTTP_GET_VARS['submit']) )
{
	$submit = ( isset($HTTP_POST_VARS['submit']) ) ? $HTTP_POST_VARS['submit'] : $HTTP_GET_VARS['submit'];
}
else
{
	$submit = '';
}
//
//confirm
//
if ( isset($HTTP_POST_VARS['confirm']) || isset($HTTP_GET_VARS['confirm']) )
{
	$confirm = ( isset($HTTP_POST_VARS['confirm']) ) ? $HTTP_POST_VARS['confirm'] : $HTTP_GET_VARS['confirm'];
}
else
{
	$confirm = '';
}
//
//id
//
if ( isset($HTTP_POST_VARS['id']) || isset($HTTP_GET_VARS['id']) )
{
	$id = ( isset($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];
}
else
{
	$id = "";
}

switch ( $mode )
{
  //--------------------------------------
  //display users items
  case 'display':
  
    if ( $submit == '' && $confirm == '' )
    {
      $template->set_filenames(array(
        "body" => "admin/store_user_inventory.tpl")
      );

      $template->assign_vars(array(
        'L_STORE_TITLE' => $lang['Store_users'],
        'L_STORE_EXPLAIN' => $lang['Store_users_explain'],

        'L_ITEM' => $lang['Store_item'],
        'L_AMOUNT' => $lang['Store_amount'],
        'L_PRICE' => $lang['Store_price'],
        'L_ACTION' => $lang['Store_action'],

        'S_DELETE_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_users.'.$phpEx.'?mode=display'),
        'S_DELETE_VALUE' => $lang['Store_remove_inventory'],

        'S_ADD_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_users.'.$phpEx.'?mode=add'),
        'S_ADD_VALUE' => $lang['Store_add_inventory'],
        'S_ADD_HIDDEN' => '<input type="hidden" name="id" value="' . $id . '">')
      );

      item_list('admin', $id);
    }
    else if ( $submit != '' && $confirm == '' )
    {
      //display confirm for remove item
      $i = 0;
      $hidden_fields = '';
      while ( $i < count($id) )
      {
        $hidden_fields .= '<input type="hidden" name="id[]" value="' . $id[$i] . '">';
        $i++;
      }

      //display confirm
      $template->set_filenames(array(
        "body" => "confirm_body.tpl")
      );

      $template->assign_vars(array(
        'MESSAGE_TITLE' => $lang['Store_inventory_delete_title'],
        'MESSAGE_TEXT' => $lang['Store_user_inventory_delete'],
        'L_YES' => $lang['Yes'],
        'L_NO' => $lang['No'],

        'S_CONFIRM_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_users.'.$phpEx.'?mode=display'),
        'S_HIDDEN_FIELDS' => $hidden_fields)
      );

    }
    else
    {
      //remove item from inventory
      $i = 0;
      while ( $i < count($id) )
      {
        $sql = "DELETE FROM " . STORE_INVENTORY . " WHERE inventory_id = " . $id[$i] . " LIMIT 1";
        if ( !$result = $db->sql_query($sql) )
        {
          message_die(GENERAL_ERROR, "Error deleting item from inventory", "", __LINE__, __FILE__, $sql);
        }
        $i++;
      }

      $message = $lang['Store_inventory_deleted'] . "<br /><br />" . sprintf($lang['Click_return_store_users'], "<a href=\"" . append_sid("admin_store_users.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

       message_die(GENERAL_MESSAGE, $message);
    }
  
    break;

  //--------------------------------------
  //add item
  case 'add':
  
    if ( $submit == '' )
    {
      //display add inventory screen
      $item_list = item_list(0, 'board_add');

      $template->set_filenames(array(
        "body" => "admin/store_inventory_add.tpl")
      );

      $template->assign_vars(array(
        'L_STORE_TITLE' => $lang['Store_user_inventory_add'],
        'L_STORE_EXPLAIN' => $lang['Store_user_inventory_add_explain'],

        'L_ITEM' => $lang['Store_item'],
        'L_AMOUNT' => $lang['Store_amount'],
        'L_PRICE' => $lang['Store_price'],
        'L_RESTOCK_TIME' => $lang['Store_restock_time'],
        'L_RESTOCK_AMOUNT' => $lang['Store_restock_amount'],

        'ITEM_LIST' => $item_list,

        'S_SUBMIT_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_users.'.$phpEx.'?mode=add'),
        'S_SUBMIT_VALUE' => $lang['Store_inventory_add'],

        'S_HIDDEN_FIELDS' => '<input type="hidden" name="id" value="' . $id . '">')
      );

    }
    else
    {

      //create new inventory
      $item = trim($HTTP_POST_VARS['item']);
      $price = intval($HTTP_POST_VARS['price']);
      $amount = intval($HTTP_POST_VARS['amount']);
      $restock_time_hr = intval($HTTP_POST_VARS['restock_time']);
      $restock_time = $restock_time_hr * 3600;
      $restock_amount = intval($HTTP_POST_VARS['restock_amount']);

      $sql = "INSERT INTO " . STORE_INVENTORY . " ( inventory_item, inventory_user, inventory_price, inventory_amount, restock_time, restock_amount, restock_last )
        VALUES ( '$item', '$id', '$price', '$amount', '$restock_time', '$restock_amount', '" . time() . "' );";
      if ( !$result = $db->sql_query($sql) )
      {
        message_die(GENERAL_ERROR, "Error adding item to inventory", "", __LINE__, __FILE__, $sql);
      }

      $message = $lang['Store_user_inventory_added'] . "<br /><br />" . sprintf($lang['Click_return_store_users'], "<a href=\"" . append_sid("admin_store_users.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

      message_die(GENERAL_MESSAGE, $message);
    }
  
    break;
    
  //--------------------------------------
  //edit item
  case 'edit':
    if ( $submit == '' )
    {
      //display edit inventory screen
      $sql = "SELECT * FROM " . STORE_INVENTORY . " WHERE inventory_id = $id LIMIT 1";
      if ( !$result = $db->sql_query($sql) )
      {
        message_die(GENERAL_ERROR, "Error getting inventory's information", "", __LINE__, __FILE__, $sql);
      }
      $row = $db->sql_fetchrow($result);

      $item_list = item_list('board_add',0, $row['inventory_item']);

      $template->set_filenames(array(
        "body" => "admin/store_inventory_add.tpl")
      );

      $restock_time_hrs = $row['restock_time'];
      $restock_time = $restock_time_hrs / 3600;

      $template->assign_vars(array(
        'L_STORE_TITLE' => $lang['Store_inventory_edit'],
        'L_STORE_EXPLAIN' => $lang['Store_inventory_edit_explain'],

        'L_ITEM' => $lang['Store_item'],
        'L_AMOUNT' => $lang['Store_amount'],
        'L_PRICE' => $lang['Store_price'],
        'L_RESTOCK_TIME' => $lang['Store_restock_time'],
        'L_RESTOCK_AMOUNT' => $lang['Store_restock_amount'],

        'ITEM_LIST' => $item_list,
        'AMOUNT' => $row['inventory_amount'],
        'PRICE' => $row['inventory_price'],
        'RESTOCK_TIME' => $restock_time,
        'RESTOCK_AMOUNT' => $row['restock_amount'],

        'S_SUBMIT_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_users.php?mode=edit'),
        'S_SUBMIT_VALUE' => $lang['Store_inventory_edit'],

        'S_HIDDEN_FIELDS' => '<input type="hidden" name="id" value="' . $id . '">')
      );

    }
    else
    {
      //create new inventory
      $item = trim($HTTP_POST_VARS['item']);
      $price = intval($HTTP_POST_VARS['price']);
      $amount = intval($HTTP_POST_VARS['amount']);
      $restock_time_hr = intval($HTTP_POST_VARS['restock_time']);
      $restock_time = $restock_time_hr * 3600;
      $restock_amount = intval($HTTP_POST_VARS['restock_amount']);

      $sql = "UPDATE " . STORE_INVENTORY . " SET
        inventory_item = '$item',
        inventory_price = '$price',
        inventory_amount = '$amount',
        restock_time = '$restock_time',
        restock_amount = '$restock_amount'
        WHERE inventory_id = $id;";
      if ( !$result = $db->sql_query($sql) )
      {
        message_die(GENERAL_ERROR, "Error editing inventory", "", __LINE__, __FILE__, $sql);
      }

      $message = $lang['Store_inventory_edited'] . "<br /><br />" . sprintf($lang['Click_return_store_users'], "<a href=\"" . append_sid("admin_store_users.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

      message_die(GENERAL_MESSAGE, $message);
    }
    break;
    
  //--------------------------------------
  //display all users on the site
  default:
  
    $template->set_filenames(array(
      "body" => "admin/store_user_list.tpl")
    );

    $template->assign_vars(array(
      'L_STORE_TITLE' => $lang['Store_users'],
      'L_STORE_EXPLAIN' => $lang['Store_users_explain'],
      'L_USERS' => $lang['Select_a_User'])
    );

    $sql = "SELECT user_id, username FROM " . USERS_TABLE . " WHERE user_id <> " . ANONYMOUS . " ORDER by username";
    if ( !$result = $db->sql_query($sql) )
    {
      message_die(GENERAL_ERROR, "Couldn't get users", "", __LINE__, __FILE__, $sql);
    }

    //loop through users
    while ( $row = $db->sql_fetchrow($result) )
    {
      $template->assign_block_vars('user_row', array(
        'LINK' => append_sid($phpbb_root_path . 'admin/admin_store_users.'.$phpEx.'?mode=display&id=' . $row['user_id']),
        'USERNAME' => $row['username'])
      );
    }

    break;
    
}
  

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
