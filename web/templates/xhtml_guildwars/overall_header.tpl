<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="{S_CONTENT_DIRECTION}">
<head>
<link rel="shortcut icon" href="favicon.gif" />
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}" />
<meta http-equiv="Content-Style-Type" content="text/css" />
{META}
{NAV_LINKS}
<title>{SITENAME} :: {PAGE_TITLE}</title>
<!-- link rel="stylesheet" href="templates/guildwars_tree/{T_HEAD_STYLESHEET}" type="text/css" -->
<style type="text/css">
<!--
/*
  The original GuildWars Theme for phpBB version 2+
  Created by niedermayr.cc
  http://www.niedermayr.cc
  based on subSilver Created by subBlue design
  http://www.subBlue.com

  NOTE: These CSS definitions are stored within the main page body so that you can use the phpBB2
  theme administration centre. When you have finalised your style you could cut the final CSS code
  and place it in an external file, deleting this section to save bandwidth.
*/

/* General page style. The scroll bar colours only visible in IE5.5+ */
body { 
	background-color: {T_BODY_BGCOLOR};
	scrollbar-face-color: {T_TR_COLOR2};
	scrollbar-highlight-color: {T_TD_COLOR2};
	scrollbar-shadow-color: {T_TR_COLOR2};
	scrollbar-3dlight-color: {T_TR_COLOR3};
	scrollbar-arrow-color:  {T_BODY_LINK};
	scrollbar-track-color: {T_TR_COLOR1};
	scrollbar-darkshadow-color: {T_TH_COLOR1};
}

/* General font families for common tags */
font,th,td,p { font-family: {T_FONTFACE1} }
a:link,a:active,a:visited { color : {T_BODY_LINK}; }
a:hover		{ text-decoration: underline; color : {T_BODY_HLINK}; }
hr	{ height: 0px; border: solid {T_TR_COLOR3} 0px; border-top-width: 1px;}

/* This is the border line & background colour round the entire page */
.bodyline	{ background-color: {T_TD_COLOR2}; border: 1px {T_TH_COLOR1} solid; }

.bodyline2	{ background-color: {T_TD_COLOR2}; border: 1px {T_TH_COLOR1} solid; }

.bodyline3	{ background-color: {T_TR_COLOR1}; border: 1px {T_TH_COLOR1} solid; }

/* This is the outline round the main forum tables */
.forumline	{ background-color: {T_TD_COLOR2}; border: 2px {T_TH_COLOR2} solid; }

/* Main table cell colours and backgrounds */
td.row1	{ background-color: {T_TR_COLOR1}; }
td.row2	{ background-color: {T_TR_COLOR2}; }
td.row3	{ background-color: {T_TR_COLOR3}; }

/*
  This is for the table cell above the Topics, Post & Last posts on the index.php page
  By default this is the fading out gradiated silver background.
  However, you could replace this with a bitmap specific for each forum
*/
td.rowpic {
		background-color: {T_TD_COLOR2};
		background-image: url(templates/guildwars_tree/images/{T_TH_CLASS3});
		background-repeat: repeat-y;
}

/* Header cells - the blue and silver gradient backgrounds */
th	{
	color: {T_FONTCOLOR3}; font-size: {T_FONTSIZE2}px; font-weight : bold; 
	background-color: {T_BODY_LINK}; height: 25px;
	background-image: url(templates/guildwars_tree/images/{T_TH_CLASS2});
}

td.cat,td.catHead,td.catSides,td.catLeft,td.catRight,td.catBottom {
			background-image: url(templates/guildwars_tree/images/{T_TH_CLASS1});
			background-color:{T_TR_COLOR3}; border: {T_TH_COLOR3}; /*border-style: solid;*/ height: 28px;
}

