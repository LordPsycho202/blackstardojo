<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                              admin_vault_exchange.php
 *                            ------------------
 *   begin                : 10/01/2004
 *
 *
 ***************************************************************************/

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Vault']['Vault_exchange'] = $file;
	return;
}

define('IN_PHPBB', true);

$phpbb_root_path = '../';
require($phpbb_root_path . 'extension.inc');
require("pagestart.$phpEx");

$template->set_filenames(array(
	'body' => 'admin/config_vault_exchange_body.tpl')
);

$submit = isset($HTTP_POST_VARS['submit']); 

$sql = "SELECT *
FROM " . VAULT_GENERAL_TABLE ;
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query config information in admin_board", "", __LINE__, __FILE__, $sql);
}
while( $row = $db->sql_fetchrow($result) )
{
	$vault[$row['config_name']] = $row['config_value'];
}

if ( $submit )
{
	$use = intval ( $HTTP_POST_VARS['use']);
	$min = intval ( $HTTP_POST_VARS['min']);
	$max = intval ( $HTTP_POST_VARS['max']);
	$time = intval ( $HTTP_POST_VARS['time']);

	$lsql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$use' WHERE config_name = 'stock_use' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 
	$lsql= "UPDATE ". VAULT_GENERAL_TABLE . " SET config_value = '$min' WHERE config_name = 'stock_min_change' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 
	$lsql= "UPDATE ". VAULT_GENERAL_TABLE . " SET config_value = '$max' WHERE config_name = 'stock_max_change' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 	
	$lsql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$time' WHERE config_name = 'stock_time' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 

	vault_update_general_config();
	message_die(GENERAL_MESSAGE, sprintf($lang['Vault_exchange_updated_return_settings'], '<a href="' . append_sid(basename(__FILE__)) . '">', '</a>'), $lang['Vault_settings']);
}

if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
}
else
{
	$mode = "";
}

