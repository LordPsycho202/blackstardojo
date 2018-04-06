<?php

/***************************************************************************
 *                            mod_store.php
 *                            ---------------
 *	begin			: 30 August 2003
 *	copyright		: eric
 *	email			: eric@egcnetwork.com
 *	version			: 1.0.0 - 30/08/2003
 *
 *	mod version		: store v 1.0.0
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

// service functions
include_once( $phpbb_root_path . 'includes/functions_mods_settings.' . $phpEx );
include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_store.'.$phpEx);

// mod definition
$mod_name = 'Store_MOD';				// $lang[] entry : will be the sub-menu option name
$config_fields = array(

  //max number of stores a user can buy
	'store_user_stores' => array(
		'lang_key'	=> 'Store_user_stores',
		'explain'	=> 'Store_user_stores_explain',
		'type'		=> 'INT',
		'default'	=> '1',
	),
		
  //max number of items can buy at a time	
	'store_max_buy' => array(
		'lang_key'	=> 'Store_max_buy',
		'explain'	=> 'Store_max_buy_explain',
		'type'		=> 'INT',
		'default'	=> '50',
	),
			
	//sell special items
  'store_sell_special' => array(
		'lang_key'	=> 'Store_sell_special',
		'explain'	=> 'Store_sell_special_explain',
		'type'		=> 'LIST_RADIO',
		'default'	=> '0',
		'values'	=> array(
		  'Yes' => 1,
		  'No' => 0,
    ),
	),
	
	//display link to users items in profile
  'store_view_profile' => array(
		'lang_key'	=> 'Store_view_profile',
		'explain'	=> 'Store_view_profile_explain',	
		'type'		=> 'LIST_RADIO',
		'default'	=> '1',
		'values'	=> array(
		  'Yes' => 1,
		  'No' => 0,
    ),
	),
	
	//display link to users items in topic
  'store_view_topic' => array(
		'lang_key'	=> 'Store_view_topic',
		'explain'	=> 'Store_view_profile_topic',
		'type'		=> 'LIST_RADIO',
		'default'	=> '1',
		'values'	=> array(
		  'Yes' => 1,
		  'No' => 0,
    ),
	),
	
	//version number
	'store_version' => array(
	 'lang_key'	=> 'Store Version',
	 'type' => 'VARCHAR',
	 'default' => '0.0.4'
  ),
	
	
);

// init config table
init_board_config($mod_name, $config_fields);

?>
