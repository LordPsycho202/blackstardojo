<?php
/***************************************************************************
 *                               constants.php
 *                            -------------------
 *   begin                : Saturday', Feb 13', 2001
 *   copyright            : ('C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: constants.php,v 1.47.2.6 2005/10/30 15:17:14 acydburn Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License', or
 *   ('at your option) any later version.
 *
 ***************************************************************************/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

// Debug Level
//define('DEBUG', 1); // Debugging on
define('DEBUG', 1); // Debugging off


// User Levels <- Do not change the values of USER or ADMIN
define('DELETED', -1);
define('ANONYMOUS', -1);

define('USER', 0);
define('ADMIN', 1);
define('MOD', 2);


// User related
define('USER_ACTIVATION_NONE', 0);
define('USER_ACTIVATION_SELF', 1);
define('USER_ACTIVATION_ADMIN', 2);

define('USER_AVATAR_NONE', 0);
define('USER_AVATAR_UPLOAD', 1);
define('USER_AVATAR_REMOTE', 2);
define('USER_AVATAR_GALLERY', 3);


// Group settings
define('GROUP_OPEN', 0);
define('GROUP_CLOSED', 1);
define('GROUP_HIDDEN', 2);
define('GROUP_AUTO', 3);




// Forum state
define('FORUM_UNLOCKED', 0);
define('FORUM_LOCKED', 1);


// Topic status
define('TOPIC_UNLOCKED', 0);
define('TOPIC_LOCKED', 1);
define('TOPIC_MOVED', 2);
define('TOPIC_WATCH_NOTIFIED', 1);
define('TOPIC_WATCH_UN_NOTIFIED', 0);


// Topic types
define('POST_NORMAL', 0);
define('POST_STICKY', 1);
define('POST_ANNOUNCE', 2);
define('POST_GLOBAL_ANNOUNCE', 3);


// SQL codes
define('BEGIN_TRANSACTION', 1);
define('END_TRANSACTION', 2);


// Error codes
define('GENERAL_MESSAGE', 200);
define('GENERAL_ERROR', 202);
define('CRITICAL_MESSAGE', 203);
define('CRITICAL_ERROR', 204);


// Private messaging
define('PRIVMSGS_READ_MAIL', 0);
define('PRIVMSGS_NEW_MAIL', 1);
define('PRIVMSGS_SENT_MAIL', 2);
define('PRIVMSGS_SAVED_IN_MAIL', 3);
define('PRIVMSGS_SAVED_OUT_MAIL', 4);
define('PRIVMSGS_UNREAD_MAIL', 5);


// URL PARAMETERS
define('POST_TOPIC_URL', 't');
define('POST_CAT_URL', 'c');
define('POST_FORUM_URL', 'f');
define('POST_USERS_URL', 'u');
define('POST_POST_URL', 'p');
define('POST_GROUPS_URL', 'g');
define('POST_MEDAL_URL', 'm');	// Medal MOD


// Session parameters
define('SESSION_METHOD_COOKIE', 100);
define('SESSION_METHOD_GET', 101);


// Page numbers for session handling
define('PAGE_INDEX', 0);
define('PAGE_LOGIN', -1);
define('PAGE_SEARCH', -2);
define('PAGE_REGISTER', -3);
define('PAGE_PROFILE', -4);
define('PAGE_VIEWONLINE', -6);
define('PAGE_VIEWMEMBERS', -7);
define('PAGE_FAQ', -8);
define('PAGE_POSTING', -9);
define('PAGE_PRIVMSGS', -10);
define('PAGE_GROUPCP', -11);
define('PAGE_MEDALS', -12);	// Medal MOD
define('PAGE_JRCHAT', -15);
define('PAGE_JRCHATRP', -16);
define('PAGE_WIO', -17);

// bookies Mod
define('PAGE_BOOKIES', -1883);
define('PAGE_BOOKIE_ALLSTATS', -1882);
define('PAGE_BOOKIE_YOURSTATS', -1881);

define('PAGE_TOPIC_OFFSET', 5000);


// Auth settings
define('AUTH_LIST_ALL', 0);
define('AUTH_ALL', 0);

define('AUTH_REG', 1);
define('AUTH_ACL', 2);
define('AUTH_MOD', 3);
define('AUTH_ADMIN', 5);

define('AUTH_VIEW', 1);
define('AUTH_READ', 2);
define('AUTH_POST', 3);
define('AUTH_REPLY', 4);
define('AUTH_EDIT', 5);
define('AUTH_DELETE', 6);
define('AUTH_ANNOUNCE', 7);
define('AUTH_STICKY', 8);
define('AUTH_POLLCREATE', 9);
define('AUTH_VOTE', 10);
define('AUTH_ATTACH', 11);


/*******************
** MOD: Report Posts
*******************/
// BEGIN : BEFORE, ADD
// Report posts
define('REPORT_POST_NEW', 1);
define('REPORT_POST_CLOSED', 2);
// END : BEFORE, ADD

