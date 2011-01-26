<br>
<form action="alliance.php?mode=admin&edit=requests&show={id}" method="POST">
	<tr>
		<th colspan=2>{requestFrom}</th>
	</tr><tr>
		<th colspan=2>{allyRequestText}</th>
	</tr><tr>
		<td class="c" colspan=2>{Alliance_RequestAnswer}</td>
	</tr><tr>
		<th>&#160;</th>
		<th><input type="submit" name="action" value="{Alliance_Accept}"></th>
	</tr><tr>
		<th>{Alliance_Reason} <span id="cntChars">0</span> / 500 {characters}</th>
		<th><textarea name="text" cols=40 rows=10 onkeyup="javascript:cntchar(500)"></textarea></th>
	</tr><tr>
		<th>&#160;</th>
		<th><input type="submit" name="action" value="{Alliance_Refuse}"></th>
	</tr><tr>
		<td colspan=2>&#160;</td>
	</tr>
</form>