/*
  Setting additional nice inner borders for the main table cells.
  The names indicate which sides the border will be on.
  Don't worry if you don't understand this, just ignore it :-)
*/
td.cat,td.catHead,td.catBottom {
	height: 29px;
	border-width: 0px 0px 0px 0px;
}
th.thHead,th.thSides,th.thTop,th.thLeft,th.thRight,th.thBottom,th.thCornerL,th.thCornerR {
	font-weight: bold; border: {T_TD_COLOR2}; /*border-style: solid;*/ height: 28px;
}
td.row3Right,td.spaceRow {
	background-color: {T_TR_COLOR3}; border: {T_TH_COLOR3}; /*border-style: solid;*/
}

th.thHead,td.catHead { font-size: {T_FONTSIZE3}px; border-width: 1px 1px 0px 1px; }
th.thSides,td.catSides,td.spaceRow	 { border-width: 0px 1px 0px 1px; }
th.thRight,td.catRight,td.row3Right	 { border-width: 0px 1px 0px 0px; }
th.thLeft,td.catLeft	  { border-width: 0px 0px 0px 1px; }
th.thBottom,td.catBottom  { border-width: 0px 1px 1px 1px; }
th.thTop	 { border-width: 1px 0px 0px 0px; }
th.thCornerL { border-width: 1px 0px 0px 1px; }
th.thCornerR { border-width: 1px 1px 0px 0px; }

/* The largest text used in the index page title and toptic title etc. */
.maintitle	{
	font-weight: bold; font-size: 22px; font-family: "{T_FONTFACE2}",{T_FONTFACE1};
	text-decoration: none; line-height : 120%; color : {T_BODY_TEXT};
}

/* General text */
.gen { font-size : {T_FONTSIZE3}px; }
.genmed { font-size : {T_FONTSIZE2}px; }
.gensmall { font-size : {T_FONTSIZE1}px; }
.gen,.genmed,.gensmall { color : {T_BODY_TEXT}; }
a.gen,a.genmed,a.gensmall { color: {T_BODY_LINK}; text-decoration: none; }
a.gen:hover,a.genmed:hover,a.gensmall:hover	{ color: {T_BODY_HLINK}; text-decoration: underline; }

/* The register, login, search etc links at the top of the page */
.mainmenu		{ font-size : {T_FONTSIZE2}px; color : {T_BODY_TEXT} }
a.mainmenu		{ text-decoration: none; color : {T_BODY_LINK};  }
a.mainmenu:hover{ text-decoration: underline; color : {T_BODY_HLINK}; }

/* Forum category titles */
.cattitle		{ font-weight: bold; font-size: {T_FONTSIZE3}px ; letter-spacing: 1px; color : {T_BODY_LINK}}
a.cattitle		{ text-decoration: none; color : {T_BODY_LINK}; }
a.cattitle:hover{ text-decoration: underline; }

/* Forum title: Text and link to the forums used in: index.php */
.forumlink		{ font-weight: bold; font-size: {T_FONTSIZE3}px; color : {T_BODY_LINK}; }
a.forumlink 	{ text-decoration: none; color : {T_BODY_LINK}; }
a.forumlink:hover{ text-decoration: underline; color : {T_BODY_HLINK}; }

/* Used for the navigation text, (Page 1,2,3 etc) and the navigation bar when in a forum */
.nav			{ font-weight: bold; font-size: {T_FONTSIZE2}px; color : {T_BODY_TEXT};}
a.nav			{ text-decoration: none; color : {T_BODY_LINK}; }
a.nav:hover		{ text-decoration: underline; }

/* titles for the topics: could specify viewed link colour too */
.topictitle,h1,h2	{ font-weight: bold; font-size: {T_FONTSIZE2}px; color : {T_BODY_TEXT}; }
a.topictitle:link   { text-decoration: none; color : {T_BODY_LINK}; }
a.topictitle:visited { text-decoration: none; color : {T_BODY_VLINK}; }
a.topictitle:hover	{ text-decoration: underline; color : {T_BODY_HLINK}; }

/* Name of poster in viewmsg.php and viewtopic.php and other places */
.name			{ font-size : {T_FONTSIZE2}px; color : {T_BODY_TEXT};}

