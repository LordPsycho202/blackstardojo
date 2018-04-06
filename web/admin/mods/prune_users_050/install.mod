##############################################################
## MOD Title: Prune Inactive Users
## MOD Author: kkroo < princeomz2004@hotmail.com > (Omar Ramadan) http://phpbb-login.sourceforge.net
## MOD Description: Adds a page in the ACP that lets you prune users. You can build your own query to fetch the users, and you can notify inactive users by email to keep their accounts active.
## MOD Version: 0.5.0
## 
## Installation Level: Easy
## Installation Time: 5 minutes
## Files To Edit: includes/constants.php
## language/lang_english/lang_admin.php
## Included Files: root/admin/admin_prune_users.php
## root/templates/subSilver/admin/prune_users.tpl
## root/templates/subSilver/admin/prune_users_sql.tpl
## root/templates/subSilver/admin/prune_users_list.tpl
## root/templates/subSilver/images/user_unflag.gif
## root/templates/subSilver/images/user_flag.gif
## root/templates/sort.js
## root/language/lang_english/email/user_inactive_notify.tpl
## License: http://opensource.org/licenses/gpl-license.php GNU General Public License v2
## Generator: Phpbb.ModTeam.Tools
##############################################################
## For security purposes, please check: http://www.phpbb.com/mods/
## for the latest version of this MOD. Although MODs are checked
## before being allowed in the MODs Database there is no guarantee
## that there are no security problems within the MOD. No support
## will be given for MODs not found within the MODs Database which
## can be found at http://www.phpbb.com/mods/
##############################################################
## Author Notes: 
## I have too many inactive users on my forum.
##############################################################
## MOD History:
## 
## 2007-3-03 - Version 0.5.0
##      -	Added a feature that allows you to flag or unflag users and search for them
##      -	Added a feature that allows you to save the query
## 
## 2007-1-17 - Version 0.4.0
##      -	Fixed the bug where the notify button sends an email only to the first user
##      -	Fixed the bug where there was a sql error when the username contained a apostrophe
##      -	Added the email of the pruned user in the pruned user list
##      -	Added a column on when the last notification email was sent
## 
## 2006-10-30 - Version 0.3.0
##      -	Pruned users now listed in a list on a new page
##      -	Tables are now dynamically sortable using javascript
##      -	You can now notify checked users
##      -	Query builder enhanced to allow you to fetch users 'Prior to' and 'Within' selected dates
##      -	Added column to fetch users last login attempt
##      -	Added a security feature that doesn't let the board founder to be pruned
## 
## 2005-09-08 - Version 0.2.0
##      -	You can now build custom querys and notify inactive users by email to activate thier accounts.
## 
## 2005-09-03 - Version 0.1.0
##      -	Released.
## 
##############################################################
## Before Adding This MOD To Your Forum, You Should Back Up All Files Related To This MOD 
##############################################################

#
#-----[ COPY ]------------------------------------------
#
copy root/admin/admin_prune_users.php to admin/admin_prune_users.php
copy root/templates/subSilver/admin/prune_users.tpl to templates/subSilver/admin/prune_users.tpl
copy root/templates/subSilver/admin/prune_users_sql.tpl to templates/subSilver/admin/prune_users_sql.tpl
copy root/templates/subSilver/admin/prune_users_list.tpl to templates/subSilver/admin/prune_users_list.tpl
copy root/templates/subSilver/images/user_flag.gif to templates/subSilver/images/user_flag.gif
copy root/templates/subSilver/images/user_unflag.gif to templates/subSilver/images/user_unflag.gif
copy root/templates/sort.js to templates/sort.js
copy root/language/lang_english/email/user_inactive_notify.tpl to language/lang_english/email/user_inactive_notify.tpl

#
#-----[ OPEN ]------------------------------------------
#
includes/constants.php
#
#-----[ FIND ]------------------------------------------
#
define('CONFIRM_TABLE', $table_prefix.'confirm');

#
#-----[ BEFORE, ADD ]------------------------------------------
#
// prune inactive users mod by kkroo
define('PRUNED_USERS_TABLE', $table_prefix.'pruned_users');

#
#-----[ OPEN ]------------------------------------------
#
language/lang_english/lang_admin.php
#
#-----[ FIND ]------------------------------------------
#
//
// That's all Folks!
// -------------------------------------------------

#
#-----[ BEFORE, ADD ]------------------------------------------
#
//
// Prune Inactive Users by kkroo < princeomz2004@hotmail.com > (Omar Ramadan) http://phpbb-login.sourceforge.net
//

