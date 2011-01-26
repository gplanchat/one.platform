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

function check_urlaubmodus ($user) {
	if ($user['urlaubs_modus'] == 1) {
		message("Vous êtes en mode vacances!", $title = $user['username'], $dest = "", $time = "3");
	}
}

function check_urlaubmodus_time () {
	global $user, $game_config;
	if ($game_config['urlaubs_modus_erz'] == 1) {
		$begrenzung             = 86400; //24x60x60= 24h
		$urlaub_modus_time      = $user['urlaubs_modus_time'];
		$urlaub_modus_time_soll = $urlaub_modus_time + $begrenzung;
		$time_jetzt             = time();
		if ($user['urlaubs_modus'] == 1 && $urlaub_modus_time_soll > $time_jetzt) {
			$soll_datum = date("d.m.Y", $urlaub_modus_time_soll);
			$soll_uhrzeit = date("H:i:s", $urlaub_modus_time_soll);
			message("Vous �tes en mode vacances!<br>Le mode vacance dure jusque $soll_datum $soll_uhrzeit<br>	Ce n'est qu'apr�s cette p�riode que vous pouvez changer vos options.", "Mode vacance");
		}
	}
}

// ----------------------------------------------------------------------------------------------------------------
//
// Routine Test de validit� d'une adresse email
//
function is_email($email) {
	return preg_match("#^([a-z0-9\-_\.]+)@(?:[a-z0-9](?:[a-z0-9\-]*[a-z0-9])?\.)+(?:[a-a]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)$#i", $email);
}

// ----------------------------------------------------------------------------------------------------------------
//
// Routine Affichage d'un message administrateur avec saut vers une autre page si souhait�
//
function AdminMessage ($mes, $title = 'Error', $dest = '', $time = '3', $color= 'red') {
	$parse['color'] = $color;
	$parse['title'] = $title;
	$parse['mes']   = $mes;

	$page = parsetemplate(gettemplate('admin/message_body'), $parse);

	display ($page, $title, false, (($dest != "") ? "<meta http-equiv=\"refresh\" content=\"$time;URL=javascript:self.location='$dest';\">" : ""), true);
}

// ----------------------------------------------------------------------------------------------------------------
//
// Routine Affichage d'un message avec saut vers une autre page si souhait�
//
function message($mes, $title = 'Error', $dest = "", $time = "3", $color = 'orange') {
    $parse['color'] = $color;
    $parse['title'] = $title;
    $parse['mes']   = $mes;

    $page = parsetemplate(gettemplate('admin/message_body'), $parse);

    display ($page, $title, false, (($dest != "") ? "<meta http-equiv=\"refresh\" content=\"$time;URL=javascript:self.location='$dest';\">" : ""), false);
}

// ----------------------------------------------------------------------------------------------------------------
//
// Routine d'affichage d'une page dans un cadre donn�
//
// $page      -> la page
// $title     -> le titre de la page
// $topnav    -> Affichage des ressources ? oui ou non ??
// $metatags  -> S'il y a quelques actions particulieres a faire ...
// $AdminPage -> Si on est dans la section admin ... faut le dire ...
function display ($page, $title = '', $topnav = true, $metatags = '', $AdminPage = false) {
	global $link, $game_config, $debug, $user, $planetrow;

	if (!$AdminPage) {
		$DisplayPage  = StdUserHeader ($title, $metatags);
	} else {
		$DisplayPage  = AdminUserHeader ($title, $metatags);
	}

	if ($topnav) {
		$DisplayPage .= ShowTopNavigationBar( $user, $planetrow );
	}
	$DisplayPage .= "<center>\n". $page ."\n</center>\n";

	// Affichage du Debug si necessaire
	if (isset($user['authlevel']) && ($user['authlevel'] == 1 || $user['authlevel'] == 3)) {
		if ($game_config['debug'] == 1) {
		    $oldDebug = new Debug(); // @deprecated
		    $oldDebug->echo_log();
		}
	}

	$DisplayPage .= StdFooter();
	if (isset($link)) {
		mysql_close($link);
	}

	echo $DisplayPage;

	die();
}

// ----------------------------------------------------------------------------------------------------------------
//
// Entete de page
//
function StdUserHeader ($title = '', $metatags = '') {
	global $user, $langInfos;

	$parse             = $langInfos;
	$parse['title']    = $title;

	if ( defined('LOGIN') ) {
		$parse['dpath']    = 'skins/xnova/';
		$parse['-style-']  = '<link rel="stylesheet" type="text/css" href="css/styles.css">' . PHP_EOL;
		$parse['-style-'] .= '<link rel="stylesheet" type="text/css" href="css/about.css">' . PHP_EOL;
	} else if ( defined('DISABLE_IDENTITY_CHECK') ) {
		$parse['dpath']    = DEFAULT_SKINPATH;
		$parse['-style-']  = "<link rel=\"stylesheet\" type=\"text/css\" href=\"". DEFAULT_SKINPATH ."default.css\" />";
		$parse['-style-'] .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"". DEFAULT_SKINPATH ."formate.css\" />";
	} else {
	    $user['dpath'] = ($user['dpath'] != '') ? $user['dpath'] : DEFAULT_SKINPATH;
		$parse['dpath']    = $user['dpath'];
		$parse['-style-']  = "<link rel=\"stylesheet\" type=\"text/css\" href=\"". $user['dpath']."default.css\" />";
		$parse['-style-'] .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"". $user['dpath'] ."formate.css\" />";
	}

	$parse['-meta-']  = ($metatags) ? $metatags : "";
	$parse['-body-']  = "<body>"; //  class=\"style\" topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">";
	return parsetemplate(gettemplate('simple_header'), $parse);
}

// ----------------------------------------------------------------------------------------------------------------
//
// Entete de page administration
//
function AdminUserHeader ($title = '', $metatags = '') {
	global $user, $dpath, $langInfos;

	$parse           = $langInfos;
	$parse['dpath']  = $dpath;
	$parse['title']  = $title;
	$parse['-meta-'] = ($metatags) ? $metatags : "";
	$parse['-body-'] = "<body>"; //  class=\"style\" topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">";
	return parsetemplate(gettemplate('admin/simple_header'), $parse);
}

// ----------------------------------------------------------------------------------------------------------------
//
// Pied de page
//
function StdFooter() {
	global $game_config, $lang;
	$parse['copyright']     = isset($game_config['copyright']) ? $game_config['copyright'] : 'XNova Support Team';
	$parse['TranslationBy'] = isset($lang['TranslationBy']) ? $lang['TranslationBy'] : '';
	return parsetemplate(gettemplate('overall_footer'), $parse);
}

// ----------------------------------------------------------------------------------------------------------------
//
// Calcul de la place disponible sur une planete
//
function CalculateMaxPlanetFields (&$planet) {
	global $resource;

	return $planet["field_max"] + ($planet[ $resource[33] ] * 5);
}

?>
