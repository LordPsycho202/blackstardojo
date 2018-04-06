<h1>{L_STORE_TITLE}</h1>

<p>{L_STORE_EXPLAIN}</p>

<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
  <tr>
    <th class="thHead" width="5%">&nbsp;</th>
    <th class="thHead" width="10%">{L_ACTION}</th>
    <th class="thHead" width="40%">{L_ITEM}</th>
    <th class="thHead" width="35%">{L_IMAGE}</th>
    <th class="thHead" width="10%">{L_SPECIAL}</th>
  </tr>
  <form action="{S_DELETE_ACTION}" method="post">
  <!-- BEGIN item_row -->
  <tr>
    <td class="row1" width="5%" align="center"><input type="checkbox" name="id[]" value="{item_row.ID}"></td>
    <td class="row2" width="10%"><span class="gen">{item_row.EDIT}</span></td>
    <td class="row1" width="40%"><span class="forumlink">{item_row.NAME}</span><span class="gen"><br />{item_row.DESCRIPTION}</span></td>
    <td class="row2" width="35%"><span class="gen">{item_row.IMAGE}</span></td>
    <td class="row3" width="10%" align="center"><span class="gen">{item_row.SPECIAL}</span></td>
  </tr>
  <!-- END item_row -->
  <tr>
    <td colspan="5" class="cathead"><input type="submit" name="delete_items" value="{S_DELETE_VALUE}" class="post"></td>
  </tr>
  </form>
  <form action="{S_CREATE_ACTION}" method="post">
  <tr>
    <td colspan="5" class="catbottom"><form action="{S_CREATE_ACTION}" method="post"><input type="text" name="new_store" value="" size="30" class="post"> <input type="submit" name="create_item" value="{S_CREATE_VALUE}" class="post"></td>
  </tr>
  </form>
</table>

<br clear="all" />
