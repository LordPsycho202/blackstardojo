 
<form action="{S_MEDAL_ACTION}" method="post">

<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
		<td align="left" class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
	</tr>
</table>

<table class="forumline" width="100%" cellspacing="1" cellpadding="4" border="0">
	<tr> 
		<th class="thHead" colspan="2" height="25">{L_MEDAL_INFORMATION}</th>
	</tr>
	<tr> 
		<td class="row3" colspan="2" align="center"><span class="gen"><b>{MEDAL_NAME}: {MEDAL_DESCRIPTION}</b></span></td>
	</tr>
<!-- BEGIN medaledit -->
	<tr> 
		<td class="row2" width="20%"><span class="gen">{medaledit.L_MEDAL_TIME}:</span></td>
		<td class="row2"><span class="gen">{medaledit.ISSUE_TIME}</span></td>
	</tr>
	<tr> 
		<td class="row1" width="20%"><span class="gen">{medaledit.L_MEDAL_REASON}:</span></td>
		<td class="row1"><textarea name="{medaledit.L_ISSUE_REASON}" rows="5" cols="60" class="post">{medaledit.ISSUE_REASON}</textarea></td>
	</tr>
<!-- END medaledit -->
	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>
</table>
{S_HIDDEN_FIELDS}</form>