/* Location, number of posts, post date etc */
.postdetails		{ font-size : {T_FONTSIZE1}px; color : {T_BODY_TEXT}; }

/* The content of the posts (body of text) */
.postbody { font-size : {T_FONTSIZE3}px; line-height: 18px}
a.postlink:link	{ text-decoration: none; color : {T_BODY_LINK} }
a.postlink:visited { text-decoration: none; color : {T_BODY_VLINK}; }
a.postlink:hover { text-decoration: underline; color : {T_BODY_HLINK}}

/* Quote & Code blocks */
.code { 
	font-family: {T_FONTFACE3}; font-size: {T_FONTSIZE2}px; color: {T_BODY_TEXT};
	background-color: {T_TR_COLOR1}; border: {T_TH_COLOR1}; border-style: solid;
	border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}

.quote {
	font-family: {T_FONTFACE1}; font-size: {T_FONTSIZE2}px; color: {T_BODY_TEXT}; line-height: 125%;
	background-color: {T_TR_COLOR1}; border: {T_TH_COLOR1}; border-style: solid;
	border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}

/* Copyright and bottom info */
.copyright		{ font-size: {T_FONTSIZE1}px; font-family: {T_FONTFACE1}; color: {T_FONTCOLOR1}; letter-spacing: -1px;}
a.copyright:link,a.copyright:active,a.copyright:visited		{ color: {T_FONTCOLOR1}; text-decoration: none;}
a.copyright:hover { color: {T_BODY_TEXT}; text-decoration: underline;}

/* Form elements */
input,textarea, select {
	color : {T_BODY_TEXT};
	font: normal {T_FONTSIZE2}px {T_FONTFACE1};
	border-color : {T_BODY_TEXT};
}

/* The text input fields background colour */
input.post, textarea.post, select {
	background-color : {T_TD_COLOR2};
}

input { text-indent : 2px; }

/* The buttons used for bbCode styling in message post */
input.button {
	background-color : {T_TR_COLOR1};
	color : {T_BODY_TEXT};
	font-size: {T_FONTSIZE2}px; font-family: {T_FONTFACE1};
}

/* The main submit button option */
input.mainoption {
	/*background-color : {T_TD_COLOR1};
	font-weight : bold;*/
	background-color : {T_TR_COLOR1};
	color : {T_BODY_TEXT};
	font-size: {T_FONTSIZE2}px; font-family: {T_FONTFACE1};
}

/* None-bold submit button */
input.liteoption {
	/*background-color : {T_TD_COLOR1};
	font-weight : normal;*/
	background-color : {T_TR_COLOR1};
	color : {T_BODY_TEXT};
	font-size: {T_FONTSIZE2}px; font-family: {T_FONTFACE1};
}

/* This is the line in the posting page which shows the rollover
  help line. This is actually a text box, but if set to be the same
  colour as the background no one will know ;)
*/
.helpline { background-color: {T_TR_COLOR2}; border-style: none; }

/* Import the fancy styles for IE only (NS4.x doesn't use the @import function) */
@import url("templates/guildwars_tree/formIE.css"); 
-->
</style>
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
</head>
<body bgcolor="{T_BODY_BGCOLOR}" text="{T_BODY_TEXT}" link="{T_BODY_LINK}" vlink="{T_BODY_VLINK}">

<!-- Account Switch Hack Start -->
<!-- BEGIN switch_user_logged_out -->
<form method="post" action="{S_LOGIN_ACTION}">
  <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr> 
	  <td class="row1" align="right" valign="middle" height="28"><span class="gensmall">{L_USERNAME}: 
		<input class="post" type="text" name="username" size="10" />
		&nbsp;&nbsp;&nbsp;{L_PASSWORD}: 
		<input class="post" type="password" name="password" size="10" />
		<br/>
		&nbsp;&nbsp; &nbsp;&nbsp;{L_LOG_ME_IN}
		<input class="text" type="checkbox" name="autologin" />
		&nbsp;&nbsp;&nbsp; 
		<input type="submit" class="mainoption" name="login" value="{L_LOGIN}" />
		</span> </td>
	</tr>
  </table>
