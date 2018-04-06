
<table width="100%" cellspacing="2" cellpadding="2" border="0">
  <tr> 
	<td align="left" valign="bottom" colspan="2"><a class="maintitle" href="{U_VIEW_TOPIC}">{TOPIC_TITLE}</a><br />
	  <span class="gensmall"><b>{PAGINATION}</b><br />
	  &nbsp; </span></td>
  </tr>
</table>

<table width="100%" cellspacing="2" cellpadding="2" border="0">
  <tr> 
	<td align="left" valign="bottom" nowrap="nowrap"><span class="nav"><a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG}" border="0" alt="{L_POST_REPLY_TOPIC}" align="middle" /></a></span></td>
	<td align="left" valign="middle" width="100%"><span class="nav">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a> 
	  &raquo; <a href="{U_VIEW_FORUM}" class="nav">{FORUM_NAME}</a></span></td>
	 <td align="right" valign="middle" nowrap="nowrap"><span class="gensmall"><a href="{U_VIEW_OLDER_TOPIC}">{L_VIEW_PREVIOUS_TOPIC}</a><br /><a href="{U_VIEW_NEWER_TOPIC}">{L_VIEW_NEXT_TOPIC}</a></span></td>
  </tr>
</table>

<table class="forumline" width="100%" cellspacing="0" cellpadding="3">
<thead>
	<caption><table border="0" cellspacing="0" cellpadding="0" width="100%" class="forumheader">
	<tr>
		<td align="left" valign="bottom" width="25"><img src="templates/AcidTechBlood/images/hdr_left.gif" width="25" height="27" alt="" /></td>
		<td align="center" class="forumheader-mid"><a href="{U_VIEW_TOPIC}">{TOPIC_TITLE}</a></td>
		<td align="right" valign="bottom" width="25"><img src="templates/AcidTechBlood/images/hdr_right.gif" width="25" height="27" alt="" /></td>
	</tr></table></caption>
