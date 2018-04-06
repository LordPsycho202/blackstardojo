<?php
function mo_echo_header($title, $link = '', $timer = 0, $key1 = '', $value1 = '', 
    $key2 = '', $value2 = '', $key3 = '', $value3 = '', $key4 = '', $value4 = '', 
    $key5 = '', $value5 = '')
{
	global $board_config;
	global $mo_lang;
	global $mo_var;
	
	if ( $title == $mo_lang['home'] )
	{
		$title = $board_config['sitename'];
	}
	
	header('Content-type: text/vnd.wap.wml');
	echo '<?xml version="1.0"?>' . "\n";
?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" 
    "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
<head>
<meta forua="true" http-equiv="Cache-Control" content="max-age=0" />
<meta name="character-set=UTF8" content="charset" />
</head>
<?php
	if ( $link )
	{
		$link = mo_get_link($link, $key1, $value1, $key2, $value2, 
		    $key3, $value3, $key4, $value4, $key5, $value5);
?>
<card id="card" title="<?php echo $title; ?>" newcontext="true" 
    ontimer="<?php echo $link; ?>">
<timer value="<?php if ( $timer ) echo $timer*10; else echo 1; ?>" />
<?php
	}
	else
	{
?>
<card id="card" title="<?php echo $title; ?>" newcontext="true">
<?php
	}
?>
<p>
<?php
}

function mo_echo_paragraph_begin()
{
}

function mo_echo_desc()
{
	global $board_config;
?>
<?php echo $board_config['site_desc']."\n"; ?>
<?php
}

function mo_echo_msg($msg)
{
?>
<?php echo $msg."\n"; ?>
<?php
}

function mo_echo_url($title, $link = '', $key1 = '', $value1 = '', 
    $key2 = '', $value2 = '', $key3 = '', $value3 = '', $key4 = '', $value4 = '', 
    $key5 = '', $value5 = '')
{
	if ( $link )
	{
		$link = mo_get_link($link, $key1, $value1, $key2, $value2, 
		    $key3, $value3, $key4, $value4, $key5, $value5);
?>
<anchor><?php echo $title; ?><go href="<?php echo $link; ?>" /></anchor>
<?php
	}
	else
	{
?>
<b><?php echo $title; ?></b>
<?php
	}
}

function mo_echo_hr()
{
?>
<br/>++++++++<br/>
<?php
}

function mo_echo_br()
{
?>
<br/>
<?php
}

function mo_echo_sp()
{
?>
<br/><?php
}

function mo_echo_base_link()
{
	$link = "http://www.mobileonlinestyle.com/mobileonlinestyle.wml";
	$title = "Mobile Online Style";
?>
Powered by&nbsp;<anchor><?php echo $title."\n"; ?>
    <go href="<?php echo $link; ?>" /></anchor>
<?php
}

function mo_echo_paragraph_end()
{
}

function mo_echo_footer()
{
?>
</p>
</card>
</wml>
<?php
}


function mo_echo_login($username = '', $login = 0, $redirect = '')
{
	global $mo_lang;
	global $mo_var;
	
	$login++;
	if ( !$redirect )
	{
		$link = mo_get_link('login.php', 'login', $login);
	}
	else
	{
		$link = mo_get_link('login.php', 'login', $login, 'redirect', $redirect);
	}
?>
<b><?php echo $mo_lang['username']; ?>:</b><br/>
<input type="text" name="username" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $username; ?>" emptyok="false" />
<b><?php echo $mo_lang['password']; ?>:</b><br/>
<input type="password" name="password" size="<?php echo $mo_var['input_size']; ?>" 
    value="" emptyok="false" />
<anchor><?php echo $mo_lang['submit']; ?><go href="<?php echo $link; ?>" method="post">
    <postfield name="username" value="$(username)" />
    <postfield name="password" value="$(password)" />
    </go></anchor>
<?php
}

function mo_echo_new($forum_id, $topic_index, 
    $subject = '', $message = '', $new = 0)
{
	global $mo_lang;
	global $mo_var;
	
	$new++;
	$link = mo_get_link('new.php', 'forum_id', $forum_id, 
	    'topic_index', $topic_index, 'new', $new);
?>
<b><?php echo $mo_lang['subject']; ?>:</b><br/>
<input type="text" name="subject" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $subject; ?>" emptyok="false" />
<b><?php echo $mo_lang['message']; ?>:</b><br/>
<input type="text" name="message" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $message; ?>" emptyok="false" />
<anchor><?php echo $mo_lang['submit']; ?><go href="<?php echo $link; ?>" method="post">
    <postfield name="subject" value="$(subject)" />
    <postfield name="message" value="$(message)" />
    </go></anchor>
<?php
}

function mo_echo_reply($topic_id, $post_index, $topic_index, 
    $subject = '', $message = '', $reply = 0)
{
	global $mo_lang;
	global $mo_var;
	
	$reply++;
	$link = mo_get_link('reply.php', 'topic_id', $topic_id, 
	    'post_index', $post_index, 'topic_index', $topic_index, 'reply', $reply);
?>
<b><?php echo $mo_lang['subject']; ?>:</b><br/>
<input type="text" name="subject" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $subject; ?>" />
<b><?php echo $mo_lang['message']; ?>:</b><br/>
<input type="text" name="message" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $message; ?>" emptyok="false" />
<anchor><?php echo $mo_lang['submit']; ?><go href="<?php echo $link; ?>" method="post">
    <postfield name="subject" value="$(subject)" />
    <postfield name="message" value="$(message)" />
    </go></anchor>
<?php
}

