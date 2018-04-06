<h1>{L_SHOPTITLE}</h1>
<p>{L_SHOPEXPLAIN}</p>
<form action="{S_CONFIG_ACTION}" method="post">
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_ABILITYTITLE}</th>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_ABILITY_NAME}</span></td>
	  <td class="row2"><input name="name" type="text" class="post" size="32" maxlength="32" value="{NAME}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_ABILITY_DESC}</span></td>
	  <td class="row2"><textarea name="desc" columns="20" rows="10" class="post">{DESC}</textarea></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_START}</span></td>
	  <td class="row2"><input name="start" type="text" class="post" size="32" maxlength="3" value="{START}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_MAX}</span></td>
	  <td class="row2"><input name="max" type="text" class="post" size="32" maxlength="3" value="{MAX}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_MASTER}</span></td>
	  <td class="row2"><input name="master" type="text" class="post" size="32" maxlength="3" value="{MASTER}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_INC}</span></td>
	  <td class="row2"><input name="inc" type="text" class="post" size="32" maxlength="1" value="{INC}" /></td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_PREREQ1}</span></td>
	  <td class="row2">
		<select name="pre1">
			<option value="-1" {NSELECT}>None</option>
		<!-- BEGIN list_pre1 -->
			<option value="{list_pre1.id}" {list_pre1.select}>{list_pre1.name}</option>
		<!-- END list_pre1 -->
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_TRADE}</span></td>
	  <td class="row2">
		<select name="trade">
		<!-- BEGIN list_items -->
			<option value="{list_items.id}" {list_items.select}>{list_items.name}</option>
		<!-- END list_items -->
		</select>
	  </td>
	<tr>
	  <td class="row2"><span class="gensmall">{L_PREREQ2}</span></td>
	  <td class="row2">
		<select name="pre2">
			<option value="-1" {NSELECT}>None</option>
		<!-- BEGIN list_pre2 -->
			<option value="{list_pre2.id}" {list_pre2.select}>{list_pre2.name}</option>
		<!-- END list_pre2 -->
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_PASS}</span></td>
	  <td class="row2">
		<input name="pass" type="text" class="post" size="32" maxlength="128" value="{PASS}">
	  </td>
	</tr>
	<tr>
	  <td class="row2"><span class="gensmall">{L_FAIL}</span></td>
	  <td class="row2">
		<input name="fail" type="text" class="post" size="32" maxlength="128" value="{FAIL}">
	  </td>
	</tr>
			
	<tr>
	  <td colspan="2" class="row2" align="center"><input type="submit" class="liteoption" value="{L_UPDATE_ABILITY}" /></td>
 {ABILITY_ID} <input type="hidden" name="action" value="{ACTION}">
</form>

<br	clear="all" />
