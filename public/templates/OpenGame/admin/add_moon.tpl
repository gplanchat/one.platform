<br /><br />

<h2>{AddMoon_Title}</h2>

<form action="add_moon.php" method="post">
<table width="320" border="0" cellspacing="2" cellpadding="0" style="color:#FFFFFF">
	<tr>
		<td class="c" colspan="6">{AddMoon_AddForm}</td>
	</tr><tr>
		<th width="150" colspan="2">{AddMoon_PlayerInfo}</th>
	</tr><tr>
		<th width="0%">
			{AddMoon_PlanetId} <input type="text" name="planetId" size="3">
		</th>
		<th width="0%"> 
			{AddMoon_Galaxy} <input type="text" name="galaxy" size="2"> 
			{AddMoon_System} <input type="text" name="system" size="4">
			{AddMoon_Planet} <input type="text" name="planet" size="3">
		</th>
	</tr><tr>
		<th colspan="2">{AddMoon_MoonName}</th>
	</tr><tr>
		<th colspan="2"><input type="text" name="moonName"></th>
	</tr><tr>
		<th colspan="2"><input type="submit" value="{AddMoon_Add}"></th>
	</tr>
</table>
</form>