<table width="305" height="205" cellpadding="0" cellspacing="0" border="0" align="center" class="forumline">
	<tr>
	  <td class="row2" align="center" background="images/scratch/scratch_card.jpg" height="100%" width="100%">
		<br />
	  </td>
	</tr>
</table>

<table width="305" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<td width="100%" align="center" class="row3"><span class="gensmall">Scratch Card: Copyright © 2006, <a href="http://www.zarath.com" class="navsmall">Zarath Technologies</a>.</span></td>
	</tr>
</table>
<!-- Div Layers for Scratch Panels -->
<!-- BEGIN list_layers -->
<div id="list_layer" style="position:absolute; top:{list_layers.POS_Y}; left:{list_layers.POS_X}; width:40; height:40; z-index:1; padding:0px; border: 0px;">
<img src="images/scratch/scratched.gif" width="40" height="40" alt=" " border="0" />
</div>

<div id="list_layer" style="position:absolute; top:{list_layers.POS_Y}; left:{list_layers.POS_X}; width:40; height:40; z-index:2; padding:0px; border: 0px;">
<img src="images/scratch/icon_{list_layers.ICON_ID}.gif" width="40" height="40" alt=" " border="0" />
</div>
<!-- END list_layers -->

<!-- BEGIN list_empty -->
<div id="list_empty_layer" style="position:absolute; top:{list_empty.POS_Y}; left:{list_empty.POS_X}; width:40; height:40; z-index:1; padding:0px; border: 0px;">
<a href="{list_empty.U_ACTION}"><img src="images/scratch/blank.gif" width="40" height="40" alt=" " border="0" /></a>
</div>
<!-- END list_empty -->

<!-- Div Layer for win/finished scratching... This will overlay the regular message below the scratch panel -->
<!-- BEGIN switch_trade_in -->
<div id="trade_in_layer" style="position:absolute; top:{switch_trade_in.Y_POS}; left:{switch_trade_in.X_POS}; width:135; height:32; z-index:1; padding:0px; border: 0px;">
<a href="{switch_trade_in.U_ACTION}"><img src="images/scratch/finish_box.gif" width="130" height="32" alt=" " border="0" /></a>
</div>
<div id="trade_in_layer" style="position:absolute; top:{switch_trade_in.Y_POS}; left:{switch_trade_in.X_POS}; width:135; height:32; z-index:2; padding:2px; border: 0px;" align="center">
<a href="{switch_trade_in.U_ACTION}" style="text-decoration: none;"><span style="color: #487CAE; font-family: Arial, Times New Roman; font-size: 9px;">{switch_trade_in.MESSAGE}</span></a>
</div>
<!-- END switch_trade_in -->

<!-- Div Layers for icon listing and potential rewards... You can delete this switch if you don't want potential rewards visible -->
<!-- BEGIN list_icons -->
<div id="icon_list_layers" style="position:absolute; top:{list_icons.Y_POS}; left:43; width:90; height:15; z-index:2; padding:1px; border: 0px;" valign="middle">
	<span style="color: #1C5C9A; font-family: Arial, Times New Roman; font-size: 10px;">3x <img src="images/scratch/icon_{list_icons.ICON_ID}.gif" width="16" height="16" /> = {list_icons.PAYOUT} {L_POINTS_NAME}</span>
</div>
<!-- END list_icons -->

<br	clear="all" />
</body>
</html>