<?php
require 'sys.php';

$page_id = PAGE_SEARCH;

require 'lib.php';

$opt['search'] = mo_get_get_opt('search');
$opt['index'] = mo_get_get_opt('index');


if ( $opt['search'] != 'newposts' && $opt['search'] != 'egosearch' && 
    $opt['search'] != 'unanswer' )
{
	mo_echo_header($mo_lang['search'], 'index.php');
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['search_not_match']);
	mo_echo_paragraph_end();
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}


if ( ( $opt['search'] == 'newposts' || $opt['search'] == 'egosearch' ) && 
    !$userdata['session_logged_in'] )
{
	mo_echo_header($mo_lang['search'], 'login.php', $mo_var['refresh_timer'], 
	    'redirect', 'search.php', 'search', $opt['search'], 
	    'index', $opt['index']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['login_entry']);
	mo_echo_br();
	mo_echo_url($mo_lang['login'], 'login.php', 'redirect', 'search.php', 
	    'search', $opt['search'], 'index', $opt['index']);
	mo_echo_paragraph_end();
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}


$rows = array();
$rows = mo_search($opt['search'], $opt['index']);

$num = count($rows);
if ( !$num )
{
	mo_echo_header($mo_lang['search']);
	mo_echo_paragraph_begin();
	mo_echo_msg($mo_lang['search_not_match']);
	mo_echo_paragraph_end();
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['menu'], 'menu.php');
	mo_echo_sp();
	mo_echo_url($mo_lang['home'], 'index.php');
	mo_echo_paragraph_end();
	mo_echo_footer();
	exit(0);
}


mo_echo_header($mo_lang['search']);
mo_echo_paragraph_begin();

$num1 = ( $num > $mo_var['row_size'] ) ? $mo_var['row_size'] : $num;
for ( $i = 0; $i < $num1; $i++ )
{
	if ( $i )
	{
		mo_echo_br();
	}
	mo_echo_url($rows[$i]['topic_title'], 'post.php', 
	    'topic_id', $rows[$i]['topic_id']);
}

if ( $num > $mo_var['row_size'] )
{
	mo_echo_paragraph_end();
	mo_echo_hr();
	mo_echo_paragraph_begin();
	mo_echo_url($mo_lang['next'], 'search.php', 'search', $opt['search'], 
	    'index', $opt['index'] + $mo_var['row_size']);
}
if ( $opt['index'] >= $mo_var['row_size'] )
{
	if ( $num > $mo_var['row_size'] )
	{
		mo_echo_sp();
	}
	else
	{
		mo_echo_paragraph_end();
		mo_echo_hr();
		mo_echo_paragraph_begin();
	}
	mo_echo_url($mo_lang['prev'], 'search.php', 'search', $opt['search'], 
	    'index', $opt['index'] - $mo_var['row_size']);
}

if ( $num > $mo_var['row_size'] || $opt['index'] >= $mo_var['row_size'] )
{
	mo_echo_br();
}
else
{
	mo_echo_paragraph_end();
	mo_echo_hr();
	mo_echo_paragraph_begin();
}

mo_echo_url($mo_lang['menu'], 'menu.php');
mo_echo_sp();
mo_echo_url($mo_lang['home'], 'index.php');

mo_echo_paragraph_end();
mo_echo_footer();
?>
