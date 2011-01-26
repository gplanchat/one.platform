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

/**
 * MissionCaseStayAlly @todo description
 *
 * @global array $lang @see common.php
 * @param array $fleetRow @see common.php
 * @return bool true.
 */
function missionCaseStayAlly ($fleetRow) {
    global $lang;

    $readConnection = Nova::getSingleton('core/database_connection_pool')
        ->getConnection('core_read');

    $startPlanet = $readConnection->select(array ('name', 'id_owner'))
        ->from($readConnection->getDeprecatedTable('planets'))
        ->where('galaxy =?', $fleetRow['fleet_start_galaxy'])
        ->where('system =?', $fleetRow['fleet_start_system'])
        ->where('planet =?', $fleetRow['fleet_start_planet'])
        ->query()->fetch()
     ;

    $endPlanet = $readConnection->select(array ('name', 'id_owner'))
        ->from($readConnection->getDeprecatedTable('planets'))
        ->where('galaxy =?', $fleetRow['fleet_end_galaxy'])
        ->where('system =?', $fleetRow['fleet_end_system'])
        ->where('planet =?', $fleetRow['fleet_end_planet'])
        ->query()->fetch()
     ;

    $fleetStartLink = GetStartAdressLink($fleetRow, '');
    $fleetEndLink = GetTargetAdressLink($fleetRow, '');

    if ($fleetRow['fleet_mess'] == 0) {

        if ($fleetRow['fleet_end_stay'] <= time()) {

            $readConnection->update(
                    $readConnection->getDeprecatedTable('fleets'),
                    array ('fleet_mess' => 1),
                    array ('fleet_id =?' => $fleetRow['fleet_id'])
                 );

        } else if ($fleetRow['fleet_start_time'] <= time()) {

            $message = sprintf($lang['sys_tran_mess_owner'],
                $endPlanet['name'], $fleetEndLink, $fleetRow['fleet_resource_metal'],
                $lang['Metal'], $fleetRow['fleet_resource_crystal'], $lang['Crystal'],
                $fleetRow['fleet_resource_deuterium'], $lang['Deuterium']
             );
            SendSimpleMessage ($startPlanet['id_owner'], '', $fleetRow['fleet_start_time'],
                5, $lang['sys_mess_tower'], $lang['sys_mess_transport'], $message);


            $message = sprintf($lang['sys_tran_mess_user'],
                $startPlanet['name'], $fleetStartLink, $endPlanet['name'], $fleetEndLink,
                $fleetRow['fleet_resource_metal'], $lang['Metal'],
                $fleetRow['fleet_resource_crystal'], $lang['Crystal'],
                $fleetRow['fleet_resource_deuterium'], $lang['Deuterium']
             );
            SendSimpleMessage ($endPlanet['id_owner'], '', $fleetRow['fleet_start_time'],
                    5, $lang['sys_mess_tower'], $lang['sys_mess_transport'], $message);


        }

    } else {
        if ($fleetRow['fleet_end_time'] <= time()) {

            $message = sprintf ($lang['sys_tran_mess_back'], $startPlanet['name'], $fleetStartLink);
            SendSimpleMessage ($startPlanet['id_owner'], '', $fleetRow['fleet_end_time'],
                    5, $lang['sys_mess_tower'], $lang['sys_mess_fleetback'], $message);

            RestoreFleetToPlanet ( $fleetRow, true );
            $readConnection->delete($readConnection->getDeprecatedTable('fleets'), array ('fleet_id =?' => $fleetRow['fleet_id']));
        }
    }

    return true;

}