<h1>{L_STORE_TITLE}</h1>

<p>{L_STORE_EXPLAIN}</p>

<form action="{S_SUBMIT_ACTION}" method="post">
<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
  <tr>
    <th class="thHead" colspan="2">{L_STORE_TITLE}</th>
  </tr>
  <tr>
    <td class="row1"><span class="gen">{L_ITEM}</span></td>
    <td class="row2">{ITEM_LIST}</td>
  </tr>
  <tr>
    <td class="row1"><span class="gen">{L_AMOUNT}</span></td>
    <td class="row2"><input type="text" name="amount" value="{AMOUNT}" cass="post" size="30"></td>
  </tr>
  <tr>
    <td class="row1"><span class="gen">{L_PRICE}</span></td>
    <td class="row2"><input type="text" name="price" value="{PRICE}" cass="post" size="30"></td>
  </tr>
  <tr>
    <td class="row1"><span class="gen">{L_RESTOCK_TIME}</span></td>
    <td class="row2"><input type="text" name="restock_time" value="{RESTOCK_TIME}" cass="post" size="30"></td>
  </tr>
  <tr>
    <td class="row1"><span class="gen">{L_RESTOCK_AMOUNT}</span></td>
    <td class="row2"><input type="text" name="restock_amount" value="{RESTOCK_AMOUNT}" cass="post" size="30"></td>
  </tr>
  <tr>
    <td class="catbottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{S_SUBMIT_VALUE}" class="mainoption"></td>
  </tr>
</table>

<br clear="all" />
