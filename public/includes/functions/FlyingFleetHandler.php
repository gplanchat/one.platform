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

function FlyingFleetHandler($planet) {
    $connection = One::app()
        ->getSingleton('core/database.connection.pool')
        ->getConnection('legacies_read')
    ;

    $connection->beginTransaction();

    $fleetCollection = One::app()
        ->getModel('legacies/fleet.collection')
        ->addFilters(array(
            One_Core_Bo_CollectionAbstract::FILTER_AND => array(
                array(
                    One_Core_Bo_CollectionAbstract::FILTER_OR => array(
                        array(
                            One_Core_Bo_CollectionAbstract::FILTER_AND => array(
                                array('fleet_start_galaxy' => $planet['galaxy']),
                                array('fleet_start_system' => $planet['system']),
                                array('fleet_start_planet' => $planet['planet']),
                                array('fleet_start_type'   => $planet['planet_type'])
                                ),
                            ),
                        array(
                            One_Core_Bo_CollectionAbstract::FILTER_AND => array(
                                array('fleet_end_galaxy' => $planet['galaxy']),
                                array('fleet_end_system' => $planet['system']),
                                array('fleet_end_planet' => $planet['planet']),
                                array('fleet_end_type'   => $planet['planet_type'])
                                ),
                            )
                        )
                    ),
                array(
                    One_Core_Bo_CollectionAbstract::FILTER_OR => array(
                        array(
                            One_Core_Bo_CollectionAbstract::FILTER_LOWER_THAN => array(
                                'fleet_start_time' => time()
                                )
                            ),
                        array(
                            One_Core_Bo_CollectionAbstract::FILTER_LOWER_THAN => array(
                                'fleet_end_time' => time()
                                )
                            )
                        )
                    )
                )
            ))
        ->load()
    ;

    foreach ($fleetCollection as $fleet) {
        switch ($fleet["fleet_mission"]) {
            case 1:
                // Attaquer
                MissionCaseAttack($fleet);
                break;

            case 2:
                // Attaque groupée
                $fleet->delete();
                break;

            case 3:
                // Transporter
                MissionCaseTransport($fleet);
                break;

            case 4:
                // Stationner
                MissionCaseStay($fleet);
                break;

            case 5:
                // Stationner chez un Allié
                MissionCaseStayAlly($fleet);
                break;

            case 6:
                // Flotte d'espionnage
                MissionCaseSpy($fleet);
                break;

            case 7:
                // Coloniser
                MissionCaseColonisation($fleet);
                break;

            case 8:
                // Recyclage
                MissionCaseRecycling($fleet);
                break;

            case 9:
                // Detruire
                MissionCaseDestruction($fleet);
                break;

            case 10:
                // Missiles
                $fleet->delete();
                break;

            case 15:
                // Expeditions
                MissionCaseExpedition($fleet);
                break;

            default:
                $fleet->delete();
        }
    }

    $connection->commit();
}