// Table names
// prune inactive users mod by kkroo
define('PRUNED_USERS_TABLE', $table_prefix.'pruned_users');

define('CONFIRM_TABLE', $table_prefix.'confirm');
define('AUTH_ACCESS_TABLE', $table_prefix.'auth_access');
define('BANLIST_TABLE', $table_prefix.'banlist');
define('CATEGORIES_TABLE', $table_prefix.'categories');
define('CONFIG_TABLE', $table_prefix.'config');
define('DISALLOW_TABLE', $table_prefix.'disallow');
define('FORUMS_TABLE', $table_prefix.'forums');
define('GROUPS_TABLE', $table_prefix.'groups');
define('MEDAL_TABLE', $table_prefix.'medal');	// Medal MOD
define('MEDAL_MOD_TABLE', $table_prefix.'medal_mod');	// Medal MOD
define('MEDAL_USER_TABLE', $table_prefix.'medal_user');	// Medal MOD

define('POSTS_TABLE', $table_prefix.'posts');
define('POSTS_TEXT_TABLE', $table_prefix.'posts_text');
define('PRIVMSGS_TABLE', $table_prefix.'privmsgs');
define('PRIVMSGS_TEXT_TABLE', $table_prefix.'privmsgs_text');
define('PRIVMSGS_IGNORE_TABLE', $table_prefix.'privmsgs_ignore');
define('PRUNE_TABLE', $table_prefix.'forum_prune');
define('RANKS_TABLE', $table_prefix.'ranks');
define('SEARCH_TABLE', $table_prefix.'search_results');
define('SEARCH_WORD_TABLE', $table_prefix.'search_wordlist');
define('SEARCH_MATCH_TABLE', $table_prefix.'search_wordmatch');
define('SESSIONS_TABLE', $table_prefix.'sessions');
define('SESSIONS_KEYS_TABLE', $table_prefix.'sessions_keys');
define('SMILIES_TABLE', $table_prefix.'smilies');
define('THEMES_TABLE', $table_prefix.'themes');
define('THEMES_NAME_TABLE', $table_prefix.'themes_name');
define('TOPICS_TABLE', $table_prefix.'topics');
define('TOPICS_WATCH_TABLE', $table_prefix.'topics_watch');
define('FORUMS_WATCH_TABLE', $table_prefix.'forums_watch');
define('USER_GROUP_TABLE', $table_prefix.'user_group');
define('USERS_TABLE', $table_prefix.'users');
define('WORDS_TABLE', $table_prefix.'words');
define('VOTE_DESC_TABLE', $table_prefix.'vote_desc');
define('VOTE_RESULTS_TABLE', $table_prefix.'vote_results');
define('VOTE_USERS_TABLE', $table_prefix.'vote_voters');
define('WPM', $table_prefix.'wpm');
/*******************
** MOD: Report Posts
*******************/
// BEGIN : AFTER, ADD
define('POST_REPORTS_TABLE', $table_prefix.'post_reports');
// END : AFTER, ADD

// Start Store MOD --------------------------
//tables
define('STORE_LIST', $table_prefix.'store_list');
define('STORE_ITEMS', $table_prefix.'store_items');
define('STORE_INVENTORY', $table_prefix.'store_inventory');

//constants
define('PAGE_STORE', -34);
define('BOARD_OWNED', 0);
define('SPECIAL_ITEM', 1);
define('ENABLE_SELL_SPECIAL', 1);
define('DISABLE_SELL_SPECIAL', 0);
// Stop Store MOD --------------------------


// Bookies Mod
define('BOOKIE_BETS_TABLE',  $table_prefix.'bookie_bets');
define('BOOKIE_STATS_TABLE',  $table_prefix.'bookie_stats');
define('BOOKIE_ADMIN_BETS_TABLE',  $table_prefix.'bookie_admin_bets');
define('BOOKIE_MEETINGS_TABLE',  $table_prefix.'bookie_meetings');
define('BOOKIE_SELECTIONS_TABLE',  $table_prefix.'bookie_selections');
define('BOOKIE_SELECTIONS_DATA_TABLE',  $table_prefix.'bookie_selections_data');
define('BOOKIE_BET_SETTER_TABLE',  $table_prefix.'bookie_bet_setter');
define('BOOKIE_CAT_TABLE',  $table_prefix.'bookie_categories');
define('LOTTERY_TABLE', $table_prefix.'lottery');
define('LOTTERY_HISTORY_TABLE', $table_prefix.'lottery_history');

define('USER_ITEMS_TABLE', $table_prefix.'user_items');
define('SHOP_TABLE', $table_prefix.'shops');
define('SHOP_ITEMS_TABLE', $table_prefix.'shopitems');
define('TRANS_TABLE', $table_prefix.'transactions');

define('AUCTIONS_TABLE', $table_prefix.'shopauctions');

DEFINE('USER_SHOPS_TABLE', $table_prefix . 'user_shops');
DEFINE('USER_SHOP_ITEMS_TABLE', $table_prefix . 'user_shops_items');

?>
