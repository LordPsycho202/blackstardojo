<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                              admin_store_items.php
 *                            -------------------
 *   begin                : Friday, 01 August, 2003
 *   copyright            : (C) 2003 wGEric
 *   email                : eric@best-1.biz
 *
 *   $Id: admin_store_items.php,v 1.1 2004/01/03 00:55:33 wgeric Exp $
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
	$module['Store_MOD']['Store_Items'] = "$file";
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
    //create new item
    //
    case "new":
        //set up varibles and submit info
        $new_item_name = trim($HTTP_POST_VARS['new_store']);

        if ( $submit == '' )
        {

			$type = '<select name="type"><option value="0">Normal Item</option><option value="1">Special Item</option><option value="2">Board Owned Service</option><option value="3">User Owned Service</option></select>';
			$user = '<select name="user"><option value="NO_MEMBER_SELECTED">Select a member</option>';

			$sql = "SELECT username, user_id 
			FROM " . USERS_TABLE . "
			WHERE user_active = '1'
			ORDER BY username";
			

			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not query users', '', __LINE__, __FILE__, $sql);
			}
			while($row=$db->sql_fetchrow($result)) {

				$user .= '<option value="'.$row['user_id'].'">'.$row['username'].'</option>
				';
			}
			$user .= '</select>';
            //display create store form
            $template->set_filenames(array(
               "body" => "admin/store_edit_body.tpl")
            );

            $template->assign_block_vars('switch_item', array());

            $template->assign_vars(array(
              'L_CREATE_STORE' => $lang['Store_create_item'],
              'L_STORE_EXPLAIN' => $lang['Store_create_item_explain'],
              'L_STORE_NAME' => $lang['Store_item'],
              'L_STORE_DESCRIPTION' => $lang['Store_description'],
              'L_ITEM_IMAGE' => $lang['Store_image'],
              
              'L_YES' => $lang['Yes'],
              'L_NO' => $lang['No'],
			  'TYPE_SELECT' =>  $type,
  			  'L_TYPE' => 'Item type',
              'S_NEW_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_items.'.$phpEx.'?mode=new'),
			  'USER_FIELD' => $user,
			  'L_USERFIELD' => 'User (required for User-owned services)',
              'STORE_NAME' => htmlspecialchars(stripslashes($new_item_name))));
        }
        else
        {
            $new_item_description = str_replace("\'", "''", trim($HTTP_POST_VARS['store_description']));
            $new_item_image = trim($HTTP_POST_VARS['item_image']);
            $new_item_special = $HTTP_POST_VARS['special'];
			$new_item_type = $HTTP_POST_VARS['type'];
			$user = $HTTP_POST_VARS['user'];
			if(($user == "NO_MEMBER_SELECTED") && ($new_item_type=="3")) {
				$message = "You must have set the user for a user owned service!";
				message_die(GENERAL_MESSAGE,$message);
			} else {
				//create the store
				$sql = "INSERT INTO " . STORE_ITEMS . " ( item_name, item_description, item_image, item_type,item_user  )
					VALUES ( '" . str_replace("\'", "''", $new_item_name) . "', '$new_item_description', '$new_item_image', '$new_item_type','$user' )";
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Error creating item", "", __LINE__, __FILE__, $sql);
				}
				else
				{
					$message = $lang['Store_item_created'] . "<br /><br />" . sprintf($lang['Click_return_itemlist'], "<a href=\"" . append_sid("admin_store_items.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

					  message_die(GENERAL_MESSAGE, $message);
				}
			}
        }
        break;
        
    //----------------------------------------------------
    //edit item
    //
    case "edit":
        if ( $submit == '' )
        {
		$sql = "SELECT * FROM " . STORE_ITEMS . " WHERE item_id = $id";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Error getting item information", "", __LINE__, __FILE__, $sql);
            }
            $row = $db->sql_fetchrow($result);
            $item_name = $row['item_name'];
            $item_description = $row['item_description'];
            $item_image = $row['item_image'];
			$item_user = $row['item_user'];
		$type = '
		<select name="type"><option value="0"'.(($row['item_type'] == 0)?' selected="selected"':'').'>Normal Item</option>
		<option value="1"'.(($row['item_type'] == 1)?' selected="selected"':'').'>Special Item</option>
		<option value="2"'.(($row['item_type'] == 2)?' selected="selected"':'').'>Board Owned Service</option>
		<option value="3"'.(($row['item_type'] == 3)?' selected="selected"':'').'>User Owned Service</option></select>';
			$user = '<select name="user"><option value="NO_MEMBER_SELECTED">Select a member</option>';

			$sql = "SELECT username, user_id 
			FROM " . USERS_TABLE . "
			WHERE user_active = '1'
			ORDER BY username";
			

			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not query users', '', __LINE__, __FILE__, $sql);
			}
			while($row=$db->sql_fetchrow($result)) {
				$user .= '<option value="'.$row['user_id'].'"'.(($row['user_id']==$item_user)?' selected="selected"':'').'">'.$row['username'].'</option>
				';
			}
			$user .= '</select>';

            
            

            //display create store form
            $template->set_filenames(array(
               "body" => "admin/store_edit_body.tpl")
            );

            $template->assign_block_vars('switch_item', array());

            $template->assign_vars(array(
              'L_CREATE_STORE' => $lang['Store_edit_item'],
              'L_STORE_EXPLAIN' => $lang['Store_edit_item_explain'],
              'L_STORE_NAME' => $lang['Store_item'],
              'L_STORE_DESCRIPTION' => $lang['Store_description'],
              'L_ITEM_IMAGE' => $lang['Store_image'],
			  'TYPE_SELECT' =>  $type,
  			  'L_TYPE' => 'Item type',
              'S_NEW_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_items.'.$phpEx.'?mode=edit'),
              'S_HIDDEN_FIELDS' => '<input type="hidden" name="id" value="' . $id . '">',
			  'USER_FIELD' => $user,
			  'L_USERFIELD' => 'User (required for User-owned services)',
              'STORE_NAME' => htmlspecialchars(stripslashes(trim($item_name))),
              'STORE_DESCRIPTION' => stripslashes(trim($item_description)),
              'ITEM_IMAGE' => stripslashes(trim($item_image)),
              'SPECIAL_YES' => $special_yes,
              'SPECIAL_NO' => $special_no)
            );
        }
        else
        {
            $new_item_name = trim($HTTP_POST_VARS['new_store']);
            $new_item_description = str_replace("\'", "''", trim($HTTP_POST_VARS['store_description']));
            $new_item_image = trim($HTTP_POST_VARS['item_image']);
            $new_item_type = $HTTP_POST_VARS['type'];
			$new_item_user = $HTTP_POST_VARS['user'];
            //edit
			if(($new_item_user == "NO_MEMBER_SELECTED") && ($new_item_type=="3")) {
				$message = "You must have set the user for a user owned service!";
				message_die(GENERAL_MESSAGE,$message);
			} else {
				$sql = "UPDATE " . STORE_ITEMS . " SET
				  item_name = '" . str_replace("\'", "''", $new_item_name) . "',
				  item_description = '$new_item_description',
				  item_image = '$new_item_image',
				  item_type = '$new_item_type',
				  item_user = '$new_item_user'
				  WHERE item_id = $id";
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Error updating item", "", __LINE__, __FILE__, $sql);
				}
				else
				{
					$message = $lang['Store_item_updated'] . "<br /><br />" . sprintf($lang['Click_return_itemlist'], "<a href=\"" . append_sid("admin_store_items.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

					  message_die(GENERAL_MESSAGE, $message);
				}
			}
			
        }
        break;

    //----------------------------------------------------
    //delete item
    //
    case "delete":
        $ids = $id;
        //see if it has been confirmed
        if ( $confirm == '' )
        {
            //display confirm
            $template->set_filenames(array(
               "body" => "confirm_body.tpl")
            );

            $i = 0;
            $hiddenfields = '';
            while ( $i < count($ids) )
            {
                $hiddenfields .= '<input type="hidden" name="id[]" value="' . $ids[$i] . '">';
                $i++;
            }

            $template->assign_vars(array(
                'MESSAGE_TITLE' => $lang['Store_delete_item_title'],
                'MESSAGE_TEXT' => $lang['Store_delete_item'],
                'L_YES' => $lang['Yes'],
                'L_NO' => $lang['No'],

                'S_CONFIRM_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_items.'.$phpEx.'?mode=delete'),
                'S_HIDDEN_FIELDS' => $hiddenfields)
            );
        }
        else
        {
            $i = 0;
            while ( $i < count($ids) )
            {
                $id = $ids[$i];
            
                //delete store and items in it
                $sql = "DELETE FROM " . STORE_INVENTORY . " WHERE inventory_item = $id";
                if ( !$result = $db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, "Error deleting inventory for store's with this item", "", __LINE__, __FILE__, $sql);
                }
                $sql = "DELETE FROM " . STORE_ITEMS . " WHERE item_id = $id LIMIT 1";
                if ( !$result = $db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, "Error deleting item", "", __LINE__, __FILE__, $sql);
                }
                $i++;
            }
            $message = $lang['Store_deleted_item'] . "<br /><br />" . sprintf($lang['Click_return_itemlist'], "<a href=\"" . append_sid("admin_store_items.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

            message_die(GENERAL_MESSAGE, $message);

        }
        break;
        
    //----------------------------------------------------
    //display item list
    //
    default:

        $template->set_filenames(array(
            "body" => "admin/store_item_body.tpl")
        );
        
        $template->assign_vars(array(
            'L_STORE_TITLE' => $lang['Store_items_acp'],
            'L_STORE_EXPLAIN' => $lang['Store_items_explain'],
            
            'L_ACTION' => $lang['Store_action'],
            'L_ITEM' => $lang['Store_item'],
            'L_IMAGE' => $lang['Store_image'],
            'L_SPECIAL' => $lang['Store_special'],
            
            'S_DELETE_ACTION' => append_sid($phpbb_root_path . 'admin/admin_store_items.'.$phpEx.'?mode=delete'),
            'S_DELETE_VALUE' => $lang['Store_delete_items'],

            'S_CREATE_ACTION' => append_sid( $phpbb_root_path . 'admin/admin_store_items.'.$phpEx.'?mode=new'),
            'S_CREATE_VALUE' => $lang['Store_create_item'])
        );

        $sql = "SELECT * FROM " . STORE_ITEMS . " ORDER BY item_name";
        if ( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, "Could not get store list", "", __LINE__, __FILE__, $sql);
        }

        //loop through items
	      while ( $row = $db->sql_fetchrow($result) )
	      {
	          $item_id = $row['item_id'];
	          $item_name = stripslashes(trim($row['item_name']));
	          $item_description = stripslashes(trim($row['item_description']));
	          $item_image = $row['item_image'];
	
	          if ( $row['item_type'] == 1 )
	          {
	             $special = $lang['Yes'];
            }
            else
            {
                $special = $lang['No'];
            }
	          
	          //image
            if ( $item_image != '' )
	          {
	              $item_image = '<img src="' . $item_image . '">';
            }
            
            //edit
            $temp_url = append_sid($phpbb_root_path . 'admin/admin_store_items.'.$phpEx.'?mode=edit&id=' . $item_id);
            $edit = '<a href="' . $temp_url . '" class="gen">' . $lang['Edit'] . '</a>';
            
            $template->assign_block_vars('item_row', array(
                'ID' => $item_id,
                'NAME' => $item_name,
                'DESCRIPTION' => $item_description,
                'IMAGE' => $item_image,
                'EDIT' => $edit,
                'SPECIAL' => $special)
            );
        }
	      
        break;
}

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