</form>
<!-- END switch_user_logged_out -->

<!-- BEGIN switch_user_logged_in -->
<form method="post" action="{S_LOGIN_ACTION}">
  <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr> 
	  <td class="row1" align="right" valign="middle" height="28"><span class="gensmall"> 
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



<a name="top"></a>

<table width="100%" cellspacing="0" cellpadding="10" border="0" align="center"> 
	<tr> 
		<td class="bodyline2">

<div align="center">
<table style="width:100%;border:0;" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<a href="{U_INDEX}"><img src="templates/guildwars_tree/images/header_logo_01.gif" width="193" height="30" alt="{L_INDEX}" border="0" /></a></td>
		<td style="background:url('templates/guildwars_tree/images/header_logo_02.gif')" width="50%">
			<img src="templates/guildwars_tree/images/header_logo_02.gif" width="1" height="30" alt="" /></td>
		<td>
			<img src="templates/guildwars_tree/images/header_logo_03.gif" width="362" height="30" alt="" /></td>
		<td style="background:url('templates/guildwars_tree/images/header_logo_04.gif')" width="50%">
			<img src="templates/guildwars_tree/images/header_logo_04.gif" width="1" height="30" alt="" /></td>
		<td>
			<img src="templates/guildwars_tree/images/header_logo_05.gif" width="193" height="30" alt="" /></td>
	</tr>
	<tr>
		<td>
			<a href="{U_INDEX}"><img src="templates/guildwars_tree/images/header_logo_06.gif" width="193" height="91" alt="{L_INDEX}" border="0" /></a></td>
		<td style="background:url('templates/guildwars_tree/images/header_logo_07.gif')" width="50%">
			<img src="templates/guildwars_tree/images/header_logo_07.gif" width="1" height="91" alt="" /></td>
		<td>
			<img src="templates/guildwars_tree/images/header_logo_08.jpg" width="362" height="91" alt="Guild Wars" /></td>
		<td style="background:url('templates/guildwars_tree/images/header_logo_09.gif')" width="50%">
			<img src="templates/guildwars_tree/images/header_logo_09.gif" width="1" height="91" alt="" /></td>
		<td>
			<img src="templates/guildwars_tree/images/header_logo_10.gif" width="193" height="91" alt="" /></td>
	</tr>
	<tr>
		<td>
			<a href="{U_INDEX}"><img src="templates/guildwars_tree/images/header_logo_11.gif" width="193" height="17" alt="{L_INDEX}" border="0" /></a></td>
		<td style="background:url('templates/guildwars_tree/images/header_logo_12.gif')" width="50%">
			<img src="templates/guildwars_tree/images/header_logo_12.gif" width="1" height="17" alt="" /></td>
		<td style="background:url('templates/guildwars_tree/images/header_logo_13.gif')" align="center">
			<span class="forumlink">{SITE_DESCRIPTION}</span></td>
		<td style="background:url('templates/guildwars_tree/images/header_logo_14.gif')" width="50%">
			<img src="templates/guildwars_tree/images/header_logo_14.gif" width="1" height="17" alt="" /></td>
		<td>
			<img src="templates/guildwars_tree/images/header_logo_15.gif" width="193" height="17" alt="" /></td>
	</tr>
	<tr>
		<td>
			<a href="{U_INDEX}"><img src="templates/guildwars_tree/images/header_logo_16.gif" width="193" height="14" alt="{L_INDEX}" border="0" /></a></td>
		<td style="background:url('templates/guildwars_tree/images/header_logo_17.gif')" width="50%">
			<img src="templates/guildwars_tree/images/header_logo_17.gif" width="1" height="14" alt="" /></td>
		<td>
			<img src="templates/guildwars_tree/images/header_logo_18.gif" width="362" height="14" alt="" /></td>
		<td style="background:url('templates/guildwars_tree/images/header_logo_19.gif')" width="50%">
			<img src="templates/guildwars_tree/images/header_logo_19.gif" width="1" height="14" alt="" /></td>
		<td>
			<img src="templates/guildwars_tree/images/header_logo_20.gif" width="193" height="14" alt="" /></td>
	</tr>
