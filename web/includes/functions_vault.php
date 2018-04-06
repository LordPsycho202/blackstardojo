<?php

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

// Define the tables
define('VAULT_BLACKLIST_TABLE',$table_prefix.'vault_blacklist');
define('VAULT_EXCHANGE_TABLE',$table_prefix.'vault_exchange');
define('VAULT_EXCHANGE_USERS_TABLE',$table_prefix.'vault_exchange_users');
define('VAULT_GENERAL_TABLE',$table_prefix.'vault_general');
define('VAULT_USERS_TABLE',$table_prefix.'vault_users');

// Include the language file
include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_vault.'.$phpEx);

// Define the points name
$board_config['points_name'] = $board_config['points_name'] ? $board_config['points_name'] : $lang['Vault_default_points_name'] ;

function vault_make_time($stamp_age)
{
	global $db , $lang , $vault , $vault_general;

	$time = '';
	$days = floor($stamp_age/86400);
	$stamp_age = $stamp_age - ( $days * 86400 );
	$hours = floor($stamp_age/3600);
	$stamp_age = $stamp_age - ( $hours * 3600 );
	$minutes = floor($stamp_age/60);
	if ( $days > 1 )
	{
		$time .= $days.'&nbsp;'.$lang['Vault_days'].'&nbsp;';
	}
	else if ( $days != 0 )
	{
		$time .= $days.'&nbsp;'.$lang['Vault_day'].'&nbsp;';
	}
	if ( $hours > 1 )
	{
		$time .= $hours.'&nbsp;'.$lang['Vault_hours'].'&nbsp;';
	}
	else if ( $hours != 0 )
	{
		$time .= $hours.'&nbsp;'.$lang['Vault_hour'].'&nbsp;';
	}
	if ( $minutes > 1 )
	{
		$time .= $minutes.'&nbsp;'.$lang['Vault_minutes'].'&nbsp;';
	}
	else if ( $minutes != 0 )
	{
		$time .= $minutes.'&nbsp;'.$lang['Vault_minute'].'&nbsp;';
	}
	return $time;
}

function vault_get_lang($key)
{
	global $lang;

	$lang_key = isset($lang[$key]) ? $lang[$key] : $key;

	return $lang_key ;
}

function vault_previous( $lang_key , $direct , $nav='' , $new_key )
{
	global $lang , $phpEx ;

	$lang_key = $lang[$lang_key];
	$return_message = ( $new_key ) ? $lang[$new_key] : $lang['Vault_return'];
	$temp = ( !$nav ) ? $direct.'.'.$phpEx : $direct.'.'.$phpEx.'?'.$nav;
	$direction = append_sid("$temp");

	$message = $lang_key .'<br /><br />'.sprintf($return_message , "<a href=\"" . $direction . "\">", "</a>") ;
	message_die( GENERAL_MESSAGE,$message);
}

function vault_get_general_config()
{
	global $db , $lang , $phpEx , $phpbb_root_path;

	// All the following code has been made by Ptirhiik 
	@include( $phpbb_root_path . './cache/vault_config.' . $phpEx );

	if ( !(empty($vault_config)) )
	{
		while ( list($config_name, $config_value) = @each($vault_config) )
		{
			$cached_vault_config[$config_name] = $config_value;
		}
	}
	else 
	{
		$sql = "SELECT * FROM  " . VAULT_GENERAL_TABLE ; 
		if (!$result = $db->sql_query($sql)) 
		{
			message_die(GENERAL_MESSAGE, 'Could not read vault config table');
		}

		@include( $phpbb_root_path . './cache/vault_config.' . $phpEx );

		if ( empty($adr_config) )
		{
			vault_update_general_config();

			include( $phpbb_root_path . './cache/vault_config.' . $phpEx );

			while ( list($config_name, $config_value) = @each($vault_config) )
			{
				$cached_vault_config[$config_name] = $config_value;
			}
		}
	}

	return $cached_vault_config;
}

function vault_update_general_config()
{
	global $db , $lang , $phpEx , $userdata , $phpbb_root_path;

	$template = new Template($phpbb_root_path);
	
	$template->set_filenames(array(
		'cache' => 'cache/vault_config_def.tpl')
	);

	$sql = "SELECT * FROM " . VAULT_GENERAL_TABLE ;
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not read vault config table', '', __LINE__, __FILE__, $sql);
	}
	while ( $row = $db->sql_fetchrow($result) )
	{
		$id = $row['config_name'];
		$cell_res = $row['config_value'];

		$template->assign_block_vars('cache_row', array(
			'ID'		=> sprintf("'%s'", str_replace("'", "\'", $id)),
			'CELLS'	=> sprintf("'%s'", str_replace("'", "\'", $cell_res)),
			)
		);
	}

	$template->assign_var_from_handle('cache', 'cache');
	$res = "<?php\n" . $template->_tpldata['.'][0]['cache'] . "\n?>";
	
	$fname = $phpbb_root_path . './cache/vault_config'.'.' . $phpEx;
	@chmod($fname, 0666);
	$handle = @fopen($fname, 'w');
	@fwrite($handle, $res);
	@fclose($handle);
}

