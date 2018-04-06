<?php
 //require_once ('/home/content/D/a/r/DarkPsycho/html/phpmyadmin/themes/original/img/s_notice.php');

/***************************************************************************
*     begin                : Sun Oct 17 2004
*     copyright            : (C) 2004 Majorflam
*     email                : majorflam@blueyonder.co.uk
*
****************************************************************************/

define('IN_PHPBB', 1);

//
// First we do the setmodules stuff for the admin cp.
//
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Bookmakers']['Admin Configuration'] = $filename;

	return;
}

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Output the authorisation details
//
$template->set_filenames(array(
	'body' => 'admin/admin_bookies_config.tpl'
	)
);

// Get language Variables
include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_bookmakers.' . $phpEx);

$redirect = '<meta http-equiv="refresh" content="2;url=' . append_sid("admin_bookies_config.$phpEx?") . '">';

$edit_stake = $board_config['bookie_edit_stake'];
$allow_pm = $board_config['bookie_pm'];
$each_way = $board_config['bookie_eachway'];
$user_bets = $board_config['bookie_user_bets'];
$fractional_decimal = $board_config['bookie_frac_or_dec'];
$welcome_text=$board_config['bookie_welcome'];
$allow_commission = $board_config['bookie_allow_commission'];
$min_stake=$board_config['bookie_min_bet'];
$max_stake=$board_config['bookie_max_bet'];
$restrict=$board_config['bookie_restrict'];

$leader_box .= '<option value="" selected="selected">' . $board_config['bookie_leader'];
$leader_box .= '<option value="5">5';
$leader_box .= '<option value="10">10';
$leader_box .= '<option value="15">15';
$leader_box .= '<option value="20">20';

$leader_box .= '</select>';

$commission_box .= '<option value="" selected="selected">' . $board_config['bookie_commission'] . '%';
for ( $i=1; $i<101; $i++ )
{
	$commission_box .= '<option value="' . $i . '">' . $i . '%';
}
$commission_box .= '</select>';

if ( isset($HTTP_POST_VARS['submit']) )
{
	$edit_stake = ( isset($HTTP_POST_VARS['editstake']) ) ? ( ($HTTP_POST_VARS['editstake']) ? TRUE : 0 ) : 0;
	$allow_pm = ( isset($HTTP_POST_VARS['allowpm']) ) ? ( ($HTTP_POST_VARS['allowpm']) ? TRUE : 0 ) : 0;
	$each_way = ( isset($HTTP_POST_VARS['alloweachway']) ) ? ( ($HTTP_POST_VARS['alloweachway']) ? TRUE : 0 ) : 0;
	$user_bets = ( isset($HTTP_POST_VARS['allowuserbets']) ) ? intval($HTTP_POST_VARS['allowuserbets']) : 0;
	$fractional_decimal = ( isset($HTTP_POST_VARS['fracdec']) ) ? ( ($HTTP_POST_VARS['fracdec']) ? TRUE : 0 ) : 0;
	$leader = intval($HTTP_POST_VARS['leader']);
	$welcome_text_input=htmlspecialchars($HTTP_POST_VARS['welcome_text']);
	$commission=intval($HTTP_POST_VARS['commission']);
	$allow_commission=( isset($HTTP_POST_VARS['allow_commission']) ) ? ( ($HTTP_POST_VARS['allow_commission']) ? TRUE : 0 ) : 0;
	$min_stake=intval($HTTP_POST_VARS['min_stake']);
	$max_stake=intval($HTTP_POST_VARS['max_stake']);
	$restrict=intval($HTTP_POST_VARS['restrict']);
	
	if ( strlen($welcome_text_input) > 250 )
	{
		$welcome_text_input=$welcome_text;
	}
	$welcome_text=$welcome_text_input;
	
	//
	// are we changing commission percentage? If so, there are some restrictions to consider...
	//
	if ( !empty($commission) )
	{
		$sql=" SELECT count(*) AS pending FROM " . BOOKIE_ADMIN_BETS_TABLE . "
		WHERE multi != -5
		";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
		}
		$row=$db->sql_fetchrow($result);
		if ( $row['pending'] )
		{
			$message=$lang['bookie_config_bets_outstanding'];
			message_die(GENERAL_MESSAGE, $message);
		}
	}
	
	$sql=" UPDATE " . CONFIG_TABLE . "
	SET config_value='" . str_replace("\'", "''", $welcome_text) . "'
	WHERE config_name='bookie_welcome'
	";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	$sql=" UPDATE " . CONFIG_TABLE . "
	SET config_value='$edit_stake'
	WHERE config_name='bookie_edit_stake'
	";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	$sql=" UPDATE " . CONFIG_TABLE . "
	SET config_value='$allow_pm'
	WHERE config_name='bookie_pm'
	";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	$sql=" UPDATE " . CONFIG_TABLE . "
	SET config_value='$each_way'
	WHERE config_name='bookie_eachway'
	";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	$sql=" UPDATE " . CONFIG_TABLE . "
	SET config_value='$user_bets'
	WHERE config_name='bookie_user_bets'
	";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	$sql=" UPDATE " . CONFIG_TABLE . "
	SET config_value='$fractional_decimal'
	WHERE config_name='bookie_frac_or_dec'
	";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	$sql=" UPDATE " . CONFIG_TABLE . "
	SET config_value='$allow_commission'
	WHERE config_name='bookie_allow_commission'
	";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	$sql=" UPDATE " . CONFIG_TABLE . "
	SET config_value='$min_stake'
	WHERE config_name='bookie_min_bet'
	";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	$sql=" UPDATE " . CONFIG_TABLE . "
	SET config_value='$max_stake'
	WHERE config_name='bookie_max_bet'
	";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	$sql=" UPDATE " . CONFIG_TABLE . "
	SET config_value='$restrict'
	WHERE config_name='bookie_restrict'
	";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
	}
	if ( !empty($leader) )
	{
		$sql=" UPDATE " . CONFIG_TABLE . "
		SET config_value='$leader'
		WHERE config_name='bookie_leader'
		";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
		}
	}
	if ( !empty($commission) )
	{
		$sql=" UPDATE " . CONFIG_TABLE . "
		SET config_value='$commission'
		WHERE config_name='bookie_commission'
		";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update config table', '', __LINE__, __FILE__, $sql);
		}
	}
	$message=$lang['bookie_config_update_success'] . $redirect;
	message_die(GENERAL_MESSAGE, $message);
}
	
