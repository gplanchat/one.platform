<br />

<table width=519>
	<tr>
		<td class="c" colspan="11">{Alliance_GiveRights}</td>
	</tr>
		{list}
</table>

<br />

<form action="alliance.php?mode=admin&edit=rights&add=name" method=POST>
<table width="519">
	<tr>
		<td class="c" colspan="2">{Alliance_MakeNewRank}</td>
	</tr>
	<tr>
		<th>{Alliance_RankName}</th>
		<th><input type="text" name="newrangname" size="20" maxlength="30"></th>
	</tr><tr>
		<th colspan="2"><input type="submit" value="{Alliance_Make}"></th>
	</tr>
</form>
</table>

<form action="alliance.php?mode=admin&edit=rights" method="post">
<table width=519>
	<tr>
		<td class=c colspan=2>{Alliance_RightsList}</td>
	</tr><tr>
		<th><img src=images/r2.png></th>
		<th>{Alliance_KickPlayer}</th>
	</tr><tr>
		<th><img src=images/r3.png></th>
		<th>{Alliance_SeeRequests}</th>
	</tr><tr>
		<th><img src=images/r4.png></th>
		<th>{Alliance_SeeMembersList}</th>
	</tr><tr>
		<th><img src=images/r5.png></th>
		<th>{Alliance_CheckRequests}</th>
	</tr><tr>
		<th><img src=images/r6.png></th>
		<th>{Alliance_AdminAlly}</th>
	</tr><tr>
		<th><img src=images/r7.png></th>
		<th>{Alliance_SeeOnlineList}</th>
	</tr><tr>
		<th><img src=images/r8.png></th>
		<th>{Alliance_WriteCircular}</th>
	</tr><tr>
		<th><img src=images/r9.png></th><th>{Alliance_RightHand}</th>
	</tr><tr>
		<td class="c" colspan="2"><a href="alliance.php?mode=admin&edit=ally">{Alliance_ReturnToOverview}</a></td>
	</tr>
</form>
</table>
	