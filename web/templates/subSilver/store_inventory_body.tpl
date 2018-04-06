<script language="Javascript" type="text/javascript">
<!--

  function itemwindow(id)
  {
    window.open('{U_ITEM_WINDOW}' + id,'item','width=500,height=250');
  }
-->
</script>

<!-- BEGIN switch_owner -->
<form action="{S_MOVE_PRICE_ACTION}" method="post">
<!-- END switch_owner -->
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> {U_STORE_NAV}</span></td>
	</tr>
</table>
<table width="100%" cellspacing="1" cellpading="3" border="0" align="center" class="forumline">
  <tr>
    <td align="center" valign="middle" class="catHead" colspan="2"><span class="cattitle">{STORE_NAME}</span></td>
  </tr>
  <tr>
    <td class="row1" width="25%"><span class="gen">{L_DESCRIPTION}</span></td><td class="row2" width="75%"><span class="gen">{STORE_DESCRIPTION}</span></td>
  </tr>
  <tr>
    <td class="row1" width="25%"><span class="gen">{L_CASH}</td><td class="row2" width="75%"><span class="gen">{STORE_CASH}</td>
  </tr>
  <tr>
    <td class="row1" width="25%"><span class="gen">{L_YOUR_CASH}</td><td class="row2" width="75%"><span class="gen">{USER_CASH}</td>
  </tr>
  <!-- BEGIN switch_owner -->
  <tr>
    <td class="row1" colspan="2" align="center"><span class="gen">{STORE_EDIT} {STORE_DELETE}</span></td>
  </tr>
  <!-- END switch_owner -->
</table>
<br />
<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
  <tr>
    <th class="thHead" width="15%">&nbsp;</th>
    <th class="thHead" width="55%">{L_ITEM}</th>
	<th class="thHead" width="10%">Image</th>
    <th class="thHead" width="10%">{L_AMOUNT}</th>
    <th class="thHead" width="10%">{L_PRICE}</th>
  </tr>
  <!-- BEGIN item_row -->
  <!-- BEGIN switch_not_owner -->
  <form method="post" action="{S_BUY_ACTION}">
  <!-- END switch_not_owner -->
  <tr>
    <td class="row2" align="center"><span class="gen">
      <!-- BEGIN switch_owner -->
      <input type="checkbox" name="id[]" value="{item_row.ID}">
      <!-- END switch_owner -->
      <!-- BEGIN switch_not_owner -->
      <input type="text" size="5" name="amount" class="post"> <input type="hidden" name="id" value="{item_row.ID}"><input type="submit" src="" name="submit" alt="{S_BUY_VALUE}" value="{S_BUY_VALUE}" class="mainoption">
      <!-- END switch_not_owner -->
    </span></td>
    <td class="row1"><span class="forumlink"><a href="javascript:itemwindow({item_row.ID})" class="forumlink">{item_row.ITEM}</a></span><span class="gen"><br />{item_row.DESCRIPTION}</span></td>
	<td class="row1"><span class="gen"><img src="{item_row.IMAGE}" /></span></td>
    <td class="row2"><span class="gen">{item_row.AMOUNT}</span></td>
    <td class="row3"><span class="gen">{item_row.PRICE}</span></td>
  </tr>
  <!-- BEGIN switch_not_owner -->
  </form>
  <!-- END switch_not_owner -->
  <!-- END item_row -->
  <!-- BEGIN switch_owner -->
  <tr>
    <td class="catbottom" colspan="5">
      <select name="mode" class="post">
        <option value="edit_price">{S_EDIT_PRICE}</option>
        <option value="move_inventory_store">{S_MOVE}</option>
		<option value="add_services">{S_ADD_SERVICE}</option>
      </select>
      <input type="hidden" name="to_id" value="0">
      {S_HIDDEN_FIELDS}
      <input type="submit" name="submit" value="{S_GO}" class="mainoption">
    </td>
  </tr>
  <!-- END switch_owner -->
</table>
<!-- BEGIN switch_owner -->
</form>
<!-- END switch_owner -->
