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
 * Return the size and the diameter of a planet 
 * 
 * @param int $position Position of the planet in galaxy
 * @param bool $homeWorld If the planet a homeworld or not
 
 * @return array 
 */
function PlanetSizeRandomiser ($position, $homeWorld = false) {
	global $game_config;
    
	/**
 	 * @var array List of planet min and max size.
 	*/
    $fieldsPlanete = array (
        0 => 0,
        1 => array ('min' => 40, 'max' => 60),
        2 => array ('min' => 40, 'max' => 70),
        3 => array ('min' => 40, 'max' => 65),
        4 => array ('min' => 100, 'max' => 250),
        5 => array ('min' => 100, 'max' => 250),
        6 => array ('min' => 100, 'max' => 250),
        7 => array ('min' => 80, 'max' => 180),
        8 => array ('min' => 80, 'max' => 180),
        9 => array ('min' => 80, 'max' => 180),
        10 => array ('min' => 70, 'max' => 130),
        11 => array ('min' => 70, 'max' => 140),
        12 => array ('min' => 70, 'max' => 130),
        13 => array ('min' => 50, 'max' => 150),
        14 => array ('min' => 60, 'max' => 140),
        15 => array ('min' => 40, 'max' => 160)
      );

    if (!$homeWorld) {
        /**
         * @var int Multiplicater to coincide with the game settings
         */
        $planetRatio    = floor($game_config['initial_fields'] /163 );
        
        $porcent = mt_rand(1, 100);
        if ($porcent < 10 ) {
            $fields = mt_rand(30, $fieldsPlanete[$position]['min']);
        } else if ($porcent > 90) {
            $fields = mt_rand($fieldsPlanete[$position]['max'], 349);
        } else {
            $fields = mt_rand($fieldsPlanete[$position]['min'], $fieldsPlanete[$position]['max']);
        }
        
        /**
         * @var int Final size of the planet
         */
        $planetFields = $fields * $planetRatio;
       
    } else {
        $planetFields = $game_config['initial_fields'];
    }
    
    return array(
        'diameter'    => ($planetFields ^ (14 / 1.5)) * 75,
        'field_max'   => $planetFields
      );

}
/**
 * Create a new planet and insert it on the database
 * 
 * @param int|string $galaxy
 * @param int|string $system
 * @param int|string $position
 * @param int|string $planetOwnerId
 * @param string $planetName
 * @param bool $homeWorld
 * 
 * @return bool
 */
function CreateOnePlanetRecord($galaxy, $system, $position, $planetOwnerId, $planetName = '', $homeWorld = false) {
	global $lang, $game_config;
    /**
     * @var resource Get database link
     */
	$readConnection = Nova::getSingleton('core/database_connection_pool')
    ->getConnection('core_read');
	
	$planetExist = $readConnection->select()
	    ->from($readConnection->getDeprecatedTable('planets'))
	    ->where('galaxy =?', $galaxy)
	    ->where('system =?', $system)
	    ->where('planet =?', $position)
	    ->query()
	    ->fetch()
	  ;

	if (!$planetExist) {
		$planet                      = PlanetSizeRandomiser ($position, $homeWorld);
		$planet['metal']             = BUILD_METAL;
		$planet['crystal']           = BUILD_CRISTAL;
		$planet['deuterium']         = BUILD_DEUTERIUM;
		$planet['metal_perhour']     = $game_config['metal_basic_income'];
		$planet['crystal_perhour']   = $game_config['crystal_basic_income'];
		$planet['deuterium_perhour'] = $game_config['deuterium_basic_income'];
		$planet['metal_max']         = BASE_STORAGE_SIZE;
		$planet['crystal_max']       = BASE_STORAGE_SIZE;
		$planet['deuterium_max']     = BASE_STORAGE_SIZE;

		$planet['galaxy'] = $galaxy;
		$planet['system'] = $system;
		$planet['planet'] = $position;

		if ($position == 1 || $position == 2 || $position == 3) {
			$planetType         = 'trocken';
			$planetDesign       = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10');
			$planet['temp_min'] = rand(0, 100);
			$planet['temp_max'] = $planet['temp_min'] + 40;
		} elseif ($position == 4 || $position == 5 || $position == 6) {
			$planetType         = 'dschjungel';
			$planetDesign       = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10');
			$planet['temp_min'] = rand(-25, 75);
			$planet['temp_max'] = $planet['temp_min'] + 40;
		} elseif ($position == 7 || $position == 8 || $position == 9) {
			$planetType         = 'normaltemp';
			$planetDesign       = array('01', '02', '03', '04', '05', '06', '07');
			$planet['temp_min'] = rand(-50, 50);
			$planet['temp_max'] = $planet['temp_min'] + 40;
		} elseif ($position == 10 || $position == 11 || $position == 12) {
			$planetType         = 'wasser';
			$planetDesign       = array('01', '02', '03', '04', '05', '06', '07', '08', '09');
			$planet['temp_min'] = rand(-75, 25);
			$planet['temp_max'] = $planet['temp_min'] + 40;
		} elseif ($position == 13 || $position == 14 || $position == 15) {
			$planetType         = 'eis';
			$planetDesign       = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10');
			$planet['temp_min'] = rand(-100, 10);
			$planet['temp_max'] = $planet['temp_min'] + 40;
		} else {
			$planetType         = 'wuesten';
			$planetDesign       = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '00',);
			$planet['temp_min'] = rand(-120, 10);
			$planet['temp_max'] = $planet['temp_min'] + 40;
		}

		$planet['image'] = $planetType
		                . 'planet'
		                . $planetDesign[ rand( 0, count( $planetDesign ) - 1 ) ];
		$planet['planet_type'] = 1;
		$planet['id_owner']    = $planetOwnerId;
		$planet['last_update'] = new Zend_Db_Expr('UNIX_TIMESTAMP()');
		$planet['name']        = ($planetName == '') ? $lang['sys_colo_defaultname'] : $planetName;

        $readConnection->insert($readConnection->getDeprecatedTable('planets'), $planet);
        $planetId = $readConnection->lastInsertId($readConnection->getDeprecatedTable('planets'));

        $galaxyPosition = $readConnection->select()
            ->from($readConnection->getDeprecatedTable('galaxy'), array ('id' => 'id_planet'))
            ->where('galaxy=?', $galaxy)
            ->where('system=?', $system)
            ->where('planet=?', $position)
            ->query()
            ->fetch()
          ;
		if ($galaxyPosition) {

			$readConnection->update($readConnection->getDeprecatedTable('galaxy'),
			    array ('id_planet' => $planetId),
			    array ('id_planet =?', $galaxyPosition['id'])
			  );
			
		} else {

			$data = array (
				'galaxy' => $planet['galaxy'], 
				'system' => $planet['system'],
				'planet' => $planet['planet'],
				'id_planet' => $planetId
              );
			
			$readConnection->insert($readConnection->getDeprecatedTable('galaxy'), $data);
			 
		}
            return $planetId;
	} else {

		return false;
	}

}