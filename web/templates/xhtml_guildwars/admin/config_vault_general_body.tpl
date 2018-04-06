
<h1>{L_VAULT_SETTINGS}</h1>

<p>{L_VAULT_SETTINGS_EXPLAIN}</p>

<form action="{S_VAULT_ACTION}" method="post">

<table border="0" cellpadding="4" cellspacing="1" width="100%" class="forumline">
	<tr>
		<td class="row1" align="center" width="65%"><span class="gen">{L_VAULT_USE}</span></td>
		<td class="row2" align="center" valign="top"><input type="checkbox" name="use" value="1" {VAULT_USE_CHECKED} /></td>
	</tr>
	<tr>
		<td class="row1" align="center" width="65%"><span class="gen">{L_VAULT_NAME}</span></td>
		<td class="row2" align="center" ><input class="post" type="text" maxlength="255" size="15" name="name" value="{VAULT_NAME}" /></td>
	</tr>
  <tr> 
	<td colspan="5" height="1" class="row3"><img src="../templates/subSilver/images/spacer.gif" width="1" height="1" alt="."></td>
  </tr>
	<tr>
		<td class="row1" align="center" width="65%"><span class="gen">{L_VAULT_INTERESTS_RATE}</span><br /><span class="gensmall">{L_VAULT_INTERESTS_RATE_EXPLAIN}</span></td>
		<td class="row2" align="center" ><input class="post" type="text" maxlength="8" size="15" name="interests_rate" value="{VAULT_INTERESTS_RATE}" /></td>
	</tr>
	<tr>
		<td class="row1" align="center" width="65%"><span class="gen">{L_VAULT_INTERESTS_TIME}</span><br /><span class="gensmall">{L_VAULT_INTERESTS_TIME_EXPLAIN}</span></td>
		<td class="row2" align="center" ><input class="post" type="text" maxlength="8" size="15" name="interests_time" value="{VAULT_INTERESTS_TIME}" /><br /><span class="gensmall">( {VAULT_INTERESTS_TIME_EXPLAIN} )</span></td>
	</tr>
  <tr> 
	<td colspan="5" height="1" class="row3"><img src="../templates/subSilver/images/spacer.gif" width="1" height="1" alt="."></td>
  </tr>
	<tr>
		<td class="row1" align="center" width="65%"><span class="gen">{L_VAULT_USE_LOAN}</span></td>
		<td class="row2" align="center" valign="top"><input type="checkbox" name="loan" value="1" {VAULT_USE_LOAN_CHECKED} /></td>
	</tr>
	<tr>
		<td class="row1" align="center" width="65%"><span class="gen">{L_VAULT_LOAN_INTERESTS}</span><br /><span class="gensmall">{L_VAULT_LOAN_INTERESTS_EXPLAIN}</span></td>
		<td class="row2" align="center" ><input class="post" type="text" maxlength="8" size="8" name="loan_interests" value="{VAULT_LOAN_INTERESTS}" /></td>
	</tr>
	<tr>
		<td class="row1" align="center" width="65%"><span class="gen">{L_VAULT_LOAN_INTERESTS_TIME}</span><br /><span class="gensmall">{L_VAULT_LOAN_INTERESTS_TIME_EXPLAIN}</span></td>
		<td class="row2" align="center" ><input class="post" type="text" maxlength="8" size="8" name="loan_time" value="{VAULT_LOAN_INTERESTS_TIME}" /><br /><span class="gensmall">( {VAULT_LOAN_INTERESTS_TIME_EXPLAIN} )</span></td>
	</tr>
	<tr>
		<td class="row1" align="center" width="65%"><span class="gen">{L_VAULT_LOAN_MAX_SUM}</span><br /><span class="gensmall">{L_VAULT_LOAN_MAX_SUM_EXPLAIN}</span></td>
		<td class="row2" align="center" ><input class="post" type="text" maxlength="8" size="8" name="loan_sum" value="{VAULT_LOAN_MAX_SUM}" /></td>
	</tr>
	<tr>
		<td class="row1" align="center" width="65%"><span class="gen">{L_VAULT_LOAN_REQUIREMENTS}</span><br /><span class="gensmall">{L_VAULT_LOAN_REQUIREMENTS_EXPLAIN}</span></td>
		<td class="row2" align="center" ><input class="post" type="text" maxlength="8" size="8" name="loan_req" value="{VAULT_LOAN_REQUIREMENTS}" /></td>
	</tr>
  <tr> 
	<td colspan="5" height="1" class="row3"><img src="../templates/subSilver/images/spacer.gif" width="1" height="1" alt="."></td>
  </tr>
	<tr>
		<td class="row1" align="center" width="65%"><span class="gen">{L_VAULT_PROFILE}</span></td>
		<td class="row2" align="center" valign="top"><input type="checkbox" name="profile" value="1" {VAULT_PROFILE_CHECKED} /></td>
	</tr>
	<tr>
		<td class="row1" align="center" width="65%"><span class="gen">{L_VAULT_TOPICS}</span></td>
		<td class="row2" align="center" valign="top"><input type="checkbox" name="topics" value="1" {VAULT_TOPICS_CHECKED} /></td>
	</tr>
	<tr>
		<td class="row1" align="center" width="65%"><span class="gen">{L_VAULT_BASE_AMOUNT}</span><br /><span class="gensmall">{L_VAULT_BASE_AMOUNT_EXPLAIN}</span></td>
		<td class="row2" align="center" ><input class="post" type="text" maxlength="8" size="8" name="base_amount" value="{VAULT_BASE_AMOUNT}" /></td>
	</tr>
	<tr>
		<td class="row1" align="center" width="65%"><span class="gen">{L_VAULT_NUM_ITEMS}</span></td>
		<td class="row2" align="center" ><input class="post" type="text" maxlength="8" size="8" name="num_items" value="{VAULT_NUM_ITEMS}" /></td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></td>
	</tr>
</table>
</form>

<br clear="all" />

