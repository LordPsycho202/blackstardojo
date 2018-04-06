 
<form action="{S_LOGIN_ACTION}" method="post" target="_top">

<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
  <tr> 
	<td align="left" class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
  </tr>
</table>

<table width="100%" cellpadding="4" cellspacing="0" class="forumline" align="center">
<thead>
	<caption><table border="0" cellspacing="0" cellpadding="0" width="100%" class="forumheader">
	<tr>
		<td align="left" valign="bottom" width="25"><img src="templates/AcidTechBlood/images/hdr_left.gif" width="25" height="27" alt="" /></td>
		<td align="center" class="forumheader-mid">{L_ENTER_PASSWORD}</td>
		<td align="right" valign="bottom" width="25"><img src="templates/AcidTechBlood/images/hdr_right.gif" width="25" height="27" alt="" /></td>
	</tr></table></caption>
</thead>
<tbody>
  <tr> 
	<td class="row1"><table border="0" cellpadding="3" cellspacing="1" width="100%">
		  <tr> 
			<td colspan="2" align="center">&nbsp;</td>
		  </tr>
		  <tr> 
			<td width="45%" align="right"><span class="gen">{L_USERNAME}:</span></td>
			<td> 
			  <input type="text" class="post" name="username" size="25" maxlength="40" value="{USERNAME}" />
			</td>
		  </tr>
		  <tr> 
			<td align="right"><span class="gen">{L_PASSWORD}:</span></td>
			<td> 
			  <input type="password" class="post" name="password" size="25" maxlength="32" />
			</td>
		  </tr>
		  <tr align="center"> 
			<td colspan="2"><table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="gen">{L_AUTO_LOGIN}:&nbsp;</span></td><td><span class="cbstyled"><input type="checkbox" name="autologin" /></span></td></tr></table></td>
		  </tr>
		  <tr align="center"> 
			<td colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="login" class="mainoption" value="{L_LOGIN}" /></td>
		  </tr>
		  <tr align="center"> 
			<td colspan="2"><span class="gensmall"><a href="{U_SEND_PASSWORD}" class="gensmall">{L_SEND_PASSWORD}</a></span></td>
		  </tr>
		</table></td>
  </tr>
 </tbody>
</table>

</form>