</table>

				<table cellspacing="0" cellpadding="2" border="0">
					<tr> 
						<td align="center" valign="top" nowrap="nowrap"><span class="mainmenu">
						<a href="{U_FAQ}" class="mainmenu">{L_FAQ}</a>
						&nbsp;�&nbsp;
						<a href="{U_SEARCH}" class="mainmenu">{L_SEARCH} </a>
						&nbsp;�&nbsp;
						<a href="{U_MEMBERLIST}" class="mainmenu">{L_MEMBERLIST}</a>
						&nbsp;�&nbsp;
						<a href="{U_GROUP_CP}" class="mainmenu">{L_USERGROUPS}</a>
						<!-- BEGIN switch_user_logged_out -->
						&nbsp;�&nbsp;<a href="{U_REGISTER}" class="mainmenu">{L_REGISTER}</a>
						<!-- END switch_user_logged_out -->
						&nbsp;�&nbsp;
						<a href="{U_PROFILE}" class="mainmenu">{L_PROFILE}</a>
						
						&nbsp;�&nbsp;
						<a href="{U_RANKS}" class="mainmenu">{L_RANKS}</a>
						&nbsp;�&nbsp;
						<a href="{U_STAFF}" class="mainmenu">{L_STAFF}</a>
						&nbsp;�&nbsp;
						<a href="{U_PRIVATEMSGS}" class="mainmenu">{PRIVATE_MESSAGE_INFO}</a>
						&nbsp;�&nbsp;
						<a href="{U_LOGIN_LOGOUT}" class="mainmenu">{L_LOGIN_LOGOUT}</a>&nbsp;<br>
						
						<a href="{U_EXCHANGE}" class="mainmenu">{L_EXCHANGE}</a>
						&nbsp;�&nbsp;
						<a href="{U_VAULT}" class="mainmenu">{L_VAULT}</a>
						&nbsp;�&nbsp;
						<a href="{U_SHOP}" class="mainmenu">{L_SHOP}</a>
						&nbsp;�&nbsp;
						<a href="{U_AUCTIONS}" class="mainmenu">{L_AUCTIONS}</a>
						&nbsp;�&nbsp;
						<a href="{U_ITEMS}" class="mainmenu">{L_ITEMS}</a>
						&nbsp;�&nbsp;
						<a href="{U_LOTTERY}" class="mainmenu">{L_LOTTERY}</a>
						&nbsp;�&nbsp;
						<a href="{U_BOOKIES}" class="mainmenu">{L_BOOKIES}</a>
						<br />
						<a href="{U_MEDALS}" class="mainmenu">{L_MEDALS}</a>
						&nbsp;�&nbsp;
						<a href="{U_WIO}" class="mainmenu">{L_WIO}</a>
						&nbsp;�&nbsp;
						<a href="{U_WIKI}" class="mainmenu">{L_WIKI}</a>
						</span>
						</td>
					
						<td align="center" valign="top" nowrap="nowrap"><div class="mainmenu"><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" name="submit" alt="Make payments with PayPal - it's fast, free and secure!" />
<img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----" />
</form></div></td></tr>
			<tr>						
						
                 				<td colspan="2" align="center" width="100%" valign="middle">
						Stock Exchange - Latest changes<br />
						<a href="vault.php" class="mainmenu">| <b>BLACKSTAR MARKET INDEX:</b> {STOCK_INDEX} ({STOCK_INDEX_DIFF}) |</a>
<!-- BEGIN stock_tick -->
<a href="vault.php" class="mainmenu">| <b>{stock_tick.STOCK_NAME}:</b> {stock_tick.STOCK_VALUE} ({stock_tick.STOCK_DIFF}) | </a>
<!-- END stock_tick -->

                				
                				</td>
						
               				</tr> 

				</table>
</div>
				<br />
