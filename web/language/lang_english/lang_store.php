<?php
/***************************************************************************
 *                          lang_store.php
 *                            -------------------
 *   begin                : Thrusday July 31, 2003
 *   copyright            : (C) 2003 wGeric
 *   email                : eric@best-1.biz
 *
 *   $Id: lang_store.php,v 1.1 2004/01/03 00:55:33 wgeric Exp $
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

//titles and sub-titles
$lang['Store_list'] = 'Store List';
$lang['Store_list_explain'] = 'Here you can see a list of all of the stores on your board.';
$lang['Store_edit'] = 'Edit Store';
$lang['Store_edit_explain'] = 'Make the changes to the store in the form to edit it';
$lang['Store_delete_title'] = 'Delete Store';
$lang['Store_inventory'] = 'Inventory';
$lang['Store_inventory_explain'] = 'Here you can see the store\'s inventory';
$lang['Store_delete_item_title'] = 'Delete Item';
$lang['Store_edit_item'] = 'Edit Item';
$lang['Store_edit_item_explain'] = 'Make changes to the form below to edit the item';
$lang['Store_inventory_add'] = 'Add Inventory';
$lang['Store_inventory_add_explain'] = 'Here you can add inventory to a story by filling out the form';
$lang['Store_inventory_edit'] = 'Edit Inventory';
$lang['Store_inventory_edit_explain'] = 'Edit the inventory\'s details by making the changes in the form below';
$lang['Store_items_acp'] = 'Store Items';
$lang['Store_items_explain'] = 'Here you can see a list of all of the items on your board';
$lang['Store_inventory_delete_title'] = 'Delete Inventory';
$lang['Store_create'] = 'Create store';
$lang['Store_create_explain'] = 'Fill out the form to create a new store for your board';
$lang['Store_users'] = 'User\'s Inventory';
$lang['Store_users_explain'] = 'Here you can edit, delete, and add items to a user\'s inventory';
$lang['Store_user_inventory_add'] = 'Add User Inventory';
$lang['Store_user_inventory_add_explain'] = 'Fill out the form to add a new item to a user\'s inventory.<br /><b>It is recommended that you don\'t put anything for restock time and restock amount</b>';


//click returns
$lang['Click_return_storelist'] = 'Click %shere%s to return to the store list';
$lang['Click_return_storelist_new'] = 'Click %shere%s to return to creating a store';
$lang['Click_return_itemlist'] = 'Click %shere%s to return to the item list';
$lang['Click_return_store'] = 'Click %shere%s to return to the store\'s inventory';
$lang['Click_return_store_user_items'] = 'Click %shere%s to return to your items';
$lang['Click_return_store_users'] = 'Click %shere%s to return to user\'s inventory';


//success
$lang['Store_created'] = 'Store Created Successfully!';
$lang['Store_updated'] = 'Store Updated Successfully!';
$lang['Store_deleted'] = 'Store Deleted Successfully!';
$lang['Store_inventory_added'] = 'Inventory Added Successfully!';
$lang['Store_inventory_deleted'] = 'Inventory Deleted Successfully!';
$lang['Store_inventory_edited'] = 'Inventory Edited Successfully!';
$lang['Store_item_created'] = 'Item Created Successfully!';
$lang['Store_item_updated'] = 'Item Updated Successfully!';
$lang['Store_deleted_item'] = 'Items Deleted Successfully';
$lang['Store_item_purchased'] = 'Item Purchased Successfully!';
$lang['Store_items_moved'] = 'Item(s) Moved Successfully!';
$lang['Store_items_priced'] = 'Item(s) Priced Changed Successfully!';
$lang['Store_user_inventory_added'] = 'Item Added to User\'s Inventory Successfully!';


//errors
$lang['Store_select_cash'] = 'Please select a Cash for a store';
$lang['Store_error_cant_buy'] = 'Can\'t buy item because you don\'t have enough cash or you were trying to buy a bad amount of the item';
$lang['Store_error_select_store_items'] = 'Please select a Store to move the items into or select the items to move';
$lang['Store_cant_create'] = 'Can\'t create store since you have the max number of stores you can own';


//confirm questions
$lang['Store_delete'] = 'Do you really want to delete this store?';
$lang['Store_delete_item'] = 'Do you really want to delete these items?';
$lang['Store_inventory_delete'] = 'Do you really want to remove the selected inventory from this store?';
$lang['Store_user_inventory_delete'] = 'Do you really want to remove the selected inventory from this user?';


//config
$lang['Store_user_stores'] = 'Allowed user stores';
$lang['Store_user_stores_explain'] = 'This is the number of stores a user is allowed to have. Zero (0) is none';
$lang['Store_max_buy'] = 'Max items can buy';
$lang['Store_max_buy_explain'] = 'This is the max number of one item a user can buy at a time';
$lang['Store_sell_special'] = 'Sell special items';
$lang['Store_sell_special_explain'] = 'Yes lets users sell special items, No doesn\'t let them sell their special items';
$lang['Store_view_profile'] = 'Link to user\'s items in profile?';
$lang['Store_view_profile_explain'] = 'Displays a link to the user\'s items in their profile if set to on';
$lang['Store_view_topic'] = 'Link to user\'s items in topic?';
$lang['Store_view_profile_topic'] = 'Displays a link to the user\'s items under their cash in a topic';

//misc
$lang['Store_name'] = 'Store name';
$lang['Store_description'] = 'Description';
$lang['Store_remove_inventory'] = 'Remove Selected Items';
$lang['Store_add_inventory'] = 'Add Items to Inventory';
$lang['Store_restock_time'] = 'Restock Time (hours)';
$lang['Store_restock_amount'] = 'Restock Amount';
$lang['Store_image'] = 'Image';
$lang['Store_delete_items'] = 'Delete Selected Items';
$lang['Store_create_item'] = 'Create Item';
$lang['Store_create_item_explain'] = 'Fill out the form to create an item';
$lang['Edit'] = 'Edit';
$lang['Store_user_owned'] = 'User Owned Stores';
$lang['Store_board_owned'] = 'Board Owned Stores';
$lang['Store_you_owned'] = 'Stores You Own';
$lang['Store_buy'] = 'Buy';
$lang['Store_action'] = 'Action';
$lang['Store'] = 'Store';
$lang['Store_owner'] = 'Owner';
$lang['Store_items'] = 'Items';
$lang['Store_item'] = 'Item';
$lang['Store_users_items'] = 'User\'s Items';
$lang['Store_users_items_goto'] = 'View user\'s items';
$lang['Store_amount'] = 'Amount';
$lang['Store_price'] = 'Price';
$lang['Store_special'] = 'Special';
$lang['Store_cash'] = 'Store\'s Cash';
$lang['Store_your_cash'] = 'Your Cash';
$lang['Store_board'] = 'Board';
$lang['Store_move_user'] = 'Move selected items to selected store';
$lang['Store_move_items'] = 'Move Items';

$lang['Store_user_items'] = 'Your Items';
$lang['Store_items_link'] = 'Store\'s Items';
$lang['Store_price_items'] = 'Edit Item(s) Price';

?>
