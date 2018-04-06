<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr> 
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a>{SHOPLOCATION}</span></td>
	</tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="5">{L_SHOP_TITLE}</th>
	</tr>
	<tr>
	  <td class="row3" align="center"><span class="gensmall"><b>{L_IMAGE}</b></span></td>
	  <td class="row3" align="center" nowrap="nowrap"><span class="gensmall"><b>{L_ITEM_NAME}</b></span></td>
	  <td class="row3" align="center"><span class="gensmall"><b>{L_DESC}</b></span></td>
	  <td class="row3" align="center"><span class="gensmall"><b>{L_REQUIRES}</b></span></td>
	  <td class="row3" align="center"><span class="gensmall"><b>{L_COST}</b></span></td>
	</tr>
<!-- BEGIN synth_items -->
	<tr>
	  <td class="{synth_items.ROW_CLASS}"><span class="gensmall"><img src="{synth_items.ITEM_IMG}" /></span></td>
	  <td class="{synth_items.ROW_CLASS}" wrap="nowrap"><span class="gensmall">{synth_items.ITEM_NAME}</td>
	  <td class="{synth_items.ROW_CLASS}"><span class="gensmall">{synth_items.ITEM_DESC} {synth_items.ITEM_DIE}</span></td>
	  <td class="{synth_items.ROW_CLASS}"><span class="gensmall"><select>{synth_items.ITEM_REQUIRES}</select></span></td>
	  <td class="{synth_items.ROW_CLASS}"><span class="gensmall">{synth_items.ITEM_COST}</span></td>
	</tr>
<!-- END synth_items -->
	<tr>
	  <td class="row3" colspan="5">
		<table width="100%" cellpadding="0" cellspacing="1" cellborder="0">
			<tr>
			  <td align="left"><span class="gensmall"><b>{L_SYNTH}: </b>
				<select>
<!-- BEGIN list_items -->
				  <option>{list_items.ITEM}</option>
<!-- END list_items -->
<!-- BEGIN switch_no_items -->
	 			  <option value="">{L_NONE}</option>
<!-- END switch_no_items -->
				</select>
			  </td>
<form method="post" action="{U_ACTION}">
  <input type="hidden" name="action" value="desynth" />
			  <td align="right">
				<select name="item_id">
<!-- BEGIN list_desynths -->
				  <option value="{list_desynths.ITEM_ID}">{list_desynths.ITEM_NAME}</option>
<!-- END list_desynths -->
<!-- BEGIN switch_no_desynth -->
	 			  <option value="">{L_NONE}</option>
<!-- END switch_no_desynth -->
				</select> <input type="submit" value="{L_DESYNTH}" class="liteoption" />
			  </td>
</form>
			</tr>
		</table>
	  </td>
	</tr>
</table>

<br />

<table width="100%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_PERSONAL_INFO}</th>
	</tr>
	<tr>
	  <td class="row1" width="50%"><span class="gensmall"><a href="{U_INVENTORY}" class="navsmall">{L_INVENTORY}</a></span></td><td class="row1" align="right" width="50%"><span class="gensmall">{USER_POINTS} {L_POINTS_NAME}</span></td>
	</tr> 
</table>
<table width="100%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<td width="100%" align="center" class="row3"><br /><span class="gensmall">Synth Shop Addon: Copyright © 2006, <a href="http://www.zarath.com" class="navsmall">Zarath Technologies</a>.</span><br /><br /></td>
	</tr>
</table>
<br	clear="all" />
