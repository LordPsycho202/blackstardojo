<?php
/*
Change History
================
Version 1.6
    - Add     $mo_lang['aim']
    - Add     $mo_lang['icq']
    - Add     $mo_lang['msn']
    - Improve $mo_lang['user_not_exist']
    - Add     $mo_lang['yim']
*/

function mo_get_lang()
{
	$mo_lang = array();
	
	$mo_lang['aim'] = 'AIM Address';
	$mo_lang['author'] = 'Author';
	
	$mo_lang['cat_not_exist'] = 'The category does not exist.';
	$mo_lang['continue'] = 'Continue';
	
	$mo_lang['email'] = 'E-mail address';
	$mo_lang['email_taken'] = 'E-mail address has already been taken.';
	
	$mo_lang['field_required'] = 'Fill in the required fields.';
	$mo_lang['first'] = 'First';
	$mo_lang['flood_error'] = 'Post too soon; Please try again in a short while.';
	$mo_lang['forum_not_exist'] = 'The forum does not exist.';
	$mo_lang['forums'] = 'Forums';
	$mo_lang['forums_empty'] = 'No forums in this category.';
	$mo_lang['from'] = 'From';
	
	$mo_lang['granted_post_only'] = 'Granted users post only forum.';
	$mo_lang['granted_read_only'] = 'Granted users read only forum.';
	$mo_lang['granted_reply_only'] = 'Granted users reply only forum.';
	$mo_lang['guest'] = 'Guest';
	
	$mo_lang['hidden'] = 'Hidden';
	$mo_lang['home'] = 'Home';
	
	$mo_lang['icq'] = 'ICQ Number';
	
	$mo_lang['last'] = 'Last';
	$mo_lang['login'] = 'Login';
	$mo_lang['login_entry'] = 'Please login.';
	$mo_lang['login_error'] = 'Incorrect or inactive username, or invalid password.';
	$mo_lang['login_successfully'] = 'Login successfully.';
	$mo_lang['logout'] = 'Logout';
	$mo_lang['logout_completely'] = 'Logout completely.';
	
	$mo_lang['menu'] = 'Menu';
	$mo_lang['message'] = 'Message';
	$mo_lang['message_empty'] = 'Please enter a message.';
	$mo_lang['moderator_post_only'] = 'Moderators post only forum.';
	$mo_lang['moderator_read_only'] = 'Moderators read only forum.';
	$mo_lang['moderator_reply_only'] = 'Moderators reply only forum.';
	$mo_lang['msn'] = 'MSN Messenger';
	
	$mo_lang['new'] = 'New';
	$mo_lang['new_failed'] = 'New post failed.';
	$mo_lang['new_successfully'] = 'New post successfully.';
	$mo_lang['next'] = 'Next';
	$mo_lang['none'] = 'None';
	
	$mo_lang['online_users'] = 'Online Users';
	
	$mo_lang['password'] = 'Password';
	$mo_lang['password_conf'] = 'Confirm Password';
	$mo_lang['password_current'] = 'Current Password';
	$mo_lang['password_mismatch'] = 'The passwords did not match.';
	$mo_lang['password_new'] = 'New Password';
	$mo_lang['password_wrong'] = 'Invalid password.';
	$mo_lang['pm'] = 'Private Message';
	$mo_lang['pm_alert'] = 'New private message(s)';
	$mo_lang['pm_empty'] = 'No messages in this folder.';
	$mo_lang['pm_inbox_full'] = 'Receiver PM Inbox is full.';
	$mo_lang['pm_not_exist'] = 'The message does not exist.';
	$mo_lang['pm_savebox_full'] = 'PM Savebox is full.';
	$mo_lang['pm_sentbox_full'] = 'PM Sentbox is full.';
	$mo_lang['pmin'] = 'PM Inbox';
	$mo_lang['pmmv'] = 'Save PM';
	$mo_lang['pmmv_failed'] = 'Save PM failed.';
	$mo_lang['pmmv_successfully'] = 'Save PM successfully.';
	$mo_lang['pmnew'] = 'New PM';
	$mo_lang['pmnew_failed'] = 'New PM failed.';
	$mo_lang['pmnew_successfully'] = 'New PM successfully.';
	$mo_lang['pmreply'] = 'Reply PM';
	$mo_lang['pmreply_failed'] = 'Reply PM failed.';
	$mo_lang['pmreply_successfully'] = 'Reply PM successfully.';
	$mo_lang['pmrm'] = 'Delete PM';
	$mo_lang['pmrm_failed'] = 'Delete PM failed.';
	$mo_lang['pmrm_successfully'] = 'Delete PM successfully.';
	$mo_lang['pmsave'] = 'PM Savebox';
	$mo_lang['pmsent'] = 'PM Sentbox';
	$mo_lang['pmview'] = 'View PM';
	$mo_lang['pmview_failed'] = 'View PM failed.';
	$mo_lang['post'] = 'Post';
	$mo_lang['prev'] = 'Previous';
	$mo_lang['profile'] = 'Profile';
	$mo_lang['profile_completely'] = 'Update profile completely.';
	
	$mo_lang['register'] = 'Register';
	$mo_lang['register_failed'] = 'Register failed.';
	$mo_lang['register_successfully'] = 'Register successfully.';
	$mo_lang['registered'] = 'Registered';
	$mo_lang['registered_post_only'] = 'Registered users post only forum.';
	$mo_lang['registered_read_only'] = 'Registered users read only forum.';
	$mo_lang['registered_reply_only'] = 'Registered users reply only forum.';
	$mo_lang['registered_users'] = 'Registered Users';
	$mo_lang['reply'] = 'Reply';
	$mo_lang['reply_failed'] = 'Reply post failed.';
	$mo_lang['reply_successfully'] = 'Reply post successfully.';
	
	$mo_lang['search'] = 'Search';
	$mo_lang['search_new'] = 'View new posts';
	$mo_lang['search_not_match'] = 'No topics or posts met your search criteria.';
	$mo_lang['search_your'] = 'View your posts';
	$mo_lang['search_unanswered'] = 'View unanswered posts';
	$mo_lang['subject'] = 'Subject';
	$mo_lang['subject_empty'] = 'Please specify a subject.';
	$mo_lang['submit'] = 'Submit';
	
	$mo_lang['to'] = 'To';
	$mo_lang['topic_not_exist'] = 'The topic does not exist.';
	$mo_lang['topics'] = 'Topics';
	$mo_lang['topics_empty'] = 'No posts in this forum.';
	
	$mo_lang['user_not_exist'] = 'Such user does not exist.';
	$mo_lang['username'] = 'Username';
	$mo_lang['username_empty'] = 'Please specify a username.';
	$mo_lang['username_taken'] = 'Username has already been taken.';
	
	$mo_lang['view'] = 'View';
	
	$mo_lang['who_is_online'] = 'Who is Online?';
	
	$mo_lang['yim'] = 'Yahoo Messenger';
	
	return $mo_lang;
}
?>