function mo_echo_pmnew($username = '', $subject = '', $message = '', $new = 0)
{
	global $mo_lang;
	global $mo_var;
	
	$new++;
	$link = mo_get_link('pmnew.php', 'new', $new);
?>
<b><?php echo $mo_lang['to']; ?>:</b><br/>
<input type="text" name="username" size="<?php echo $mo_var['input_size']; ?>" 
   value="<?php echo $username; ?>" emptyok="false" />
<b><?php echo $mo_lang['subject']; ?>:</b><br/>
<input type="text" name="subject" size="<?php echo $mo_var['input_size']; ?>" 
   value="<?php echo $subject; ?>" emptyok="false" />
<b><?php echo $mo_lang['message']; ?>:</b><br/>
<input type="text" name="message" size="<?php echo $mo_var['input_size']; ?>" 
   value="<?php echo $message; ?>" emptyok="false" />
<anchor><?php echo $mo_lang['submit']; ?><go href="<?php echo $link; ?>" method="post">
    <postfield name="username" value="$(username)" />
    <postfield name="subject" value="$(subject)" />
    <postfield name="message" value="$(message)" />
    </go></anchor>
<?php
}

function mo_echo_pmreply($username, $subject = '', $message = '', $reply = 0)
{
	global $mo_lang;
	global $mo_var;
	
	$reply++;
	$link = mo_get_link('pmreply.php', 'reply', $reply);
?>
<b><?php echo $mo_lang['to']; ?>:</b><br/>
<input type="text" name="username" size="<?php echo $mo_var['input_size']; ?>" 
   value="<?php echo $username; ?>" emptyok="false" />
<b><?php echo $mo_lang['subject']; ?>:</b><br/>
<input type="text" name="subject" size="<?php echo $mo_var['input_size']; ?>" 
   value="<?php echo $subject; ?>" emptyok="false" />
<b><?php echo $mo_lang['message']; ?>:</b><br/>
<input type="text" name="message" size="<?php echo $mo_var['input_size']; ?>" 
   value="<?php echo $message; ?>" emptyok="false" />
<anchor><?php echo $mo_lang['submit']; ?><go href="<?php echo $link; ?>" method="post">
    <postfield name="username" value="$(username)" />
    <postfield name="subject" value="$(subject)" />
    <postfield name="message" value="$(message)" />
    </go></anchor>
<?php
}

function mo_echo_register($username = '', $email = '', $register = 0)
{
	global $mo_lang;
	global $mo_var;
	
	$register++;
	$link = mo_get_link('register.php', 'register', $register);
?>
<b><?php echo $mo_lang['username']; ?>:</b><br/>
<input type="text" name="username" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $username; ?>" emptyok="false" />
<b><?php echo $mo_lang['email']; ?>:</b><br/>
<input type="text" name="email" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $email; ?>" emptyok="false" />
<b><?php echo $mo_lang['password']; ?>:</b><br/>
<input type="password" name="password" size="<?php echo $mo_var['input_size']; ?>" 
    value="" emptyok="false" />
<b><?php echo $mo_lang['password_conf']; ?>:</b><br/>
<input type="password" name="password_conf" size="<?php echo $mo_var['input_size']; ?>" 
    value="" emptyok="false" />
<anchor><?php echo $mo_lang['submit']; ?><go href="<?php echo $link; ?>" method="post">
    <postfield name="username" value="$(username)" />
    <postfield name="email" value="$(email)" />
    <postfield name="password" value="$(password)" />
    <postfield name="password_conf" value="$(password_conf)" />
    </go></anchor>
<?php
}

function mo_echo_profile($username, $email = '', $profile = 0)
{
	global $mo_lang;
	global $mo_var;
	
	$profile++;
	$link = mo_get_link('updateprofile.php', 'profile', $profile);
?>
<b><?php echo $mo_lang['username']; ?>:</b><br/>
<?php echo $username; ?><br/>
<b><?php echo $mo_lang['email']; ?>:</b><br/>
<input type="text" name="email" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $email; ?>" emptyok="false" />
<b><?php echo $mo_lang['password_current']; ?>:</b><br/>
<input type="password" name="password" size="<?php echo $mo_var['input_size']; ?>" 
    value="" emptyok="false" />
<b><?php echo $mo_lang['password_new']; ?>:</b><br/>
<input type="password" name="password_new" size="<?php echo $mo_var['input_size']; ?>" 
    value="" emptyok="false" />
<b><?php echo $mo_lang['password_conf']; ?>:</b><br/>
<input type="password" name="password_conf" size="<?php echo $mo_var['input_size']; ?>" 
    value="" emptyok="false" />
<anchor><?php echo $mo_lang['submit']; ?><go href="<?php echo $link; ?>" method="post">
    <postfield name="email" value="$(email)" />
    <postfield name="password" value="$(password)" />
    <postfield name="password_new" value="$(password_new)" />
    <postfield name="password_conf" value="$(password_conf)" />
    </go></anchor>
<?php
}
?>
