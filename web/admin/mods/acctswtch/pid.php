<?php
define("IN_PHPBB", true);

$phpbb_root_path = "./";
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Set page ID for session management
//
$userdata = session_pagestart($user_ip, PAGE_LOGIN);
init_userprefs($userdata);
//
// End session management
//

$page_title = $lang['PID'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'pid' => 'pid.tpl'));

if ( isset($HTTP_POST_VARS['pidreq']) )
{
	$pid = $HTTP_POST_VARS['pid'] ? $HTTP_POST_VARS['pid'] : '';
	$pid = (ereg("^[0-9]+$", $pid)) ? $pid : '0';
	if ($pid == '')
		message_die(GENERAL_ERROR, "You specified no Personnel ID.  Please go back and enter one.");
	if($pid == '0')
		message_die(GENERAL_ERROR, "Sorry, Personnel IDs must be numerical in value.  Please go back and try again.");
	else{
		$sql = "SELECT * FROM " . USERS_TABLE . " WHERE PID = '$pid'";
		$checkpid = $db->sql_query($sql);
		if ($checkresult = $db->sql_fetchrow($checkpid))
			message_die(GENERAL_ERROR, "Sorry, that Personnel ID is taken.  Please go back and choose another.");
	
		else
		{
			$username = isset($HTTP_POST_VARS['username']) ? phpbb_clean_username($HTTP_POST_VARS['username']) : '';
			$password = $HTTP_POST_VARS['password'] ? $HTTP_POST_VARS['password'] : '';
			$password = md5($password);	
			$validsql = "SELECT * FROM " . USERS_TABLE . " WHERE username = '$username' AND user_password = '$password'";
			$validresult = $db->sql_query($validsql);
			if ($validresult)
			{
				$sql = "UPDATE " . USERS_TABLE . " SET  PID= '$pid' WHERE username='$username'";
				$result = $db->sql_query($sql);
				if ($result)
				{	
					$message = $lang['Profile_updated'] . "<br /><br />" . sprintf($lang['Click_return_index'],  "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
					message_die(GENERAL_ERROR, $message);
				}
				else
					message_die(GENERAL_ERROR, "Could not update user table");	
			}
			else
				message_die(GENERAL_ERROR, "Could not retrieve user info");		
		}
	}
	
}
else
{
	$template->assign_vars(array(
			"USERNAME" => $username,
			"PASSWORD" => $user_password,
			"S_PID_ACTION" =>append_sid("pid.$phpEx"))
		);
	$template->pparse('pid');
}
?>
