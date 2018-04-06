<?php
$userdata = session_pagestart($user_ip, $page_id);
init_userprefs($userdata);
if ( $mo_enable_nuke )
{
	$session_id = session_begin($userdata['user_id'], $user_ip, $page_id);
}


require 'var.php';
$mo_var = array();
$mo_var = mo_get_var();

require 'db.php';

require 'opt.php';

$mo_opt_type = mo_get_type(mo_get_get_opt('type', ''));
require $mo_opt_type . '.' . $phpEx;

$lang = $userdata['user_lang'] ? $userdata['user_lang'] : $board_config['default_lang'];
require 'lang_' . $lang . '.' . $phpEx;
$mo_lang = array();
$mo_lang = mo_get_lang();


function mo_append_sid($url)
{
	global $SID;
	
	if ( !empty($SID) && !preg_match('#sid=#', $url) )
	{
		$url .= ( strpos($url, '?') ? '&amp;' : '?' ) . $SID;
	}
	
	return $url;
}

function mo_get_one_link($link, $key, $value)
{
	if ( !$key )
	{
		return $link;
	}
	
	$link .= (strpos($link, '?') ?  '&amp;' : '?') . $key . '=' . $value;
	
	return $link;
}

function mo_get_link($link, $key1 = '', $value1 = '', $key2 = '', $value2 = '', 
    $key3 = '', $value3 = '', $key4 = '', $value4 = '', $key5 = '', $value5 = '')
{
	global $mo_opt_type;
	
	$link = mo_append_sid($link);
	$link = mo_get_one_link($link, 'type', $mo_opt_type);
	
	$link = mo_get_one_link($link, $key1, $value1);
	$link = mo_get_one_link($link, $key2, $value2);
	$link = mo_get_one_link($link, $key3, $value3);
	$link = mo_get_one_link($link, $key4, $value4);
	$link = mo_get_one_link($link, $key5, $value5);
	
	return $link;
}

function mo_prepare_message($message)
{
	$html_match = array('/&/', '/>/', '/</');
	$html_replace = array('&amp;', '&gt;', '&lt;');
	
	$message = preg_replace($html_match, $html_replace, $message);
	$message = preg_replace('/&amp;#(x[0-9a-f]+|[0-9]+);/i', '&#$1;', $message);
	
	return $message;
}

function mo_prepare_message1($message)
{
	$html_match = array('/&/', '/>/', '/</', '/"/');
	$html_replace = array('&amp;', '&gt;', '&lt;', '&quot;');
	
	$message = preg_replace($html_match, $html_replace, $message);
	$message = preg_replace('/&amp;#(x[0-9a-f]+|[0-9]+);/i', '&#$1;', $message);
	
	return $message;
}

function mo_get_online_status($rows)
{
	$registered = 0;
	$hidden = 0;
	$guest = 0;
	$registered_users = array();
	
	$prev_user = 0;
	$prev_ip = '';
	
	foreach ( $rows as $value )
	{
		if ( $value['session_logged_in'] )
		{
			if ( $value['user_id'] == $prev_user )
			{
				continue;
			}
			
			if ( $value['user_allow_viewonline'] )
			{
				$registered_users[] = $value;
				$registered++;
			}
			else
			{
				$hidden++;
			}
		}
		else
		{
			if ( $value['session_ip'] == $prev_ip )
			{
				continue;
			}
			
			$guest++;
		}
		
		$prev_user = $value['user_id'];
		$prev_ip = $value['session_ip'];
	}
	
	return array($registered, $hidden, $guest, $registered_users);
}

function get_user_enable()
{
	global $userdata;
	global $board_config;
	
	$enable_bbcode = $board_config['allow_bbcode'] ? 
	    $userdata['user_allowbbcode'] : 0;
	$enable_html = $board_config['allow_html'] ?  
	    $userdata['user_allowhtml'] : 0;
	$enable_smilies = $board_config['allow_smilies'] ? 
	    $userdata['user_allowsmile'] : 0;
	$enable_sig = ( $board_config['allow_sig'] && $userdata['user_sig'] ) ? 1 : 0;
	
	return array($enable_bbcode, $enable_html, $enable_smilies, $enable_sig);
}

function mo_get_type($type)
{
	global $mo_enable_wurfl;
	global $wurfl;
	
	if ( $type || !$mo_enable_wurfl )
	{
		if ( $type != 'xhtml' && $type != 'wap' )
		{
			$type = 'html';
		}
		
		return $type;
	}
	
	if ( $wurfl->browser_is_wap )
	{
		$type = $wurfl->capabilities['markup']['html_wi_oma_xhtmlmp_1_0'] ? 
		    'xhtml' : 'wap';
	}
	else
	{
		$type = 'html';
	}
	
	return $type;
}

function mo_post_from_mobile($message)
{
	global $mo_enable_wurfl;
	global $wurfl;
	
	if ( !$mo_enable_wurfl )
	{
		return $message;
	}
	
	if ( !$wurfl->brand )
	{
		return $message;
	}
	
	$message .= "<br/>";
	$message .= "<br/>-- posted from $wurfl->brand $wurfl->model --";
	
	return $message;
}

function mo_get_last($index)
{
	global $mo_var;
	
	$index--;
	$index -= $index % $mo_var['row_size'];
	
	return $index;
}

function get_redirect_opt($redirect)
{
	$redirect_opt = '';
	
	if ( !preg_match("/redirect=[0-9a-z\.]+&(.+)/si", $redirect, $redirection) )
	{
		return $redirect_opt;
	}
	
	$redirect_opts = explode('&', $redirection[1]);
	for ( $i = 0; $i < count($redirect_opts); $i++ )
	{
		if ( ereg("sid=", $redirect_opts[$i]) )
		{
			continue;
		}
		elseif ( ereg("type=", $redirect_opts[$i]) )
		{
			continue;
		}
		
		if ( $redirect_opt )
		{
			$redirect_opt .= '&amp;';
		}
		$redirect_opt .= $redirect_opts[$i];
	}
	
	return $redirect_opt;
}
?>
