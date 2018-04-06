<script language="Javascript" type="text/javascript">
<!--

  function itemwindow(id)
  {
    window.open('{U_ITEM_WINDOW}' + id,'item','width=500,height=250');
  }
-->
</script>

<form method="post" action="{S_MOVE_ACTION}">
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
	</tr>
</table>
<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
  <tr>
    <td class="cathead" colspan="4"><span class="cattitle">{USER_NAME}</span></td>
  </tr>
<tr>
    <!-- BEGIN switch_owner -->
    <th class="thHead" width="10%">&nbsp;</th>
	<!-- END switch_owner -->
    <th class="thHead" width="15%">{L_ACTION}</th>
    <th class="thHead" width="65%">{L_ITEM}</th>
    <th class="thHead" width="10%">{L_AMOUNT}</th>
  </tr>
  
  <!-- BEGIN item_row -->
    <tr>
	<!-- BEGIN switch_owner -->
		<td class="row1" align="center"><input type="checkbox" name="id[]" value="{item_row.ID}" class="post"></td>
	<!-- END switch_owner -->
		<td class="row2" align="center"><span class="gen">{item_row.DELETE}</span></td>
	

    <td class="row1"><span class="forumlink"><a href="javascript:itemwindow({item_row.ID})" class="forumlink">{item_row.ITEM}</a></span><span class="gen"><br />{item_row.DESCRIPTION}</span></td>
    <td class="row2"><span class="gen">{item_row.AMOUNT}</span></td>
  </tr>
  <!-- END item_row -->
  <!-- BEGIN switch_owner -->
  <tr>
    <td colspan="4" class="catbottom">{S_HIDDEN}<select name="to_id" class="post">{S_STORE_LIST}</select><input type="submit" name="submit" value="{S_MOVE_VALUE}" class="mainoption"></td>
  </tr>
  <!-- END switch_owner -->
</form>
</table>
