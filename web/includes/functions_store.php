<?php
/***************************************************************************
 *                          functions_store.php
 *                            -------------------
 *   begin                : Thrusday July 31, 2003
 *   copyright            : (C) 2003 wGeric
 *   email                : eric@best-1.biz
 *
 *   $Id: functions_store.php,v 1.1 2004/01/03 00:55:33 wgeric Exp $
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
 
  //---------------------------------------------------------
 //get_owner($id)
 //gets the owner of an item or store
 //
 function get_owner($id)
 {
     global $db, $phpEx, $phpbb_root_path, $lang;
     
     if( $id == BOARD_OWNED )
     {
         $owner = $lang['Store_board'];
     }
     else
     {
         $sql = "SELECT username FROM " . USERS_TABLE . " WHERE user_id = ". $id;
         if ( !$result = $db->sql_query($sql) )
         {
             message_die(GENERAL_ERROR, "Could not get owner", "", __LINE__, __FILE__, $sql);
         }
         $row = $db->sql_fetchrow($result);
     
         $temp_url = append_sid($phpbb_root_path . 'profile.php?mode=viewprofile&u=' . $id);
         $owner = '<a href="' . $temp_url . '" class="gen" target="_blank">' . $row['username'] . '</a>';
     }
     
     return $owner;
} //get_owner()
 
//---------------------------------------------------------
//item_list($mode = '', $id = false, $item_id = false )
//gets the items for a user and puts them in a drop down list
//OR gets the items with a return type of $id OR some other
//thing but I forget what. Bloody useful function though.
//
function item_list($mode = '', $id = false, $itemid = false)
{
    global $db, $template, $userdata, $store_info, $lang, $phpEx, $phpbb_root_path;

    switch ( $mode )
    {
        case "board_add":

            $list = '<select name="item" class="post">';

            $sql = "SELECT * FROM " . STORE_ITEMS . " ORDER BY item_name";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "error getting items", "", __LINE__, __FILE__, $sql);
            }
            while ( $row = $db->sql_fetchrow($result) )
            {
                if ($itemid == $row['item_id'] ) //MONKEY BRAINS
                {
                    $status = 'selected="selected"';
                }
                else
                {
                    $status = '';
                }
                $list .= '<option value="' . $row['item_id'] . '" ' . $status . '>' . stripslashes(htmlspecialchars(trim($row['item_name']))) . '</option>';
            }
            
            $list .= '</select>';
            return $list;
            break;
            
		case 'returntypeid':
			$list = '<select name="service" class="post">';
			$sql = "SELECT * FROM phpbb_store_items ORDER BY item_name";
			//echo $sql;
			if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "error getting items", "", __LINE__, __FILE__, $sql);
            }
			$list .= '<option value="suckage">Please select a service</option>';
			while ( $row = $db->sql_fetchrow($result) )
			{

				if($id == 3 && $row['item_user'] == $itemid) { //using id as typeid and itemid as userid.
				//echo $row['item_name'];
					$list .= '<option value="' . $row['item_id'] . '" ' . $status . '>' . 		stripslashes(htmlspecialchars(trim($row['item_name']))) . '</option>';
				} else if($id != 3) {
					$list .= '<option value="' . $row['item_id'] . '" ' . $status . '>' . 		stripslashes(htmlspecialchars(trim($row['item_name']))) . '</option>';
				}
			}
			$list .= "</select>";
			return $list;
			break;
        case 'store':

            //display inventory in store
            $sql = "SELECT * FROM " . STORE_INVENTORY . " WHERE inventory_store = $id";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Error getting store's inventory", "",__LINE__,__FILE__, $sql);
            }
            while ( $inven = $db->sql_fetchrow($result) )
            {
                $inventory_id = $inven['inventory_id'];
                $inventory_amount = $inven['inventory_amount'];
                $inventory_price = $inven['inventory_price'];
                
                $restock_time = $inven['restock_time'];
                $restock_amount = $inven['restock_amount'];
                $restock_last = $inven['restock_last'];
                
                $current_time = time();
                $temp = $current_time - $restock_last;
                if ( $temp >= $restock_time && $restock_time != '' && $restock_time != 'NULL' && !$restock_time && $restock_time != '0')
                {
                    $i = 0;
                    while ( $temp >= 0 )
                    {
                      $temp = $temp - $restock_time;
                      $i++;
                    }
                    $inventory_amount = $inventory_amount + ($restock_amount * $i);
                    store_restock($inventory_id, $inventory_amount, $current_time);
                }
                
                $inventory_item = $inven['inventory_item'];
                $items = item_info($inventory_item);
                $item_name = stripslashes(trim($items['item_name']));
                $item_description = stripslashes(trim($items['item_description']));

                if ( $userdata['user_id'] == $store_info['owner_id'] && $store_info['owner_id'] != BOARD_OWNED )
                {
                  //edit link
                  $temp_url = append_sid($phpbb_root_path . 'admin/admin_store_list.'.$phpEx.'?mode=editinventory&id='.$inventory_id);
                  $edit = '<a href="' . $temp_url . '" class="gen">' . $lang['Edit'] . '</a>';
                }
                else
                {
                  $edit = '';
                }
				
				$guildwar = ($userdata['user_style'] == 2)?true:false;
                $template->assign_block_vars('item_row', array(
                    'ID' => $inventory_id,
                    'ITEM' => $item_name,
                    'DESCRIPTION' => $item_description,
					'SPECIAL' => ($items['item_type']=="1")?'<img src="http://blackstar.inkwell.com.ru/images/special'. (($guildwar==true)?'_trans':'').'.gif'.'" />':'',
                    'AMOUNT' => $inventory_amount,
                    'PRICE' => $inventory_price,
                    'EDIT' => $edit,
					'IMAGE' => $items['item_image'])
                );
                
                if ( $userdata['user_id'] == $store_info['owner_id'] && $store_info['owner_id'] != BOARD_OWNED )
                {
                  $template->assign_block_vars('item_row.switch_owner', array());
                }
                else
                {
                  $template->assign_block_vars('item_row.switch_not_owner', array());
                }
            }
            break;

        case 'admin':
            //display users inventory
            $sql = "SELECT * FROM " . STORE_INVENTORY . " WHERE inventory_user = $id";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Error getting user's inventory", "",__LINE__,__FILE__, $sql);
            }
            while ( $inven = $db->sql_fetchrow($result) )
            {
                $inventory_id = $inven['inventory_id'];
                $inventory_amount = $inven['inventory_amount'];
                $inventory_price = $inven['inventory_price'];

                $inventory_item = $inven['inventory_item'];
                $items = item_info($inventory_item);
                $item_name = stripslashes(trim($items['item_name']));
                $item_description = stripslashes(trim($items['item_description']));

                //edit link
                $temp_url = append_sid($phpbb_root_path . 'admin/admin_store_users.'.$phpEx.'?mode=edit&amp;id='.$inventory_id);
                $edit = '<a href="' . $temp_url . '" class="gen">' . $lang['Edit'] . '</a>';
				$guildwar = ($userdata['user_style'] == 2)?true:false;
                $template->assign_block_vars('item_row', array(
                    'ID' => $inventory_id,
                    'ITEM' => $item_name,
                    'DESCRIPTION' => $item_description,
					'SPECIAL' => ($items['item_type']=="1")?'<img src="http://blackstar.inkwell.com.ru/images/special'. (($guildwar==true)?'_trans':'').'.gif'.'" />':'',
                    'AMOUNT' => $inventory_amount,
                    'PRICE' => $inventory_price,
                    'EDIT' => $edit)
                );
            }
            break;

        default:
            //display users inventory
            $sql = "SELECT * FROM " . STORE_INVENTORY . " WHERE inventory_user = $id";
            if ( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Error getting user's inventory", "",__LINE__,__FILE__, $sql);
            }
            while ( $inven = $db->sql_fetchrow($result) )
            {
				$inventory_id = $inven['inventory_id'];
                $inventory_amount = $inven['inventory_amount'];

                $inventory_item = $inven['inventory_item'];
                $items = item_info($inventory_item);
                $item_name = stripslashes(trim($items['item_name']));
                $item_description = stripslashes(trim($items['item_description']));

                if ( $userdata['user_id'] == $id || $userdata['user_level'] == ADMIN )
                {
                  //delete link
                  $temp_url = append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=deleteinventory&id='.$inventory_id);
                  $delete = '<a href="' . $temp_url . '" class="gen">' . $lang['Delete'] . '</a>';
                  $edit = '';
                  //edit link
                  if ( $userdata['user_level'] == ADMIN )
                  {
                    $temp_url = append_sid($phpbb_root_path . 'admin/admin_store_users.'.$phpEx.'?id='.$inventory_id);
                    $edit = '<a href="' . $temp_url . '" class="gen">' . $lang['Edit'] . '</a>';
                  }
                  
                }
                else
                {
                  $delete = '';
                }
				$donate = ''; $spesh = false;
				$useserv= "";
				if($items['item_type'] == "1") {
					$spesh = true;
				} else if($items['item_type'] == "3" || $items['item_type'] == "2") {
					//service link
					if($userdata['user_id'] == $id) {
						$temp_url = append_sid($phpbb_root_path.'store.'.$phpEx.'?mode=addmessagetoservice&orig_user_id='.$id.'&item='.$inventory_id);
						$useserv = '<a href="' . $temp_url.'" class="gen">Use this service</a><br />';
					}
				} 
				$guildwar = ($userdata['user_style'] == 2 || $userdata['user_style'] == 4)?true:false;
				$trans = ($guildwar==true)?'_trans':'';
				if($spesh==true) {
					$template->assign_block_vars('item_row', array(
						'ID' => $inventory_id,
						'ITEM' => $item_name,
						'DESCRIPTION' => $item_description.'<img src="http://blackstar.inkwell.com.ru/images/special'.$trans .'.gif'.'" />',
						'AMOUNT' => $inventory_amount,
						'DELETE' => $edit.'<br />'.$useserv.$delete)
					);
				} else {
					$template->assign_block_vars('item_row', array(
						'ID' => $inventory_id,
						'ITEM' => $item_name,
						'DESCRIPTION' => $item_description,
						'AMOUNT' => $inventory_amount,
						'DELETE' => $edit.'<br />'.$useserv.$delete)
					);
				}
                if ( $userdata['user_id'] == $id || $userdata['user_level'] == ADMIN )
                {
                  $template->assign_block_vars('item_row.switch_owner', array());
                  
                  if ( $userdata['user_level'] == ADMIN )
                  {
                    $template->assign_block_vars('item_row.switch_owner.switch_admin', array());
                  }
                }
            }
            break;
    }
    return;
 } //item_list()
 
//---------------------------------------------------------
//get_stores($mode, $block, $id = 0)
//gets stores and puts them in a block
//
function get_stores($mode, $block, $id = 0)
{
  global $db, $template, $lang, $userdata, $phpEx;
  
  switch($mode)
  {
    case 'owned':
      $where_sql = " WHERE owner_id = $id";
      break;
      
    case 'board':
      $where_sql = " WHERE owner_id = " . BOARD_OWNED;
      break;
    case 'user':
      $where_sql = " WHERE owner_id <> " . BOARD_OWNED;
      break;
  } //switch
  
  $sql = "SELECT * FROM " . STORE_LIST . $where_sql;
  if ( !$result = $db->sql_query($sql) )
  {
    message_die(GENERAL_ERROR, "error getting stores the user owns", "", __LINE__, __FILE__, $sql);
  }
  while ( $row = $db->sql_fetchrow($result) )
  {
    $store_id = $row['store_id'];
    $store_name = stripslashes(trim($row['store_name']));
    $temp_url = append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=store_items&id='.$store_id);
    $store = '<a href="' . $temp_url . '" class="forumlink">' . $store_name . '</a>';
    $owner = get_owner($row['owner_id']);
    $store_description = stripslashes(trim($row['store_description']));
    
    $cash_id = $row['cash_id'];
    $sql3 = "SELECT cash_name FROM " . CASH_TABLE . " WHERE cash_id = $cash_id LIMIT 1";
    if ( !$result3 = $db->sql_query($sql3) )
    {
      message_die(GENERAL_ERROR, "Could not get cash name", "", __LINE__, __FILE__, $sql);
    }
    $cash = $db->sql_fetchrow($result3);
    
    if ( $userdata['session_logged_in'] && $userdata['user_id'] == $row['owner_id'] )
    {
      //delete link
      $temp_url = append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=delete_store&id=' . $store_id);
      $delete = '&nbsp;<a href="' . $temp_url . '" class="genmed">' . $lang['Delete'] . '</a>&nbsp;';

      //edit link
      $temp_url = append_sid($phpbb_root_path . 'store.'.$phpEx.'?mode=edit_store&id=' . $store_id);
      $edit = '&nbsp;<a href="' . $temp_url . '" class="genmed">' . $lang['Edit'] . '</a>&nbsp;';
    }
    else
    {
      $delete = '';
      $edit = '';
      $inventory = '';
    }

    //count number of items in store
    $sql = "SELECT COUNT(inventory_id) AS total FROM " . STORE_INVENTORY . " WHERE inventory_store = $store_id";
    if ( !$resul2 = $db->sql_query($sql) )
    {
      message_die(GENERAL_ERROR, "Could not get number of items", "", __LINE__, __FILE__, $sql);
    }
    $items = $db->sql_fetchrow($result2);
    $item_number = $items['total'];

    $template->assign_block_vars($block, array(
        'STORE_NAME' => $store,
        'STORE_DESCRIPTION' => $store_description,
        'STORE_OWNER' => $owner,
        'ITEMS' => $item_number,
        'CASH' => $cash['cash_name'],

        'DELETE' => $delete,
        'EDIT' => $edit)
    );
  }
  return array($template, $store);
  
} //get_stores()

//--------------------------------------------------
//store_info($id)
//gets stores information
//
function store_info($id)
{
    global $db;
    
    $sql = "SELECT * FROM " . STORE_LIST . " WHERE store_id = $id LIMIT 1";
    if ( !$result = $db->sql_query($sql) )
    {
      message_die(GENERAL_ERROR, "error getting store's information", "", __LINE__, __FILE__, $sql);
    }
    $store_info = $db->sql_fetchrow($result);
    
    //get cash name
    $cash_id = $store_info['cash_id'];
    $sql2 = "SELECT cash_dbfield, cash_name FROM " . CASH_TABLE . " WHERE cash_id = $cash_id LIMIT 1";
    if ( !$result2 = $db->sql_query($sql2) )
    {
      message_die(GENERAL_ERROR, "Could not get cash name", "", __LINE__, __FILE__, $sql);
    }
    $cash = $db->sql_fetchrow($result2);
    $store_info['cash_field'] = $cash['cash_dbfield'];
    $store_info['cash_name'] = $cash['cash_name'];
    
    return $store_info;
} //store_info();

//--------------------------------------------------
//item_info($id)
//gets item's information
//
function item_info($id)
{
    global $db;

    $sql = "SELECT * FROM " . STORE_ITEMS . " WHERE item_id = $id LIMIT 1";
    if ( !$result = $db->sql_query($sql) )
    {
      message_die(GENERAL_ERROR, "error getting items's information", "", __LINE__, __FILE__, $sql);
    }
    $item_info = $db->sql_fetchrow($result);

    return $item_info;
} //item_info();

//--------------------------------------------------
//inventory_info($id)
//gets inventory info
//
function inventory_info($id)
{
    global $db;

    $sql = "SELECT * FROM " . STORE_INVENTORY . " WHERE inventory_id = $id LIMIT 1";
    if ( !$result = $db->sql_query($sql) )
    {
      message_die(GENERAL_ERROR, "error getting inventory's information", "", __LINE__, __FILE__, $sql);
    }
    $inventory_info = $db->sql_fetchrow($result);

    return $inventory_info;
} //inventory_info();

//--------------------------------------------------
//store_restock($id, $amount,$current_time)
//restocks an inventory item if the time is right
//
function store_restock($id, $amount, $current_time)
{
    global $db;

    $sql = "UPDATE " . STORE_INVENTORY . " SET
        inventory_amount = $amount,
        restock_last = $current_time
        WHERE inventory_id = $id";
        
    if ( !$result = $db->sql_query($sql) )
    {
      message_die(GENERAL_ERROR, "error restock item", "", __LINE__, __FILE__, $sql);
    }

    return;
} //store_restock()

//--------------------------------------------------
//store_update_cash($id, $cash_amount, $cash_field)
//updates user cash when buying an item occurs
//
function store_update_cash($id, $cash_amount, $cash_field)
{
    global $db;

    $sql = "UPDATE " . USERS_TABLE . " SET
        $cash_field = $cash_amount
        WHERE user_id = $id";

    if ( !$result = $db->sql_query($sql) )
    {
      message_die(GENERAL_ERROR, "error updating user's cash", "", __LINE__, __FILE__, $sql);
    }
    return;
} //store_update_cash()

//--------------------------------------------------
//get_user_stores($id)
//gets the user's stores and puts them in a drop down list
//
function get_user_stores($id)
{
    global $db, $lang;

    $sql = "SELECT store_id, store_name FROM " . STORE_LIST . " WHERE owner_id = $id";

    if ( !$result = $db->sql_query($sql) )
    {
      message_die(GENERAL_ERROR, "error getting users stores", "", __LINE__, __FILE__, $sql);
    }
    $store_list = '<option value="-1">' . $lang['Select_one'] . '</option>';
    while ( $row = $db->sql_fetchrow($result) )
    {
      $store_list .= '<option value="' . $row['store_id'] . '">' . $row['store_name'] . '</option>';
    }
    return $store_list;
} //store_update_cash()

?>
