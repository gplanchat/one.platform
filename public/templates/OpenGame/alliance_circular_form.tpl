<br />
<form action="?a=17&sendmail=1" method=post>
<table width=519>
	<tr>
		<td class=c colspan=2>{Alliance_SendCircular}</td>
	</tr><tr>
		<th>{Alliance_Recipient}</th>
		<th>
			<select name=r>{r_list}</select>
		</th>
	</tr><tr>
		<th>{Alliance_CircularText} (<span id="cntChars">0</span> / 5000 {Alliance_Characters})</th>
		<th>
			<textarea name="text" cols="60" rows="10" onkeyup="javascript:cntchar(5000)"></textarea>
		</th>
	</tr><tr>
		<td class="c"><a href="alliance.php">{Alliance_ReturnToOverview}</a></td>
		<td class="c">
			<input type="reset" value="{Alliance_Clear}">
			<input type="submit" value="{Alliance_Send}">
		</td>
	</tr>
</table>
</form>
