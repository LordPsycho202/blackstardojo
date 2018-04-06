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
	
	header("Content-type: text/html");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
	if ( $link )
	{
		$link = mo_get_link($link, $key1, $value1, $key2, $value2, 
		    $key3, $value3, $key4, $value4, $key5, $value5);
?>
<meta http-equiv="refresh" content="<?php echo $timer; ?>; URL=<?php echo $link; ?>" />
<?php
	}
?>
<title><?php echo $title; ?></title>
<style type="text/css" media="all">@import "html.css";</style>
</head>
<body>
<?php
}

function mo_echo_paragraph_begin()
{
?>
<p>
<?php
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
<a href="<?php echo $link; ?>"><?php echo $title; ?></a>
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
<hr>
<?php
}

function mo_echo_br()
{
?>
<br>
<?php
}

function mo_echo_sp()
{
?>
&nbsp;<?php
}

function mo_echo_base_link()
{
	$link = "http://www.mobileonlinestyle.com/mobileonlinestyle.html";
	$title = "Mobile Online Style";
?>
Powered by <a href="<?php echo $link; ?>">
    <?php echo $title; ?></a>
<?php
}

function mo_echo_paragraph_end()
{
?>
</p>
<?php
}

function mo_echo_footer()
{
?>
</body>
</html>
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
<form action="<?php echo $link; ?>" method="post">
<p>
<label><b><?php echo $mo_lang['username']; ?>:</b></label><br/>
<input type="text" name="username" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $username; ?>" /><br/>
<label><b><?php echo $mo_lang['password']; ?>:</b></label><br/>
<input type="password" name="password" size="<?php echo $mo_var['input_size']; ?>" 
    value="" /><br/>
<input type="submit" name="submit" value="<?php echo $mo_lang['submit']; ?>" />
</p>
</form>
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
<form action="<?php echo $link; ?>" method="post">
<p>
<label><b><?php echo $mo_lang['subject']; ?>:</b></label><br/>
<input type="text" name="subject" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $subject; ?>" /><br/>
<label><b><?php echo $mo_lang['message']; ?>:</b></label><br/>
<textarea name="message" rows="<?php echo $mo_var['textarea_rows']; ?>" 
    cols="<?php echo $mo_var['textarea_cols']; ?>">
<?php echo $message; ?></textarea><br/>
<input type="submit" name="submit" value="<?php echo $mo_lang['submit']; ?>" />
</p>
</form>
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
<form action="<?php echo $link; ?>" method="post">
<p>
<label><b><?php echo $mo_lang['subject']; ?>:</b></label><br/>
<input type="text" name="subject" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $subject; ?>" /><br/>
<label><b><?php echo $mo_lang['message']; ?>:</b></label><br/>
<textarea name="message" rows="<?php echo $mo_var['textarea_rows']; ?>" 
    cols="<?php echo $mo_var['textarea_cols']; ?>">
<?php echo $message; ?></textarea><br/>
<input type="submit" name="submit" value="<?php echo $mo_lang['submit']; ?>" />
</p>
</form>
<?php
}

function mo_echo_pmnew($username = '', $subject = '', $message = '', $new = 0)
{
	global $mo_lang;
	global $mo_var;
	
	$new++;
	$link = mo_get_link('pmnew.php', 'new', $new);
?>
<form action="<?php echo $link; ?>" method="post">
<p>
<label><b><?php echo $mo_lang['username']; ?>:</b></label><br/>
<input type="text" name="username" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $username; ?>" /><br/>
<label><b><?php echo $mo_lang['subject']; ?>:</b></label><br/>
<input type="text" name="subject" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $subject; ?>" /><br/>
<label><b><?php echo $mo_lang['message']; ?>:</b></label><br/>
<textarea name="message" rows="<?php echo $mo_var['textarea_rows']; ?>" 
    cols="<?php echo $mo_var['textarea_cols']; ?>">
<?php echo $message; ?></textarea><br/>
<input type="submit" name="submit" value="<?php echo $mo_lang['submit']; ?>" />
</p>
</form>
<?php
}

function mo_echo_pmreply($username, $subject = '', $message = '', $reply = 0)
{
	global $mo_lang;
	global $mo_var;
	
	$reply++;
	$link = mo_get_link('pmreply.php', 'reply', $reply);
?>
<form action="<?php echo $link; ?>" method="post">
<p>
<label><b><?php echo $mo_lang['username']; ?>:</b></label><br/>
<input type="text" name="username" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $username; ?>" /><br/>
<label><b><?php echo $mo_lang['subject']; ?>:</b></label><br/>
<input type="text" name="subject" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $subject; ?>" /><br/>
<label><b><?php echo $mo_lang['message']; ?>:</b></label><br/>
<textarea name="message" rows="<?php echo $mo_var['textarea_rows']; ?>" 
    cols="<?php echo $mo_var['textarea_cols']; ?>">
<?php echo $message; ?></textarea><br/>
<input type="submit" name="submit" value="<?php echo $mo_lang['submit']; ?>" />
</p>
</form>
<?php
}

function mo_echo_register($username = '', $email = '', $register = 0)
{
	global $mo_lang;
	global $mo_var;
	
	$register++;
	$link = mo_get_link('register.php', 'register', $register);
?>
<form action="<?php echo $link; ?>" method="post">
<p>
<label><b><?php echo $mo_lang['username']; ?>:</b></label><br/>
<input type="text" name="username" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $username; ?>" /><br/>
<label><b><?php echo $mo_lang['email']; ?>:</b></label><br/>
<input type="text" name="email" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $email; ?>" /><br/>
<label><b><?php echo $mo_lang['password']; ?>:</b></label><br/>
<input type="password" name="password" size="<?php echo $mo_var['input_size']; ?>" 
    value="" /><br/>
<label><b><?php echo $mo_lang['password_conf']; ?>:</b></label><br/>
<input type="password" name="password_conf" size="<?php echo $mo_var['input_size']; ?>" 
    value="" /><br/>
<input type="submit" name="submit" value="<?php echo $mo_lang['submit']; ?>" />
</p>
</form>
<?php
}

function mo_echo_profile($username, $email = '', $profile = 0)
{
	global $mo_lang;
	global $mo_var;
	
	$profile++;
	$link = mo_get_link('updateprofile.php', 'profile', $profile);
?>
<form action="<?php echo $link; ?>" method="post">
<p>
<label><b><?php echo $mo_lang['username']; ?>:</b></label><br/>
<label><?php echo $username; ?></label><br/>
<label><b><?php echo $mo_lang['email']; ?>:</b></label><br/>
<input type="text" name="email" size="<?php echo $mo_var['input_size']; ?>" 
    value="<?php echo $email; ?>" /><br/>
<label><b><?php echo $mo_lang['password_current']; ?>:</b></label><br/>
<input type="password" name="password" size="<?php echo $mo_var['input_size']; ?>" 
    value="" /><br/>
<label><b><?php echo $mo_lang['password_new']; ?>:</b></label><br/>
<input type="password" name="password_new" size="<?php echo $mo_var['input_size']; ?>" 
    value="" /><br/>
<label><b><?php echo $mo_lang['password_conf']; ?>:</b></label><br/>
<input type="password" name="password_conf" size="<?php echo $mo_var['input_size']; ?>" 
    value="" /><br/>
<input type="submit" name="submit" value="<?php echo $mo_lang['submit']; ?>" />
</p>
</form>
<?php
}
?>
