<form action="{S_NEW_ACTION}" method="post">
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
	</tr>
</table>
<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
  <tr>
    <th class="thHead" colspan="2">{L_CREATE_STORE}</th>
  </tr>
  <tr>
    <td class="row1"><span class="gen">{L_STORE_NAME}</span></td>
    <td class="row2"><input type="text" size="25" name="new_store" value="{STORE_NAME}" class="post"></td>
  </tr>
  <tr>
    <td class="row1"><span class="gen">{L_STORE_DESCRIPTION}</span></td>
    <td class="row2"><textarea name="store_description" class="post" rows="5" cols="50">{STORE_DESCRIPTION}</textarea></td>
  </tr>
  <tr>
	  <td class="row1"><span class="gen">{L_CASH}</span></td>
	  <td class="row2"><select name="cash_id" style="width:100">
		<option value="0">{L_SELECT_ONE}</option>
		<!-- BEGIN cashrow -->
		<option value="{cashrow.CASH_ID}" {cashrow.CASH_SELECTED}>{cashrow.CASH_NAME}</option>
		<!-- END cashrow -->
		</select></td>
	</tr>
  <tr>
    <td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_CREATE_STORE}" class="mainoption"></td>
  </tr>
</table>
</form>
