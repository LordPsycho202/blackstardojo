<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="{S_CONTENT_DIRECTION}">
<head>
<link rel="shortcut icon" href="favicon_elveron.gif" >
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}">
<meta http-equiv="Content-Style-Type" content="text/css">
{META}
{NAV_LINKS}
<title>{SITENAME} :: {PAGE_TITLE}</title>
<link rel="stylesheet" href="templates/Elveron/{T_HEAD_STYLESHEET}" type="text/css" />
<!-- BEGIN switch_enable_pm_popup -->
<script language="Javascript" type="text/javascript">
<!--
	if ( {PRIVATE_MESSAGE_NEW_FLAG} )
	{
		window.open('{U_PRIVATEMSGS_POPUP}', '_phpbbprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400');;
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
<body bgcolor="{T_BODY_BGCOLOR}" text="{T_BODY_TEXT}" link="{T_BODY_LINK}" vlink="{T_BODY_VLINK}">



<a name="top"></a>
<table class="bordermaintable" align="center" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<td class="border_left" valign="top">
				<table cellpadding="0" cellspacing="0"><tr><td class="border_left_logo" valign="top"></td></tr></table>
			</td>
			<td>
				<table class="maintable" cellspacing="0" cellpadding="0" border="0"> 
					<!-- LOGO START -->
					<tr><td class="header_background" align="left">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr>
							<td width="170" onclick="self.parent.location='{U_INDEX}';" style="cursor:pointer; cursor:hand;" class="logo"></td>
							<td align="right"><span class="maintitle">{SITENAME}</span><br/><span class="gen">{SITE_DESCRIPTION}</span></td>
						</tr>
					</table>
					</td></tr>
					<!-- LOGO END -->
					<!-- TOP MENU START -->
					<tr><td class="maintablebody" valign="top" align="left">
						<br/>

						<table cellpadding="0" cellspacing="0" border="0" align="center" bgcolor="#445533" width="100%"><tr><td>
						<table cellpadding="0" cellspacing="0" border="0" align="center" class="topmenu">
							<tr><td height="3"></td></tr>
							<tr>
							<td class="b_spacer"></td><td class="b_left_normal"></td><td class="b_center"><a href="{U_FAQ}" title="{L_FAQ}">{L_FAQ}</a></td><td class="b_right"></td>
							<td class="b_spacer"></td><td class="b_left_normal"></td><td class="b_center"><a href="{U_MEMBERLIST}" title="{L_MEMBERLIST}">{L_MEMBERLIST}</a></td><td class="b_right"></td>
							<td class="b_spacer"></td><td class="b_left_normal"></td><td class="b_center"><a href="{U_GROUP_CP}" title="{L_USERGROUPS}">{L_USERGROUPS}</a></td><td class="b_right"></td>
							<!-- BEGIN switch_user_logged_out -->
							<td class="b_spacer"></td><td class="b_left_red"></td><td class="b_center"><a href="{U_REGISTER}" title="{L_REGISTER}">{L_REGISTER}</a></td><td class="b_right"></td>
							<!-- END switch_user_logged_out -->
							<td class="b_spacer"></td><td class="b_left_red"></td><td class="b_center"><a href="{U_LOGIN_LOGOUT}" title="{L_LOGIN_LOGOUT}">{L_LOGIN_LOGOUT}</a></td><td class="b_right"></td>
							</tr>
							<tr><td height="3"></td></tr>

						</table>
						</td></tr></table>
				
						<table cellpadding="0" cellspacing="0" border="0" align="center" class="topmenu">
							<tr><td height="7"></td></tr>
							<tr>
							<td class="b_spacer"></td><td class="b_left_grey"></td><td class="b_center"><a href="{U_PROFILE}" title="{L_PROFILE}">{L_PROFILE}</a></td><td class="b_right"></td>
							<td class="b_spacer"></td><td class="b_left_grey"></td><td class="b_center"><a href="{U_PRIVATEMSGS}" title="{PRIVATE_MESSAGE_INFO}">{PRIVATE_MESSAGE_INFO}</a></td><td class="b_right"></td>
							<td class="b_spacer"></td><td class="b_left_grey"></td><td class="b_center"><a href="{U_SEARCH}" title="{L_SEARCH}">{L_SEARCH}</a></td><td class="b_right"></td>
							<td class="b_spacer"></td><td class="b_left_normal"></td><td class="b_center"><a href="{U_MEDALS}" class="mainmenu">{L_MEDALS}</a></td><td class="b_right"></td>
							</tr>
						</table>
						<table cellpadding="0" cellspacing="0" border="0" align = "center" class="topmenu">
							<tr><td height="7"></td></tr>
							<tr>
							<td class="b_spacer"></td><td class="b_left_red"></td><td class="b_center"><a href="{U_RANKS}" class="mainmenu">{L_RANKS}</a></td><td class="b_right"></td>
							<td class="b_spacer"></td><td class="b_left_red"></td><td class="b_center"><a href="{U_STAFF}" class="mainmenu">{L_STAFF}</a></td><td class="b_right"></td>

							<td class="b_spacer"></td><td class="b_left_normal"></td><td class="b_center"><a href="{U_EXCHANGE}" class="mainmenu">{L_EXCHANGE}</a></td><td class="b_right"></td>
							<td class="b_spacer"></td><td class="b_left_normal"></td><td class="b_center"><a href="{U_SHOP}" class="mainmenu">{L_SHOP}</a></td><td class="b_right"></td>
							<td class="b_spacer"></td><td class="b_left_normal"></td><td class="b_center"><a href="{U_ITEMS}" class="mainmenu">{L_ITEMS}</a></td><td class="b_right"></td>
							<td class="b_spacer"></td><td class="b_left_normal"></td><td class="b_center"><a href="{U_shop_list}" class="mainmenu">{L_shop_list}</a></td><td class="b_right"></td>
						</table>
						<table cellpadding="0" cellspacing="0" border="0" align = "center" class="topmenu">
							<tr><td height="7"></td></tr><tr>
							<td class="b_spacer"></td><td class="b_left_normal"></td><td class="b_center"><a href="{U_VAULT}" class="mainmenu">{L_VAULT}</a></td><td class="b_right"></td>
							<td class="b_spacer"></td><td class="b_left_normal"></td><td class="b_center"><a href="{U_LOTTERY}" class="mainmenu">{L_LOTTERY}</a></td><td class="b_right"></td>
							<td class="b_spacer"></td><td class="b_left_red"></td><td class="b_center"><a href="{U_WIKI}" class="mainmenu">{L_WIKI}</a></td><td class="b_right"></td>
							<td class="b_spacer"></td><td class="b_left_normal"></td><td class="b_center"><a href="{U_AUCTIONS}" class="mainmenu">{L_AUCTIONS}</a></td><td class="b_right"></td>
                                                        <td class="b_spacer"></td><td class="b_left_normal"></td><td class="b_center"><a href="/affiliates.php" class="mainmenu">Affiliates</a></td><td class="b_right"></td>
							</tr></table>

						<table cellpadding="0" cellspacing="0" border="0" align = "center" class="topmenu"><tr><td height="7"></td></tr><tr>
							<td class="b_spacer"></td><td class="b_left_normal"></td><td class="b_center"><a href="{U_JRCHAT}" class="mainmenu">{L_JRCHAT}</a></td><td class="b_right"></td>
							<td class="b_spacer"></td><td class="b_left_normal"></td><td class="b_center"><a href="{U_JRCHATRP}" class="mainmenu">{L_JRCHATRP}</a></td><td class="b_right"></td>
							<td class="b_spacer"></td><td class="b_left_red"></td><td class="b_center"><a href="{U_WIO}" class="mainmenu">{L_WIO}</a></td><td class="b_right"></td>
		<!-- BEGIN switch_user_logged_in -->	<td class="b_spacer"></td><td class="b_left_normal"></td><class="b_center"><a href="{U_IM_LAUNCH}" target="prillian" onClick="javascript:prill_launch('{U_IM_LAUNCH}', '{IM_WIDTH}', '{IM_HEIGHT}'); return false" class="mainmenu">{L_IM_LAUNCH}</a></td><td class="b_right"></td>
							<td class="b_spacer"></td><td class="b_left_normal"></td><td class="b_center"><a href="{U_CONTACT_MAN}" class="mainmenu">{L_CONTACT_MAN}</a></td><td class="b_right"></td><!-- END switch_user_logged_in -->
</tr>
</table>
												
								
					</td></tr>
			
			<td><tr>
<td align="center" width="100%" valign="middle">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
</form></td></tr>
<td><tr>				
						
                 				<td align="center" width="100%" valign="middle">
						Stock Exchange - Latest changes
						<marquee scrollamount="3" direction="left" loop="true" onmouseover="this.stop()" onmouseout="this.start()"><a href="shop.php" class="mainmenu">|<b>CURRENT MAX RARITY INDEX:</b> {MAX_ITEM_RARITY_I}|</a><a href="vault.php" class="mainmenu">| <b>BLACKSTAR MARKET INDEX:</b> {STOCK_INDEX} ({STOCK_INDEX_DIFF}) |</a>
<!-- BEGIN stock_tick -->
<a href="vault.php" class="mainmenu">| <b>{stock_tick.STOCK_NAME}:</b> {stock_tick.STOCK_VALUE} ({stock_tick.STOCK_DIFF}) | </a>
<!-- END stock_tick -->

                				
                				</marquee></span></td>
						<br /><br /><td>
               				</tr> <tr><td>

<!-- Account Switch Hack Start -->

<!-- BEGIN switch_user_logged_in -->
<form method="post" action="{S_LOGIN_ACTION}">
  <table width="100%" cellpadding="3" cellspacing="1" border="0">
	<tr> 
	  <td align="right" valign="middle" height="28"><span class="gensmall"> 
<!-- END switch_user_logged_in -->

<!-- BEGIN switch_as_allowed -->	
		&nbsp;&nbsp;&nbsp;{L_AS_TAG}:
		<select name="jump">
		{SWITCH}
		</select>
		<input type="hidden" name="redirect" value="{REQUEST_URI}">
		<input type="submit" class="mainoption" name="switch" value="Switch" />
<!-- END switch_as_allowed -->


<!-- BEGIN switch_user_logged_in -->
		</span> </td>
	</tr>
  </table>
</form>
<!-- END switch_user_logged_in -->
<!-- Account Switch Hack End -->
</td></tr>
					<!-- TOP MENU END -->
					<tr><td>

					<table class="maintableinner" cellspacing="0" cellpadding="10" border="0">
						<tr><td class="maintablebody" valign="top" align="left">
							<br />

<b>{RANDOM_ITEM_MESSAGE}</b>
<br />

<!-- MAIN CONTENT BODY START -->