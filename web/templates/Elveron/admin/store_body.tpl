<h1>{L_STORE_TITLE}</h1>

<p>{L_STORE_EXPLAIN}</p>

<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
  <tr>
    <th class="thHead">{L_ACTION}</th>
    <th class="thHead">{L_STORE}</th>
    <th class="thHead">{L_OWNER}</th>
    <th class="thHead">{L_ITEMS}</th>
    <th class="thHead">{L_CASH}</th>
  </tr>
  <!-- BEGIN store_row -->
  <tr>
    <td class="row2"><span class="gen">{store_row.INVENTORY}<br />{store_row.EDIT}<br />{store_row.DELETE}</span></td>
    <td class="row1"><span class="forumlink">{store_row.STORE_NAME}</span><span class="gen"><br />{store_row.STORE_DESCRIPTION}</td>
    <td class="row2"><span class="gen">{store_row.STORE_OWNER}</span></td>
    <td class="row3"><span class="gen">{store_row.ITEMS}</span></td>
    <td class="row2"><span class="gen">{store_row.CASH}</span></td>
  </tr>
  <!-- END store_row -->
  <form action="{S_NEW_ACTION}" method="post">
  <tr>
    <td class="catbottom" colspan="5"><input type="text" size="35" name="new_store" class="post"> <input type="submit" name="new" value="{S_NEW_VALUE}" class="post"></td>
  </tr>
  </form>
</table>

<br clear="all" />