// Set template Vars
$template->assign_vars(array(
'CONFIG_HEADER' => $lang['bookie_config_header'],
'CONFIG_EXPLAIN' => $lang['bookie_config_explain'],
'L_YES' => $lang['Yes'],
'L_NO' => $lang['No'],
'L_SUBMIT' => $lang['Submit'],
'ALLOW_EDIT_STAKE' => $lang['bookie_allow_edit_stake'],
'ALLOW_EDIT_STAKE_EXP' => $lang['bookie_allow_edit_stake_exp'],
'ALLOW_SEND_PM' => $lang['bookie_allow_pm'],
'ALLOW_SEND_PM_EXP' => $lang['bookie_allow_pm_exp'],
'ALLOW_EDIT_STAKE_YES' => ( $edit_stake ) ? 'checked="checked"' : '',
'ALLOW_EDIT_STAKE_NO' => ( !$edit_stake ) ? 'checked="checked"' : '',
'ALLOW_PM_YES' => ( $allow_pm ) ? 'checked="checked"' : '',
'ALLOW_PM_NO' => ( !$allow_pm ) ? 'checked="checked"' : '',
'ALLOW_EACH_WAY_YES' => ( $each_way ) ? 'checked="checked"' : '',
'ALLOW_EACH_WAY_NO' => ( !$each_way ) ? 'checked="checked"' : '',
'ALLOW_USER_BETS_YES' => ( $user_bets == 1 ) ? 'checked="checked"' : '',
'ALLOW_USER_BETS_NO' => ( !$user_bets ) ? 'checked="checked"' : '',
'ALLOW_USER_BETS_COND' => ( $user_bets == 2 ) ? 'checked="checked"' : '',
'ALLOW_FRACTIONAL_YES' => ( !$fractional_decimal ) ? 'checked="checked"' : '',
'ALLOW_FRACTIONAL_NO' => ( $fractional_decimal ) ? 'checked="checked"' : '',
'LEADERBOARD' => $lang['bookie_leaderboard'],
'LEADERBOARD_EXP' => $lang['bookie_leaderboard_exp'],
'LEADER_BOX' => $leader_box,
//
// for 2.0.5
//
'ALLOW_EACH_WAY' => $lang['bookie_allow_each_way'],
'ALLOW_EACH_WAY_EXP' => $lang['bookie_allow_each_way_exp'],
'ALLOW_USER_BETS' => $lang['bookie_allow_user_bets'],
'ALLOW_USER_BETS_EXP' => $lang['bookie_allow_user_bets_exp'],
'FRAC_DEC' => $lang['bookie_frac_or_dec'],
'FRAC_DEC_EXP' => $lang['bookie_frac_or_dec_exp'],
'L_FRAC' => $lang['bookie_fractional'],
'L_DEC' => $lang['bookie_decimal'],
//
// for 2.0.7
//
'WELCOME_TEXT' => $welcome_text,
'WELCOME' => $lang['bookie_config_welcome'],
'WELCOME_EXP' => $lang['bookie_config_welcome_exp'],
'L_CONDITION' => $lang['bookie_userbet_conditional'],
//
// for 2.0.8
//
'COMMISSION_BOX' => $commission_box,
'ALLOW_COM_YES' => ( $allow_commission ) ? 'checked="checked"' : '',
'ALLOW_COM_NO' => ( !$allow_commission ) ? 'checked="checked"' : '',
'ALLOW_COMMISSION' => $lang['bookie_config_all_com'],
'ALLOW_COMMISSION_EXP' => $lang['bookie_config_all_com_exp'],
'COMMISSION' => $lang['bookie_config_com_per'],
'COMMISSION_EXP' => $lang['bookie_config_com_per_exp'],
'GENERAL_HEAD' => $lang['bookie_config_gen_head'],
'BOOKIE_SETTINGS' => $lang['bookie_config_bookie_set'],
'MISC_SETTINGS' => $lang['bookie_config_bookie_misc'],
'ALLOW_MIN_BET' => $lang['bookie_config_allow_min_bet'],
'ALLOW_MIN_BET_EXP' => $lang['bookie_config_allow_min_bet_exp'],
'ALLOW_MAX_BET' => $lang['bookie_config_allow_max_bet'],
'ALLOW_MAX_BET_EXP' => $lang['bookie_config_allow_max_bet_exp'],
'MIN_STAKE' => $min_stake,
'MAX_STAKE' => $max_stake,
'BET_RESTRICT' => $lang['bookie_bet_restrict'],
'BET_RESTRICT_EXP' => $lang['bookie_bet_restrict_exp'],
'ALLOW_RESTRICT_YES' => ( $restrict ) ? 'checked="checked"' : '',
'ALLOW_RESTRICT_NO' => ( !$restrict ) ? 'checked="checked"' : '',
'BOOKIE_VERSION' => $board_config['bookie_version'],
));

include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>