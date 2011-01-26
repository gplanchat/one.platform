<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-2010, XNova Support Team <http://www.xnova-ng.org>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */

define('INSIDE' , true);
define('INSTALL' , false);
require_once dirname(__FILE__) .'/common.php';

includeLang('alliance');
			
$tag = (isset($_GET['tag'])) ? mysql_real_escape_string($_GET['tag']) : false;

	if ($tag) {

		$row = $lang;
		$allyRow = doquery("SELECT * FROM {{table}} WHERE ally_tag = '{$tag}' LIMIT 1", 'alliance', true);
				
		if ($allyRow) {
			
			if($allyRow['ally_description'] !== '') {
			    $allyDescription = bbcode($allyRow['ally_description']);
				$row['ally_description'] = "<tr><th colspan=\"2\" height=\"100\">{$allyDescription}</th></tr>";
			} else {
				$row['ally_description'] = "<tr><th colspan=2 height=100>{$lang['Alliance_NoDescription']}</th></tr>";
			}
				
			$row['ally_image'] = ($allyRow['ally_image'] !== '') ? 
				"<tr><th colspan=2><img src=\"{$allyRow['ally_image']}\"></td></tr>" : '';
			$row['ally_web'] = ($allyRow['ally_web'] !== '') ?
				"<a href=\"{$allyRow['ally_web']}\">{$allyRow['ally_web']}</a>" : "{$lang['Alliance_NoHomePage']}";
			
			$row['ally_member_scount'] = $allyRow['ally_members'];
			$row['ally_name'] = $allyRow['ally_name'];
			$row['ally_tag'] = $allyRow['ally_tag'];
					
			$row['request'] = ($user['ally_id'] == 0) ?
				"<tr><th>{$lang['Alliance_Requests']}</th><th><a href=\"alliance.php?mode=apply&amp;allyid={$id}\">{$lang['Alliance_WriteRequest']}</a></th></tr>" : '';
				
			$page = parsetemplate(gettemplate('alliance_ainfo'), $row);		
				display($page, $lang['Alliance_AllianceInformation']);
				
			} 
		}
		
		message($lang['Alliance_AllyDoesentExist'], $lang['Alliance_AllianceInformation']);
			