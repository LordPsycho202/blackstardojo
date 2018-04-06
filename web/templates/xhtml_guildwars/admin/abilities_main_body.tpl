<h1>{L_SHOPTITLE}</h1>
<p>{L_SHOPEXPLAIN}</p>
<form action="{S_CONFIG_ACTION}" method="post">
 <input type="hidden" name="action" value="updatestatus" />
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">Abilities Mod activation status</th>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">Status</span></td>
	  <td class="row2">
		<select name='status'>
			<option value='0' {OFF}>Off</option>
			<option value='1' {ADMIN}>Admin access only</option>
			<option value='2' {ON}>On</option>
		</select>
	  </td>
	</tr>
	
	<tr>
	  <td class="row1" colspan="2" align="center">
		<input type="submit" class="liteoption" value="{L_UPDATE}" />
	  </td>
	</tr>
  </table>
</form>

<br /><br />

<form action="{S_CONFIG_ACTION}" method="post">
  <table width="50%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="8">Abilities in the system</th>
	</tr>
	<tr>
	  <th class="thTop">Name</th>
	  <th class="thTop">Start level</th>
	  <th class="thTop">Max level</th>
	  <th class="thTop">Increment</th>
	  <th class="thTop">Prereq 1</th>
	  <th class="thTop">Prereq 2</th>
	  <th colspan="2" class="thTop">Action</th>
	</tr>
<!-- BEGIN list_abilities -->
	<tr>
	  <td class="row2">{name}</td>
	  <td class="row2">{start}</td>
	  <td class="row2">{max}</td>
	  <td class="row2">{inc}</td>
	  <td class="row2">{pre1}</td>
	  <td class="row2">{pre2}</td>
	  <td class="row2">{trade}</td>
	  <td class="row2"><a href="{U_ABILITY_EDIT}">Edit</a></td>
	  <td class="row2"><a href="{U_ABILITY_DELETE}">Delete</a></td>
	</tr>
<!-- END shop_listrow -->
	<tr>
	  <td class="catBottom" colspan="8" align="center"><input type="submit" name="new" value="Add new ability" class="liteoption" /></td>
	</tr>
  </table>
  <input type="hidden" name="action" value="new">
</form>


<br	clear="all" />
