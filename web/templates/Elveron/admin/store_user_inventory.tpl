<h1>{L_STORE_TITLE}</h1>

<p>{L_STORE_EXPLAIN}</p>

<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
  <tr>
    <th class="thHead" width="5%">&nbsp;</th>
    <th class="thHead" width="10%">{L_ACTION}</th>
    <th class="thHead" width="30%">{L_ITEM}</th>
    <th class="thHead" width="14%">{L_AMOUNT}</th>
    <th class="thHead" width="14%">{L_PRICE}</th>
  </tr>
  <form action="{S_DELETE_ACTION}" method="post">
  <!-- BEGIN item_row -->
  <tr>
    <td class="row1" align="center"><input type="checkbox" name="id[]" value="{item_row.ID}"></td>
    <td class="row2"><span class="gen">{item_row.EDIT}</span></td>
    <td class="row1"><span class="forumlink">{item_row.ITEM}</span><span class="gen"><br />{item_row.DESCRIPTION}</span></td>
    <td class="row2"><span class="gen">{item_row.AMOUNT}</span></td>
    <td class="row3"><span class="gen">{item_row.PRICE}</span></td>
  </tr>
  <!-- END item_row -->
  <tr>
    <td colspan="7" class="cathead"><input type="submit" name="submit" value="{S_DELETE_VALUE}" class="post"></td>
  </tr>
  </form>
  <form action="{S_ADD_ACTION}" method="post">
  <tr>
    <td colspan="7" class="catbottom">{S_ADD_HIDDEN}<input type="submit" name="new" value="{S_ADD_VALUE}" class="post"></td>
  </tr>
  </form>
</table>

<br clear="all" />
