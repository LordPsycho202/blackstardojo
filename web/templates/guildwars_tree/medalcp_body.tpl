 
<form action="{S_MEDALCP_ACTION}" method="post">

<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
		<td align="left" class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
	</tr>
</table>

<table class="forumline" width="100%" cellspacing="1" cellpadding="4" border="0">
	<tr> 
		<th class="thHead" colspan="7" height="25">{L_MEDAL_INFORMATION}</th>
	</tr>
	<tr> 
		<td class="row1" width="30%"><span class="gen">{L_MEDAL_NAME}:</span></td>
		<td class="row2"><span class="gen"><b>{MEDAL_NAME}</b></span></td>
	</tr>
	<tr> 
		<td class="row1" width="30%"><span class="gen">{L_MEDAL_DESC}:</span></td>
		<td class="row2"><span class="gen">{MEDAL_DESC}</span></td>
	</tr>
	<tr> 
		<td class="row1" width="30%"><span class="gen">{L_MEDAL_IMAGE}:</span></td>
		<td class="row2"><span class="gen">{MEDAL_IMAGE_DISPLAY} &nbsp;&nbsp;
		</span></td>
	</tr>
	<tr> 
		<td class="row1" width="30%"><span class="gen">{L_MEDAL_MODERATOR}:</span></td>
		<td class="row2"><span class="gen">{MEDAL_MODERATOR} &nbsp;&nbsp;
		</span></td>
	</tr>
	<tr> 
		<td class="row1" width="30%"><span class="gen">{L_MEDAL_MEMBERS}:</span><br /><span class="gensmall">{L_MEDAL_MEMBERS_EXPLAIN}</span></td>
		<td class="row2"><span class="gen">{MEDAL_MEMBER} &nbsp;&nbsp;
		</span></td>
	</tr>
</table>

{S_HIDDEN_FIELDS}</form>

<form method="post" name="post" action="{S_MEDALCP_ACTION}"><table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_MEDAL_USER}</th>
	</tr>
	<tr> 
	  <td class="row1" width="50%"><span class="gen">{L_USERNAME}:</span></td>
	  <td class="row2"><input class="post" type="text" class="post" name="username" maxlength="50" size="20" /> <input type="hidden" name="mode" value="edit" />{S_HIDDEN_FIELDS} <input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onClick="window.open('{U_SEARCH_USER}', '_phpbbsearch', 'HEIGHT=250,resizable=yes,WIDTH=400');return false;" /></td>
	</tr>
	<tr> 
	  <td class="row1" width="50%"><span class="gen">{L_MEDAL_REASON}:</span><br /><span class="gensmall">{L_MEDAL_REASON_EXPLAIN}</span></td>
	  <td class="row2"><textarea class="post" name="issue_reason" rows="5" cols="60"></textarea></td>
	</tr>
	<tr> 
	  <th class="thHead" colspan="2">{L_UNMEDAL_USER}</th>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen">{L_USERNAME}: </span><br /><span class="gensmall">{L_UNMEDAL_USER_EXPLAIN}</span></td>
	  <td class="row2">{S_UNMEDAL_USERLIST_SELECT}</td>
	</tr>
	<tr> 
	  <td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>
</table>

{S_HIDDEN_FIELDS}</form>

<table width="100%" cellspacing="2" border="0" align="center">
  <tr> 
	<td valign="top" align="right">{JUMPBOX}</td>
  </tr>
</table>
