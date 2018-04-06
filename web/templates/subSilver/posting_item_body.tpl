<tr>
	<th class="thHead" colspan="2">{L_USE_AN_ITEM}</th>
</tr>
<tr>
	<td class="row2"><span class="gen"><b>{L_ITEM}</b></span></td> 
	<td class="row2" nowrap="nowrap">
		<select name='item_id'>
			<option value="none"><i>None</i></option>
<!-- BEGIN list_items -->
			<option value="{list_items.item_id}">{list_items.iName}</option>
<!-- END list_items -->
		</select>
	</td>
<tr>
	<td class="row2"><span class="gen"><b>{L_User}</b></span></td>
	<td class="row2" nowrap="nowrap">
		<input type="text" size="16" maxlength="32" name="user" class="post" />
	</td>
</tr>
<tr>
	<td class="row2"><span class="gen"><b>{L_Target}</b></span></td>
	<td class="row2" nowrap="nowrap">
		<input type="text" size="16" maxlength="32" name="target" class="post" />
	</td>
</tr>
