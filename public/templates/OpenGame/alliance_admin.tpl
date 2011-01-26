<script src="scripts/cntchar.js" type="text/javascript"></script>
<br />
<table width=519>
	<tr>
		<td class=c colspan=2>{Alliance_AdministrationAlly}</td>
	</tr><tr>
		<th colspan=2><a href="?mode=admin&edit=rights">{Alliance_SettingRights}</a></th>
	</tr><tr>
		<th colspan=2><a href="?mode=admin&edit=members">{Alliance_MembersAdmin}</a></th>
	</tr><tr>
		<th colspan=2><a href="?mode=admin&edit=editName">{Alliance_RenameAlly}</a></th>
	</tr>
</table>

<br />

<form action="alliance.php?mode=admin&edit=ally&t={t}" method="POST">
<table width=519>
	<tr>
		<td class="c" colspan=3>{Alliance_AdminTexts}</td>
	</tr><tr>
		<th><a href="?mode=admin&edit=ally&t=1">{Alliance_ExternalText}</a></th>
		<th><a href="?mode=admin&edit=ally&t=2">{Alliance_InternalText}</a></th>
		<th><a href="?mode=admin&edit=ally&t=3">{Alliance_RequestText}</a></th>
	</tr><tr>
		<td class=c colspan=3>{typeText} (<span id="cntChars">0</span> / 5000 {Alliance_Characters})</td>
	</tr><tr>
		<th colspan=3><textarea name="text" cols=70 rows=15 onkeyup="javascript:cntchar(5000)">{text}</textarea></th>
	</tr><tr>
		<th colspan=3>
			<input type="hidden" name=t value={t}><input type="reset" value="{Alliance_Reset}"> 
			<input type="submit" value="{Alliance_Save}">
		</th>
	</tr>
</table>
</form>

<br />

<form action="alliance.php?mode=admin&edit=ally&t={t}" method="POST">
<input type="hidden" name="options" value="1" />
<table width=519>
	<tr>
		<td class=c colspan=2>{Alliance_Options}</td>
	</tr><tr>
		<th>{Alliance_MainPage}</th>
		<th><input type=text name="web" value="{ally_web}" size="70"></th>
	</tr><tr>
		<th>{Alliance_Logo}</th>
		<th><input type=text name="image" value="{ally_image}" size="70"></th>
	</tr><tr>
		<th>{Alliance_Requests}</th>
		<th>
			<select name="request_notallow">
				<option value=1{ally_request_notallow_0}>{Alliance_UnallowRequest}</option>
				<option value=0{ally_request_notallow_1}>{Alliance_AllowRequest}</option>
			</select>
		</th>
	</tr><tr>
		<th>{Alliance_OwnerRankName}</th>
		<th><input type="text" name="owner_range" value="{ally_owner_range}" size=30></th>
	</tr><tr>
		<th colspan=2><input type="submit" value="{Alliance_Save}"></th>
	</tr>
</table>
</form>

{Disolve_alliance}
<br />
{Transfer_alliance}

