
<form action="{S_PROFILE_ACTION}" method="post">
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
  <tr> 
	<td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
  </tr>
</table>
  <table cellpadding="3" cellspacing="0" width="100%" class="forumline">
<thead>
	<caption><table border="0" cellspacing="0" cellpadding="0" width="100%" class="forumheader">
	<tr>
		<td align="left" valign="bottom" width="25"><img src="templates/AcidTechBlood/images/hdr_left.gif" width="25" height="27" alt="" /></td>
		<td align="center" class="forumheader-mid">{L_SEND_PASSWORD}</td>
		<td align="right" valign="bottom" width="25"><img src="templates/AcidTechBlood/images/hdr_right.gif" width="25" height="27" alt="" /></td>
	</tr></table></caption>
</thead>
<tbody>
	<tr> 
	  <td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></td>
	</tr>
	<tr> 
	  <td class="row1" width="38%"><span class="gen">{L_USERNAME}: *</span></td>
	  <td class="row2"> 
		<input type="text" class="post" style="width: 200px" name="username" size="25" maxlength="40" value="{USERNAME}" />
	  </td>
	</tr>
	<tr> 
	  <td class="row1"><span class="gen">{L_EMAIL_ADDRESS}: *</span></td>
	  <td class="row2"> 
		<input type="text" class="post" style="width: 200px" name="email" size="25" maxlength="255" value="{EMAIL}" />
	  </td>
	</tr>
	<tr> 
	  <td class="catBottom" colspan="2" align="center" height="28">{S_HIDDEN_FIELDS} 
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
		&nbsp;&nbsp; 
		<input type="reset" value="{L_RESET}" name="reset" class="liteoption" />
	  </td>
	</tr>
   </tbody>
  </table>
</form>
