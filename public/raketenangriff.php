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

/*
 * This file is called when a user has already selected the destination and the number of missiles.
 * and check if the user can send the missile.
 */

define('INSIDE' , true);
define('INSTALL' , false);
require_once dirname(__FILE__) .'/common.php';

includeLang('missile');

if ($user['urlaubs_modus']) //@todo Implementation de la constante USER_IN_VACATION
    message($lang['Missile_VacationMode'], $lang['Missile_Error']);

if (IsTechnologieAccessible($user, $planetrow, Legacies_Empire::ID_SPECIAL_INTERPLANETARY_MISSILE)) {
	
    $galaxy = (isset($_GET['galaxy'])) ? (int) $_GET['galaxy'] : 0;
    $system = (isset($_GET['system'])) ? (int) $_GET['system'] : 0;
    $planet = (isset($_GET['planet'])) ? (int) $_GET['planet'] : 0;
	
    $count = (isset($_POST['SendMI'])) ? (int) $_POST['SendMI'] : 0;
    $target = (isset($_POST['Target']) && in_array($_POST['Target'], $reslist[Legacies_Empire::TYPE_DEFENSE])) ? 
        (int) $_POST['Target'] : Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER;

	if ($count > $planetrow['interplanetary_misil'])
		$count = $planetrow['interplanetary_misil'];

	$missileOutreach = 5 * $user[$resource[Legacies_Empire::ID_RESEARCH_IMPULSE_DRIVE]] + 1;
	$systemToTarget = abs($system - $planetrow['system']);
	$galaxyToTarget = abs($galaxy - $planetrow['galaxy']); // Should be 0.
	
	if ($systemToTarget <= $missileOutreach && $galaxyToTarget == 0) {
		
        $sql = Legacies_Database::getInstance()
            ->select()
            ->from(Legacies_Database::getTable('deprecated/planets'))
            ->where('galaxy =?', $galaxy)
            ->where('system =?', $system)
            ->where('planet =?', $planet)
            ->where('planet_type =?', '1')
         ;
	        $targetPlanet = Legacies_Database::getInstance()->fetchRow($sql);
        
        $sql = Legacies_Database::getInstance()
            ->select()
            ->from(Legacies_Database::getTable('deprecated/users'))
            ->where('id =?', $targetPlanet['id_owner'])
          ;
		    $targetUser = Legacies_Database::getInstance()->fetchRow($sql);
		    
		if ($targetPlanet && $targetUser && $targetUser['urlaubs_modus'] != 1) {
			
			$fleetTime = round((30 + $systemToTarget * 60) / ($game_config['fleet_speed'] / 2500));

			$data = array(
				'zeit' => new Zend_Db_Expr("UNIX_TIMESTAMP() + $fleetTime"),
				'galaxy' => $galaxy,
			    'system' => $system,
			    'planet' => $planet,
			    'galaxy_angreifer' => $planetrow['galaxy'],
			    'system_angreifer' => $planetrow['system'],
			    'planet_angreifer' => $planetrow['planet'],
			    'owner' => $user['id'],
			    'zielid' => $targetUser['id'],
			    'anzahl' => $count,
			    'primaer' => $target
			);
            
			Legacies_Database::getInstance()->insert(Legacies_Database::getTable('deprecated/iraks'), $data);
			
            $remainingMissile = $planetrow['interplanetary_misil'] - $count;
	        Legacies_Database::getInstance()->update(Legacies_Database::getTable('deprecated/planets'), array('interplanetary_misil' => $remainingMissile), array ('id=?' => $user['current_planet']));
				
			    message($lang['Missile_MissileSended'], $lang['Missile_Title']);
			
		} else 
			message($lang['Missile_NotFound'], $lang['Missile_Error']);
		
	} else
		message($lang['Missile_ToFar'], $lang['Missile_Error']);
	
} else
	message($lang['Missile_NoTechnology'], $lang['Missile_Error']);

