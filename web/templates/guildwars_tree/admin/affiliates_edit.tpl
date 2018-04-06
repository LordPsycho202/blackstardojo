
<form action="{S_CONFIG_ACTION}" method="post">
	<table width="75%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
		<input type="hidden" name="id" value="{ID}" />
		<input type="hidden" name="confirmed" value="true" />
		<tr>
		  <th class="thHead" colspan="2">{NAME}</th>
		</tr>
		<tr>
			<td class="row1" width="50%">{L_SITENAME}</td>
			<td class="row2"><input type="text" name="sitename" class="post" value="{NAME}"/></td>
		</tr>
		<tr>
			<td class="row1">{L_SITEADDRESS}</td>
			<td class="row2"><input type="text" class="post" name="address" value="{ADDRESS}" /></td>
		</tr>
		<tr>
			<td class="row1">{L_SITEBANNER}</td>
			<td class="row2"><input type="text" class="post" name="banner" value="{IMAGE}" /></td>
		</tr>
		<tr>
			<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="Submit" class="post" /></td>
		</tr>
	</table>
</form>