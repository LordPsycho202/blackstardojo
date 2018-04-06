<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
 *                              admin_vault_users.php
 *                                   ------------------
 *
 *   begin                : 		    12/01/2004
 *
 *
 ***************************************************************************/

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Vault']['Vault_users'] = $file;
	return;
}

define('IN_PHPBB', true);

$phpbb_root_path = '../';
require($phpbb_root_path . 'extension.inc');
require("pagestart.$phpEx");

$template->set_filenames(array(
	'body' => 'admin/config_vault_users_body.tpl')
);

$submit = isset($HTTP_POST_VARS['submit']); 

if ( ( isset($HTTP_POST_VARS['owner_name']) || isset($HTTP_GET_VARS['owner_name']) ) && ( $HTTP_POST_VARS['owner_name'] != '' || $HTTP_GET_VARS['owner_name'] != '' ))
{
	$username = ( isset($HTTP_POST_VARS['owner_name']) ) ? $HTTP_POST_VARS['owner_name'] : $HTTP_GET_VARS['owner_name'];
	$sql = " SELECT user_id FROM ". USERS_TABLE ." 
		WHERE username = '$username' ";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);	
	$owner_id = intval($row['user_id']);
}
else if ( isset($HTTP_POST_VARS[POST_OWNERS_URL]) || isset($HTTP_GET_VARS[POST_OWNERS_URL]) || !(is_numeric( $owner_id ))  )
{
	$owner_id = ( isset($HTTP_POST_VARS[POST_OWNERS_URL]) ) ? intval($HTTP_POST_VARS[POST_OWNERS_URL]) : intval($HTTP_GET_VARS[POST_OWNERS_URL]);
}
if ( !$owner_id )
{
	$owner_id = 2;
}

$sql = "SELECT u.user_id , u.username
	FROM " . USERS_TABLE . " u , ". VAULT_USERS_TABLE . " v
	WHERE u.user_id = v.owner_id
	ORDER by u.username";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
}
$select_list = '';
if ( $row = $db->sql_fetchrow($result) )
{
	$select_list .= '<select name="' . POST_OWNERS_URL . '">';
	do
	{
		$select_list .= '<option value="' . $row['user_id'] . '">' . $row['username'] . '</option>';
	}
	while ( $row = $db->sql_fetchrow($result) );
	$select_list .= '</select>';
}

if( $submit )
{
	$owner_id = intval ($HTTP_POST_VARS['user_id']);
	$on_account = intval($HTTP_POST_VARS['on_account']);
	$loan_sum = intval( $HTTP_POST_VARS['loan_sum'] );
	$pay_off = intval( $HTTP_POST_VARS['pay_off'] );
	$protect_account = intval( $HTTP_POST_VARS['protect_account'] );
	$protect_loan = intval( $HTTP_POST_VARS['protect_loan'] );
	$newsletter = intval( $HTTP_POST_VARS['newsletter'] );

	if ( $pay_off || !$loan_sum )
	{
		$loan_sum = 0;
		$loan_sql = 'loan_time = 0 ,';
	}

	$sql = "UPDATE " . VAULT_USERS_TABLE . "
		SET account_sum = $on_account ,
		loan_sum = $loan_sum ,
		$loan_sql 
		account_protect = $protect_account ,
		loan_protect = $protect_loan ,
		newsletter = $newsletter 
		WHERE owner_id = $owner_id";
	if (!$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not update user's account", '', __LINE__, __FILE__, $sql);
	}
	else
	{
		message_die(GENERAL_MESSAGE, sprintf($lang['Vault_users_updated_return_settings'], '<a href="' . append_sid(basename(__FILE__)) . '">', '</a>'), $lang['Vault_settings']);
	}

}
else
{	
	$sql = "SELECT * FROM  " . VAULT_GENERAL_TABLE ; 
	if (!$result = $db->sql_query($sql)) 
	{
		message_die(CRITICAL_ERROR, 'Error Getting Vault Config!');
	}
	while( $row = $db->sql_fetchrow($result) )
	{
		$vault_general[$row['config_name']] = $row['config_value'];
	}

	$sql = "SELECT vu.* , u.username FROM " . VAULT_USERS_TABLE . " vu , " . USERS_TABLE . " u
		WHERE vu.owner_id = ".$owner_id."
		AND vu.owner_id = u.user_id";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain rabbitoshi owners data", "", __LINE__, __FILE__, $sql);
	}
	$vault_owner = $db->sql_fetchrow($result);

	if ( $vault_owner['loan_sum'] != 0 )
	{
		$loan_sum = $vault_owner['loan_sum'];
		$template->assign_block_vars( 'active_loan' , array());
	}
	else
	{
		$loan_sum = $lang['Vault_no_loan'];
		$template->assign_block_vars( 'no_loan' , array());
	}



	$sql = "SELECT * FROM " . VAULT_EXCHANGE_TABLE ;
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain stock exchange data", "", __LINE__, __FILE__, $sql);
	}
	$vault_items = $db->sql_fetchrowset($result);

	
	
}

$template->assign_vars(array(
	"USER_ID" => $vault_owner['owner_id'],
	"OWNER" => $vault_owner['username'],
	"ON_ACCOUNT" => $vault_owner['account_sum'],
	"LOAN" => $loan_sum,
	"PROTECT_ACCOUNT_CHECKED" => ( $vault_owner['account_protect'] ? 'CHECKED' :'' ),
	"PROTECT_LOAN_CHECKED"  => ( $vault_owner['loan_protect'] ? 'CHECKED' :'' ),
	"NEWSLETTER_CHECKED"  => ( $vault_owner['newsletter'] ? 'CHECKED' :'' ),
	"L_POINTS" => $board_config['points_name'],
	"L_VAULT_TITLE" => $lang['Vault_users_title'],
	"L_VAULT_TEXT" => $lang['Vault_users_title_explain'],
	"L_SUBMIT" => $lang['Submit'],
	"L_SELECT" => $lang['Select'],
	"L_SELECT_OWNER" => $lang['Vault_user_select'],
	"L_SELECT_OWNER_LIST" => $lang['Vault_user_select_list'],
	"L_SELECT_OWNER_INPUT" => $lang['Vault_user_select_input'],
	"L_OWNER" => $lang['Vault_user'],
	"L_ACCOUNT" => $lang['Vault_user_account'],
	"L_ON_ACCOUNT" => $lang['Vault_user_on_account'],
	"L_LOAN" => $lang['Vault_user_loan'],
	"L_STOCK_EXCHANGE" => $lang['Vault_user_stocks'],
	"L_LOAN_PAY_OFF" => $lang['Vault_user_pay_off'],
	"L_PREFERENCES" => $lang['Vault_user_preferences'],
	"L_PROTECT_ACCOUNT" => $lang['Vault_user_protect_account'],
	"L_PROTECT_LOAN" => $lang['Vault_user_protect_loan'],
	"L_NEWSLETTER" => $lang['Vault_user_newsletter'], 
	"S_SELECT_OWNER" => $select_list,
	"S_VAULT_ACTION" => append_sid("admin_vault_users.$phpEx?owner_id = ".POST_OWNERS_URL.""),
));


$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>