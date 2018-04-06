<form action="{S_BOOKIE_ADMIN}" method="post">
<h1>{CONFIG_HEADER}</h1>
<p>{CONFIG_EXPLAIN}</p>
<table border="0" cellpadding="3" cellspacing="1" width="100%" class="forumline">
	<tr>
		<th height="25" class="thCornerL" nowrap="nowrap" colspan="2">{CONFIG_HEADER}</th>		
	</tr>
	<tr>
		<td class="catLeft" align="left" colspan="2"><span class="cattitle">{GENERAL_HEAD}</span></td>
	</tr>
	<tr> 
	  	<td class="row1" width="60%" valign="top"><span class="gen"><b><u>{WELCOME}</u></b></span><p><span class="genmed">{WELCOME_EXP}</span></p></td>
	  	<td class="row2" width="40%" align="center" valign="middle">
		<textarea name="welcome_text" style="width: 250px"  rows="5" cols="10" class="post">{WELCOME_TEXT}</textarea></td>
	</tr>
	<tr> 
	  	<td class="row1" width="60%"><span class="gen"><b><u>{LEADERBOARD}</u></b></span><p><span class="genmed">{LEADERBOARD_EXP}</span></p></td>
	  	<td class="row2" width="40%" align="center" valign="middle">
		<select name="leader">{LEADER_BOX}</td>
	</tr>
	<tr>
		<td class="catLeft" align="left" colspan="2"><span class="cattitle">{BOOKIE_SETTINGS}</span></td>
	</tr>
	<tr> 
	  	<td class="row1" width="60%"><span class="gen"><b><u>{FRAC_DEC}</u></b></span><p><span class="genmed">{FRAC_DEC_EXP}</span></p></td>
	  	<td class="row2" width="40%" align="center" valign="middle"> 
		<input type="radio" name="fracdec" value="0" {ALLOW_FRACTIONAL_YES} />
		<span class="gen">{L_FRAC}</span>&nbsp;&nbsp; 
		<input type="radio" name="fracdec" value="1" {ALLOW_FRACTIONAL_NO} />
		<span class="gen">{L_DEC}</span></td>
	</tr>
	<tr> 
	 	<td class="row1" width="60%"><span class="gen"><b><u>{ALLOW_USER_BETS}</u></b></span><p><span class="genmed">{ALLOW_USER_BETS_EXP}</span></p></td>
	  	<td class="row2" width="40%" align="center" valign="middle"> 
		<input type="radio" name="allowuserbets" value="1" {ALLOW_USER_BETS_YES} />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
		<input type="radio" name="allowuserbets" value="0" {ALLOW_USER_BETS_NO} />
		<span class="gen">{L_NO}</span>&nbsp;&nbsp;
		<input type="radio" name="allowuserbets" value="2" {ALLOW_USER_BETS_COND} />
		<span class="gen">{L_CONDITION}</span></td>
	</tr>
	<tr> 
	  	<td class="row1" width="60%"><span class="gen"><b><u>{ALLOW_EACH_WAY}</u></b></span><p><span class="genmed">{ALLOW_EACH_WAY_EXP}</span></p></td>
	  	<td class="row2" width="40%" align="center" valign="middle"> 
		<input type="radio" name="alloweachway" value="1" {ALLOW_EACH_WAY_YES} />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
		<input type="radio" name="alloweachway" value="0" {ALLOW_EACH_WAY_NO} />
		<span class="gen">{L_NO}</span></td>
	</tr>
	<tr> 
	  	<td class="row1" width="60%"><span class="gen"><b><u>{ALLOW_EDIT_STAKE}</u></b></span><p><span class="genmed">{ALLOW_EDIT_STAKE_EXP}</span></p></td>
	  	<td class="row2" width="40%" align="center" valign="middle"> 
		<input type="radio" name="editstake" value="1" {ALLOW_EDIT_STAKE_YES} />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
		<input type="radio" name="editstake" value="0" {ALLOW_EDIT_STAKE_NO} />
		<span class="gen">{L_NO}</span></td>
	</tr>
	<tr> 
	 	 <td class="row1" width="60%"><span class="gen"><b><u>{ALLOW_SEND_PM}</u></b></span><p><span class="genmed">{ALLOW_SEND_PM_EXP}</span></p></td>
	  	<td class="row2" width="40%" align="center" valign="middle"> 
		<input type="radio" name="allowpm" value="1" {ALLOW_PM_YES} />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
		<input type="radio" name="allowpm" value="0" {ALLOW_PM_NO} />
		<span class="gen">{L_NO}</span></td>
	</tr>
	<tr> 
	 	<td class="row1" width="60%"><span class="gen"><b><u>{ALLOW_MIN_BET}</u></b></span><p><span class="genmed">{ALLOW_MIN_BET_EXP}</span></p></td>
	  	<td class="row2" width="40%" align="center" valign="middle"><input class="post" type="text" style="width: 50px" maxlength="6" size="11" name="min_stake" value="{MIN_STAKE}" /></td>
	</tr>
	<tr> 
	 	<td class="row1" width="60%"><span class="gen"><b><u>{ALLOW_MAX_BET}</u></b></span><p><span class="genmed">{ALLOW_MAX_BET_EXP}</span></p></td>
	  	<td class="row2" width="40%" align="center" valign="middle"><input class="post" type="text" style="width: 50px" maxlength="6" size="11" name="max_stake" value="{MAX_STAKE}" /></td>
	</tr>
	<tr>
		<td class="catLeft" align="left" colspan="2"><span class="cattitle">{MISC_SETTINGS}</span></td>
	</tr>
	<tr> 
	 	 <td class="row1" width="60%"><span class="gen"><b><u>{ALLOW_COMMISSION}</u></b></span><p><span class="genmed">{ALLOW_COMMISSION_EXP}</span></p></td>
	  	<td class="row2" width="40%" align="center" valign="middle"> 
		<input type="radio" name="allow_commission" value="1" {ALLOW_COM_YES} />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
		<input type="radio" name="allow_commission" value="0" {ALLOW_COM_NO} />
		<span class="gen">{L_NO}</span></td>
	</tr>
	<tr> 
	  	<td class="row1" width="60%"><span class="gen"><b><u>{COMMISSION}</u></b></span><p><span class="genmed">{COMMISSION_EXP}</span></p></td>
	  	<td class="row2" width="40%" align="center" valign="middle">
		<select name="commission">{COMMISSION_BOX}</td>
	</tr>
	<tr> 
	  	<td class="row1" width="60%"><span class="gen"><b><u>{BET_RESTRICT}</u></b></span><p><span class="genmed">{BET_RESTRICT_EXP}</span></p></td>
	  	<td class="row2" width="40%" align="center" valign="middle"> 
		<input type="radio" name="restrict" value="1" {ALLOW_RESTRICT_YES} />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp; 
		<input type="radio" name="restrict" value="0" {ALLOW_RESTRICT_NO} />
		<span class="gen">{L_NO}</span></td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center" height="28"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></td>
	</tr>
</table>
<br />
<div align="center"><span class="copyright">Forum Bookmakers Mod {BOOKIE_VERSION} by Majorflam &copy 2004-2005 <a href="http://www.majormod.com" class="copyright">Major Mod - Software Modifications For phpBB2</a></span></div>


