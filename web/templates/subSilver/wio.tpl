<HTML>
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
<tr> 
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
</tr></table>

<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
<tr> 
		<td   align="center" colspan="10"><img src="templates/guildwars_tree/images/temblem_li.gif" align="middle"><span class="cattitle"><b>{L_WHO_IS_ONLINE}</b></span><img src="templates/guildwars_tree/images/temblem_re.gif" align="middle"></td>
	</tr>
  <tr> 	
	<th width="20%" class="thCornerL" height="25">&nbsp;Avatar&nbsp;</th>
	<th width="20%" class="thTop" height="25">&nbsp;{L_USERNAME}&nbsp;</th>
	<th width="20%" height="25" class="thTop">&nbsp;{L_STARTED}&nbsp;</th>
	<th width="20%" class="thTop">&nbsp;{L_LAST_UPDATE}&nbsp;</th>
	<th width="20%" class="thCornerR">&nbsp;{L_FORUM_LOCATION}&nbsp;</th>
  </tr>
<tr>
	<td class="catBottom" align="left" colspan="10" heigt="2{NUM_COLUMNS}"><span class="cattitle"><b>Registered Users</b></span></td>
</tr>
  <!-- BEGIN reg_user_row -->
  <tr> 
	<td width="20" valign="middle" align ="center" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen"><a href="{reg_user_row.U_USER_PROFILE}" class="gen">{reg_user_row.AVATAR}</a></span></td>
	<td width="20" valign="middle" align ="center" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen"><a href="{reg_user_row.U_USER_PROFILE}" class="gen">{reg_user_row.USERNAME}</a></span></td>
	<td width="20%" valign="middle" align="center" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen">{reg_user_row.STARTED}</span>&nbsp;</td>
	<td width="20%" valign="middle"align="center" nowrap="nowrap" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen">{reg_user_row.LASTUPDATE}</span>&nbsp;</td>
	<td width="20%" valign="middle" class="{reg_user_row.ROW_CLASS}">&nbsp;<span class="gen"><a href="{reg_user_row.U_FORUM_LOCATION}" class="gen">{reg_user_row.FORUM_LOCATION}</a></span>&nbsp;</td>
  </tr>
  <!-- END reg_user_row -->
<tr>
	<td class="catBottom" align="left" colspan="10" heigt="2{NUM_COLUMNS}"><span class="cattitle"><b>Guest Users</b></span></td>
</tr>
  <!-- BEGIN guest_user_row -->
  <tr> 
	<td width="20%" align="center" class="{guest_user_row.ROW_CLASS}"><img src="/images/null.png" alt="No Avatar"/></td>	
	<td width="20%" valign="middle" align ="center" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen">{guest_user_row.USERNAME}</span>&nbsp;</td>
	<td width="20%" align="center" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen">{guest_user_row.STARTED}</span>&nbsp;</td>
	<td width="20%" align="center" nowrap="nowrap" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen">{guest_user_row.LASTUPDATE}</span>&nbsp;</td>
	<td width="20%" class="{guest_user_row.ROW_CLASS}">&nbsp;<span class="gen"><a href="{guest_user_row.U_FORUM_LOCATION}" class="gen">{guest_user_row.FORUM_LOCATION}</a></span>&nbsp;</td>
	
  </tr>
  <!-- END guest_user_row -->
</table>

<HTML>
