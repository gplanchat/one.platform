<br />

<form action="alliance.php?mode=admin&edit=editName" method="post">
<table width=519>
	<tr>
		<td class=c colspan=2>{Alliance_RenameAlly}</td>
	</tr><tr>
		<th>{Alliance_NewName}</th><th><input type="text" name="newname" value="{allyName}"> </th>
	</tr><tr>
		<th>{Alliance_NewTag}</th><th><input type="text" name="newtag" value="{allyTag}"> </th>
	</tr><tr>
		<th colspan="2"><input type=submit value="{Alliance_Change}"></th>
	</tr><tr>
		<td class="c" colspan="9"><a href="alliance.php?mode=admin&edit=ally">{Alliance_ReturnToOverview}</a></td>
	</tr>
</table>
</form>