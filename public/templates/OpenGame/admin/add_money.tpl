<br /><br />

<h2>{AddMoney_Title}</h2>

<form action="add_money.php" method="post">
<table width="305">
	<tr>
		<td class="c" colspan="6">{AddMoney_Form}</td>
	</tr><tr>
		<th width="130" colspan="2">{AddMoney_PlayerInfo}</th>
	</tr><tr>
		<th>
			{AddMoney_PlanetId} <input type="text" name="planetId" size="3">
		</th>
		<th width="0%"> 
			{AddMoney_Galaxy} <input type="text" name="galaxy" size="2"> 
			{AddMoney_System} <input type="text" name="system" size="4">
			{AddMoney_Planet} <input type="text" name="planet" size="3">
		</th>
	</tr><tr>
		<th width="130" colspan="2">{AddMoney_Resource}</th>
	</tr><tr>
		<th>{AddMoney_Metal}</th>
		<th><input name="metal" type="text" value="0" /></th>
	</tr><tr>
		<th>{AddMoney_Crystal}</td>
		<th><input name="cristal" type="text" value="0" /></th>
	</tr><tr>
		<th>{AddMoney_Deuterium}</td>
		<th><input name="deut" type="text" value="0" /></th>
	</tr><tr>
		<th colspan="2"><input type="Submit" value="{AddMoney_Add}" /></th>
	</tr>
</table>
</form>