if( isset($HTTP_POST_VARS['add']) || isset($HTTP_GET_VARS['add']) )
{

	$template->set_filenames(array(
		"body" => 'admin/config_vault_exchange_add_body.tpl')
	);
	$template->assign_block_vars('exchange_add',array());

	$s_hidden_fields = '<input type="hidden" name="mode" value="savenew" />';
	$stock_out = 0;
	$template->assign_vars(array(
		'L_VAULT_EXCHANGE_ADD_SETTINGS' => $lang['Vault_exchange_settings_add'],
		'L_VAULT_EXCHANGE_ADD_SETTINGS_EXPLAIN' => $lang['Vault_exchange_settings_explain_add'],
		'L_STOCK_EXCHANGE_ACTIONS_ADD' => $lang['Vault_exchange_actions_add'],
		'L_STOCK_NAME' => $lang['Vault_exchange_actions_name'],
		'L_STOCK_DESC' => $lang['Vault_exchange_actions_desc'],
		'L_STOCK_AMOUNT' => $lang['Vault_exchange_actions_amount'],
		'L_STOCK_CAP' => "Max Stock Allowed",
		'STOCK_OUT' => $stock_out,
		'L_STOCK_OUT' => "Stock Issued",
		"S_HIDDEN_FIELDS" => $s_hidden_fields) 
	);

}
else if ( $mode != "" )
{
	switch( $mode )
	{
		case 'delete':

			$stock_id = ( !empty($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];

			$sql = "SELECT * FROM " . VAULT_EXCHANGE_USERS_TABLE . "
				WHERE stock_id = " . $stock_id;
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, "Couldn't delete stock", "", __LINE__, __FILE__, $sql);
			}
			$users = $db->sql_fetchrowset($result);
			for ( $i = 0 ; $i < count ( $users ) ; $i ++ )
			{
				$ssql = "SELECT stock_price FROM " . VAULT_EXCHANGE_TABLE . "
					WHERE stock_id = " . $stock_id;
				$sresult = $db->sql_query($ssql);
				if( !$sresult )
				{
					message_die(GENERAL_ERROR, "Couldn't delete stock", "", __LINE__, __FILE__, $ssql);
				}
				$prize = $db->sql_fetchrow($sresult);
				$price = $prize['stock_price'] * $users[$i]['stock_amount'];
				$usql = "UPDATE " . USERS_TABLE . "
					SET user_points = user_points + $price 
					WHERE user_id =  ".$users[$i]['user_id'];
				$uresult = $db->sql_query($usql);
				if( !$uresult )
				{
					message_die(GENERAL_ERROR, "Couldn't delete stock", "", __LINE__, __FILE__, $usql);
				}
			}

			$sql = "DELETE FROM " . VAULT_EXCHANGE_USERS_TABLE . " 
 				WHERE stock_id = " . $stock_id;
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, "Couldn't delete stock", "", __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . VAULT_EXCHANGE_TABLE . "
				WHERE stock_id = " . $stock_id;
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, "Couldn't delete stock", "", __LINE__, __FILE__, $sql);
			}

			message_die(GENERAL_MESSAGE, sprintf($lang['Vault_exchange_deleted_return_settings'], '<a href="' . append_sid(basename(__FILE__)) . '">', '</a>'), $lang['Vault_settings']);
			break;

		case 'edit':

			$stock_id = ( !empty($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];

			$sql = "SELECT *
				FROM " . VAULT_EXCHANGE_TABLE . "
				WHERE stock_id = " . $stock_id;
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, 'Could not obtain stock exchange information', "", __LINE__, __FILE__, $sql);
			}
			$stock_data = $db->sql_fetchrow($result);

			$template->set_filenames(array(
				"body" => 'admin/config_vault_exchange_add_body.tpl')
			);

			$template->assign_block_vars('exchange_edit',array());

			$s_hidden_fields = '<input type="hidden" name="mode" value="save" /><input type="hidden" name="stock_id" value="' . $stock_data['stock_id'] . '" />';
			$sql = "SELECT * FROM ". VAULT_EXCHANGE_USERS_TABLE ." WHERE stock_id = ".$stock_data['stock_id'];
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, "Couldn't obtain stock exchange from database", "", __LINE__, __FILE__, $sql);
			}
			$s_out = $db->sql_fetchrowset($result);
			$stock_out = 0;
			for($q = 0; $q < count($s_out); $q++)
			{
				$stock_out = $stock_out + $s_out[$q]['stock_amount'];
			}
			$template->assign_vars(array(
				'STOCK_NAME' => $stock_data['stock_name'],
				'STOCK_DESC' => $stock_data['stock_desc'],
				'STOCK_AMOUNT' => $stock_data['stock_price'],
				'STOCK_CAP' => $stock_data['stock_cap'],
				'STOCK_OUT' => $stock_out,
				'L_STOCK_EXCHANGE_ACTIONS_EDIT'  => $lang['Vault_exchange_actions_edit'],
				'L_VAULT_EXCHANGE_EDIT_SETTINGS' => $lang['Vault_exchange_settings_edit'],
				'L_VAULT_EXCHANGE_EDIT_SETTINGS_EXPLAIN' => $lang['Vault_exchange_settings_explain_edit'],
				'L_STOCK_NAME' => $lang['Vault_exchange_actions_name'],
				'L_STOCK_DESC' => $lang['Vault_exchange_actions_desc'],
				'L_STOCK_AMOUNT' => $lang['Vault_exchange_actions_amount'],
				'L_STOCK_CAP' => "Maximum stock Allowed",
				'L_STOCK_OUT' => "Stock Currently Sold",
				"S_HIDDEN_FIELDS" => $s_hidden_fields) 
			);

			break;

		case "save":
			
			$stock_name = ( isset($HTTP_POST_VARS['stock_name']) ) ? trim($HTTP_POST_VARS['stock_name']) : trim($HTTP_GET_VARS['stock_name']);
			$stock_desc = ( isset($HTTP_POST_VARS['stock_desc']) ) ? trim($HTTP_POST_VARS['stock_desc']) : trim($HTTP_GET_VARS['stock_desc']);
			$stock_price = ( isset($HTTP_POST_VARS['stock_price']) ) ? intval($HTTP_POST_VARS['stock_price']) : intval($HTTP_GET_VARS['stock_price']);
			$stock_id = ( isset($HTTP_POST_VARS['stock_id']) ) ? intval($HTTP_POST_VARS['stock_id']) : intval($HTTP_GET_VARS['stock_id']);
			$stock_cap = ( isset($HTTP_POST_VARS['stock_cap']) ) ? intval($HTTP_POST_VARS['stock_cap']) : intval($HTTP_GET_VARS['stock_cap']);
			$sql = "SELECT * FROM ". VAULT_EXCHANGE_USERS_TABLE ." WHERE stock_id = ".$stock_id;
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, "Couldn't obtain stock exchange from database", "", __LINE__, __FILE__, $sql);
			}
			$s_out = $db->sql_fetchrowset($result);
			$stock_out = 0;
			for($q = 0; $q < count($s_out); $q++)
			{
				$stock_out = $stock_out + $s_out[$q]['stock_amount'];
			}

			if ($stock_name == '' || $stock_desc == '' || $stock_price == '' || $stock_cap == '')
			{
				message_die(MESSAGE, $lang['Fields_empty']);
			}

			if ($stock_out > $stock_cap)
			{
				message_die(MESSAGE, "The New maximum exceeds stock already issued");
			}
			$sql = "UPDATE " . VAULT_EXCHANGE_TABLE . "
				SET stock_name = '" . str_replace("\'", "''", $stock_name) . "', 
				stock_desc = '" . str_replace("\'", "''", $stock_desc) . "', 
				stock_price = $stock_price,
				stock_cap = $stock_cap
				WHERE stock_id = " . $stock_id;
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, "Couldn't update stock", "", __LINE__, __FILE__, $sql);
			}

			message_die(GENERAL_MESSAGE, sprintf($lang['Vault_exchange_edited_return_settings'], '<a href="' . append_sid(basename(__FILE__)) . '">', '</a>'), $lang['Vault_settings']);
			break;

		case "savenew":

			$sql = "SELECT *
			FROM " . VAULT_EXCHANGE_TABLE ."
			ORDER BY stock_id 
			DESC LIMIT 1";
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, 'Could not obtain stock exchange information', "", __LINE__, __FILE__, $sql);
			}
			$stock_data = $db->sql_fetchrow($result);

			$stock_name = ( isset($HTTP_POST_VARS['stock_name']) ) ? trim($HTTP_POST_VARS['stock_name']) : trim($HTTP_GET_VARS['stock_name']);
			$stock_desc = ( isset($HTTP_POST_VARS['stock_desc']) ) ? trim($HTTP_POST_VARS['stock_desc']) : trim($HTTP_GET_VARS['stock_desc']);
			$stock_price = ( isset($HTTP_POST_VARS['stock_price']) ) ? intval($HTTP_POST_VARS['stock_price']) : intval($HTTP_GET_VARS['stock_price']);
			$stock_id = $stock_data['stock_id'] +1;
			$stock_cap = ( isset($HTTP_POST_VARS['stock_cap']) ) ? intval($HTTP_POST_VARS['stock_cap']) : intval($HTTP_GET_VARS['stock_cap']);

			if ($stock_name == '' || $stock_desc == '' || $stock_price == '')
			{
				message_die(MESSAGE, $lang['Fields_empty']);
			}

			if ($stock_cap == '')
			{
				$stock_cap = 0;
			}

			$sql = "INSERT INTO " . VAULT_EXCHANGE_TABLE . " (stock_id, stock_name, stock_desc, stock_price,stock_previous_price,stock_worst_price,stock_best_price,stock_cap)
				VALUES ( $stock_id, '" . str_replace("\'", "''", $stock_name) . "' , '" . str_replace("\'", "''", $stock_desc) . "' , $stock_price, $stock_price, $stock_price, $stock_price,$stock_cap)";
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, "Couldn't insert new stock", "", __LINE__, __FILE__, $sql);
			}

			message_die(GENERAL_MESSAGE, sprintf($lang['Vault_exchange_added_return_settings'], '<a href="' . append_sid(basename(__FILE__)) . '">', '</a>'), $lang['Vault_settings']);
			break;
	}
}
else
{
	$sql = "SELECT *
		FROM " . VAULT_EXCHANGE_TABLE ."
		ORDER BY stock_id ";
	$result = $db->sql_query($sql);
	if( !$result )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain stock exchange from database", "", __LINE__, __FILE__, $sql);
	}

	$exchange = $db->sql_fetchrowset($result);
	
	for($i = 0; $i < count($exchange); $i++)
	{		
		
		$sql = "SELECT * FROM ". VAULT_EXCHANGE_USERS_TABLE ." WHERE stock_id = ".$exchange[$i]['stock_id'];
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, "Couldn't obtain stock exchange from database", "", __LINE__, __FILE__, $sql);
			}
			$s_out = $db->sql_fetchrowset($result);
			$stock_out = 0;
			for($q = 0; $q < count($s_out); $q++)
			{
				$stock_out = $stock_out + $s_out[$q]['stock_amount'];
			}
		$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$stock_name = isset($lang[$exchange[$i]['stock_name']]) ? $lang[$exchange[$i]['stock_name']] : $exchange[$i]['stock_name'];
		$stock_desc = isset($lang[$exchange[$i]['stock_desc']]) ? $lang[$exchange[$i]['stock_desc']] : $exchange[$i]['stock_desc'];

		$template->assign_block_vars("exchange", array(
			"ROW_COLOR" => "#" . $row_color,
			"ROW_CLASS" => $row_class,
			"STOCK_NAME" =>  $stock_name,
			"STOCK_DESC" =>  $stock_desc,
			"STOCK_AMOUNT" =>  $exchange[$i]['stock_price'], 
			"STOCK_OUT" => $stock_out,
			"STOCK_CAP" => $exchange[$i]['stock_cap'],
			"U_STOCK_EDIT" => append_sid("admin_vault_exchange.$phpEx?mode=edit&amp;id=" . $exchange[$i]['stock_id']), 
			"U_STOCK_DELETE" => append_sid("admin_vault_exchange.$phpEx?mode=delete&amp;id=" . $exchange[$i]['stock_id']))
		);
	}
	$template->assign_vars(array(
		'L_STOCK_EXCHANGE_ACTIONS' => $lang['Vault_exchange_actions'],
		'L_STOCK_NAME' => $lang['Vault_exchange_actions_name'],
		'L_STOCK_DESC' => $lang['Vault_exchange_actions_desc'],
		'L_STOCK_AMOUNT' => $lang['Vault_exchange_actions_amount'],
		'L_STOCK_OUT' => "Shares Issued",
		'L_STOCK_CAP' => "Stock Cap",
		'L_ACTION' => $lang['Vault_exchange_action'],
		'L_EDIT' => $lang['Vault_exchange_edit'],
		'L_DELETE' => $lang['Vault_exchange_delete'],
		'L_STOCK_ADD' => $lang['Vault_exchange_actions_add'],
	));

}

