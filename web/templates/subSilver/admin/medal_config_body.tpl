
<h1>{L_CONFIGURATION_TITLE}</h1>

<p>{L_CONFIGURATION_EXPLAIN}</p>

<form action="{S_CONFIG_ACTION}" method="post"><table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
	  <th class="thHead" colspan="2">{L_MEDAL_SETTINGS}</th>
	</tr>
	<tr>
		<td class="row1"><span class="gen">{L_ALLOW_MEDAL}</span></td>
		<td class="row2"><input type="radio" name="allow_medal_dispaly" value="1" {MEDAL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_medal_dispaly" value="0" {MEDAL_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1"><span class="gen">{L_MEDAL_RAND}</span><br /><span class="gensmall">{L_MEDAL_RAND_EXPLAIN}</span></td>
		<td class="row2"><input type="radio" name="medal_display_order" value="1" {RAND_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="medal_display_order" value="0" {RAND_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1"><span class="gen">{L_MEDAL_DISPLAY}</span><br /><span class="gensmall">{L_MEDAL_DISPLAY_EXPLAIN}</span></td>
		<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="medal_display_row" value="{MEDAL_DISPALY_ROW}" /> x <input class="post" type="text" size="3" maxlength="4" name="medal_display_col" value="{MEDAL_DISPALY_COL}"></td>
	</tr>
	<tr>
		<td class="row1"><span class="gen">{L_MEDAL_SIZE}</span><br /><span class="gensmall">{L_MEDAL_SIZE_EXPLAIN}</span></td>
		<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="medal_display_height" value="{MEDAL_DISPALY_H}" /> x <input class="post" type="text" size="3" maxlength="4" name="medal_display_width" value="{MEDAL_DISPALY_W}"></td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" />
		</td>
	</tr>
</table></form>

<br clear="all" />