$lang['Click_return_userprune'] = 'Click %sHere%s to return to Prune Inactive Users';
$lang['Prune_users_page_title'] = 'Pruned Users List';
$lang['Pruned_users_page_title'] = 'Prune Inactive Users';
$lang['Pruned_users_page_explain'] = 'This is a list of users who have been pruned by this mod. You can organize this table by clicking on the dotted underlined headings.';
$lang['Prune_users_page_explain'] = 'This is a list of users fetched by the query you created. Select the users that you want to prune below and hit submit. If you would would like to notify the user that his account is inactive, click email on the user\'s row. You can flag or unflag a user by clicking on the flag in the column titled "Flagged" or by selecting them and hit "Flag/Unflag". You can organize this table by clicking on the dotted underlined headings.';
$lang['Notify_selected'] = 'Are you sure you want to notify the selected users?';
$lang['Flag_selected'] = 'Are you sure you want to flag/unflag the selected users?';
$lang['Email_Confirm_message'] = 'Are you sure you want to send a notification message to %s?';
$lang['Flag_Confirm_message'] = 'Are you sure you want to %action %s?';
$lang['Confirm_message'] = 'Are you sure you want to delete these users?';
$lang['Total_pruned_users'] = 'Total Pruned Users';
$lang['Last_visit'] = 'Last Visit';
$lang['Last_login_try'] = 'Last Login Try';
$lang['Last_Notified'] = 'Last Notified';
$lang['Select_all_none'] = 'Select All/None';
$lang['Selected'] = 'Selected';
$lang['Prior'] = 'Prior to';
$lang['Within'] = 'Within';
$lang['Never'] = 'Never';
$lang['Seven_days'] = 'Seven days ago';
$lang['Ten_days'] = 'Ten days ago';
$lang['Two_weeks'] = 'Two weeks ago';
$lang['One_month'] = 'One month ago';
$lang['Two_months'] = 'Two months ago';
$lang['Three_months'] = 'Three months ago';
$lang['Six_months'] = 'Six months ago';
$lang['One_year'] = 'One year ago';

$lang['Old_Version'] = 'Deleted With Old Version';
$lang['Notify_user'] = 'Notify';
$lang['Show_all'] = 'Show All';
$lang['user_regdate'] = 'Registered';
$lang['user_active'] = 'Activated';
$lang['User_Unflag'] = 'Unflag';
$lang['Flag_User'] = 'Flag';
$lang['Flagged'] = 'Flagged';
$lang['Flag'] = 'Flag/Unflag';
$lang['Remember_settings'] = 'Remember Settings';
$lang['user_posts'] = 'Posts';
$lang['Deleted_By'] = 'Deleted By';
$lang['Delete_Date'] = 'Delete Date';
$lang['Prune_users_sql_explain'] = 'Build your search criteria using the form below.';
$lang['Build_Query'] = 'Build Query';
$lang['Build_Your_Query'] = 'Build your query';
$lang['last_visit_explain'] = 'Search for users who either have or have not logged in within the selected period. A "Prior to" selection will include users who have never logged in. Select "Never" in both fields to find only users who have never logged in. To set the time window, also select whether the users you selected should be within the time frame, or prior to it.';
$lang['user_regdate_explain'] = 'Set a limit for the user registration dates. For example, if you use the "Active" check below, you might want to give users a few days to click their activation email first.  The default limit is "Prior to Seven days", meaning that users who have registered WITHIN the last 7 days are EXCLUDED from the search results. To set the time window, also select whether the users you selected should be within the time frame, or prior to it.';
$lang['user_active_explain'] = 'Use this option to search for users who have not activated their accounts, or who have become inactive for some other reason (e.g. they have submitted but not yet validated an email address change).';
$lang['user_posts_explain'] = 'Set a criterion for the user\'s post count. The most common setting is for "zero post" users.';
$lang['Show_all_explain'] = 'By checking the "Active" check, the list will display all the columns, even the ones you didn\'t check the "Active" check for. Otherwise, it will only display the columns you checked.';
$lang['users_flagged_explain'] = 'Use this option to limit the search for users who are or are not flagged. You can flag users to keep track of who notified you to keep their account.';
$lang['users_settings_explain'] = 'Select this box to remember your settings. The next time you load this page, the same settings will automatically be selected.';
$lang['Your_account_is_inactive'] = 'Your account is inactive.';


#
#-----[ SQL ]------------------------------------------
#
CREATE TABLE `phpbb_pruned_users` (
  `id` mediumint(8) NOT NULL auto_increment,
  `deleted_by` int(255) NOT NULL,
  `delete_time` int(11) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY  (`id`)
);

ALTER TABLE `phpbb_users` ADD `user_last_notified` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `phpbb_users` ADD `user_prune_flagged` INT( 11 ) NOT NULL DEFAULT '0';

INSERT INTO `phpbb_config` VALUES ( 'prune_users_default', '' );
ALTER TABLE `phpbb_config` CHANGE `config_value` `config_value` TEXT NULL DEFAULT NULL 


#
#-----[ SAVE/CLOSE ALL FILES ]------------------------------------------
#
# EoM
