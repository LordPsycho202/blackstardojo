<form action="{S_VAULT_ACTION}" method="post">
<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
	<tr> 
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> {L_PUBLIC_TITLE}</span></td>
		</span></td>
	</tr>
  </table>

<table width="100%" cellspacing="1" cellpadding="3" align="center">
	<tr>
		<td class="catHead" colspan="1" align="center"><span class="cattitle">{L_PUBLIC_TITLE}</span></td>
	</tr>
	<tr>
		<td class="row2" align="center" colspan="3" ><span class="gen">{L_OPENED_ACCOUNTS} : {ACCOUNTS}</span> </td>
	</tr>
	<tr>
		<td class="row2" align="center" colspan="3" ><span class="gen">{L_TOTAL_DEPOSIT} : {TOTAL_DEPOSIT} {L_POINTS}</span> </td>
	</tr>
</table>
<br clear="all" />

<!-- BEGIN blacked -->
<table width="100%" cellspacing="1" cellpadding="3" align="center">
	<tr>
		<td class="catHead" colspan="1" align="center"><span class="cattitle">{L_BLACK_LISTED}</span></td>
	</tr>
	<tr>
		<td class="row1" align="center" ><span class="gensmall">{L_BLACK_LISTED_EXPLAIN}</span> </td>
	</tr>
	<tr>
		<td class="row2" align="center" ><span class="gen">{L_BLACK_LISTED_DUE} : {DUE} {L_POINTS}</span> </td>
	</tr>
	<tr>
		<td class="row3" align="center" ><input type="hidden" name="due" value="{DUE}"><input type="submit" value="{L_DUE_PAYOFF}" name="due_payoff" class="mainoption" / ></td>
	</tr>
</table>
<!-- END blacked -->

<!-- BEGIN no_account -->
<table width="100%" cellspacing="1" cellpadding="3" align="center">
	<tr>
		<td class="catHead" colspan="1" align="center"><span class="cattitle">{L_PERSONAL_INFORMATIONS}</span></td>
	</tr>
	<tr>
		<td class="row2" align="center" ><span class="gen">{L_OWNER_POINTS} : {POINTS} {L_POINTS}</span> </td>
	</tr>
	<tr>
		<td class="row2" align="center" ><span class="gen">{L_NO_ACCOUNT}</span> </td>
	</tr>
	<tr>
		<td class="row3" align="center" ><input type="submit" value="{L_OPEN_ACCOUNT}" name="open" class="mainoption" /></td>
	</tr>
</table>
<!-- END no_account -->
<!-- BEGIN account -->

<table width="100%" cellspacing="1" cellpadding="3" align="center">
	<tr>
		<td class="catHead" colspan="3" align="center"><span class="cattitle">{L_ACCOUNT_INFORMATIONS}</span></td>
	</tr>
	<tr>
		<td class="row2" align="center" ><span class="gen">{L_INTEREST_RATE}</span> </td>
		<td class="row1" align="center" colspan="2" ><span class="gen">{INTEREST_RATE} %</span></td>
	</tr>
	<tr>
		<td class="row2" align="center" ><span class="gen">{L_INTEREST_TIME}</span> </td>
		<td class="row1" align="center" colspan="2" ><span class="gen">{INTEREST_TIME}</span></td>
	</tr>
	<tr>
		<td class="row1" width="50%" align="center"><span class="gen">{L_ACCOUNT_DEPOSIT}</span></td>
		<td class="row2" align="center"><input class="post" type="text" maxlength="8" size="8" name="deposit_sum" /><span class="gensmall"> {L_POINTS}</span></td>
		<td class="row3" align="center"><input type="submit" value="{L_DEPOSIT}" name="deposit" class="liteoption" /></td>
	</tr>
	<tr>
		<td class="row1" width="50%" align="center"><span class="gen">{L_ACCOUNT_WITHDRAW}</span></td>
		<td class="row2" align="center"><input class="post" type="text" maxlength="8" size="8" name="withdraw_sum" /> <span class="gensmall"> {L_POINTS}</span></td>
		<td class="row3" align="center"><input type="submit" value="{L_WITHDRAW}" name="withdraw" class="liteoption" /></td>
	</tr>

