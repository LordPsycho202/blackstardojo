<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{S_CONTENT_DIRECTION}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
<meta http-equiv="Content-Style-Type" content="text/css">
{META}
{NAV_LINKS}
<title>{SITENAME} :: {PAGE_TITLE}</title>
<link rel="stylesheet" href="templates/AcidTechBlood/{T_HEAD_STYLESHEET}" type="text/css">
<script language="javascript" type="text/javascript" src="templates/AcidTechBlood/scripts.js"></script>
<script language="javascript" type="text/javascript" src="templates/AcidTechBlood/formStyle.js"></script>
<link rel="icon" href="favicon.ico" />
<!-- BEGIN switch_enable_pm_popup -->
<script language="Javascript" type="text/javascript">
<!--
	if ( {PRIVATE_MESSAGE_NEW_FLAG} )
	{
	/*	window.open('{U_PRIVATEMSGS_POPUP}', '_phpbbprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400'); */
	}
//-->
</script>
<!-- END switch_enable_pm_popup -->
<!-- Prillian - Begin Code Additions -->
<!-- BEGIN switch_user_logged_in -->
<script language="JavaScript" type="text/javascript">
<!--
	function prill_launch(url, w, h)
	{
		window.name = 'phpbbmain';
		prillian = window.open(url, 'prillian', 'height=' + h + ', width=' + w + ', innerWidth=' + w + ', innerHeight=' + h + ', resizable, scrollbars');
	}

	if ( {IM_AUTO_POPUP} ) 
	{ 
		prill_launch('{U_IM_LAUNCH}', '{IM_WIDTH}', '{IM_HEIGHT}');
	} 

//-->
</script>
<!-- END switch_user_logged_in -->
<!-- BEGIN buddy_alert -->
<script language="Javascript" type="text/javascript">
	if ( {buddy_alert.BUDDY_ALERT} )
	{
		window.open('{buddy_alert.U_BUDDY_ALERT}', '_buddyalert', 'HEIGHT=225,resizable=yes,WIDTH=400');
	}
</script>
<!-- END buddy_alert -->
<!-- Prillian - End Code Additions -->

</head>
<body>
<a name="top"></a>
<img id="formStyleTestImage" src="templates/AcidTechBlood/images/spacer.gif" />
<table width="{T_BODY_BACKGROUND}" cellspacing="0" cellpadding="0" border="0" align="center" id="maintable">
<tr>
	<td valign="top" class="content-row"><table border="0" width="100%" cellspacing="0" cellpadding="0" id="logotable">
	<tr>
		<td align="center" class="logorow1"><div class="logorow2"><a href="{U_INDEX}"><img src="templates/AcidTechBlood/images/logo_mid.gif" height="88" alt="{L_INDEX}" /></a></div></td>
	</tr>
	</table>
	<table border="0" width="100%" cellspacing="0" cellpadding="0" id="buttonstable">
	<tr>
		<td align="center" valign="middle" width="100%" id="header-buttons">
			<!-- BEGIN switch_user_logged_out --> 
			<a href="{U_REGISTER}">{L_REGISTER}</a> &#8226; 
			<!-- END switch_user_logged_out --> 
			<!-- BEGIN switch_user_logged_in --> 
			<a href="{U_PROFILE}">{L_PROFILE}</a> &#8226; 
			<a href="{U_PRIVATEMSGS}">{L_PRIVATEMSGS}</a> &#8226; 
			<!-- END switch_user_logged_in --> 
			<a href="{U_SEARCH}">{L_SEARCH}</a> &#8226; 
			<a href="{U_FAQ}">{L_FAQ}</a> &#8226; 
			<a href="{U_MEMBERLIST}">{L_MEMBERLIST}</a> &#8226; 
			<a href="{U_GROUP_CP}">{L_USERGROUPS}</a> &#8226; 
			<a href="{U_LOGIN_LOGOUT}">{L_LOGIN_LOGOUT}</a> &#8226; 
			<a href="{U_RANKS}">{L_RANKS}</a> &#8226; 
			<a href="{U_STAFF}">{L_STAFF}</a>
			<br>
			<a href="{U_EXCHANGE}">{L_EXCHANGE}</a> &#8226; 
			<a href="{U_VAULT}">{L_VAULT}</a> &#8226; 
			<a href="{U_SHOP}">{L_SHOP}</a> &#8226; 
			<a href="{U_AUCTIONS}">{L_AUCTIONS}</a> &#8226; 
			<a href="{U_ITEMS}">{L_ITEMS}</a> &#8226; 
			<a href="{U_shop_list}">{L_shop_list}</a> &#8226; 
			<a href="{U_LOTTERY}">{L_LOTTERY}</a> &#8226; 
			<a href="{U_BOOKIES}">{L_BOOKIES}</a>
			<br />
			<!-- Prillian - Begin Code Additions -->
			<!-- BEGIN switch_user_logged_in -->
			<a href="{U_IM_LAUNCH}" target="prillian" onClick="javascript:prill_launch('{U_IM_LAUNCH}', '{IM_WIDTH}', '{IM_HEIGHT}'); return false">{L_IM_LAUNCH}</a> &#8226; 
			<a href="{U_CONTACT_MAN}">{L_CONTACT_MAN}</a> &#8226; 
			<!-- END switch_user_logged_in -->
			<!-- Prillian - End Code Additions -->
			<a href="{U_MEDALS}">{L_MEDALS}</a> &#8226; 
			<a href="{U_JRCHAT}">{L_JRCHAT}</a> &#8226; 
			<a href="{U_JRCHATRP}">{L_JRCHATRP}</a> &#8226; 
			<a href="{U_WIO}">{L_WIO}</a> &#8226; 
			<a href="{U_WIKI}">{L_WIKI}</a>
		</td>
	</tr>
	</table>
	<table border="0" width="100%" cellspacing="0" cellpadding="0" class="content">
	<tr>
		<td class="content" valign="center">
			<center>
				Stock Exchange - Latest changes<br>
				<marquee scrollamount="3" direction="left" loop="true" onmouseover="this.stop()" onmouseout="this.start()">
					<a href="shop.php" class="mainmenu">|<b>CURRENT MAX ITEM RARITY INDEX:</b> {MAX_ITEM_RARITY_I}|</a>
					<a href="vault.php" class="mainmenu">| <b>BLACKSTAR MARKET INDEX:</b> {STOCK_INDEX} ({STOCK_INDEX_DIFF}) |</a>
					<!-- BEGIN stock_tick -->
						<a href="vault.php" class="mainmenu">| <b>{stock_tick.STOCK_NAME}:</b> {stock_tick.STOCK_VALUE} ({stock_tick.STOCK_DIFF}) | </a>
					<!-- END stock_tick -->
				</marquee>
		</td>
	</tr>
	<tr>
		<td class="content" valign="top">

	<!-- BEGIN switch_user_logged_in -->
	<center><div class="pm-{PRIVMSG_IMG}"><a href="{U_PRIVATEMSGS}">{PRIVATE_MESSAGE_INFO}</a></div></center>
	<!-- END switch_user_logged_in -->
