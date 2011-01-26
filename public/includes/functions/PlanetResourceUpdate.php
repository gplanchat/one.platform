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

function PlanetResourceUpdate ( $CurrentUser, &$CurrentPlanet, $UpdateTime, $Simul = false ) {
	global $ProdGrid, $resource, $reslist, $game_config;

	// Mise a jour de l'espace de stockage
	$CurrentPlanet['metal_max']     = (floor (BASE_STORAGE_SIZE * pow (1.5, $CurrentPlanet[ $resource[22] ] ))) * (1 + ($CurrentUser['rpg_stockeur'] * 0.5));
	$CurrentPlanet['crystal_max']   = (floor (BASE_STORAGE_SIZE * pow (1.5, $CurrentPlanet[ $resource[23] ] ))) * (1 + ($CurrentUser['rpg_stockeur'] * 0.5));
	$CurrentPlanet['deuterium_max'] = (floor (BASE_STORAGE_SIZE * pow (1.5, $CurrentPlanet[ $resource[24] ] ))) * (1 + ($CurrentUser['rpg_stockeur'] * 0.5));

	// Calcul de l'espace de stockage (avec les debordements possibles)
	$MaxMetalStorage                = $CurrentPlanet['metal_max']     * MAX_OVERFLOW;
	$MaxCristalStorage              = $CurrentPlanet['crystal_max']   * MAX_OVERFLOW;
	$MaxDeuteriumStorage            = $CurrentPlanet['deuterium_max'] * MAX_OVERFLOW;

	$userInVacation = ($CurrentUser['urlaubs_modus'] == 1) ? true : false; 
		
	if ($CurrentPlanet['planet_type'] == 3 || $userInVacation == true) {
		
		$CurrentPlanet['metal_perhour']        = 0;
		$CurrentPlanet['crystal_perhour']      = 0;
		$CurrentPlanet['deuterium_perhour']    = 0;
		$CurrentPlanet['energy_used']          = 0;
		$CurrentPlanet['energy_max']           = 0;
				
	} else {
		
		$Caps             = array('metal_perhour' => 0, 'crystal_perhour' => 0, 'deuterium_perhour' => 0, 'energy_used' => 0, 'energy_max' => 0);
		$BuildTemp        = $CurrentPlanet['temp_max'];	
		
		foreach ($reslist['prod'] as $ProdId) {
		
			$BuildLevelFactor = $CurrentPlanet[$resource[$ProdId].'_porcent'];
			$BuildLevel       = $CurrentPlanet[$resource[$ProdId]];
	
			$Caps['metal_perhour']     +=  floor( eval  ( $ProdGrid[$ProdId][Legacies_Empire::RESOURCE_FORMULA][Legacies_Empire::RESOURCE_METAL]     ) * ( $game_config['resource_multiplier'] ) * ( 1 + ( $CurrentUser['rpg_geologue']  * 0.05 ) ) );
			$Caps['crystal_perhour']   +=  floor( eval  ( $ProdGrid[$ProdId][Legacies_Empire::RESOURCE_FORMULA][Legacies_Empire::RESOURCE_CRISTAL]   ) * ( $game_config['resource_multiplier'] ) * ( 1 + ( $CurrentUser['rpg_geologue']  * 0.05 ) ) );
			$Caps['deuterium_perhour'] +=  floor( eval  ( $ProdGrid[$ProdId][Legacies_Empire::RESOURCE_FORMULA][Legacies_Empire::RESOURCE_DEUTERIUM] ) * ( $game_config['resource_multiplier'] ) * ( 1 + ( $CurrentUser['rpg_geologue']  * 0.05 ) ) );
				
			if ($ProdId < 4) {
				$Caps['energy_used']   +=  floor( eval  ( $ProdGrid[$ProdId][Legacies_Empire::RESOURCE_FORMULA][Legacies_Empire::RESOURCE_ENERGY]    ) * ( $game_config['resource_multiplier'] ) * ( 1 + ( $CurrentUser['rpg_ingenieur'] * 0.05 ) ) );
			} else {
				$Caps['energy_max']    +=  floor( eval  ( $ProdGrid[$ProdId][Legacies_Empire::RESOURCE_FORMULA][Legacies_Empire::RESOURCE_ENERGY]    ) * ( $game_config['resource_multiplier'] ) * ( 1 + ( $CurrentUser['rpg_ingenieur'] * 0.05 ) ) );
			}
		}	

		$CurrentPlanet['metal_perhour']        = $Caps['metal_perhour'];
		$CurrentPlanet['crystal_perhour']      = $Caps['crystal_perhour'];
		$CurrentPlanet['deuterium_perhour']    = $Caps['deuterium_perhour'];
		$CurrentPlanet['energy_used']          = $Caps['energy_used'];
		$CurrentPlanet['energy_max']           = $Caps['energy_max'];

		// Depuis quand n'avons nous pas les infos ressources a jours ?
			$ProductionTime               = ($UpdateTime - $CurrentPlanet['last_update']);
			$CurrentPlanet['last_update'] = $UpdateTime;

		if ($CurrentPlanet['energy_max'] == 0) { // Si l'nergie = 0 : mode vacances ou pas de production

			$CurrentPlanet['metal_perhour']     = $game_config['metal_basic_income'];
			$CurrentPlanet['crystal_perhour']   = $game_config['crystal_basic_income'];
			$CurrentPlanet['deuterium_perhour'] = $game_config['deuterium_basic_income'];
			$CurrentPlanet['energy_used']		= 0;

			$production_level = 100;
		
		} elseif ($CurrentPlanet['energy_max'] >= abs($CurrentPlanet['energy_used'])) {
			$production_level = 100;
		} else {
			$production_level = floor(($CurrentPlanet['energy_max']) / abs($CurrentPlanet['energy_used']) * 100);
		}

		$CurrentPlanet['porcent'] = $production_level;
	
		if ( $CurrentPlanet['metal'] <= $MaxMetalStorage ) {
		
			$MetalProduction = (($ProductionTime * ($CurrentPlanet['metal_perhour'] / 3600)) * $game_config['resource_multiplier']) * (0.01 * $production_level);
			$MetalBaseProduc = (($ProductionTime * ($game_config['metal_basic_income'] / 3600 )) * $game_config['resource_multiplier']);
			$MetalTheorical  = $CurrentPlanet['metal'] + $MetalProduction  +  $MetalBaseProduc;
			
			if ( $MetalTheorical <= $MaxMetalStorage ) {
				$CurrentPlanet['metal']  = $MetalTheorical;
			} else {
				$CurrentPlanet['metal']  = $MaxMetalStorage;
			}
		
		}

		if ( $CurrentPlanet['crystal'] <= $MaxCristalStorage ) {
			
			$CristalProduction = (($ProductionTime * ($CurrentPlanet['crystal_perhour'] / 3600)) * $game_config['resource_multiplier']) * (0.01 * $production_level);
			$CristalBaseProduc = (($ProductionTime * ($game_config['crystal_basic_income'] / 3600 )) * $game_config['resource_multiplier']);
			$CristalTheorical  = $CurrentPlanet['crystal'] + $CristalProduction  +  $CristalBaseProduc;
		
			if ( $CristalTheorical <= $MaxCristalStorage ) {
				$CurrentPlanet['crystal']  = $CristalTheorical;
			} else {
				$CurrentPlanet['crystal']  = $MaxCristalStorage;
			}
		
		}

		if ( $CurrentPlanet['deuterium'] <= $MaxDeuteriumStorage ) {
		
			$DeuteriumProduction = (($ProductionTime * ($CurrentPlanet['deuterium_perhour'] / 3600)) * $game_config['resource_multiplier']) * (0.01 * $production_level);
			$DeuteriumBaseProduc = (($ProductionTime * ($game_config['deuterium_basic_income'] / 3600 )) * $game_config['resource_multiplier']);
			$DeuteriumTheorical  = $CurrentPlanet['deuterium'] + $DeuteriumProduction  +  $DeuteriumBaseProduc;
		
			if ( $DeuteriumTheorical <= $MaxDeuteriumStorage ) {
				$CurrentPlanet['deuterium']  = $DeuteriumTheorical;
			} else {
				$CurrentPlanet['deuterium']  = $MaxDeuteriumStorage;
			}
		
		}
	
		if ($Simul == false) { // Ce n'est pas une simulation, on construit
		
			$Builded = HandleElementBuildingQueue($CurrentUser, $CurrentPlanet, $ProductionTime);
			
			$BuildsEnd = '';
			if ( $Builded != '' ) {
				foreach ( $Builded as $Element => $Count ) {
					if ($Element !== '')
						$BuildsEnd .= "`". $resource[$Element] ."` = '". $CurrentPlanet[$resource[$Element]] ."', ";
				}
			}
				
			$sql = <<<SQL
				UPDATE {{table}}
				SET
					`metal` = '{$CurrentPlanet['metal']}',
					`crystal` = '{$CurrentPlanet['crystal']}',
					`deuterium` = '{$CurrentPlanet['deuterium']}',
					`last_update` = '{$CurrentPlanet['last_update']}',
					`b_hangar_id` = '{$CurrentPlanet['b_hangar_id']}',
					`metal_perhour` = '{$CurrentPlanet['metal_perhour']}',
					`crystal_perhour` = '{$CurrentPlanet['crystal_perhour']}',
					`deuterium_perhour` = '{$CurrentPlanet['deuterium_perhour']}',
					`energy_used` = '{$CurrentPlanet['energy_used']}',
					`energy_max` = '{$CurrentPlanet['energy_max']}',
					$BuildsEnd
					`b_hangar` = '{$CurrentPlanet['b_hangar']}'
				WHERE
					`id` = '{$CurrentPlanet['id']}'
SQL;

			doquery($sql, 'planets');

		}
	}
	
	return $CurrentPlanet;

}

// Revision History
// - 1.0 Mise en module initiale
// - 1.1 Mise a jour automatique mines / silos / energie ...
// - 1.2 Nettoyage du code
?>