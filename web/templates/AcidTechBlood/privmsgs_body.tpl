 
<table border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
  <tr> 
	<td valign="top" align="center" width="100%"> 
	  <table height="40" cellspacing="2" cellpadding="2" border="0">
		<tr valign="middle"> 
		  <td><span class="gen">{INBOX} &nbsp;</span></td>
		  <td><span class="gen">{SENTBOX} &nbsp;</span></td>
		  <td><span class="gen">{OUTBOX} &nbsp;</span></td>
		  <td><span class="gen">{SAVEBOX} &nbsp;</span></td>
		</tr>
	  </table>
	</td>
	<td align="right"> 
	  <!-- BEGIN switch_box_size_notice -->
	  <table width="175" cellspacing="0" cellpadding="2" class="forumline">
		<tr> 
		  <td colspan="3" width="175" class="row1" nowrap="nowrap" align="center"><span class="gensmall">{BOX_SIZE_STATUS}</span></td>
		</tr>
		<tr> 
		  <td colspan="3" width="175" class="row2" align="left">
			<table cellspacing="0" cellpadding="1" border="0">
			  <tr> 
				<td class="progress"><img src="templates/AcidTechBlood/images/spacer.gif" width="{INBOX_LIMIT_IMG_WIDTH}" height="8" alt="{INBOX_LIMIT_PERCENT}" /></td>
			  </tr>
			</table>
		  </td>
		</tr>
		<tr> 
		  <td width="33%" class="row1" align="left"><span class="gensmall">0%</span></td>
		  <td width="34%" align="center" class="row1"><span class="gensmall">50%</span></td>
		  <td width="33%" align="right" class="row1"><span class="gensmall">100%</span></td>
		</tr>
	  </table>
	  <!-- END switch_box_size_notice -->
	</td>
  </tr>
</table>

<br clear="all" />

<form method="post" name="privmsg_list" action="{S_PRIVMSGS_ACTION}">
  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr> 
	  <td align="left" valign="middle">{POST_PM_IMG}</td>
	  <td align="left" width="100%">&nbsp;<span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
	  <td align="right" nowrap="nowrap"><span class="gensmall">{L_DISPLAY_MESSAGES}: 
		<select name="msgdays">{S_SELECT_MSG_DAYS}
		</select>
		<input type="submit" value="{L_GO}" name="submit_msgdays" class="liteoption" />
		</span></td>
	</tr>
  </table>

  <table cellpadding="3" cellspacing="0" width="100%" class="forumline">
<thead>
	<caption><table border="0" cellspacing="0" cellpadding="0" width="100%" class="forumheader">
	<tr>
		<td align="left" valign="bottom" width="25"><img src="templates/AcidTechBlood/images/hdr_left.gif" width="25" height="27" alt="" /></td>
		<td align="center" class="forumheader-mid">{BOX_NAME}</td>
		<td align="right" valign="bottom" width="25"><img src="templates/AcidTechBlood/images/hdr_right.gif" width="25" height="27" alt="" /></td>
	</tr></table></caption>
</thead>
<tbody>
	<tr> 
	  <th width="5%" height="25" class="thCornerL" nowrap="nowrap">&nbsp;{L_FLAG}&nbsp;</th>
	  <th width="55%" class="thTop" nowrap="nowrap">&nbsp;{L_SUBJECT}&nbsp;</th>
	  <th width="20%" class="thTop" nowrap="nowrap">&nbsp;{L_FROM_OR_TO}&nbsp;</th>
	  <th width="15%" class="thTop" nowrap="nowrap">&nbsp;{L_DATE}&nbsp;</th>
	  <th width="5%" class="thCornerR" nowrap="nowrap">&nbsp;{L_MARK}&nbsp;</th>
	</tr>
	<!-- BEGIN listrow -->
	<tr> 
	  <td class="{listrow.ROW_CLASS}" width="5%" align="center" valign="middle"><img src="{listrow.PRIVMSG_FOLDER_IMG}" width="16" height="16" alt="{listrow.L_PRIVMSG_FOLDER_ALT}" title="{listrow.L_PRIVMSG_FOLDER_ALT}" /></td>
	  <td width="55%" valign="middle" class="{listrow.ROW_CLASS}"><span class="topictitle">&nbsp;<script language="JavaScript">FolderStart('{listrow.PRIVMSG_FOLDER_IMG}');</script><a href="{listrow.U_READ}" class="topictitle">{listrow.SUBJECT}</a><script language="JavaScript">FolderEnd();</script></span></td>
	  <td width="20%" valign="middle" align="center" class="{listrow.ROW_CLASS}"><span class="name">&nbsp;<a href="{listrow.U_FROM_USER_PROFILE}">{listrow.FROM}</a></span></td>
	  <td width="15%" align="center" valign="middle" class="{listrow.ROW_CLASS}"><span class="postdetails">{listrow.DATE}</span></td>
	  <td width="5%" align="center" valign="middle" class="{listrow.ROW_CLASS}"><span class="cbstyled"> 
		<input type="checkbox" name="mark[]2" value="{listrow.S_MARK_ID}" />
		</span></td>
	</tr>
	<!-- END listrow -->
	<!-- BEGIN switch_no_messages -->
	<tr> 
	  <td class="row1" colspan="5" align="center" valign="middle"><span class="gen">{L_NO_MESSAGES}</span></td>
	</tr>
	<!-- END switch_no_messages -->
	<tr> 
	  <td class="catBottom" colspan="5" height="28" align="right"> {S_HIDDEN_FIELDS} 
		<input type="submit" name="save" value="{L_SAVE_MARKED}" class="mainoption" />
		&nbsp; 
		<input type="submit" name="delete" value="{L_DELETE_MARKED}" class="liteoption" />
		&nbsp; 
		<input type="submit" name="deleteall" value="{L_DELETE_ALL}" class="liteoption" />
	  </td>
	</tr>
   </tbody>
  </table>

  <table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
	<tr> 
	  <td align="left" valign="middle"><span class="nav">{POST_PM_IMG}</span></td>
	  <td align="left" valign="middle" width="100%"><span class="nav">{PAGE_NUMBER}</span></td>
	  <td align="right" valign="top" nowrap="nowrap"><span class="nav">{PAGINATION}<br /></span><span class="gensmall">{S_TIMEZONE}</span></td>
	</tr>
  </table>
</form>

<table width="100%" border="0">
  <tr> 
	<td align="right" valign="top">{JUMPBOX}</td>
  </tr>
</table>