</table>
<br clear="all" />
<table width="100%" cellspacing="1" cellpadding="3" align="center">
	<tr>
		<td class="catHead" colspan="1" align="center"><span class="cattitle">{L_PERSONAL_INFORMATIONS}</span></td>
	</tr>
	<tr>
		<td class="row2" align="center" ><span class="gen">{L_OWNER_POINTS} : {POINTS} {L_POINTS}</span> </td>
	</tr>
	<tr>
		<td class="row2" align="center" ><span class="gen">{L_ACCOUNT} : {ACCOUNT_SUM} {L_POINTS}</span> </td>
	</tr>
</table>
<br clear="all" />
<!-- BEGIN loan_authed -->
<br clear="all" />
<table width="100%"cellspacing="1" cellpadding="3" align="center">
	<tr>
		<td class="catHead" colspan="4" align="center"><span class="cattitle">{L_LOAN_INFORMATIONS}</span></td>
	</tr>
<!-- BEGIN no_loan -->
	<tr>
		<td class="row2" align="center" ><span class="gen">{L_NO_LOAN_EXPLAIN} {POSTS_REQ} {L_POSTS_REQ}</span> </td>
	</tr>
<!-- END no_loan -->
<!-- BEGIN active_loan -->
	<tr>
		<td class="row2" align="center" colspan="4"><span class="gen">{L_LOAN_ACTIVE}</span> </td>
	</tr>
	<tr>
		<td class="row1" width="25%" align="center"><span class="gen"><b>{L_LOAN_SUM}</b></span></td>
		<td class="row1" width="25%" align="center"><span class="gen"><b>{L_LOAN_REMAINING_TIME}</b></span></td>
		<td class="row1" width="25%" align="center"><span class="gen"><b>{L_LOAN_REMAINING_DATE}</b></span></td>
		<td class="row1" width="25%" align="center"><span class="gen"><b>{L_LOAN_LOAN}</b></span></td>
	</tr>
	<tr>
		<td class="row2" width="25%" align="center"><span class="gen">{LOAN_SUM} {L_POINTS}</span></td>
		<td class="row2" width="25%" align="center"><span class="gen">{LOAN_REMAINING_TIME}</span></td>
		<td class="row2" width="25%" align="center"><span class="gen">{LOAN_REMAINING_DATE}</span></td>
		<td class="row2" width="25%" align="center"><span class="gen">{LOAN_LOAN} {L_POINTS}</span></td>
	</tr>
	<tr>
		<td class="row3" align="center" colspan="4"><input type="submit" value="{L_LOAN_BACK}" name="loan_back" class="mainoption" /></td>
	</tr>
<!-- END active_loan -->
<!-- BEGIN loan -->
	<tr>
		<td class="row2" align="center" ><span class="gen">{L_LOAN_RATE}</span> </td>
		<td class="row1" align="center" colspan="2" ><span class="gen">{LOAN_RATE} %</span></td>
	</tr>
	<tr>
		<td class="row2" align="center" ><span class="gen">{L_LOAN_TIME}</span> </td>
		<td class="row1" align="center" colspan="2" ><span class="gen">{LOAN_TIME}</span></td>
	</tr>
	<tr>
		<td class="row2" align="center" ><span class="gen">{L_LOAN_MAX_SUM}</span> </td>
		<td class="row1" align="center" colspan="2" ><span class="gen">{LOAN_MAX_SUM} {L_POINTS}</span></td>
	</tr>
	<tr>
		<td class="row1" width="50%" align="center"><span class="gen">{L_ACCOUNT_LOAN}</span></td>
		<td class="row2" align="center"><input class="post" type="text" maxlength="8" size="8" name="loan_sum" /><span class="gensmall"> {L_POINTS}</span></td>
		<td class="row3" align="center"><input type="submit" value="{L_LOAN}" name="loan" class="liteoption" /></td>
	</tr>
<!-- END loan -->
</table>
<!-- END loan_authed -->
<br clear="all" />
<table width="100%" cellspacing="1" cellpadding="3" align="center">
	<tr>
		<td class="catHead" colspan="1" align="center"><span class="cattitle">{L_OTHERS}</span></td>
	</tr>
	<tr>
		<td class="row1" align="center" ><input type="submit" value="{L_PREFERENCES}" name="prefs" class="mainoption" /></td>
	</tr>
	<tr>
		<td class="row2" align="center" ><input type="submit" value="{L_LIST}" name="list" class="mainoption" /></td>
	</tr>
	<!-- BEGIN stock -->
	<tr>
		<td class="row3" align="center" ><input type="submit" value="{L_STOCK_EXCHANGE}" name="stock_exchange" class="mainoption" /></td>
	</tr>
	<!-- END stock -->
</table>
<!-- END account -->
</form>
<br clear="all" />


