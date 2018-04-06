
<h1>{L_MEDAL_TITLE}</h1>

<p class="gen">{L_MEDAL_EXPLAIN}</p>

<form action="{S_MEDAL_ACTION}" method="post"><table class="forumline" cellpadding="4" cellspacing="1" border="0" align="center">
	<tr>
		<th class="thTop" colspan="2">{MEDAL_NAME}</th>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_MEDAL_NAME}</span></td>
		<td class="row2"><input class="post" type="text" name="medal_name" size="50" maxlength="40" value="{MEDAL_NAME}" /></td>
	</tr>
	<tr>
		<td class="row1" width="38%"><span class="gen">{L_MEDAL_DESCRIPTION}</span></td>
		<td class="row2"><input class="post" type="text" name="medal_description" size="50" maxlength="255" value="{MEDAL_DESCRIPTION}" /></td>
	</tr>
	<tr> 
		<td class="row1"><span class="gen">{L_MEDAL_IMAGE}</span><br /><span class="gensmall">{L_MEDAL_IMAGE_EXPLAIN}</span></td>
	  	<td class="row2"><input class="post" type="text" name="medal_image" size="35" maxlength="255" value="{IMAGE}" />&nbsp;&nbsp;&nbsp;{IMAGE_DISPLAY}</td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>
</table>
{S_HIDDEN_FIELDS}</form>
