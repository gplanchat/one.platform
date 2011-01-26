<br>
<table width=519>
	<tr>
		<td class=c colspan=8>{Alliance_MembersList} ({Alliance_AllyPlayerNumber}: {i})</td>
	</tr><tr>
		<th><a href="?mode=memberslist&sortby=0&sortorder={s}">{Alliance_PlayerId}</a></th>
		<th><a href="?mode=memberslist&sortby=username&sortorder={s}">{Alliance_Username}</a></th>
		<th></th>
		<th><a href="?mode=memberslist&sortby=username&sortorder={s}">{Alliance_UserRank}</a></th>
		<th>{Alliance_UsersPoint}</th>
		<th>{Alliance_Position}</th>
		<th><a href="?mode=memberslist&sortby=ally_register_time&sortorder={s}">{Alliance_RegisterTime}</a></th>
		<th><a href="?mode=memberslist&sortby=onlinetime&sortorder={s}">{Alliance_Onlinetime}</a></th>
	</tr>
		{list}
	<tr>
		<td class="c" colspan="9"><a href="alliance.php">{Alliance_ReturnToOverview}</a></td>
	</tr>
</table>
