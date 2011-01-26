<center>
<br /><br />

<h2>
	<font size="+3">{Reg_Registry}</font>
	<br><img src="images/xnova.png" align="top" border="0" >
</h2>

<form action="reg.php" method="post">

<table width="438">
<tr>
	<td colspan="2" class="c"><b>{Reg_Form}</b></td>
</tr><tr>
	<th width="293">{Reg_Username}</th>
    <th width="293"><input name="character" size="20" maxlength="20" type="text" onKeypress="
		if (event.keyCode==60 || event.keyCode==62) event.returnValue = false;
		if (event.which==60 || event.which==62) return false;">
	</th>
</tr><tr>
	<th>{Reg_Password}</th>
	<th><input name="passwrd" size="20" maxlength="20" type="password" onKeypress="
		if (event.keyCode==60 || event.keyCode==62) event.returnValue = false;
		if (event.which==60 || event.which==62) return false;">
	</th>
</tr><tr>
	<th>{Reg_Email}</th>
	<th><input name="email" size="20" maxlength="40" type="text" onKeypress="
		if (event.keyCode==60 || event.keyCode==62) event.returnValue = false;
		if (event.which==60 || event.which==62) return false;">
	</th>
</tr><tr>
	<th>{Reg_PlanetName}</th>
	<th><input name="planet" size="20" maxlength="20" type="text" onKeypress="
		if (event.keyCode==60 || event.keyCode==62) event.returnValue = false;
		if (event.which==60 || event.which==62) return false;">
	</th>
</tr><tr>
	<th>{Reg_Sexe}</th>
	<th>
		<select name="sex">
			<option value="">{Reg_Undefined}</option>
			<option value="M">{Reg_Men}</option>
			<option value="F">{Reg_Women}</option>
		</select>
	</th>
</tr><tr>
	<th colspan="2"><input id="regt" name="rgt" type="checkbox"><label for="regt">{Reg_AcceptGamesRules}</label></th>
</tr><tr>
	<th colspan="2"><input name="submit" type="submit" value="{Reg_SignUp}"></th>
</tr>
</table>

</form>
</center>