if ( !$board_config['stock_last_change'] )
{
	$lsql= "UPDATE ". CONFIG_TABLE . " SET config_value = ".time()." WHERE config_name = 'stock_last_change' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 
	$board_config['stock_last_change'] = time();
}

if ( ( time() - $board_config['stock_last_change'] ) > $board_config['stock_time'] )
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
		if ( $vault_general['stock_min_change'] > $vault_general['stock_max_change'] )
		{
			$vault_general['stock_min_change'] = $vault_general['stock_max_change'];
		}
		$variation = rand($vault_general['stock_min_change'] , $vault_general['stock_max_change']);
		$hazard = rand(1,2);
		if ( $hazard == '2' )
		{
			$variation = - $variation ;
		}
		$new_price = ceil($exchange[$i]['stock_price'] * ( 1 + ( $variation / 100 )));
		$old_price = $exchange[$i]['stock_price'] ;
		$best_price = ( $new_price > $exchange[$i]['stock_best_price'] ) ? $new_price : $exchange[$i]['stock_best_price'];
		$worst_price = ( $new_price < $exchange[$i]['stock_worst_price'] ) ? $new_price : $exchange[$i]['stock_worst_price'];

		$sql = "UPDATE " . VAULT_EXCHANGE_TABLE ."
			SET stock_price = $new_price ,
			stock_previous_price = $old_price ,
			stock_best_price = $best_price ,
			stock_worst_price = $worst_price
			WHERE stock_id = ".$exchange[$i]['stock_id'];
		$result = $db->sql_query($sql);
		if( !$result )
		{
			message_die(GENERAL_ERROR, "Couldn't update stock exchange", "", __LINE__, __FILE__, $sql);
		}
	}
	$new_time = $board_config['stock_last_change'] +  $board_config['stock_time'];
	$lsql= "UPDATE ". CONFIG_TABLE . " SET config_value = $new_time WHERE config_name = 'stock_last_change' ";
	if ( !($lresult = $db->sql_query($lsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Vault_update_error'] , "", __LINE__, __FILE__, $lsql); 
	} 

	$sql = "SELECT * FROM  " . VAULT_USERS_TABLE ."
		WHERE newsletter = 1 "; 
	if (!$result = $db->sql_query($sql)) 
	{
		message_die(CRITICAL_ERROR, 'Error Getting Vault Config!');
	}
	$row = $db->sql_fetchrowset($result);

	for ( $i = 0 ; $i < count ( $row ) ; $i ++)
	{
		$sql = "UPDATE " . USERS_TABLE . " 
			SET user_new_privmsg = user_new_privmsg + 1 , user_last_privmsg = '99999999' 
			WHERE user_id = " . $row[$i]['owner_id']; 
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Could not update users table', '', __LINE__, __FILE__, $sql); 
		} 
		$user_id = $row[$i]['owner_id']; 
	
		$new_comment_subject = $lang['Vault_newsletter_pm'];
		$new_comment = sprintf($lang['Vault_newsletter_pm_explain'], '<a href="' . append_sid("vault.$phpEx?from=pm") . '">', '</a>');
		$comment_date = date("U"); 

		$sql = "INSERT INTO " . PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies, privmsgs_attach_sig) VALUES ('" . PRIVMSGS_NEW_MAIL . "', '" . str_replace("\'", "''", addslashes(sprintf($new_comment_subject))) . "', '2', '" . $user_id . "', '" . $comment_date . "', '0', '1', '1', '0')"; 
		if ( !$db->sql_query($sql) ) 
		{ 
			message_die(GENERAL_ERROR, 'Could not insert private message sent info', '', __LINE__, __FILE__, $sql); 
		} 
		$privmsg_sent_id = $db->sql_nextid(); 
		$privmsgs_text = $new_comment; 

		$sql = "INSERT INTO " . PRIVMSGS_TEXT_TABLE . " (privmsgs_text_id, privmsgs_text) VALUES ($privmsg_sent_id, '" . str_replace("\'", "''", addslashes(sprintf($privmsgs_text))) . "')"; 
		if ( !$db->sql_query($sql) ) 
		{ 
			message_die(GENERAL_ERROR, 'Could not insert private message sent text', '', __LINE__, __FILE__, $sql); 
		}
	}
}

?>
