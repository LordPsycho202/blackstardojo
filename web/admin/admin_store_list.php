<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                              admin_store_list.php
 *                            -------------------
 *   begin                : Thursday, July 31, 2003
 *   copyright            : (C) 2003 wGEric
 *   email                : eric@best-1.biz
 *
 *   $Id: admin_store_list.php,v 1.1 2004/01/03 00:55:33 wgeric Exp $
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
	$module['Store_MOD']['Store_List'] = "$file";
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

switch($mode)
{
    //----------------------------------------------------
    //create new store
    //
    case "new":
        //set up varibles and submit info
        $new_store_name = trim($HTTP_POST_VARS['new_store']);

        if ( $submit == '' )
        {
            //display create store form
            $template->set_filenames(array(
               "body" => "admin/store_edit_body.tpl")
            );

            $template->assign_block_vars('switch_store', array());

            $sql = "SELECT cash_id, cash_name FROM " . CASH_TABLE . " ORDER BY cash_order";
            if ( !($cash_result = $db->sql_query($sql)) )
            {
	             message_die(GENERAL_ERROR, "Could not obtain cash information", '', __LINE__, __FILE__, $sql);
            }
            while ( $cash = $db->sql_fetchrow($cash_result) )
            {
              $template->assign_block_vars("switch_store.cashrow",array(
		            'CASH_ID' => $cash['cash_id'],
		            'CASH_NAME' => $cash['cash_name'])
              );
            }

            $template->assign_vars(array(
              'L_CREATE_STORE' => $lang['Store_create'],
              'L_STORE_EXPLAIN' => $lang['Store_create_explain'],
              'L_STORE_NAME' => $lang['Store_name'],
              'L_STORE_DESCRIPTION' => $lang['Store_description'],
              'L_CASH' => $lang['Store_cash'],
              'L_SELECT_ONE' => $lang['Select_one'],
          
              'S_NEW_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_list.'.$phpEx.'?mode=new'),

              'STORE_NAME' => htmlspecialchars(stripslashes(trim($new_store_name))))
            );
        }
        else
        {
            $new_cash_id = $HTTP_POST_VARS['cash_id'];
            if ( !$new_cash_id )
            {
                $message = $lang['Store_select_cash'] . "<br /><br />" . sprintf($lang['Click_return_storelist_new'], "<a href=\"" . append_sid("admin_store_list.$phpEx?mode=new") . "\">", "</a>");

	              message_die(GENERAL_MESSAGE, $message);
            }
            $new_store_description = str_replace("\'", "''", trim($HTTP_POST_VARS['store_description']));
            //create the store
            $sql = "INSERT INTO " . STORE_LIST . " ( owner_id, store_name, store_description, cash_id )
                VALUES ( '" . BOARD_OWNED . "', '" . str_replace("\'", "''",$new_store_name) . "', '$new_store_description', '$new_cash_id' )";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Error creating store", "", __LINE__, __FILE__, $sql);
            }
            else
            {
                $message = $lang['Store_created'] . "<br /><br />" . sprintf($lang['Click_return_storelist'], "<a href=\"" . append_sid("admin_store_list.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	              message_die(GENERAL_MESSAGE, $message);
            }
        }
        
        break;
        
    //----------------------------------------------------
    //edit store
    //
    case "edit":

        if ( $submit == '' )
        {
            $sql = "SELECT * FROM " . STORE_LIST . " WHERE store_id = $id";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Error getting store information", "", __LINE__, __FILE__, $sql);
            }
            $row = $db->sql_fetchrow($result);
            $store_name = $row['store_name'];
            $store_description = $row['store_description'];
            $store_cash = $row['cash_id'];

            $template->assign_block_vars('switch_store', array());

            $sql2 = "SELECT cash_id, cash_name FROM " . CASH_TABLE . " ORDER BY cash_order";
            if ( !($cash_result = $db->sql_query($sql2)) )
            {
	             message_die(GENERAL_ERROR, "Could not obtain cash information", '', __LINE__, __FILE__, $sql);
            }
            while ( $cash = $db->sql_fetchrow($cash_result) )
            {
              if ( $cash['cash_id'] == $store_cash )
              {
                $selected = 'selected';
              }
              else
              {
                $selected = '';
              }
              $template->assign_block_vars("switch_store.cashrow",array(
		            'CASH_ID' => $cash['cash_id'],
		            'CASH_NAME' => $cash['cash_name'],
                'CASH_SELECTED' => $selected)
              );
            }

            //show edit store form
            $template->set_filenames(array(
               "body" => "admin/store_edit_body.tpl")
            );

            $template->assign_vars(array(
              'L_CREATE_STORE' => $lang['Store_edit'],
              'L_STORE_EXPLAIN' =>$lang['Store_edit_explain'],
              'L_STORE_NAME' => $lang['Store_name'],
              'L_STORE_DESCRIPTION' => $lang['Store_description'],
              'L_CASH' => $lang['Store_cash'],
              'L_SELECT_ONE' => $lang['Select_one'],

              'S_NEW_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_list.'.$phpEx.'?mode=edit'),
              'S_HIDDEN_FIELDS' => '<input type="hidden" name="id" value="' . $id . '">',

              'STORE_NAME' => htmlspecialchars(stripslashes(trim($store_name))),
              'STORE_DESCRIPTION' => stripslashes(trim($store_description)))
            );
        }
        else
        {
            //update stores info
            $new_store_description = str_replace("\'", "''", trim($HTTP_POST_VARS['store_description']));
            $new_store_name = str_replace("\'", "''", trim($HTTP_POST_VARS['new_store']));
            $new_cash_id = $HTTP_POST_VARS['cash_id'];
            
            $sql = "UPDATE " . STORE_LIST . " SET store_name = '$new_store_name', store_description = '$new_store_description', cash_id = '$new_cash_id' WHERE store_id = $id";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Error updating store", "", __LINE__, __FILE__, $sql);
            }
            else
            {
                $message = $lang['Store_updated'] . "<br /><br />" . sprintf($lang['Click_return_storelist'], "<a href=\"" . append_sid("admin_store_list.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	              message_die(GENERAL_MESSAGE, $message);
            }
        }
        
        break;
        
    //----------------------------------------------------
    //delete store
    //
    case "delete":
        //see if it has been confirmed
        if ( $confirm == '' )
        {
            //display confirm
            $template->set_filenames(array(
               "body" => "confirm_body.tpl")
            );
            
            $template->assign_vars(array(
                'MESSAGE_TITLE' => $lang['Store_delete_title'],
                'MESSAGE_TEXT' => $lang['Store_delete'],
                'L_YES' => $lang['Yes'],
                'L_NO' => $lang['No'],
                
                'S_CONFIRM_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_list.'.$phpEx.'?mode=delete'),
                'S_HIDDEN_FIELDS' => '<input type="hidden" name="id" value="' . $id . '">')
            );
        }
        else
        {
            $store_info = store_info($id);
            //delete store and items in it
            $sql = "DELETE FROM " . STORE_INVENTORY . " WHERE inventory_store = $id";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Error deleting store's inventory", "", __LINE__, __FILE__, $sql);
            }
            $sql = "DELETE FROM " . STORE_LIST . " WHERE store_id = $id LIMIT 1";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Error deleting store", "", __LINE__, __FILE__, $sql);
            }
            if ( $store_info['owner_id'] != BOARD_OWNED )
            {
              $owner_info = get_userdata($store_info['owner_id']);
              $new_owned_number = $owner_info['stores_owned'] - 1;
              $sql = "UPDATE " . USERS_TABLE . " SET
                stores_owned = '$new_owned_number'
                WHERE user_id = " . $store_info['owner_id'];
              if ( !$result = $db->sql_query($sql) )
              {
                message_die(GENERAL_ERROR, "Error updating users stores", "", __LINE__, __FILE__, $sql);
              }
            }
            $message = $lang['Store_deleted'] . "<br /><br />" . sprintf($lang['Click_return_storelist'], "<a href=\"" . append_sid("admin_store_list.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

            message_die(GENERAL_MESSAGE, $message);
            
        }
        break;

    //----------------------------------------------------
    //display and delete store inventory
    //
    case "inventory":
        if ( $submit == '' && $confirm == '' )
        {
            $template->set_filenames(array(
                "body" => "admin/store_inventory_body.tpl")
            );
            
            $template->assign_vars(array(
               'L_STORE_TITLE' => $lang['Store_inventory'],
               'L_STORE_EXPLAIN' => $lang['Store_inventory_explain'],
               
               'L_ITEM' => $lang['Store_item'],
               'L_AMOUNT' => $lang['Store_amount'],
               'L_PRICE' => $lang['Store_price'],
               'L_ACTION' => $lang['Store_action'],
               'L_RESTOCK_TIME' => $lang['Store_restock_time'],
               'L_RESTOCK_AMOUNT' => $lang['Store_restock_amount'],

               'S_DELETE_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_list.'.$phpEx.'?mode=inventory'),
               'S_DELETE_VALUE' => $lang['Store_remove_inventory'],

               'S_ADD_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_list.'.$phpEx.'?mode=addinventory'),
               'S_ADD_VALUE' => $lang['Store_add_inventory'],
               'S_ADD_HIDDEN' => '<input type="hidden" name="id" value="' . $id . '">')
            );

            //display inventory in store
            $sql = "SELECT * FROM " . STORE_INVENTORY . " WHERE inventory_store = $id ORDER BY inventory_id ASC";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Error getting store's inventory", "",__LINE__,__FILE__, $sql);
            }
            while ( $inven = $db->sql_fetchrow($result) )
            {
                $inventory_id = $inven['inventory_id'];
                $inventory_amount = $inven['inventory_amount'];
                $inventory_price = $inven['inventory_price'];
                $restock_time_hrs = $inven['restock_time'];
                $restock_time = $restock_time_hrs / 3600;
                $restock_amount = $inven['restock_amount'];
                
                $inventory_item = $inven['inventory_item'];
                $sql = "SELECT * FROM " . STORE_ITEMS . " WHERE item_id = $inventory_item";
                if ( !$result2 = $db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, "Error getting items information", "",__LINE__,__FILE__, $sql);
                }
                $items = $db->sql_fetchrow($result2);
                $item_name = stripslashes(trim($items['item_name']));
                $item_description = stripslashes(trim($items['item_description']));
                
                //edit link
                $temp_url = append_sid($phpbb_root_path . 'admin/admin_store_list.'.$phpEx.'?mode=editinventory&id='.$inventory_id);
                $edit = '<a href="' . $temp_url . '" class="gen">' . $lang['Edit'] . '</a>';
                
                $template->assign_block_vars('item_row', array(
                    'ID' => $inventory_id,
                    'ITEM' => $item_name,
                    'DESCRIPTION' => $item_description,
                    'AMOUNT' => $inventory_amount,
                    'PRICE' => $inventory_price,
                    'EDIT' => $edit,
                    'RESTOCK_TIME' => $restock_time,
                    'RESTOCK_AMOUNT' => $restock_amount)
                );
            }
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
                'MESSAGE_TEXT' => $lang['Store_inventory_delete'],
                'L_YES' => $lang['Yes'],
                'L_NO' => $lang['No'],

                'S_CONFIRM_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_list.'.$phpEx.'?mode=inventory'),
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

            $message = $lang['Store_inventory_deleted'] . "<br /><br />" . sprintf($lang['Click_return_storelist'], "<a href=\"" . append_sid("admin_store_list.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

            message_die(GENERAL_MESSAGE, $message);
        }
        break;

    //----------------------------------------------------
    //add inventory
    //
    case "addinventory":
        if ( $submit == '' )
        {
           //display add inventory screen
           $item_list = item_list(0, 'board_add');
           
           $template->set_filenames(array(
               "body" => "admin/store_inventory_add.tpl")
           );
           
           $template->assign_vars(array(
               'L_STORE_TITLE' => $lang['Store_inventory_add'],
               'L_STORE_EXPLAIN' => $lang['Store_inventory_add_explain'],
               
               'L_ITEM' => $lang['Store_item'],
               'L_AMOUNT' => $lang['Store_amount'],
               'L_PRICE' => $lang['Store_price'],
               'L_RESTOCK_TIME' => $lang['Store_restock_time'],
               'L_RESTOCK_AMOUNT' => $lang['Store_restock_amount'],
               
               'ITEM_LIST' => $item_list,
               
               'S_SUBMIT_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_list.'.$phpEx.'?mode=addinventory'),
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
            
            $sql = "INSERT INTO " . STORE_INVENTORY . " ( inventory_item, inventory_store, inventory_price, inventory_amount, restock_time, restock_amount, restock_last )
                VALUES ( '$item', '$id', '$price', '$amount', '$restock_time', '$restock_amount', '" . time() . "' );";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Error adding item to inventory", "", __LINE__, __FILE__, $sql);
            }
            
            $message = $lang['Store_inventory_added'] . "<br /><br />" . sprintf($lang['Click_return_storelist'], "<a href=\"" . append_sid("admin_store_list.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

            message_die(GENERAL_MESSAGE, $message);
        }
        break;
    //----------------------------------------------------
    //edit store's inventory
    //
    case "editinventory":
        if ( $submit == '' )
        {
           //display edit inventory screen
           $sql = "SELECT * FROM " . STORE_INVENTORY . " WHERE inventory_id = $id LIMIT 1";
           if ( !$result = $db->sql_query($sql) )
           {
              message_die(GENERAL_ERROR, "Error getting inventory's information", "", __LINE__, __FILE__, $sql);
           }
           $row = $db->sql_fetchrow($result);

          $item_list = item_list(0, 'board_add', $row['inventory_item']);

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

               'S_SUBMIT_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_list.php?mode=editinventory'),
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

            $message = $lang['Store_inventory_edited'] . "<br /><br />" . sprintf($lang['Click_return_storelist'], "<a href=\"" . append_sid("admin_store_list.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

            message_die(GENERAL_MESSAGE, $message);
        }
        break;

    //----------------------------------------------------
    //display store list
    //
    default:
    
       //display list of stores
       $template->set_filenames(array(
           "body" => "admin/store_body.tpl")
       );
       
       $template->assign_vars(array(
          'L_STORE_TITLE' => $lang['Store_list'],
          'L_STORE_EXPLAIN' => $lang['Store_list_explain'],

          'L_ACTION' => $lang['Store_action'],
          'L_STORE' => $lang['Store'],
          'L_OWNER' => $lang['Store_owner'],
          'L_ITEMS' => $lang['Store_items'],
          'L_CASH' => $lang['Store_cash'],

          'S_NEW_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_list.'.$phpEx.'?mode=new'),
          'S_NEW_VALUE' => $lang['Store_create'])
       );
       
       $sql = "SELECT * FROM " . STORE_LIST . " ORDER BY store_name";
       if ( !$result = $db->sql_query($sql) )
       {
           message_die(GENERAL_ERROR, "Could not get store list", "", __LINE__, __FILE__, $sql);
       }
       
       //loop through stores
	     while ( $row = $db->sql_fetchrow($result) )
	     {
           $store_id = $row['store_id'];
           $store_name = stripslashes(trim($row['store_name']));
           $temp_url = append_sid($phpbb_root_path . 'store.'.$phpEx.'?store='.$store_id);
           $store = '<a href="' . $temp_url . '" class="forumlink" target="_blank">' . $store_name . '</a>';
           $owner = get_owner($row['owner_id']);
           $store_description = stripslashes(trim($row['store_description']));
           
           $cash_id = $row['cash_id'];
           $sql2 = "SELECT cash_name FROM " . CASH_TABLE . " WHERE cash_id = $cash_id LIMIT 1";
           if ( !$result2 = $db->sql_query($sql2) )
           {
              message_die(GENERAL_ERROR, "Could not get cash name", "", __LINE__, __FILE__, $sql);
           }
           $cash = $db->sql_fetchrow($result2);
           
           //delete link
           $temp_url = append_sid($phpbb_root_path . 'admin/admin_store_list.'.$phpEx.'?mode=delete&id=' . $store_id);
           $delete = '<a href="' . $temp_url . '" class="gen">' . $lang['Delete'] . '</a>';
           
           //edit link
           $temp_url = append_sid($phpbb_root_path . 'admin/admin_store_list.'.$phpEx.'?mode=edit&id=' . $store_id);
           $edit = '<a href="' . $temp_url . '" class="gen">' . $lang['Edit'] . '</a>';
           
           //inventory link
           $temp_url = append_sid($phpbb_root_path . 'admin/admin_store_list.'.$phpEx.'?mode=inventory&id=' . $store_id);
           $inventory = '<a href="' . $temp_url . '" class="gen">' . $lang['Store_inventory'] . '</a>';
           
           //count number of items in store
           $sql3 = "SELECT COUNT(inventory_id) AS total FROM " . STORE_INVENTORY . " WHERE inventory_store = $store_id";
           if ( !$resul3 = $db->sql_query($sql3) )
           {
               message_die(GENERAL_ERROR, "Could not get number of items", "", __LINE__, __FILE__, $sql);
           }
           $items = $db->sql_fetchrow($result3);
           $item_number = $items['total'];
           
           $template->assign_block_vars('store_row', array(
               'STORE_NAME' => $store,
               'STORE_DESCRIPTION' => $store_description,
               'STORE_OWNER' => $owner,
               'ITEMS' => $item_number,
               'CASH' => $cash['cash_name'],

               'DELETE' => $delete,
               'EDIT' => $edit,
               'INVENTORY' => $inventory)
           );
       }
       
       break;
}



$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
