<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                              admin_vault_general.php
 *                            ------------------
 *   begin                : 06/01/2004
 *
 *
 ***************************************************************************/

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Vault']['Vault_settings'] = $file;
	return;
}

define('IN_PHPBB', true);

$phpbb_root_path = '../';
require($phpbb_root_path . 'extension.inc');
require("pagestart.$phpEx");

$template->set_filenames(array(
	'body' => 'admin/config_vault_general_body.tpl')
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

$template->assign_vars(array(
	'VAULT_NAME' => $board_config['vault_name'],
	'VAULT_USE_CHECKED' => ( $board_config['vault_enable'] ? 'CHECKED' :'' ),
	'VAULT_USE_LOAN_CHECKED' => ( $vault['vault_loan_enable'] ? 'CHECKED' :'' ),
	'VAULT_PROFILE_CHECKED' => ( $board_config['vault_display_profile'] ? 'CHECKED' :'' ),
	'VAULT_TOPICS_CHECKED' => ( $board_config['vault_display_topics'] ? 'CHECKED' :'' ),
	'VAULT_INTERESTS_RATE' => $vault['interests_rate'],
	'VAULT_INTERESTS_TIME' => $vault['interests_time'],
	'VAULT_INTERESTS_TIME_EXPLAIN' =>$lang['Vault_time_explain'].vault_make_time($vault['interests_time']), 
	'VAULT_LOAN_INTERESTS' => $vault['loan_interests'],
	'VAULT_LOAN_INTERESTS_TIME' => $vault['loan_interests_time'],
	'VAULT_LOAN_INTERESTS_TIME_EXPLAIN' => $lang['Vault_time_explain'].vault_make_time($vault['loan_interests_time']),
	'VAULT_LOAN_MAX_SUM' => $vault['loan_max_sum'],
	'VAULT_LOAN_REQUIREMENTS' => $vault['loan_requirements'],
	'VAULT_BASE_AMOUNT' => $vault['base_amount'],
	'VAULT_NUM_ITEMS' => $vault['num_items'],
));

if ( $submit )
{
	$use = intval ( $HTTP_POST_VARS['use']);
	$loan = intval ( $HTTP_POST_VARS['loan']);
	$topics = intval ( $HTTP_POST_VARS['topics']);
	$profile = intval ( $HTTP_POST_VARS['profile']);
	$name = $HTTP_POST_VARS['name'];
	$interests_rate = $HTTP_POST_VARS['interests_rate'];
	$interests_time = $HTTP_POST_VARS['interests_time'];
	$loan_interests = $HTTP_POST_VARS['loan_interests'];
	$loan_time = $HTTP_POST_VARS['loan_time'];
	$loan_sum = $HTTP_POST_VARS['loan_sum'];
	$loan_req = $HTTP_POST_VARS['loan_req'];
	$base_amount = intval ( $HTTP_POST_VARS['base_amount']);
	$num_items = intval ( $HTTP_POST_VARS['num_items']);

	$lsql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$use' WHERE config_name = 'vault_enable' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 
	$lsql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$name' WHERE config_name = 'vault_name' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 
	$lsql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$topics' WHERE config_name = 'vault_display_topics' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 
	$lsql= "UPDATE ". CONFIG_TABLE . " SET config_value = '$profile' WHERE config_name = 'vault_display_profile' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 
	$lsql= "UPDATE ". VAULT_GENERAL_TABLE . " SET config_value = '$loan' WHERE config_name = 'vault_loan_enable' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 
	$lsql= "UPDATE ". VAULT_GENERAL_TABLE . " SET config_value = '$interests_rate' WHERE config_name = 'interests_rate' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 
	$lsql= "UPDATE ". VAULT_GENERAL_TABLE . " SET config_value = '$interests_time' WHERE config_name = 'interests_time' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 
	$lsql= "UPDATE ". VAULT_GENERAL_TABLE . " SET config_value = '$loan_interests' WHERE config_name = 'loan_interests' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 
	$lsql= "UPDATE ". VAULT_GENERAL_TABLE . " SET config_value = '$loan_time' WHERE config_name = 'loan_interests_time' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 
	$lsql= "UPDATE ". VAULT_GENERAL_TABLE . " SET config_value = '$loan_sum' WHERE config_name = 'loan_max_sum' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 	
	$lsql= "UPDATE ". VAULT_GENERAL_TABLE . " SET config_value = '$loan_req' WHERE config_name = 'loan_requirements' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 
	$lsql= "UPDATE ". VAULT_GENERAL_TABLE . " SET config_value = '$base_amount' WHERE config_name = 'base_amount' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 	
	$lsql= "UPDATE ". VAULT_GENERAL_TABLE . " SET config_value = '$num_items' WHERE config_name = 'num_items' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 

	vault_update_general_config();
	message_die(GENERAL_MESSAGE, sprintf($lang['Vault_updated_return_settings'], '<a href="' . append_sid(basename(__FILE__)) . '">', '</a>'), $lang['Vault_settings']);
}

// Send vars to template
$template->assign_vars(array(
	'L_VAULT_SETTINGS' => $lang['Vault_settings'],
	'L_VAULT_SETTINGS_EXPLAIN' => $lang['Vault_settings_explain'],
	'L_VAULT_USE' => $lang['Vault_use'],
	'L_VAULT_NAME' => $lang['Vault_settings_name'],
	'L_VAULT_INTERESTS_RATE' => $lang['Vault_interests_rate'],
	'L_VAULT_INTERESTS_RATE_EXPLAIN' => $lang['Vault_interests_rate_explain'],
	'L_VAULT_INTERESTS_TIME' => $lang['Vault_interests_time'],
	'L_VAULT_INTERESTS_TIME_EXPLAIN' => $lang['Vault_interests_time_explain'],
	'L_VAULT_USE_LOAN' => $lang['Vault_loan_use'],
	'L_VAULT_LOAN_INTERESTS' => $lang['Vault_loan_interests'],
	'L_VAULT_LOAN_INTERESTS_EXPLAIN' => $lang['Vault_loan_interests_explain'],
	'L_VAULT_LOAN_INTERESTS_TIME' => $lang['Vault_loan_interests_time'],
	'L_VAULT_LOAN_INTERESTS_TIME_EXPLAIN' => $lang['Vault_loan_interests_time_explain'],
	'L_VAULT_LOAN_MAX_SUM' => $lang['Vault_max_sum'],
	'L_VAULT_LOAN_MAX_SUM_EXPLAIN' => $lang['Vault_max_sum_explain'],
	'L_VAULT_LOAN_REQUIREMENTS' => $lang['Vault_requirements'],
	'L_VAULT_LOAN_REQUIREMENTS_EXPLAIN' => $lang['Vault_requirements_explain'],
	'L_VAULT_PROFILE' => $lang['Vault_display_profile'],
	'L_VAULT_TOPICS' => $lang['Vault_display_topics'],
	'L_VAULT_BASE_AMOUNT' => $lang['Vault_base_amount'],
	'L_VAULT_BASE_AMOUNT_EXPLAIN' => $lang['Vault_base_amount_points'], 
	'L_VAULT_NUM_ITEMS' => $lang['Vault_num_items'],
	'L_SUBMIT' => $lang['Submit'], 
	'S_VAULT_ACTION' => append_sid(basename(__FILE__)))
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>