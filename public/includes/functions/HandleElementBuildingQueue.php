<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-Present, XNova Support Team <http://www.xnova-ng.org>
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

function HandleElementBuildingQueue($currentUser, &$currentPlanet, $productionTime) {
    global $resource;
    // Pendant qu'on y est, si on verifiait ce qui se passe dans la queue de construction du chantier ?
    if ($currentPlanet['b_hangar_id']) {
        $buildArray = array();
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
                $currentPlanet['b_hangar'] -= $buildTime * $count;
                $buildArray[$element] += $count;
                $currentPlanet[$resource[$element]] += $count;

                $currentPlanet['b_hangar_id'] .= "$element,$Count;";
            }
        }
    } else {
        $buildArray = array();
        $currentPlanet['b_hangar'] = 0;
    }

    return $buildArray;
}