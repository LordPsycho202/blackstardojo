
<h1>{L_PAGE_TITLE}</h1>

<p>{L_PAGE_EXPLAIN}</p>

<script type="text/javascript">
<!--
function selectValueFromSelect(/*HTMLSelectElement*/ sel, /*String*/ val) {
  for(var i=0;sel.options[i].value != val;i++) void(0);
  sel.selectedIndex = i;
}


function selectValueFromCheckBox(/*HTMLButtonElement*/ checkbox, /*String*/ val) {

  for(var i=0;checkbox[i].value.match(val) != val;i++) void(0);
  checkbox[i].checked = true;
}

function selectValueFromButton(/*HTMLButtonElement*/ button, /*String*/ val) {

  for(var i=0;button[i].value != val;i++) void(0);
  button[i].checked = true;
}

function selectValueFromText(/*HTMLTextElement*/ box, /*String*/ val) {
   box.value = val;
}

// -->
</script>

<body onload='{ONLOAD_JAVASCRIPT}' >
<form id="prune_users" method="post" action="{S_FORM_ACTION}"><table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline" width="85%">
	<tr>
		<th class="thTop" align="center" colspan="6">{L_BUILD_YOUR_QUERY}</td>
  	</tr>
	<tr>
		<td class="row1" align="left">{L_USER_REGDATE}</td>
        <td class="row1" align="center"><input type="checkbox" name="registered_check" value="1" ></td>
        <td class="row2" align="center"><select name="user_registered_condition"><option value="AND">AND</option><option value="OR">OR</option></select></td>      
        <td class="row2" align="center"><select name="user_regdate_sign"><option value="&gt;=">{L_WITHIN}</option><option value="&lt;=">{L_PRIOR}</option></select></td>      
		<td class="row2" align="left">
			<select name="user_registered">
				<option value="{SEVEN_DAYS}">{L_SEVEN_DAYS}</option>
				<option value="{TEN_DAYS}">{L_TEN_DAYS}</option>
				<option value="{TWO_WEEKS}">{L_TWO_WEEKS}</option>
				<option value="{ONE_MONTH}">{L_ONE_MONTH}</option>
				<option value="{TWO_MONTHS}">{L_TWO_MONTHS}</option>
				<option value="{THREE_MONTHS}">{L_THREE_MONTHS}</option>
				<option value="{SIX_MONTHS}">{L_SIX_MONTHS}</option>
				<option value="{ONE_YEAR}">{L_ONE_YEAR}</option>
			</select> 		
		</td>
        <td class="row2" align="left" >{L_USER_REGDATE_EXPLAIN}</td>	
	</tr>
	<tr>
		<td class="row1" align="left">{L_USER_LAST_VISIT}</td>
        <td class="row1" align="center"><input type="checkbox" name="login_check" value="1" ></td>
        <td class="row2" align="center"><select name="user_lastvisit_condition"><option value="AND">AND</option><option value="OR">OR</option></select></td>
        <td class="row2" align="center"><select name="user_lastvisit_sign"><option value="&gt;=">{L_WITHIN}</option><option value="&lt;=">{L_PRIOR}</option><option value="=">Never</option></select></td>      
        <td class="row2" align="left">
			<select name="user_lastvisit">
				<option value="0">{L_NEVER}</option>
				<option value="{SEVEN_DAYS}">{L_SEVEN_DAYS}</option>
				<option value="{TEN_DAYS}">{L_TEN_DAYS}</option>
				<option value="{TWO_WEEKS}">{L_TWO_WEEKS}</option>
				<option value="{ONE_MONTH}">{L_ONE_MONTH}</option>
				<option value="{TWO_MONTHS}">{L_TWO_MONTHS}</option>
				<option value="{THREE_MONTHS}">{L_THREE_MONTHS}</option>
				<option value="{SIX_MONTHS}">{L_SIX_MONTHS}</option>
				<option value="{ONE_YEAR}">{L_ONE_YEAR}</option>				
			</select>
		</td>
        <td class="row2" align="left">{L_USER_LAST_VISIT_EXPLAIN}</td>
	</tr>
	<tr>
		<td class="row1" align="left">{L_USER_ACTIVE}</td>
        <td class="row1" align="center"><input type="checkbox" name="active_check" value="1" ></td>
        <td class="row2" align="center"><select name="user_active_condition"><option value="AND">AND</option><option value="OR">OR</option></select></td>
        <td class="row2" align="center"></td>            
		<td class="row2" align="left"><input name="user_active" value="1" type="radio"> {L_YES}&nbsp;&nbsp;<input name="user_active" value="0" type="radio"> {L_NO}</td>
        <td class="row2" align="left">{L_USER_ACTIVE_EXPLAIN}</td>
	</tr>
	<tr>
		<td class="row1" align="left">{L_USER_POSTS}</td>
        <td class="row1" align="center"><input type="checkbox" name="posts_check" value="1" ></td>
        <td class="row2" align="center"><select name="user_posts_condition"><option value="AND">AND</option><option value="OR">OR</option></select></td>
        <td class="row2" align="center"><select name="user_posts_sign"><option value="=">=</option><option value=">">&gt;</option><option value="<">&lt;</option><option value="&gt;=">&gt;=</option><option value="&lt;=">&lt;=</option><option value="&lt;&gt;">&lt;&gt;</option></select></td>      
        <td class="row2" align="left">
		<input class="post" name="user_posts" size="5" maxlength="255" type="text" value="0" >
		</td>
        <td class="row2" align="left">{L_USER_POSTS_EXPLAIN}</td>
	</tr>
	<tr>
		<td class="row1" align="left">{L_USER_FLAGGED}</td>
        <td class="row1" align="center"><input type="checkbox" name="flagged_check" value="1" ></td>
        <td class="row2" align="center"><select name="user_flagged_condition"><option value="AND">AND</option><option value="OR">OR</option></select></td>
        <td class="row2" align="center"></td>            
		<td class="row2" align="left"><input name="user_flagged" value="1" type="radio"> {L_YES}&nbsp;&nbsp;<input name="user_flagged" value="0" type="radio"> {L_NO}</td>
        <td class="row2" align="left">{L_USER_FLAGGED_EXPLAIN}</td>
	</tr>	
	<tr>
		<td class="row1" align="left">{L_REMEMBER_SETTINGS}</td>
        <td class="row1" align="center"><input type="checkbox" name="save_query" value="1" ></td>
        <td class="row2" align="center"></td>
        <td class="row2" align="center"></td>      
        <td class="row2" align="left"></td>
        <td class="row2" align="left">{L_SAVE_SETTINGS_EXPLAIN}</td>
	</tr>	
	<tr>
		<td class="catBottom" align="center" colspan="6"> <input type="submit" class="mainoption" name="fetch" value="{L_BUILD_QUERY}" /> </td>
	</tr>
</table>
</form>