$template->assign_vars(array(
	'VAULT_EXCHANGE_USE_CHECKED' => ( $board_config['stock_use'] ? 'CHECKED' :'' ),
	'VAULT_EXCHANGE_TIME' => $board_config['stock_time'],
	'VAULT_EXCHANGE_TIME_EXPLAIN' => $lang['Vault_time_explain'].vault_make_time($board_config['stock_time']),
	'VAULT_EXCHANGE_MAX' => $vault['stock_max_change'],
	'VAULT_EXCHANGE_MIN' => $vault['stock_min_change'],
	'L_VAULT_EXCHANGE_LANGUAGE_KEY' => $lang['Vault_language_key'],
	'L_VAULT_EXCHANGE_SETTINGS' => $lang['Vault_exchange_settings'],
	'L_VAULT_EXCHANGE_SETTINGS_EXPLAIN' => $lang['Vault_exchange_settings_explain'],
	'L_VAULT_EXCHANGE_USE' => $lang['Vault_exchange_use'],
	'L_VAULT_EXCHANGE_MIN' => $lang['Vault_exchange_min'],
	'L_VAULT_EXCHANGE_MIN_EXPLAIN' => $lang['Vault_exchange_min_explain'],
	'L_VAULT_EXCHANGE_MAX' => $lang['Vault_exchange_max'],
	'L_VAULT_EXCHANGE_MAX_EXPLAIN' => $lang['Vault_exchange_max_explain'],
	'L_VAULT_EXCHANGE_TIME' => $lang['Vault_exchange_time'],
	'L_VAULT_EXCHANGE_TIME_EXPLAIN' => $lang['Vault_exchange_time_explain'],
	'L_SUBMIT' => $lang['Submit'],
	'S_VAULT_ACTION' => append_sid(basename(__FILE__)),
	"S_HIDDEN_FIELDS" => $s_hidden_fields)
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>