</thead>
<tbody>
	{POLL_DISPLAY} 
	<tr>
		<th class="thLeft" width="150" height="26" nowrap="nowrap">{L_AUTHOR}</th>
		<th class="thRight" nowrap="nowrap">{L_MESSAGE}</th>
	</tr>
	<!-- BEGIN postrow -->
	<tr> 
		<td width="150" align="center" valign="top" class="row1"><span class="gen">
			<span class="postername"><a name="{postrow.U_POST_ID}"></a>{postrow.POSTER_NAME}</span><br />
			<span class="posterrank">{postrow.USER_RANK_01}</span>
			{postrow.USER_RANK_01_IMG}
			<span class="posterrank">{postrow.USER_RANK_02}</span>
			{postrow.USER_RANK_02_IMG}
			<span class="posterrank">{postrow.USER_RANK_03}</span>
			{postrow.USER_RANK_03_IMG}
			<span class="posterrank">{postrow.USER_RANK_04}</span>
			{postrow.USER_RANK_04_IMG}
			<span class="posterrank">{postrow.USER_RANK_05}</span>
			{postrow.USER_RANK_05_IMG}<br />{postrow.POSTER_AVATAR}<br /><br />
			<table border="0" cellspacing="0" cellpadding="2" width="95%">
			<tr>
				<td align="left" class="posterprofile">{postrow.POSTER_JOINED}<br />{postrow.POSTER_POSTS}<br />{postrow.POSTER_FROM}<br />{postrow.POSTER_VAULT}<br />{postrow.CASH}<br />{postrow.CASH_OPTIONS}<br />-->{postrow.POSTER_Ri}<br />{postrow.POSTER_MEDAL_COUNT}<br />
		<!-- BEGIN medal -->
		<table border="0" cellspacing="0" cellpadding="5">
		<!-- BEGIN medal_row -->
		<tr align="left" valign="middle"> 
		  <!-- BEGIN medal_col -->
		  <td><img src="{postrow.medal.medal_row.medal_col.MEDAL_IMAGE}" border="0" alt="{postrow.medal.medal_row.medal_col.MEDAL_NAME} {postrow.medal.medal_row.medal_col.MEDAL_COUNT}" title="{postrow.medal.medal_row.medal_col.MEDAL_NAME} {postrow.medal.medal_row.medal_col.MEDAL_COUNT}" {postrow.medal.medal_row.medal_col.MEDAL_WIDTH} {postrow.medal.medal_row.medal_col.MEDAL_HEIGHT} /></a></td>
		  <!-- END medal_col -->
		</tr>
		<!-- END medal_row -->
		</table>
		<!-- END medal --></td>
			</tr>
			</table></span><br />
		</span></td>
		<td class="{postrow.ROW_CLASS}" width="100%" height="28" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="100%" style="padding: 3px 0;"><span class="postsubject"><a href="{postrow.U_MINI_POST}"><img src="{postrow.MINI_POST_IMG}" width="12" height="9" alt="{postrow.L_MINI_POST_ALT}" title="{postrow.L_MINI_POST_ALT}" border="0" /></a> {postrow.POST_SUBJECT}</span></td>
				<td valign="top" nowrap="nowrap">{postrow.QUOTE_IMG} {postrow.EDIT_IMG} {postrow.DELETE_IMG} {postrow.IP_IMG}</td>
			</tr>
			<tr>
				<td colspan="2" class="postbody">{postrow.MESSAGE}<br />{postrow.SIGNATURE}<span class="gensmall">{postrow.EDITED_MESSAGE}</span></td>
			</tr>
		</table></td>
	</tr>
	<tr> 
		<td class="postbottom" align="center" valign="middle" nowrap="nowrap">{postrow.POST_DATE}</td>
		<td class="postbottom" width="100%" valign="middle" nowrap="nowrap">{postrow.PROFILE_IMG} {postrow.PM_IMG} {postrow.EMAIL_IMG} {postrow.WWW_IMG} {postrow.AIM_IMG} {postrow.YIM_IMG} {postrow.MSN_IMG} {postrow.ICQ_IMG}</td>
	</tr>
	<tr> 
		<td class="spaceRow" colspan="2"><img src="templates/AcidTechBlood/images/spacer.gif" alt="" width="1" height="1" /></td>
	</tr>
	<!-- END postrow -->
	<tr align="center"> 
		<td class="catBottom" colspan="2" height="28"><table cellspacing="0" cellpadding="0" border="0">
			<tr><form method="post" action="{S_POST_DAYS_ACTION}">
				<td valign="middle"><span class="gensmall">{L_DISPLAY_POSTS}:&nbsp;</span></td>
				<td valign="middle">{S_SELECT_POST_DAYS}</td>
				<td valign="middle"><span class="gensmall">&nbsp;{S_SELECT_POST_ORDER}&nbsp;</span></td>
				<td valign="middle"><input type="submit" value="{L_GO}" class="liteoption" name="submit" /></td>
			</form></tr>
		</table></td>
	</tr>
</tbody>
</table>

<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
  <tr> 
	<td align="left" valign="middle" nowrap="nowrap"><span class="nav"><a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG}" border="0" alt="{L_POST_REPLY_TOPIC}" align="middle" /></a></span></td>
	<td align="left" valign="middle" width="100%"><span class="nav">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a> 
	  &raquo; <a href="{U_VIEW_FORUM}" class="nav">{FORUM_NAME}</a></span></td>
	<td align="right" valign="top" nowrap="nowrap"><span class="gensmall">{S_TIMEZONE}</span><br /><span class="nav">{PAGINATION}</span> 
	  </td>
  </tr>
  <tr>
	<td align="left" colspan="3"><span class="nav">{PAGE_NUMBER}</span></td>
  </tr>
</table>

<table width="100%" cellspacing="2" border="0" align="center">
  <tr> 
	<td width="40%" valign="top" nowrap="nowrap" align="left"><span class="gensmall">{S_WATCH_TOPIC}</span><br />
	  &nbsp;<br />
	  {S_TOPIC_ADMIN}</td>
	<td align="right" valign="top" nowrap="nowrap">{JUMPBOX}<span class="gensmall">{S_AUTH_LIST}</span></td>
  </tr>
</table>
