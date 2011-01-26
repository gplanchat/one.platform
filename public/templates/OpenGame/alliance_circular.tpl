<br />
<form action="?mode=circular" method="post">
	
<table width=519>
	<tr>
		<td class="c" colspan=2>{Alliance_SendCircular}</td>
	</tr><tr>
		<th>{Alliance_Recipient}</th>
	 	<th>
			<select name="r">{r_list}</select>
		</th>
	</tr><tr>
		<th>{Text_mail} (<span id="cntChars">0</span> / 5000 {Alliance_Characters})</th>
		<th>
			<textarea name="text" cols="60" rows="10" onkeyup="javascript:cntchar(5000)"></textarea>
		</th>
	</tr><tr>
		<th class="c" colspan="3">
			<input type="reset" value="{Alliance_Clear}">
			<input type="submit" value="{Alliance_Send}">
		</th>
	</tr><tr>
		<td class="c" colspan="9"><a href="alliance.php">{Alliance_ReturnToOverview}</a></td>
	</tr>
</table>
</form>
