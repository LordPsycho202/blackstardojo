<h1>{TITEL}</h1>
<p>{DESCRIPTION}</p>
<form action="{CASH_ACTION}" method="post">
<input type="hidden" name="progress" value="alter">
<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
	  <th class="thHead" colspan="2">{L_TITLE_TWO}</th>
	</tr>

	<tr>
		<td class="row1">{L_DISPLAY_RICHEST}</td>
		<td class="row2"><label><input type="radio" name="display_richest" value="1" {DISPLAY_RICHEST_YES} /> {L_YES}&nbsp;&nbsp;</label><label><input type="radio" name="display_richest" value="0" {DISPLAY_RICHEST_NO} /> {L_NO}</label></td>
	</tr>
	<tr>
		<td class="row1">{L_RICH_NUM}</td>
		<td class="row2"><input class="post" type="text" maxlength="10" size="10" name="richest_num" value="{RICH_NUM}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_DISPLAY_USERS}</td>
		<td class="row2"><label><input type="radio" name="display_users" value="1" {DISPLAY_USERS_YES} /> {L_YES}&nbsp;&nbsp;</label><label><input type="radio" name="display_users" value="0" {DISPLAY_USERS_NO} /> {L_NO}</label></td>
	</tr>
	<tr>
		<td class="row1">{L_SEPERATOR}</td>
		<td class="row2"><label><input type="radio" name="seperator" value="1" {KOMMA} /> ,&nbsp;&nbsp;</label><label><input type="radio" name="seperator" value="0" {BR} /> &lt;br></label></td>
	</tr>
	<tr>
		<td class="row1">{L_SYSTEM}</td>
		<td class="row2"><label><input type="radio" name="system" value="1" {CASH} /> Cash Mod&nbsp;&nbsp;<label><input type="radio" name="system" value="0" {POINTS} /> Points System</label></td>
	</tr>
	<!-- BEGIN switch_cashmod -->
	<tr>
		<td class="row1">{L_SELECT_CURRENCIES}</td>
		<td class="row2">{CURRENCIES_SELECT}</td>
	</tr>
	<tr>
		<td class="row1">{L_DISPLAY_CURRENCY}</td>
		<td class="row2">{CURRENCY_SELECT}</td>
	</tr>
	<!-- END switch_cashmod -->
	<tr>
		<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" />
		</td>
	</tr>
</table>
</form>

<br clear="all" />