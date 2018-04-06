  <table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr> 
	  <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a>{SHOPLOCATION}</span></td>
	</tr>
  </table>
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="5">{L_SHOP_TITLE}</th>
	</tr>
	<tr>
	  <td width="2%" class="row3"><span class="gensmall"><b>{L_ICON}</b></span></td>
	  <td class="row3"><span class="gensmall"><b>{L_NAME}</b></span></td>
	  <td class="row3"><span class="gensmall"><b>{L_DESCRIPTION}</b></span></td>
<!-- BEGIN switch_grouped -->
	  <td class="row3"><span class="gensmall"><b>{L_OWNED}</b></span></td>
<!-- END switch_grouped -->
<!-- BEGIN switch_move -->
	  <td class="row3" nowrap="nowrap"><span class="gensmall"><b>{L_MOVE_TO}</b></span></td>
<!-- END switch_move -->
	</tr>
<!-- BEGIN list_wrapped -->
	<tr>
	  <td class="{list_wrapped.ROW_NUM}"><span class="gensmall"><img src="shop/images/wrapped/{list_wrapped.WRAPPED}.jpg" title="{list_wrapped.WRAPPED}" alt="{list_wrapped.WRAPPED}"></span></td>
	  <td class="{list_wrapped.ROW_NUM}"><span class="gensmall">{list_wrapped.WRAPPED}</span></td>
	  <td class="{list_wrapped.ROW_NUM}"><span class="gensmall">{L_WRAPPED_DESC}{list_wrapped.WRAPPED_LINK}{list_wrapped.DECAY_TIME}</td>
	  {list_wrapped.AMOUNT}
	  {list_wrapped.MOVE}
	</tr>
<!-- END list_wrapped -->
<!-- BEGIN list_items -->
	<tr>
	  <td class="row2"><span class="gensmall"><img src="shop/images/{list_items.ITEM_NAME}.{list_items.IMG_EXT}" title="{list_items.ITEM_NAME}" alt="{list_items.ITEM_NAME}"></span></td>
	  <td class="row2"><span class="gensmall">{list_items.LINK}{list_items.ITEM_NAME}</a></span></td>
	  <td class="row2"><span class="gensmall">{list_items.ITEM_L_DESC} {list_items.ENCHANTED} {list_items.TIME_LEFT}</td>
	  {list_items.AMOUNT}
	  {list_items.MOVE}
	</tr>
<!-- END list_items -->
  </table>
<!-- BEGIN switch_is_action -->
<br />
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="3">{L_ACTIONS}</th>
	</tr>
	<tr>
<!-- END switch_is_action -->
<!-- BEGIN list_ability -->
	<td class="row2" align="center"><a href="{list_ability.U_ABILITY}" class="nav">{list_ability.L_ABILITY}</a></td>
<!-- END list_ability -->
<!-- BEGIN switch_is_action -->
	</tr>
  </table>
<!-- END switch_is_action -->
<!-- BEGIN switch_has_trade -->
<br />
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="3">{L_TRADE}</th>
	</tr>
	<tr>
	  <td class="row2" colspan="2" align="center"><span class="gensmall"><b>{switch_has_trade.USERNAME} {switch_has_trade.L_PROPOSED_TRADE}</b></span></td></tr>
	<tr>
	  <td class="row2" colspan="1"><span class="gensmall">{switch_has_trade.L_OFFERING}:</td>
	  <td class="row2" colspan="1"><span class="gensmall">{switch_has_trade.TRADE_ITEMS} {switch_has_trade.L_AND} {switch_has_trade.TRADE_GOLD} {L_POINTS_NAME}</span></td>
	</tr>
	<tr>
	  <td class="row2" colspan="1"><span class="gensmall">{switch_has_trade.L_WANTS}:</td>
	  <td class="row2" colspan="1"><span class="gensmall">{switch_has_trade.WANT_ITEMS} {switch_has_trade.L_AND} {switch_has_trade.WANT_GOLD} {L_POINTS_NAME}</span></td>
	</tr>
	<tr>
	  <td class="row2" align="center" width="50%"><a href="{switch_has_trade.U_ACCEPT_TRADE}" class="nav">{switch_has_trade.L_ACCEPT_TRADE}</a></td>
	  <td class="row2" align="center" width="50%"><a href="{switch_has_trade.U_REFUSE_TRADE}" class="nav">{switch_has_trade.L_REFUSE_TRADE}</a></td>
	</tr>
  </table>
<!-- END switch_has_trade -->
<br />
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr> 
	  <th class="thHead" colspan="2">{L_PERSONAL_INFO}</th>
	</tr>
	<tr>
	  <td class="row1" width="50%"><a href="{U_INVENTORY}" class="gensmall">{L_INVENTORY}</a></td>
	  <td class="row1" align="right" width="50%"><span class="gensmall">{USER_POINTS} {L_POINTS_NAME}</span></td>
	</tr>
  </table>
  <table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<td width="100%" align="center" class="row1"><br /><span class="gensmall">Shop Modification: Copyright © 2003, 2006 <a href="http://www.zarath.com" class="navsmall">Zarath Technologies</a>.</span><br /><br /></td>
	</tr>
  </table>
<br	clear="all" />
