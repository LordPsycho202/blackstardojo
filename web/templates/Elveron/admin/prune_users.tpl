
<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>
<style type="text/css">
table.forumline span.heading {
	border-bottom: 1px dotted;
}
</style>
<script type="text/javascript">
function confirmSubmit()
{
	return confirm("{L_CONFIRM_MESSAGE}");
}
function confirmNotifySelected()
{
	return confirm("{L_CONFIRM_NOTIFY_SELECTED}");
}
function confirmFlagSelected()
{
	return confirm("{L_CONFIRM_FLAG_SELECTED}");
}
function confirmNotify(user)
{
	var msg = "{L_NOTIFY_MESSAGE}";
	msg = msg.replace(/%s/, user);
	return confirm(msg);
}
function confirmFlag(user, action)
{
	var msg = "{L_FLAG_MESSAGE}";
	msg = msg.replace(/%action/, action);	
	msg = msg.replace(/%s/, user);
	return confirm(msg);
}
function ts_makeSortable(table) {
    if (table.rows && table.rows.length > 3) {
        var firstRow = table.rows[0];
    }
    if (!firstRow) return;
    
    // We have a first row: assume it's the header, and make its contents clickable links
    for (var i=0;i<firstRow.cells.length;i++) {
        var cell = firstRow.cells[i];
        var txt = ts_getInnerText(cell);
		if ( txt !== "{L_NOTIFY_USER}" && txt !== "{L_SELECTED}" && txt !== "{L_FLAG_USER}" )
		{
	        cell.innerHTML = '<span '+ 
	        'onclick="ts_resortTable(this, '+i+');return false;" class="heading">' + 
	        txt+'</span><span id="arrow'+i+'" class="sortarrow"></span>';
		}
    }
}

</script>
<script src="../templates/sort.js"></script>			
<form id="prune_users" method="post" action="{S_FORM_ACTION}"><table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline" id="table" width="80%">
	<thead>
		<tr>
			<th class="thCornerL">{L_USERNAME}</th>
			<th class="thTop">{L_USER_LAST_VISIT}</th>	
			<th class="thTop">{L_USER_LAST_LOGIN_TRY}</th>
			<th class="thTop">{L_LAST_NOTIFIED}</th>
			<th class="thTop">{L_USER_REGDATE}</th>		
			<th class="thTop">{L_USER_ACTIVE}</th>
			<th class="thTop">{L_USER_POSTS}</th>
			<th class="thTop">{L_FLAG_USER}</th>		
			<th class="thTop">{L_NOTIFY_USER}</th>		
			<th class="thCornerR">{L_SELECTED}</th>
		</tr>
	</thead>
	<tbody>	
	<!-- BEGIN inactive_users -->

		<tr>
			<td class="{inactive_users.ROW_CLASS}" id="{inactive_users.USERNAME}" align="center"><a href="{inactive_users.U_USER_PROFILE}">{inactive_users.USERNAME}</a></td>
			<td class="{inactive_users.ROW_CLASS}" id="{inactive_users.USER_LAST_VISIT_RAW}" align="center">{inactive_users.USER_LAST_VISIT}</td>
			<td class="{inactive_users.ROW_CLASS}" id="{inactive_users.USER_LAST_LOGIN_TRY_RAW}" align="center">{inactive_users.USER_LAST_LOGIN_TRY}</td>
			<td class="{inactive_users.ROW_CLASS}" id="{inactive_users.USER_LAST_NOTIFIED_RAW}" align="center">{inactive_users.USER_LAST_NOTIFIED}</td>
			<td class="{inactive_users.ROW_CLASS}" id="{inactive_users.USER_REGDATE_RAW}" align="center">{inactive_users.USER_REGDATE}</td>			
			<td class="{inactive_users.ROW_CLASS}" align="center">{inactive_users.USER_ACTIVE}</td>
			<td class="{inactive_users.ROW_CLASS}" align="center">{inactive_users.USER_POSTS}</td>
			<td class="{inactive_users.ROW_CLASS}" align="center"><a href="{inactive_users.U_FLAG_USER}" onClick='return confirmFlag("{inactive_users.USERNAME}", "{inactive_users.L_USER_FLAGGED}");' ><img src="../templates/subSilver/images/{inactive_users.USER_FLAGGED}" border="0" alt="{inactive_users.L_USER_FLAGGED}"></a></td>
			<td class="{inactive_users.ROW_CLASS}" align="center"><a href="{inactive_users.U_NOTIFY_USER}" onClick='return confirmNotify("{inactive_users.USERNAME}");' >{L_EMAIL}</a></td>
		    <td class="{inactive_users.ROW_CLASS}" align="center"><input type="checkbox" name="inactive_users[]" value="{inactive_users.USER_ID}" class="user_selected" checked="checked"></td>
		</tr>
	<!-- END inactive_users -->	
	<!-- BEGIN no_inactive_users -->
	<tr>
		<td class="row1" align="center" colspan="10">{no_inactive_users.L_NONE}</td>
  	</tr>
	<!-- END no_inactive_users -->	
	</tbody>	
	<tr>
		<td class="catBottom" align="right" colspan="10">{L_SELECT_ALL_NONE} <input type="checkbox" onclick="checkAll(document.getElementById('prune_users'), 'user_selected',this);" checked="checked"/></td>
	</tr>
</table>
<br><div align="center"><input type="submit" class="mainoption" name="flag" value="{L_FLAG_SELECTED}" onClick="return confirmFlagSelected()" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" class="mainoption" name="notify" value="{L_NOTIFY_SELECTED}" onClick="return confirmNotifySelected()" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" class="mainoption" name="submit" value="{L_SUBMIT}" onClick="return confirmSubmit()" /></div><br>
</form>
