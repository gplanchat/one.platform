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
 * Check the hangar queue.
 *
 * @global array $resource @see vars.php
 * @param array $currentUser Similar as $user
 * @param array $currentPlanet Similar as $planetrow
 * @param int $productionTime Pass time beetween now and the last update
 * @return array Contain the builded element.
 */
function HandleElementBuildingQueue($currentUser, &$currentPlanet, $productionTime) {
    global $resource;


    $buildArray = array();
    if ($currentPlanet['b_hangar_id'] !== 0) {

        $currentPlanet['b_hangar'] += $productionTime;
        $buildQueue = explode(';', $currentPlanet['b_hangar_id']);

        $currentPlanet['b_hangar_id'] = '';

        foreach ($buildQueue as $element) {

            if (empty($element) || !($element = explode(',', $element)) || count($element) != 2) {
                continue;
            }

            list($item, $count) = $element;
            $buildTime = GetBuildingTime($currentUser, $currentPlanet, $item);

            if($currentPlanet['b_hangar'] >= $buildTime && $count > 0) {
                $buildedElements = floor($currentPlanet['b_hangar'] / $buildTime);
                $buildedElements = ($buildedElements > $count) ? $count : $buildedElements;
                if ($buildedElements < $count) {
                    $currentPlanet['b_hangar_id'] .= "$item,".($count - $buildedElements).";";
                }
                $currentPlanet['b_hangar'] -= $buildTime * $buildedElements;
                $buildArray[$item] += $buildedElements;
                $currentPlanet[$resource[$item]] += $buildedElements;
            } else {
                $currentPlanet['b_hangar_id'] .= "$item,$count;";
            }
        }
    } else {
        $currentPlanet['b_hangar'] = 0;
    }

    return $buildArray;
}