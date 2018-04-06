<form action="{FORMACTION}">
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center" class="forumline">
	<tr>
		<th class="thHead" width="10%">&nbsp;</th>
		<th class="thHead" width="15%">Action</th>
		<th class="thHead" width="37.5%">Your Offer</th>
		<th class="thHead" width="37.5%">Their Offer</th>
	</tr>
	<!-- BEGIN trade_row -->
	<tr>
		<td class="row1" align="center"><input type="checkbox" name="id[]" value="{trade_row.ID}" class="post"></td>
		<td class="row2"><span class="gen">{trade_row.ACTION}</span></td>
		<td class="row1">{trade_row.YOUROFFER}</td>
		<td class="row1">{trade_row.THEIROFFER}</td>
	</tr>
	<!-- END trade_row -->
	<tr>
		<td class="row1" align="center" colspan="2">{S_HIDDEN}<input type="submit" name="submit" value="Accept" /><input type="submit" name="submit" value="Reject" /><input type="submit" name="submit" value="Edit" /></td>
	</tr>
</table>
