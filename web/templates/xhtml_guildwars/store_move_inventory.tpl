<form method="post" action="{S_SUBMIT_ACTION}">
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> {U_STORE_NAV}</span></td>
	</tr>
</table>
<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
  <tr>
    <th class="thHead">{L_ITEM}</th>
    <!-- BEGIN switch_amount -->
    <th class="thHead">{L_AMOUNT}</th>
    <!-- END switch_amount -->
    <!-- BEGIN switch_price -->
    <th class="thHead">{L_PRICE}</th>
    <!-- END switch_price -->
  </tr>
  <tr>
    <td class="cathead" colspan="3"><span class="cattitle">{L_TITLE}</span></td>
  </tr>
  <!-- BEGIN item_row -->
  <tr>
    <td class="row1"><span class="gen"><input type="hidden" name="id[{item_row.I}]" value="{item_row.ID}">{item_row.ITEM}</span></td>
    <!-- BEGIN switch_amount -->
    <td class="row2"><span class="gen"><input type="text" name="amount[{item_row.I}]" size="5" class="post">&nbsp;/&nbsp;{item_row.AMOUNT}</span></td>
    <!-- END switch_amount -->
    <!-- BEGIN switch_price -->
    <td class="row3"><span class="gen"><input type="text" name="price[{item_row.I}]" size="5" class="post"></span></td>
    <!-- END switch_price -->
  </tr>
  <!-- END item_row -->
  <tr>
    <td class="catbottom" colspan="3" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="confirm" value="{S_SUBMIT_VALUE}" class="mainoption"> <input type="submit" name="cancel" value="{S_CANCEL_VALUE}" class="liteoption"></td>
  </tr>
</table>
</form>
