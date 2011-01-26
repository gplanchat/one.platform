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
 * Cancel a building from the queue and give the resource to the player
 *
 * @param array $currentPlanet @see $planetrow
 * @param array $currentUser @see $user
 * @return bool True if the cancel the cancel is correct
 */
function CancelBuildingFromQueue(&$currentPlanet, &$currentUser)
{
    if ($currentPlanet['b_building_id'] == 0) {
        return false;
    }

    $currentQueue = explode(';', $currentPlanet['b_building_id']);
    $firstElement = explode(',', $currentQueue[0]);

    array_shift($currentQueue);
    $queueSize = count($currentQueue);

    $forDestroy = ($firstElement[4] == 'destroy') ? true : false;
    $elementPrice = GetBuildingPrice($currentUser, $currentPlanet, $firstElement[0], true, $forDestroy);

    $currentPlanet['metal']     += $elementPrice['metal'];
    $currentPlanet['crystal']   += $elementPrice['crystal'];
    $currentPlanet['deuterium'] += $elementPrice['deuterium'];

    if ($queueSize > 0) {

        $buildEndTime = time();
        $newQueue = array();
        for ($i = 0; $i < $queueSize; $i++) {
            $elementArray = explode(',', $currentQueue[$i]);

            if ($firstElement[0] == $elementArray[0]) {
                $elementArray[1]--;
                $elementArray[2] = GetBuildingTimeLevel($currentUser, $currentPlanet, $elementArray[0], $elementArray[1]);
            }
            $buildEndTime += $elementArray[2];
            $elementArray[3] = $buildEndTime;

            $newQueue[$i] = implode(',', $elementArray);
        }
    }
    $currentPlanet['b_building_id'] = ($queueSize > 0) ? implode(';', $newQueue) : 0;
    $currentPlanet['b_building'] = ($queueSize > 0) ? $buildEndTime : 0;

    return true;
}
