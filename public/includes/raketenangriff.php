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
 * but WITHOUT ANY WARRANTYqsdqd; without even the implied warranty of
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
 * Missile combat engine
 *
 * @param int $defenderTech Level of the defence tech for the defender.
 * @param int $attackerTech Level of the weapeon tech for attacker.
 * @param int $nbMissile Number of missile send.
 * @param array $targetDef Defense on the target planet.
 * @param int $firstTarget First choice on attack.
 *
 * @return array $return
 */

function raketenangriff($defenderTech, $attackerTech, $nbMissile, $targetDef, $firstTarget) {
	global $reslist, $pricelist;

	/**
	 * @var array Defense on the planet after the attack.
	 */
	$stayingDefense	= array();

	/**
	 * @var array Defense destroyed durring the attack
	 */
	$destroyDefense = array();

	/**
	 * Check if there are Antiballistic Missile on the planet
	 */
	if ($targetDef[Legacies_Empire::ID_SPECIAL_ANTIBALLISTIC_MISSILE] > 0) {
		if (($x = $targetDef[Legacies_Empire::ID_SPECIAL_ANTIBALLISTIC_MISSILE] - $nbMissile) > 0) {
            $stayingDefense[Legacies_Empire::ID_SPECIAL_ANTIBALLISTIC_MISSILE] = $x;
            $destroyDefense[Legacies_Empire::ID_SPECIAL_ANTIBALLISTIC_MISSILE] = $nbMissile;
		    $nbMissile = 0;
		} else {
			$nbMissile -= $targetDef[Legacies_Empire::ID_SPECIAL_ANTIBALLISTIC_MISSILE];
			$stayingDefense[Legacies_Empire::ID_SPECIAL_ANTIBALLISTIC_MISSILE] = 0;
			$destroyDefense[Legacies_Empire::ID_SPECIAL_ANTIBALLISTIC_MISSILE] = abs($x);
		}
	}

	/**
	 * @var array Met la d�fense prioritaire en premier dans l'ordre des destructions //LANG
	 */
	$attackOrder = array(0 => $firstTarget);
	foreach ($reslist[Legacies_Empire::TYPE_DEFENSE] as $id) {
		if ($id != $firstTarget)
			$attackOrder[] = $id;
	}

	/**
	 * @var int Number of attack point.
	 */
	$attackPoints = $nbMissile * 120;
	$techDifference = $attackerTech - $defenderTech;
	$attackPoints = ($techDifference > 0) ? $attackPoints + $techDifference * 8 : $attackPoints + $techDifference * 6;


	foreach ($attackOrder as $value => $id) {
	    if ($targetDef[$id] > 0 && $attackPoints > 0) {

	        /**
	         * @var int Number of structure point ( Resource / 1000)
	         */
		    $currentDefensePoint = ($pricelist[$id][Legacies_Empire::RESOURCE_METAL] + $pricelist[$id][Legacies_Empire::RESOURCE_CRISTAL]
				+ $pricelist[$id][Legacies_Empire::RESOURCE_DEUTERIUM]) / 1000;

		    /**
		     * @var int Total of structure point for the current defense
		     */
		    $defensePoint = $targetDef[$id] * $currentDefensePoint;

		    /**
		     * @var int Destroyed point for the current defense
		     */
		    $destroyPoint = ($attackPoints > $defensePoint) ? $defensePoint : $attackPoints;

		    $attackPoints = ($attackPoints > $defensePoint) ? $attackPoints - $defensePoint : 0;
            $destroyDefense[$id] = floor($destroyPoint / $currentDefensePoint);
		    $stayingDefense[$id] = $targetDef[$id] - $destroyDefense[$id];

	    } else {
	        $stayingDefense[$id] = $targetDef[$id];
	    }
	}

	$return = array();
	    $return['stayingDefense'] = $stayingDefense;
	    $return['destroyDefense'] = $destroyDefense;

	    return $return;

}