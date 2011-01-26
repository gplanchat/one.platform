<script src="scripts/wz_tooltip.js" type="text/javascript"></script>
<br />
<table width="519">
	<tr>
		<td class="c" colspan="9">{Alliance_MembersList} ({Alliance_AllyPlayerNumber}: {memberzahl})</td>
	</tr><tr>
		<th>{Alliance_PlayerId}</th>
		<th><a href="?mode=admin&edit=members&sortby=username&sortorder={s}">{Alliance_Username}</a></th>
		<th> </th>
		<th>{Alliance_UserRank}</th>
		<th>{Alliance_UsersPoint}</th>
		<th>{Alliance_Position}</th>
		<th><a href="?mode=admin&edit=members&sortby=ally_register_time&sortorder={s}">{Alliance_RegisterTime}</a></th>
		<th><a href="?mode=admin&edit=members&sortby=onlinetime&sortorder={s}">{Alliance_Onlinetime}</a></th>
		<th>{Alliance_Function}</th>
	</tr>
		{memberslist}
	<tr>
		<td class="c" colspan="9"><a href="alliance.php?mode=admin&edit=ally">{Alliance_ReturnToOverview}</a></td>
	</tr>
</table>

