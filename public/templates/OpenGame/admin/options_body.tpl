<br /> <br />

<h2>{GameSettings_Title}</h2>

<form action="settings.php" method="post">
<table width="519" style="color:#FFFFFF">

<tr>
	<td class="c" colspan="2">{GameSettings_GameParameter}</td>
</tr><tr>
	<th>{GameSettings_GameName}</th>
	<th><input name=game_name size=20 value="{game_name}" type=text></th>
</tr><tr>
	<th>{GameSettings_GameDisable}<br /></th>
	<th><input name="game_disable"{closed} type="checkbox" /></th>
</tr><tr>
	<th>{GameSettings_CloseReason}<br /></th>
	<th><textarea name="close_reason" cols="80" rows="5" size="80" >{close_reason}</textarea></th>
</tr><tr>
	<th>{GameSettings_EnableLink}</th>
	<th><input name="link_enable" size="20" value="{link_enable}" type="text"></th>
</tr><tr>
	<th>{GameSettings_LinkName}</th>
	<th><input name="link_name" size="20" value="{link_name}" type="text"></th>
</tr><tr>
	<th>{GameSettings_LinkUrl}</th>
	<th><input name="link_url" size="20" value="{link_url}" type="text"></th>
</tr><tr>
	<th>{GameSettings_GameSpeed}</th>
	<th><input name="game_speed" size="2" value="{game_speed}" type="text"></th>
</tr><tr>
	<th>{GameSettings_FleetSpeed}</th>
	<th><input name="fleet_speed" size="2" value="{fleet_speed}" type="text"></th>
</tr><tr>
	<th>{GameSettings_StatsSetting}</th>
	<th>{GameSettings_StatsDesc}<input name="stat_settings" size="2" value="{stat_settings}" type="text">{GameSettings_StatsResource}</th>
</tr><tr>
	<th>{GameSettings_ResourceCoeff}</th>
	<th><input name="resource_multiplier" maxlength="8" size="10" value="{resource_multiplier}" type="text"></th>
</tr><tr>
	<th>{GameSettings_BoardUrl}<br /></th>
	<th><input name="forum_url" size="40" maxlength="254" value="{forum_url}" type="text"></th>
</tr><tr>
	<td class="c" colspan="2">{GameSettings_MessageSettings}</td>
</tr><tr>
	<th>{GameSettings_EnableBbcode}<br />{GameSettings_1Enable0Disable}</th>
	<th><input name="enable_bbcode" size="1" maxlength="254" value="{enable_bbcode}" type="text"></th>
</tr><tr>
	<td class="c" colspan="2">{GameSettings_BotSettings}</td>
</tr><tr>
	<th>{GameSettings_BotEnable}<br />{GameSettings_1Enable0Disable}</th>
	<th><input name="enable_bot" size="1" value="{enable_bot}" type="text"></th>
</tr><tr>
	<th>{GameSettings_BotName}</th>
<th><textarea name="bot_name" cols="1" rows="1" size="80" >{bot_name}</textarea></th>
</tr><tr>
	<th>{GameSettings_EmailAdress}</th>
<th><textarea name="bot_adress" cols="80" rows="1" size="80" >{bot_adress}</textarea></th>
</tr><tr>
	<th>{GameSettings_BotBanDuration}</th>
	<th><input name="duration_ban" size="20" value="{duration_ban}" type="text"></th>
</tr><tr>
	<td class="c" colspan="2">{GameSettings_PlanetSettings}</td>
</tr><tr>
	<th>{GameSettings_PlanetInitialCase}</th>
	<th><input name="initial_fields" maxlength="80" size="10" value="{initial_fields}" type="text"> cases</th>
</tr><tr>
	<th>{GameSettings_InitialIncome}{Metal}</th>
	<th><input name="metal_basic_income" maxlength="2" size="10" value="{metal_basic_income}" type="text"> par heure</th>
</tr><tr>
	<th>{GameSettings_InitialIncome}{Crystal}</th>
	<th><input name="crystal_basic_income" maxlength="2" size="10" value="{crystal_basic_income}" type="text"> par heure   </th>
</tr><tr>
	<th>{GameSettings_InitialIncome}{Deuterium}</th>
	<th><input name="deuterium_basic_income" maxlength="2" size="10" value="{deuterium_basic_income}" type="text"> par heure   </th>
</tr><tr>
	<th>{GameSettings_InitialIncome}{Energy}</th>
	<th><input name="energy_basic_income" maxlength="2" size="10" value="{energy_basic_income}" type="text"> par heure</th>
</tr><tr>
	<td class="c" colspan="2">{GameSettings_MenuPages}</td>
</tr><tr>
	<th>{GameSettings_EnableAds}<br />{GameSettings_1Enable0Disable}</th>
	<th><input name="enable_announces" size=1" value="{enable_announces}" type="text"></th>
</tr>
<tr>
	<th>{GameSettings_EnableMarchand}<br />{GameSettings_1Enable0Disable}</th>
	<th><input name="enable_marchand" size="1" value="{enable_marchand}" type="text"></th>
</tr><tr>
	<th>{GameSettings_EnableNotes}<br />{GameSettings_1Enable0Disable}</th>
	<th><input name="enable_notes" size="1" value="{enable_notes}" type="text"></th>
</tr>
<tr>
	<td class="c" colspan="2">{GameSettings_OtherInformations}</td>
</tr><tr>
	<th>{GameSettings_EnableBanner}<br /></th>
	<th><input name="ForumBannerFrame"{bannerframe} type="checkbox" />({GameSettings_BannerWarning})</th>
</tr><tr>
	<th>{GameSettings_NewsFrame}<br /></th>
	<th><input name="OverviewNewsFrame"{newsframe} type="checkbox" /></th>
</tr><tr>
	<th colspan="2"><textarea name="OverviewNewsText" cols="80" rows="5" size="80" >{OverviewNewsText}</textarea></th>
</tr><tr>
	<th>{GameSettings_ExternChat}</th>
	<th><input name="OverviewExternChat"{chatframe} type="checkbox" /></th>
</tr><tr>
	<th colspan="2"><textarea name="OverviewExternChatCmd" cols="80" rows="5" size="80" >{OverviewExternChatCmd}</textarea></th>
</tr><tr>
	<th>{GameSettings_GoogleAds}</th>
	<th><input name="OverviewBanner"{googlead} type="checkbox" /></th>
</tr><tr>
	<th colspan="2"><textarea name="OverviewClickBanner" cols="80" rows="5" size="80" >{OverviewClickBanner}</textarea></th>
</tr><tr>
	<th>{GameSettings_DebugMode}</a></th>
	<th><input name="debug"{debug} type="checkbox" /></th>
</tr><tr>
	<th>{GameSettings_Banner}</th>
	<th><textarea name="banner_source_post" cols="80" rows="1" size="80" >{banner_source_post}</textarea></th>	
</tr><tr>
	<th colspan="2"><img src="{banner_source_post}" alt="{banner_source_post}" title="{banner_source_post}"></th>
</tr></tr>
	<th colspan="3"><input value="{GameSettings_Save}" type="submit"></th>
</tr>

</table>
</form>