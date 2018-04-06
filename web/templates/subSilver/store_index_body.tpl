<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
	</tr>
</table>

<!-- BEGIN switch_owned_stores -->
<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
  <tr>
    <th class="thHead">{L_ACTION}</th>
    <th class="thHead">{L_STORE}</th>
    <th class="thHead">{L_OWNER}</th>
    <th class="thHead">{L_ITEMS}</th>
    <th class="thHead">{L_CASH}</th>
  </tr>
  <tr>
    <td class="catHead" colspan="5"><span class="cattitle">{L_OWNED_STORES}</span></td>
  </tr>
    <!-- BEGIN owned_row -->
  <tr>
    <td class="row2"><span class="gen">{switch_owned_stores.owned_row.EDIT}<br />{switch_owned_stores.owned_row.DELETE}</span></td>
    <td class="row1"><span class="forumlink">{switch_owned_stores.owned_row.STORE_NAME}</span><span class="gen"><br />{switch_owned_stores.owned_row.STORE_DESCRIPTION}</td>
    <td class="row2"><span class="gen">{switch_owned_stores.owned_row.STORE_OWNER}</span></td>
    <td class="row3"><span class="gen">{switch_owned_stores.owned_row.ITEMS}</span></td>
    <td class="row2"><span class="gen">{switch_owned_stores.owned_row.CASH}</span></td>
  </tr>
  <!-- END owned_row -->
  <!-- BEGIN switch_new_store -->
  <form action="{S_NEW_ACTION}" method="post">
  <tr>
    <td class="catbottom" colspan="5"><input type="text" size="35" name="new_store" class="post"> <input type="submit" name="new" value="{S_NEW_VALUE}" class="post"></td>
  </tr>
  </form>
  <!-- END switch_new_store -->
</table>
<br clear="all" />
<!-- END switch_owned_stores -->

<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
  <tr>
    <th class="thHead">{L_STORE}</th>
    <th class="thHead">{L_OWNER}</th>
    <th class="thHead">{L_ITEMS}</th>
    <th class="thHead">{L_CASH}</th>
  </tr>
  <tr>
    <td class="catHead" colspan="4"><span class="cattitle">{L_BOARD_STORES}</span></td>
  </tr>
  <!-- BEGIN board_row -->
  <tr>
    <td class="row1"><span class="forumlink">{board_row.STORE_NAME}</span><span class="gen"><br />{board_row.STORE_DESCRIPTION}</td>
    <td class="row2"><span class="gen">{board_row.STORE_OWNER}</span></td>
    <td class="row3"><span class="gen">{board_row.ITEMS}</span></td>
    <td class="row2"><span class="gen">{board_row.CASH}</span></td>
  </tr>
  <!-- END board_row -->
</table>

<!-- BEGIN switch_stores -->
<br clear="all" />

<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
  <tr>
    <th class="thHead">{L_STORE}</th>
    <th class="thHead">{L_OWNER}</th>
    <th class="thHead">{L_ITEMS}</th>
    <th class="thHead">{L_CASH}</th>
  </tr>
  <tr>
    <td class="catHead" colspan="4"><span class="cattitle">{L_USER_STORES}</span></td>
  </tr>
  
  <!-- BEGIN user_row -->
  <tr>
    <td class="row1"><span class="forumlink">{switch_stores.user_row.STORE_NAME}</span><span class="gen"><br />{switch_stores.user_row.STORE_DESCRIPTION}</td>
    <td class="row2"><span class="gen">{switch_stores.user_row.STORE_OWNER}</span></td>
    <td class="row3"><span class="gen">{switch_stores.user_row.ITEMS}</span></td>
    <td class="row2"><span class="gen">{switch_stores.user_row.CASH}</span></td>
  </tr>
  <!-- END user_row -->
</table>
<!-- END switch_stores -->

<br clear="all" />
