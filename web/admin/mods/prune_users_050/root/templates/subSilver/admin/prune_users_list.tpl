
<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<p><strong>{L_TOTAL_PRUNED_USERS}:</strong> {TOTAL_PRUNDED_USERS}</p>

<style type="text/css">
table.forumline span.heading {
	border-bottom: 1px dotted;
}
</style>
<script type="text/javascript">
function ts_makeSortable(table) {
    if (table.rows && table.rows.length > 3) {
        var firstRow = table.rows[0];
    }
    if (!firstRow) return;
    
    // We have a first row: assume it's the header, and make its contents clickable links
    for (var i=0;i<firstRow.cells.length;i++) {
        var cell = firstRow.cells[i];
        var txt = ts_getInnerText(cell);
		cell.innerHTML = '<span '+ 
		'onclick="ts_resortTable(this, '+i+');return false;" class="heading">' + 
		txt+'</span><span id="arrow'+i+'" class="sortarrow"></span>';
    }
}
</script>
<script src="../templates/sort.js"></script>			
<table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline" id="table" >
	<thead>
		<tr>
			<th class="thCornerL">{L_USERNAME}</th>
			<th class="thCornerL">{L_EMAIL}</th>
			<th class="thCornerL">{L_DELETED_BY}</th>
			<th class="thCornerL">{L_DELETE_DATE}</th>
			<th class="thTop">{L_USER_LAST_VISIT}</th>
			<th class="thTop">{L_USER_LAST_LOGIN_TRY}</th>
			<th class="thTop">{L_USER_REGDATE}</th>
			<th class="thTop">{L_USER_ACTIVE}</th>
			<th class="thTop">{L_USER_POSTS}</th>
		</tr>
	</thead>
	<tbody>	
	<!-- BEGIN inactive_users -->
		<tr> 
			<td class="{inactive_users.ROW_CLASS}" align="center">{inactive_users.USERNAME}</td>
			<td class="{inactive_users.ROW_CLASS}" align="center">{inactive_users.USER_EMAIL}</td>
			<td class="{inactive_users.ROW_CLASS}" id="{inactive_users.USER_DELETED_BY}" align="center"><a href="{inactive_users.DELETED_BY_PROFILE}">{inactive_users.USER_DELETED_BY}</a></td>
			<td class="{inactive_users.ROW_CLASS}" id="{inactive_users.USER_DELETE_DATE_RAW}" align="center">{inactive_users.USER_DELETE_DATE}</td>
			<td class="{inactive_users.ROW_CLASS}" id="{inactive_users.USER_LAST_VISIT_RAW}" align="center">{inactive_users.USER_LAST_VISIT}</td>
			<td class="{inactive_users.ROW_CLASS}" align="center">{inactive_users.USER_LAST_LOGIN_TRY}</td>
			<td class="{inactive_users.ROW_CLASS}" id="{inactive_users.USER_REGDATE_RAW}" align="center">{inactive_users.USER_REGDATE}</td>	
			<td class="{inactive_users.ROW_CLASS}" align="center">{inactive_users.USER_ACTIVE}</td>
			<td class="{inactive_users.ROW_CLASS}" align="center">{inactive_users.USER_POSTS}</td>
		</tr>
	<!-- END inactive_users -->		
	<!-- BEGIN no_inactive_users -->
	<tr>
		<td class="row1" align="center" colspan="9">{no_inactive_users.L_NONE}</td>
  	</tr>
	<!-- END no_inactive_users -->	
	</tbody>	
	<tr>
		<td class="catBottom" align="right" colspan="9"></td>
	</tr>
</table>
<br>
