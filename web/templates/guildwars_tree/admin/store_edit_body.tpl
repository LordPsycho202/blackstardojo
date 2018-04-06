<h1>{L_CREATE_STORE}</h1>

<p>{L_STORE_EXPLAIN}</p>

<form action="{S_NEW_ACTION}" method="post">
<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
  <tr>
    <th class="thHead" colspan="2">{L_CREATE_STORE}</th>
  </tr>
  <tr>
    <td class="row1">{L_STORE_NAME}</td>
    <td class="row2"><input type="text" size="25" name="new_store" value="{STORE_NAME}" class="post"></td>
  </tr>
  <tr>
    <td class="row1">{L_STORE_DESCRIPTION}</td>
    <td class="row2"><textarea name="store_description" class="post" rows="5" cols="50">{STORE_DESCRIPTION}</textarea></td>
  </tr>
  <!-- BEGIN switch_store -->
  <tr>
	  <td class="row1">{L_CASH}</td>
	  <td class="row2"><select name="cash_id" style="width:100">
		<option value="0">{L_SELECT_ONE}</option>
		<!-- BEGIN cashrow -->
		<option value="{switch_store.cashrow.CASH_ID}" {switch_store.cashrow.CASH_SELECTED}>{switch_store.cashrow.CASH_NAME}</option>
		<!-- END cashrow -->
		</select></td>
	</tr>
  <!-- END switch_store -->
  <!-- BEGIN switch_item -->
  <tr>
    <td class="row1">{L_ITEM_IMAGE}</td>
    <td class="row2"><input type="text" size="25" class="post" name="item_image" value="{ITEM_IMAGE}"></td>
  </tr>
   <tr>
	<td class="row1">{L_TYPE}</td>
	<td class="row2">{TYPE_SELECT}</td>
  </tr>
  <tr>
    <td class="row1">{L_USERFIELD}</td>
	<td class="row2">{USER_FIELD}</td>
  </tr>
  <!-- END switch_item -->
  <tr>
    <td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_CREATE_STORE}" class="mainoption"></td>
  </tr>
</table>
</form>

<br clear="all" />
