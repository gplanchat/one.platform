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

if (!defined('ROOT_PATH')) {
    die('Hacking attempt');
}

require_once APPLICATION_PATH . DS . 'code/community/Legacies/Empire.php';

// Liste de champs pour l'indication des messages en attante
$messfields = array (
    0   => "mnl_spy",
    1   => "mnl_joueur",
    2   => "mnl_alliance",
    3   => "mnl_attaque",
    4   => "mnl_exploit",
    5   => "mnl_transport",
    15  => "mnl_expedition",
    97  => "mnl_general",
    99  => "mnl_buildlist",
    100 => "new_message"
    );

/**
 * Database fields translation table with element ids.
 *
 * @var Array
 */
$resource = array(
//
// Buildings
// {{{
    Legacies_Empire::ID_BUILDING_METAL_MINE            => "metal_mine",
    Legacies_Empire::ID_BUILDING_CRISTAL_MINE          => "crystal_mine",
    Legacies_Empire::ID_BUILDING_DEUTERIUM_SYNTHETISER => "deuterium_sintetizer",
    Legacies_Empire::ID_BUILDING_SOLAR_PLANT           => "solar_plant",
    Legacies_Empire::ID_BUILDING_FUSION_REACTOR        => "fusion_plant",
    Legacies_Empire::ID_BUILDING_ROBOTIC_FACTORY       => "robot_factory",
    Legacies_Empire::ID_BUILDING_NANITE_FACTORY        => "nano_factory",
    Legacies_Empire::ID_BUILDING_SHIPYARD              => "hangar",
    Legacies_Empire::ID_BUILDING_METAL_STORAGE         => "metal_store",
    Legacies_Empire::ID_BUILDING_CRISTAL_STORAGE       => "crystal_store",
    Legacies_Empire::ID_BUILDING_DEUTERIUM_TANK        => "deuterium_store",
    Legacies_Empire::ID_BUILDING_RESEARCH_LAB          => "laboratory",
    Legacies_Empire::ID_BUILDING_TERRAFORMER           => "terraformer",
    Legacies_Empire::ID_BUILDING_ALLIANCE_DEPOT        => "ally_deposit",
    Legacies_Empire::ID_BUILDING_LUNAR_BASE            => "mondbasis",
    Legacies_Empire::ID_BUILDING_SENSOR_PHALANX        => "phalanx",
    Legacies_Empire::ID_BUILDING_JUMP_GATE             => "sprungtor",
    Legacies_Empire::ID_BUILDING_MISSILE_SILO          => "silo",
// }}}

//
// Researches
// {{{
    Legacies_Empire::ID_RESEARCH_ESPIONAGE_TECHNOLOGY           => "spy_tech",
    Legacies_Empire::ID_RESEARCH_COMPUTER_TECHNOLOGY            => "computer_tech",
    Legacies_Empire::ID_RESEARCH_WEAPON_TECHNOLOGY              => "military_tech",
    Legacies_Empire::ID_RESEARCH_SHIELDING_TECHNOLOGY           => "defence_tech",
    Legacies_Empire::ID_RESEARCH_ARMOUR_TECHNOLOGY              => "shield_tech",
    Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY              => "energy_tech",
    Legacies_Empire::ID_RESEARCH_HYPERSPACE_TECHNOLOGY          => "hyperspace_tech",
    Legacies_Empire::ID_RESEARCH_COMBUSTION_DRIVE               => "combustion_tech",
    Legacies_Empire::ID_RESEARCH_IMPULSE_DRIVE                  => "impulse_motor_tech",
    Legacies_Empire::ID_RESEARCH_HYPERSPACE_DRIVE               => "hyperspace_motor_tech",
    Legacies_Empire::ID_RESEARCH_LASER_TECHNOLOGY               => "laser_tech",
    Legacies_Empire::ID_RESEARCH_ION_TECHNOLOGY                 => "ionic_tech",
    Legacies_Empire::ID_RESEARCH_PLASMA_TECHNOLOGY              => "buster_tech",
    Legacies_Empire::ID_RESEARCH_INTERGALACTIC_RESEARCH_NETWORK => "intergalactic_tech",
    Legacies_Empire::ID_RESEARCH_EXPEDITION_TECHNOLOGY          => "expedition_tech",
    Legacies_Empire::ID_RESEARCH_GRAVITON_TECHNOLOGY            => "graviton_tech",
// }}}

//
// Ships
// {{{
    Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT => "small_ship_cargo",
    Legacies_Empire::ID_SHIP_LARGE_TRANSPORT => "big_ship_cargo",
    Legacies_Empire::ID_SHIP_LIGHT_FIGHTER   => "light_hunter",
    Legacies_Empire::ID_SHIP_HEAVY_FIGHTER   => "heavy_hunter",
    Legacies_Empire::ID_SHIP_CRUISER         => "crusher",
    Legacies_Empire::ID_SHIP_BATTLESHIP      => "battle_ship",
    Legacies_Empire::ID_SHIP_COLONY_SHIP     => "colonizer",
    Legacies_Empire::ID_SHIP_RECYCLER        => "recycler",
    Legacies_Empire::ID_SHIP_SPY_DRONE       => "spy_sonde",
    Legacies_Empire::ID_SHIP_BOMBER          => "bomber_ship",
    Legacies_Empire::ID_SHIP_SOLAR_SATELLITE => "solar_satelit",
    Legacies_Empire::ID_SHIP_DESTRUCTOR      => "destructor",
    Legacies_Empire::ID_SHIP_DEATH_STAR      => "dearth_star",
    Legacies_Empire::ID_SHIP_BATTLECRUISER   => "battleship",
//

//
// Defenses
// {{{
    Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER   => "misil_launcher",
    Legacies_Empire::ID_DEFENSE_LIGHT_LASER       => "small_laser",
    Legacies_Empire::ID_DEFENSE_HEAVY_LASER       => "big_laser",
    Legacies_Empire::ID_DEFENSE_ION_CANNON        => "gauss_canyon",
    Legacies_Empire::ID_DEFENSE_GAUSS_CANNON      => "ionic_canyon",
    Legacies_Empire::ID_DEFENSE_PLASMA_TURRET     => "buster_canyon",
    Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => "small_protection_shield",
    Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => "big_protection_shield",

    Legacies_Empire::ID_SPECIAL_ANTIBALLISTIC_MISSILE  => "interceptor_misil",
    Legacies_Empire::ID_SPECIAL_INTERPLANETARY_MISSILE => "interplanetary_misil",
// }}}

//
// Officers
// {{{
    601 => "rpg_geologue",
    602 => "rpg_amiral",
    603 => "rpg_ingenieur",
    604 => "rpg_technocrate",
    605 => "rpg_constructeur",
    606 => "rpg_scientifique",
    607 => "rpg_stockeur",
    608 => "rpg_defenseur",
    609 => "rpg_bunker",
    610 => "rpg_espion",
    611 => "rpg_commandant",
    612 => "rpg_destructeur",
    613 => "rpg_general",
    614 => "rpg_raideur",
    615 => "rpg_empereur"
// }}}
    );

$requirements = array(
//
// Planet buildings requirements
// {{{
    Legacies_Empire::ID_BUILDING_FUSION_REACTOR => array(
        Legacies_Empire::ID_BUILDING_DEUTERIUM_SYNTHETISER => 5,
        Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY     => 3
        ),

    Legacies_Empire::ID_BUILDING_NANITE_FACTORY => array(
        Legacies_Empire::ID_BUILDING_ROBOTIC_FACTORY     => 10,
        Legacies_Empire::ID_RESEARCH_COMPUTER_TECHNOLOGY => 10
        ),

    Legacies_Empire::ID_BUILDING_SHIPYARD => array(
        Legacies_Empire::ID_BUILDING_ROBOTIC_FACTORY => 2
        ),

    Legacies_Empire::ID_BUILDING_TERRAFORMER => array(
        Legacies_Empire::ID_BUILDING_NANITE_FACTORY    => 1,
        Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY => 12
        ),
// }}}

//
// Moon buildings requirements
// {{{
     Legacies_Empire::ID_BUILDING_SENSOR_PHALANX => array(
        Legacies_Empire::ID_BUILDING_LUNAR_BASE => 1
        ),

     Legacies_Empire::ID_BUILDING_JUMP_GATE => array(
        Legacies_Empire::ID_BUILDING_LUNAR_BASE            => 1,
        Legacies_Empire::ID_RESEARCH_HYPERSPACE_TECHNOLOGY => 7
        ),
// }}}

//
// Technologies requirements
// {{{
    Legacies_Empire::ID_RESEARCH_ESPIONAGE_TECHNOLOGY => array(
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB => 3
        ),

    Legacies_Empire::ID_RESEARCH_COMPUTER_TECHNOLOGY => array(
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB => 1
        ),

    Legacies_Empire::ID_RESEARCH_WEAPON_TECHNOLOGY => array(
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB => 4
        ),

    Legacies_Empire::ID_RESEARCH_SHIELDING_TECHNOLOGY => array(
        Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY => 3,
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB      => 6
        ),

    Legacies_Empire::ID_RESEARCH_ARMOUR_TECHNOLOGY => array(
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB => 2
        ),

    Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY => array(
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB => 1
        ),

    Legacies_Empire::ID_RESEARCH_HYPERSPACE_TECHNOLOGY => array(
        Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY => 5,
        Legacies_Empire::ID_RESEARCH_SHIELDING_TECHNOLOGY  => 5,
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB      => 7
        ),

    Legacies_Empire::ID_RESEARCH_COMBUSTION_DRIVE => array(
        Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY => 1,
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB      => 1
        ),

    Legacies_Empire::ID_RESEARCH_IMPULSE_DRIVE => array(
        Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY => 1,
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB      => 2
        ),

    Legacies_Empire::ID_RESEARCH_HYPERSPACE_DRIVE => array(
        Legacies_Empire::ID_RESEARCH_HYPERSPACE_TECHNOLOGY => 3,
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB          => 7
        ),

    Legacies_Empire::ID_RESEARCH_LASER_TECHNOLOGY => array(
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB      => 1,
        Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY => 2
        ),

    Legacies_Empire::ID_RESEARCH_ION_TECHNOLOGY => array(
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB      => 4,
        Legacies_Empire::ID_RESEARCH_LASER_TECHNOLOGY  => 5,
        Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY => 4
        ),

    Legacies_Empire::ID_RESEARCH_PLASMA_TECHNOLOGY => array(
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB      => 5,
        Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY => 8,
        Legacies_Empire::ID_RESEARCH_LASER_TECHNOLOGY  => 10,
        Legacies_Empire::ID_RESEARCH_ION_TECHNOLOGY    => 5
        ),

    Legacies_Empire::ID_RESEARCH_INTERGALACTIC_RESEARCH_NETWORK => array(
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB          => 10,
        Legacies_Empire::ID_RESEARCH_COMPUTER_TECHNOLOGY   => 8,
        Legacies_Empire::ID_RESEARCH_HYPERSPACE_TECHNOLOGY => 8
        ),

    Legacies_Empire::ID_RESEARCH_EXPEDITION_TECHNOLOGY => array(
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB        => 3,
        Legacies_Empire::ID_RESEARCH_COMPUTER_TECHNOLOGY => 4,
        Legacies_Empire::ID_RESEARCH_IMPULSE_DRIVE       => 3
        ),

    Legacies_Empire::ID_RESEARCH_GRAVITON_TECHNOLOGY => array(
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB => 12
        ),
// }}}

//
// Fleets requirements
// {{{
    Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT => array(
        Legacies_Empire::ID_BUILDING_SHIPYARD         => 2,
        Legacies_Empire::ID_RESEARCH_COMBUSTION_DRIVE => 2
        ),

    Legacies_Empire::ID_SHIP_LARGE_TRANSPORT => array(
        Legacies_Empire::ID_BUILDING_SHIPYARD         => 4,
        Legacies_Empire::ID_RESEARCH_COMBUSTION_DRIVE => 6
        ),

    Legacies_Empire::ID_SHIP_LIGHT_FIGHTER => array(
        Legacies_Empire::ID_BUILDING_SHIPYARD         => 1,
        Legacies_Empire::ID_RESEARCH_COMBUSTION_DRIVE => 1
        ),

    Legacies_Empire::ID_SHIP_HEAVY_FIGHTER => array(
        Legacies_Empire::ID_BUILDING_SHIPYARD          => 3,
        Legacies_Empire::ID_RESEARCH_ARMOUR_TECHNOLOGY => 2,
        Legacies_Empire::ID_RESEARCH_IMPULSE_DRIVE     => 2
        ),

    Legacies_Empire::ID_SHIP_CRUISER => array(
        Legacies_Empire::ID_BUILDING_SHIPYARD       => 5,
        Legacies_Empire::ID_RESEARCH_IMPULSE_DRIVE  => 4,
        Legacies_Empire::ID_RESEARCH_ION_TECHNOLOGY => 2
        ),

    Legacies_Empire::ID_SHIP_BATTLESHIP => array(
        Legacies_Empire::ID_BUILDING_SHIPYARD         => 7,
        Legacies_Empire::ID_RESEARCH_HYPERSPACE_DRIVE => 4
        ),

    Legacies_Empire::ID_SHIP_COLONY_SHIP => array(
        Legacies_Empire::ID_BUILDING_SHIPYARD      => 4,
        Legacies_Empire::ID_RESEARCH_IMPULSE_DRIVE => 3
        ),

    Legacies_Empire::ID_SHIP_RECYCLER => array(
        Legacies_Empire::ID_BUILDING_SHIPYARD             => 4,
        Legacies_Empire::ID_RESEARCH_COMBUSTION_DRIVE     => 6,
        Legacies_Empire::ID_RESEARCH_SHIELDING_TECHNOLOGY => 2
        ),

    Legacies_Empire::ID_SHIP_SPY_DRONE => array(
        Legacies_Empire::ID_BUILDING_SHIPYARD             => 3,
        Legacies_Empire::ID_RESEARCH_COMBUSTION_DRIVE     => 3,
        Legacies_Empire::ID_RESEARCH_ESPIONAGE_TECHNOLOGY => 2
        ),

    Legacies_Empire::ID_SHIP_BOMBER => array(
        Legacies_Empire::ID_RESEARCH_IMPULSE_DRIVE     => 6,
        Legacies_Empire::ID_BUILDING_SHIPYARD          => 8,
        Legacies_Empire::ID_RESEARCH_PLASMA_TECHNOLOGY => 5
        ),

    Legacies_Empire::ID_SHIP_SOLAR_SATELLITE => array(
        Legacies_Empire::ID_BUILDING_SHIPYARD => 1
        ),

    Legacies_Empire::ID_SHIP_DESTRUCTOR => array(
        Legacies_Empire::ID_BUILDING_SHIPYARD              => 9,
        Legacies_Empire::ID_RESEARCH_HYPERSPACE_DRIVE      => 6,
        Legacies_Empire::ID_RESEARCH_HYPERSPACE_TECHNOLOGY => 5
        ),

    Legacies_Empire::ID_SHIP_DEATH_STAR => array(
        Legacies_Empire::ID_BUILDING_SHIPYARD              => 12,
        Legacies_Empire::ID_RESEARCH_HYPERSPACE_DRIVE      => 7,
        Legacies_Empire::ID_RESEARCH_HYPERSPACE_TECHNOLOGY => 6,
        Legacies_Empire::ID_RESEARCH_GRAVITON_TECHNOLOGY   => 1
        ),

    Legacies_Empire::ID_SHIP_BATTLECRUISER => array(
        Legacies_Empire::ID_RESEARCH_HYPERSPACE_TECHNOLOGY => 5,
        Legacies_Empire::ID_RESEARCH_LASER_TECHNOLOGY      => 12,
        Legacies_Empire::ID_RESEARCH_HYPERSPACE_DRIVE      => 5,
        Legacies_Empire::ID_BUILDING_SHIPYARD              => 8
        ),
// }}}

//
// Defenses requirements
// {{{
    Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER => array(
        Legacies_Empire::ID_BUILDING_SHIPYARD => 1
        ),

    Legacies_Empire::ID_DEFENSE_LIGHT_LASER => array(
        Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY => 1,
        Legacies_Empire::ID_BUILDING_SHIPYARD          => 2,
        Legacies_Empire::ID_RESEARCH_LASER_TECHNOLOGY  => 3
        ),

    Legacies_Empire::ID_DEFENSE_HEAVY_LASER => array(
        Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY => 3,
        Legacies_Empire::ID_BUILDING_SHIPYARD          => 4,
        Legacies_Empire::ID_RESEARCH_LASER_TECHNOLOGY  => 6
        ),

    Legacies_Empire::ID_DEFENSE_ION_CANNON => array(
        Legacies_Empire::ID_BUILDING_SHIPYARD             => 6,
        Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY    => 6,
        Legacies_Empire::ID_RESEARCH_WEAPON_TECHNOLOGY    => 3,
        Legacies_Empire::ID_RESEARCH_SHIELDING_TECHNOLOGY => 1
        ),

    Legacies_Empire::ID_DEFENSE_GAUSS_CANNON => array(
        Legacies_Empire::ID_BUILDING_SHIPYARD       => 4,
        Legacies_Empire::ID_RESEARCH_ION_TECHNOLOGY => 4
        ),

    Legacies_Empire::ID_DEFENSE_PLASMA_TURRET => array(
        Legacies_Empire::ID_BUILDING_SHIPYARD          => 8,
        Legacies_Empire::ID_RESEARCH_PLASMA_TECHNOLOGY => 7
        ),

    Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => array(
        Legacies_Empire::ID_RESEARCH_SHIELDING_TECHNOLOGY => 2,
        Legacies_Empire::ID_BUILDING_SHIPYARD             => 1
        ),

    Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => array(
        Legacies_Empire::ID_RESEARCH_SHIELDING_TECHNOLOGY => 6,
        Legacies_Empire::ID_BUILDING_SHIPYARD             => 6
        ),

    Legacies_Empire::ID_SPECIAL_ANTIBALLISTIC_MISSILE => array(
        Legacies_Empire::ID_BUILDING_MISSILE_SILO => 2
        ),

    Legacies_Empire::ID_SPECIAL_INTERPLANETARY_MISSILE => array(
        Legacies_Empire::ID_BUILDING_MISSILE_SILO => 4
    ),
// }}}

//
// Officers requirements
// {{{
    603 => array(601 => 5),
    604 => array(602 => 5),
    605 => array(601 => 10, 603 => 2),
    606 => array(601 => 10, 603 => 2),
    607 => array(605 => 1),
    608 => array(606 => 1),
    609 => array(601 => 20, 603 => 10, 605 => 3, 606 => 3, 607 => 2, 608 => 2),
    610 => array(602 => 10, 604 => 5),
    611 => array(602 => 10, 604 => 5),
    612 => array(610 => 1),
    613 => array(611 => 1),
    614 => array(602 => 20, 604 => 10, 610 => 2, 611 => 2, 612 => 1, 613 => 3),
    615 => array(614 => 1, 609 => 1)
    );
// }}}
// Legacy support for the mispelled variable.
$requeriments = &$requirements;

$pricelist = array(
//
// Buildings costs
// {{{
    Legacies_Empire::ID_BUILDING_METAL_MINE => array(
        Legacies_Empire::RESOURCE_METAL      => 60,
        Legacies_Empire::RESOURCE_CRISTAL    => 15,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1.5
        ),

    Legacies_Empire::ID_BUILDING_CRISTAL_MINE => array(
        Legacies_Empire::RESOURCE_METAL      => 48,
        Legacies_Empire::RESOURCE_CRISTAL    => 24,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1.6
        ),

    Legacies_Empire::ID_BUILDING_DEUTERIUM_SYNTHETISER => array(
        Legacies_Empire::RESOURCE_METAL      => 225,
        Legacies_Empire::RESOURCE_CRISTAL    => 75,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1.5
        ),

    Legacies_Empire::ID_BUILDING_SOLAR_PLANT => array(
        Legacies_Empire::RESOURCE_METAL      => 75,
        Legacies_Empire::RESOURCE_CRISTAL    => 30,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1.5
        ),

    Legacies_Empire::ID_BUILDING_FUSION_REACTOR => array(
        Legacies_Empire::RESOURCE_METAL      => 900,
        Legacies_Empire::RESOURCE_CRISTAL    => 360,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 180,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1.8
        ),

    Legacies_Empire::ID_BUILDING_ROBOTIC_FACTORY => array(
        Legacies_Empire::RESOURCE_METAL      => 400,
        Legacies_Empire::RESOURCE_CRISTAL    => 120,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 200,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_BUILDING_NANITE_FACTORY => array(
        Legacies_Empire::RESOURCE_METAL      => 1000000,
        Legacies_Empire::RESOURCE_CRISTAL    => 500000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 100000,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_BUILDING_SHIPYARD => array(
        Legacies_Empire::RESOURCE_METAL      => 400,
        Legacies_Empire::RESOURCE_CRISTAL    => 200,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 100,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_BUILDING_METAL_STORAGE => array(
        Legacies_Empire::RESOURCE_METAL      => 2000,
        Legacies_Empire::RESOURCE_CRISTAL    => 0,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_BUILDING_CRISTAL_STORAGE => array(
        Legacies_Empire::RESOURCE_METAL      => 2000,
        Legacies_Empire::RESOURCE_CRISTAL    => 1000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_BUILDING_DEUTERIUM_TANK => array(
        Legacies_Empire::RESOURCE_METAL      => 2000,
        Legacies_Empire::RESOURCE_CRISTAL    => 2000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_BUILDING_RESEARCH_LAB => array(
        Legacies_Empire::RESOURCE_METAL      => 200,
        Legacies_Empire::RESOURCE_CRISTAL    => 400,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 200,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_BUILDING_TERRAFORMER => array(
        Legacies_Empire::RESOURCE_METAL      => 0,
        Legacies_Empire::RESOURCE_CRISTAL    => 50000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 100000,
        Legacies_Empire::RESOURCE_ENERGY     => 1000,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_BUILDING_ALLIANCE_DEPOT => array(
        Legacies_Empire::RESOURCE_METAL      => 20000,
        Legacies_Empire::RESOURCE_CRISTAL    => 40000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),
    Legacies_Empire::ID_BUILDING_LUNAR_BASE => array(
        Legacies_Empire::RESOURCE_METAL      => 20000,
        Legacies_Empire::RESOURCE_CRISTAL    => 40000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 20000,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),
    Legacies_Empire::ID_BUILDING_SENSOR_PHALANX => array(
        Legacies_Empire::RESOURCE_METAL      => 20000,
        Legacies_Empire::RESOURCE_CRISTAL    => 40000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 20000,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),
    Legacies_Empire::ID_BUILDING_JUMP_GATE => array(
        Legacies_Empire::RESOURCE_METAL      => 2000000,
        Legacies_Empire::RESOURCE_CRISTAL    => 4000000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 2000000,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),
    Legacies_Empire::ID_BUILDING_MISSILE_SILO => array(
        Legacies_Empire::RESOURCE_METAL      => 20000,
        Legacies_Empire::RESOURCE_CRISTAL    => 20000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 1000,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),
// }}}

//
// Researches costs
// {{{
    Legacies_Empire::ID_RESEARCH_ESPIONAGE_TECHNOLOGY => array(
        Legacies_Empire::RESOURCE_METAL      => 200,
        Legacies_Empire::RESOURCE_CRISTAL    => 1000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 200,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_RESEARCH_COMPUTER_TECHNOLOGY => array(
        Legacies_Empire::RESOURCE_METAL      => 0,
        Legacies_Empire::RESOURCE_CRISTAL    => 400,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 600,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_RESEARCH_WEAPON_TECHNOLOGY => array(
        Legacies_Empire::RESOURCE_METAL      => 800,
        Legacies_Empire::RESOURCE_CRISTAL    => 200,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_RESEARCH_SHIELDING_TECHNOLOGY => array(
        Legacies_Empire::RESOURCE_METAL      => 200,
        Legacies_Empire::RESOURCE_CRISTAL    => 600,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_RESEARCH_ARMOUR_TECHNOLOGY => array(
        Legacies_Empire::RESOURCE_METAL      => 1000,
        Legacies_Empire::RESOURCE_CRISTAL    => 0,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY => array(
        Legacies_Empire::RESOURCE_METAL      => 0,
        Legacies_Empire::RESOURCE_CRISTAL    => 800,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 400,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_RESEARCH_HYPERSPACE_TECHNOLOGY => array(
        Legacies_Empire::RESOURCE_METAL      => 0,
        Legacies_Empire::RESOURCE_CRISTAL    => 4000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 2000,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_RESEARCH_COMBUSTION_DRIVE => array(
        Legacies_Empire::RESOURCE_METAL      => 400,
        Legacies_Empire::RESOURCE_CRISTAL    => 0,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 600,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_RESEARCH_IMPULSE_DRIVE => array(
        Legacies_Empire::RESOURCE_METAL      => 2000,
        Legacies_Empire::RESOURCE_CRISTAL    => 4000,
        Legacies_Empire::RESOURCE_DEUTERIUM => 600,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_RESEARCH_HYPERSPACE_DRIVE => array(
        Legacies_Empire::RESOURCE_METAL      => 10000,
        Legacies_Empire::RESOURCE_CRISTAL    => 20000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 6000,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_RESEARCH_LASER_TECHNOLOGY => array(
        Legacies_Empire::RESOURCE_METAL      => 200,
        Legacies_Empire::RESOURCE_CRISTAL    => 100,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_RESEARCH_ION_TECHNOLOGY => array(
        Legacies_Empire::RESOURCE_METAL      => 1000,
        Legacies_Empire::RESOURCE_CRISTAL    => 300,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 100,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_RESEARCH_PLASMA_TECHNOLOGY => array(
        Legacies_Empire::RESOURCE_METAL      => 2000,
        Legacies_Empire::RESOURCE_CRISTAL    => 4000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 1000,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_RESEARCH_INTERGALACTIC_RESEARCH_NETWORK => array(
        Legacies_Empire::RESOURCE_METAL      => 240000,
        Legacies_Empire::RESOURCE_CRISTAL    => 400000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 160000,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_RESEARCH_EXPEDITION_TECHNOLOGY => array(
        Legacies_Empire::RESOURCE_METAL      => 4000,
        Legacies_Empire::RESOURCE_CRISTAL    => 8000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 4000,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 2
        ),

    Legacies_Empire::ID_RESEARCH_GRAVITON_TECHNOLOGY => array(
        Legacies_Empire::RESOURCE_METAL      => 0,
        Legacies_Empire::RESOURCE_CRISTAL    => 0,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        'energy_max'                => 300000, // FIXME:
        Legacies_Empire::RESOURCE_MULTIPLIER => 3
        ),
// }}}

//
// Ship costs & technical properties
// {{{
    Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT => array(
        Legacies_Empire::RESOURCE_METAL              => 2000,
        Legacies_Empire::RESOURCE_CRISTAL            => 2000,
        Legacies_Empire::RESOURCE_DEUTERIUM          => 0,
        Legacies_Empire::RESOURCE_ENERGY             => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER         => 1,
        Legacies_Empire::SHIPS_CONSUMPTION_PRIMARY   => 20,
        Legacies_Empire::SHIPS_CONSUMPTION_SECONDARY => 40,
        Legacies_Empire::SHIPS_CELERITY_PRIMARY      => 5000,
        Legacies_Empire::SHIPS_CELERITY_SECONDARY    => 10000,
        Legacies_Empire::SHIPS_CAPACITY              => 5000
        ),

    Legacies_Empire::ID_SHIP_LARGE_TRANSPORT => array(
        Legacies_Empire::RESOURCE_METAL              => 6000,
        Legacies_Empire::RESOURCE_CRISTAL            => 6000,
        Legacies_Empire::RESOURCE_DEUTERIUM          => 0,
        Legacies_Empire::RESOURCE_ENERGY             => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER         => 1,
        Legacies_Empire::SHIPS_CONSUMPTION_PRIMARY   => 50,
        Legacies_Empire::SHIPS_CONSUMPTION_SECONDARY => 50,
        Legacies_Empire::SHIPS_CELERITY_PRIMARY      => 7500,
        Legacies_Empire::SHIPS_CELERITY_SECONDARY    => 7500,
        Legacies_Empire::SHIPS_CAPACITY              => 25000
        ),

    Legacies_Empire::ID_SHIP_LIGHT_FIGHTER => array(
        Legacies_Empire::RESOURCE_METAL              => 3000,
        Legacies_Empire::RESOURCE_CRISTAL            => 1000,
        Legacies_Empire::RESOURCE_DEUTERIUM          => 0,
        Legacies_Empire::RESOURCE_ENERGY             => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER         => 1,
        Legacies_Empire::SHIPS_CONSUMPTION_PRIMARY   => 20,
        Legacies_Empire::SHIPS_CONSUMPTION_SECONDARY => 20,
        Legacies_Empire::SHIPS_CELERITY_PRIMARY      => 12500,
        Legacies_Empire::SHIPS_CELERITY_SECONDARY    => 12500,
        Legacies_Empire::SHIPS_CAPACITY              => 50
        ),

    Legacies_Empire::ID_SHIP_HEAVY_FIGHTER => array(
        Legacies_Empire::RESOURCE_METAL              => 6000,
        Legacies_Empire::RESOURCE_CRISTAL            => 4000,
        Legacies_Empire::RESOURCE_DEUTERIUM          => 0,
        Legacies_Empire::RESOURCE_ENERGY             => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER         => 1,
        Legacies_Empire::SHIPS_CONSUMPTION_PRIMARY   => 75,
        Legacies_Empire::SHIPS_CONSUMPTION_SECONDARY => 75,
        Legacies_Empire::SHIPS_CELERITY_PRIMARY      => 10000,
        Legacies_Empire::SHIPS_CELERITY_SECONDARY    => 15000,
        Legacies_Empire::SHIPS_CAPACITY              => 100
        ),

    Legacies_Empire::ID_SHIP_CRUISER => array(
        Legacies_Empire::RESOURCE_METAL              => 20000,
        Legacies_Empire::RESOURCE_CRISTAL            => 7000,
        Legacies_Empire::RESOURCE_DEUTERIUM          => 2000,
        Legacies_Empire::RESOURCE_ENERGY             => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER         => 1,
        Legacies_Empire::SHIPS_CONSUMPTION_PRIMARY   => 300 ,
        Legacies_Empire::SHIPS_CONSUMPTION_SECONDARY => 300,
        Legacies_Empire::SHIPS_CELERITY_PRIMARY      => 15000,
        Legacies_Empire::SHIPS_CELERITY_SECONDARY    => 15000,
        Legacies_Empire::SHIPS_CAPACITY              => 800
        ),

    Legacies_Empire::ID_SHIP_BATTLESHIP => array(
        Legacies_Empire::RESOURCE_METAL              => 45000,
        Legacies_Empire::RESOURCE_CRISTAL            => 15000,
        Legacies_Empire::RESOURCE_DEUTERIUM          => 0,
        Legacies_Empire::RESOURCE_ENERGY             => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER         => 1,
        Legacies_Empire::SHIPS_CONSUMPTION_PRIMARY   => 500,
        Legacies_Empire::SHIPS_CONSUMPTION_SECONDARY => 500,
        Legacies_Empire::SHIPS_CELERITY_PRIMARY      => 10000,
        Legacies_Empire::SHIPS_CELERITY_SECONDARY    => 10000,
        Legacies_Empire::SHIPS_CAPACITY              => 1500
        ),

    Legacies_Empire::ID_SHIP_COLONY_SHIP => array(
        Legacies_Empire::RESOURCE_METAL              => 10000,
        Legacies_Empire::RESOURCE_CRISTAL            => 20000,
        Legacies_Empire::RESOURCE_DEUTERIUM          => 10000,
        Legacies_Empire::RESOURCE_ENERGY             => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER         => 1,
        Legacies_Empire::SHIPS_CONSUMPTION_PRIMARY   => 1000,
        Legacies_Empire::SHIPS_CONSUMPTION_SECONDARY => 1000,
        Legacies_Empire::SHIPS_CELERITY_PRIMARY      => 2500,
        Legacies_Empire::SHIPS_CELERITY_SECONDARY    => 2500,
        Legacies_Empire::SHIPS_CAPACITY              => 7500
        ),

    Legacies_Empire::ID_SHIP_RECYCLER => array(
        Legacies_Empire::RESOURCE_METAL              => 10000,
        Legacies_Empire::RESOURCE_CRISTAL            => 6000,
        Legacies_Empire::RESOURCE_DEUTERIUM          => 2000,
        Legacies_Empire::RESOURCE_ENERGY             => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER         => 1,
        Legacies_Empire::SHIPS_CONSUMPTION_PRIMARY   => 300,
        Legacies_Empire::SHIPS_CONSUMPTION_SECONDARY => 300,
        Legacies_Empire::SHIPS_CELERITY_PRIMARY      => 2000,
        Legacies_Empire::SHIPS_CELERITY_SECONDARY    => 2000,
        Legacies_Empire::SHIPS_CAPACITY              => 20000
        ),

    Legacies_Empire::ID_SHIP_SPY_DRONE => array(
        Legacies_Empire::RESOURCE_METAL              => 0,
        Legacies_Empire::RESOURCE_CRISTAL            => 1000,
        Legacies_Empire::RESOURCE_DEUTERIUM          => 0,
        Legacies_Empire::RESOURCE_ENERGY             => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER         => 1,
        Legacies_Empire::SHIPS_CONSUMPTION_PRIMARY   => 1,
        Legacies_Empire::SHIPS_CONSUMPTION_SECONDARY => 1,
        Legacies_Empire::SHIPS_CELERITY_PRIMARY      => 100000000,
        Legacies_Empire::SHIPS_CELERITY_SECONDARY    => 100000000,
        Legacies_Empire::SHIPS_CAPACITY              => 5
        ),

    Legacies_Empire::ID_SHIP_BOMBER => array(
        Legacies_Empire::RESOURCE_METAL              => 50000,
        Legacies_Empire::RESOURCE_CRISTAL            => 25000,
        Legacies_Empire::RESOURCE_DEUTERIUM          => 15000,
        Legacies_Empire::RESOURCE_ENERGY             => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER         => 1,
        Legacies_Empire::SHIPS_CONSUMPTION_PRIMARY   => 1000,
        Legacies_Empire::SHIPS_CONSUMPTION_SECONDARY => 1000,
        Legacies_Empire::SHIPS_CELERITY_PRIMARY      => 4000,
        Legacies_Empire::SHIPS_CELERITY_SECONDARY    => 5000,
        Legacies_Empire::SHIPS_CAPACITY              => 500
        ),

    Legacies_Empire::ID_SHIP_SOLAR_SATELLITE => array(
        Legacies_Empire::RESOURCE_METAL              => 0,
        Legacies_Empire::RESOURCE_CRISTAL            => 2000,
        Legacies_Empire::RESOURCE_DEUTERIUM          => 500,
        Legacies_Empire::RESOURCE_ENERGY             => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER         => 1,
        Legacies_Empire::SHIPS_CONSUMPTION_PRIMARY   => 0,
        Legacies_Empire::SHIPS_CONSUMPTION_SECONDARY => 0,
        Legacies_Empire::SHIPS_CELERITY_PRIMARY      => 0,
        Legacies_Empire::SHIPS_CELERITY_SECONDARY    => 0,
        Legacies_Empire::SHIPS_CAPACITY              => 0
        ),

    Legacies_Empire::ID_SHIP_DESTRUCTOR => array(
        Legacies_Empire::RESOURCE_METAL              => 60000,
        Legacies_Empire::RESOURCE_CRISTAL            => 50000,
        Legacies_Empire::RESOURCE_DEUTERIUM          => 15000,
        Legacies_Empire::RESOURCE_ENERGY             => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER         => 1,
        Legacies_Empire::SHIPS_CONSUMPTION_PRIMARY   => 1000,
        Legacies_Empire::SHIPS_CONSUMPTION_SECONDARY => 1000,
        Legacies_Empire::SHIPS_CELERITY_PRIMARY      => 5000,
        Legacies_Empire::SHIPS_CELERITY_SECONDARY    => 5000,
        Legacies_Empire::SHIPS_CAPACITY              => 2000
        ),

    Legacies_Empire::ID_SHIP_DEATH_STAR => array(
        Legacies_Empire::RESOURCE_METAL              => 5000000,
        Legacies_Empire::RESOURCE_CRISTAL            => 4000000,
        Legacies_Empire::RESOURCE_DEUTERIUM          => 1000000,
        Legacies_Empire::RESOURCE_ENERGY             => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER         => 1,
        Legacies_Empire::SHIPS_CONSUMPTION_PRIMARY   => 1,
        Legacies_Empire::SHIPS_CONSUMPTION_SECONDARY => 1,
        Legacies_Empire::SHIPS_CELERITY_PRIMARY      => 100,
        Legacies_Empire::SHIPS_CELERITY_SECONDARY    => 100,
        Legacies_Empire::SHIPS_CAPACITY              => 1000000
        ),

    Legacies_Empire::ID_SHIP_BATTLECRUISER => array(
        Legacies_Empire::RESOURCE_METAL              => 30000,
        Legacies_Empire::RESOURCE_CRISTAL            => 40000,
        Legacies_Empire::RESOURCE_DEUTERIUM          => 15000,
        Legacies_Empire::RESOURCE_ENERGY             => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER         => 1,
        Legacies_Empire::SHIPS_CONSUMPTION_PRIMARY   => 250,
        Legacies_Empire::SHIPS_CONSUMPTION_SECONDARY => 250,
        Legacies_Empire::SHIPS_CELERITY_PRIMARY      => 10000,
        Legacies_Empire::SHIPS_CELERITY_SECONDARY    => 10000,
        Legacies_Empire::SHIPS_CAPACITY              => 750
        ),
// }}}

//
// Defenses
// {{{
    Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER => array(
        Legacies_Empire::RESOURCE_METAL      => 2000,
        Legacies_Empire::RESOURCE_CRISTAL    => 0,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1
        ),

    Legacies_Empire::ID_DEFENSE_LIGHT_LASER => array(
        Legacies_Empire::RESOURCE_METAL      => 1500,
        Legacies_Empire::RESOURCE_CRISTAL    => 500,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1
        ),

    Legacies_Empire::ID_DEFENSE_HEAVY_LASER => array(
        Legacies_Empire::RESOURCE_METAL      => 6000,
        Legacies_Empire::RESOURCE_CRISTAL    => 2000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1
        ),

    Legacies_Empire::ID_DEFENSE_ION_CANNON => array(
        Legacies_Empire::RESOURCE_METAL      => 20000,
        Legacies_Empire::RESOURCE_CRISTAL    => 15000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 2000,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1
        ),

    Legacies_Empire::ID_DEFENSE_GAUSS_CANNON => array(
        Legacies_Empire::RESOURCE_METAL      => 2000,
        Legacies_Empire::RESOURCE_CRISTAL    => 6000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1
        ),

    Legacies_Empire::ID_DEFENSE_PLASMA_TURRET => array(
        Legacies_Empire::RESOURCE_METAL      => 50000,
        Legacies_Empire::RESOURCE_CRISTAL    => 50000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 30000,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1
        ),

    Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => array(
        Legacies_Empire::RESOURCE_METAL      => 10000,
        Legacies_Empire::RESOURCE_CRISTAL    => 10000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1
        ),

    Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => array(
        Legacies_Empire::RESOURCE_METAL      => 50000,
        Legacies_Empire::RESOURCE_CRISTAL    => 50000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1
        ),

    Legacies_Empire::ID_SPECIAL_ANTIBALLISTIC_MISSILE => array(
        Legacies_Empire::RESOURCE_METAL      => 8000,
        Legacies_Empire::RESOURCE_CRISTAL    => 2000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1
        ),

    Legacies_Empire::ID_SPECIAL_INTERPLANETARY_MISSILE => array(
        Legacies_Empire::RESOURCE_METAL      => 12500,
        Legacies_Empire::RESOURCE_CRISTAL    => 2500,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 10000,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1
        ),
// }}}

//
// Officers
// {{
    601 => array('max' => 20),
    602 => array('max' => 20),
    603 => array('max' => 10),
    604 => array('max' => 10),
    605 => array('max' => 3),
    606 => array('max' => 3),
    607 => array('max' => 2),
    608 => array('max' => 2),
    609 => array('max' => 1),
    610 => array('max' => 2),
    611 => array('max' => 2),
    612 => array('max' => 1),
    613 => array('max' => 3),
    614 => array('max' => 1),
    615 => array('max' => 1)
// }}}
    );

/**
 * Combat capacities
 * @var array
 */
$CombatCaps = array(
//
// Ships combat capacities
// {{{
    Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT => array(
        Legacies_Empire::ID_COMBAT_SHIELDS    => 10,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 5,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_CRUISER              => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP           => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP          => 1,
            Legacies_Empire::ID_SHIP_RECYCLER             => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE            => 5,
            Legacies_Empire::ID_SHIP_BOMBER               => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE      => 5,
            Legacies_Empire::ID_SHIP_DESTRUCTOR           => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR           => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER        => 1,
            Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER   => 1,
            Legacies_Empire::ID_DEFENSE_LIGHT_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_HEAVY_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_ION_CANNON        => 1,
            Legacies_Empire::ID_DEFENSE_GAUSS_CANNON      => 1,
            Legacies_Empire::ID_DEFENSE_PLASMA_TURRET     => 1,
            Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => 1,
            Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => 1
            )
        ),

    Legacies_Empire::ID_SHIP_LARGE_TRANSPORT => array(
        Legacies_Empire::ID_COMBAT_SHIELDS    => 25,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 5,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_CRUISER              => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP           => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP          => 1,
            Legacies_Empire::ID_SHIP_RECYCLER             => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE            => 5,
            Legacies_Empire::ID_SHIP_BOMBER               => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE      => 5,
            Legacies_Empire::ID_SHIP_DESTRUCTOR           => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR           => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER        => 1,
            Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER   => 1,
            Legacies_Empire::ID_DEFENSE_LIGHT_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_HEAVY_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_ION_CANNON        => 1,
            Legacies_Empire::ID_DEFENSE_GAUSS_CANNON      => 1,
            Legacies_Empire::ID_DEFENSE_PLASMA_TURRET     => 1,
            Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => 1,
            Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => 1
            )
        ),

    Legacies_Empire::ID_SHIP_LIGHT_FIGHTER => array(
        Legacies_Empire::ID_COMBAT_SHIELDS    => 10,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 50,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT      => 2,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_CRUISER              => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP           => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP          => 1,
            Legacies_Empire::ID_SHIP_RECYCLER             => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE            => 5,
            Legacies_Empire::ID_SHIP_BOMBER               => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE      => 5,
            Legacies_Empire::ID_SHIP_DESTRUCTOR           => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR           => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER        => 1,
            Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER   => 1,
            Legacies_Empire::ID_DEFENSE_LIGHT_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_HEAVY_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_ION_CANNON        => 1,
            Legacies_Empire::ID_DEFENSE_GAUSS_CANNON      => 1,
            Legacies_Empire::ID_DEFENSE_PLASMA_TURRET     => 1,
            Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => 1,
            Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => 1
            )
        ),

    Legacies_Empire::ID_SHIP_HEAVY_FIGHTER => array(
        Legacies_Empire::ID_COMBAT_SHIELDS    => 25,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 150,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT      => 3,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_CRUISER              => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP           => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP          => 1,
            Legacies_Empire::ID_SHIP_RECYCLER             => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE            => 5,
            Legacies_Empire::ID_SHIP_BOMBER               => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE      => 5,
            Legacies_Empire::ID_SHIP_DESTRUCTOR           => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR           => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER        => 1,
            Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER   => 1,
            Legacies_Empire::ID_DEFENSE_LIGHT_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_HEAVY_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_ION_CANNON        => 1,
            Legacies_Empire::ID_DEFENSE_GAUSS_CANNON      => 1,
            Legacies_Empire::ID_DEFENSE_PLASMA_TURRET     => 1,
            Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => 1,
            Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => 1
            )
        ),

    Legacies_Empire::ID_SHIP_CRUISER => array(
        Legacies_Empire::ID_COMBAT_SHIELDS    => 50,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 400,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER        => 6,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_CRUISER              => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP           => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP          => 1,
            Legacies_Empire::ID_SHIP_RECYCLER             => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE            => 5,
            Legacies_Empire::ID_SHIP_BOMBER               => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE      => 5,
            Legacies_Empire::ID_SHIP_DESTRUCTOR           => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR           => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER        => 1,
            Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER   => 10,
            Legacies_Empire::ID_DEFENSE_LIGHT_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_HEAVY_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_ION_CANNON        => 1,
            Legacies_Empire::ID_DEFENSE_GAUSS_CANNON      => 1,
            Legacies_Empire::ID_DEFENSE_PLASMA_TURRET     => 1,
            Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => 1,
            Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => 1
            )
        ),

    Legacies_Empire::ID_SHIP_BATTLESHIP => array(
        Legacies_Empire::ID_COMBAT_SHIELDS    => 200,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 1000,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_CRUISER              => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP           => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP          => 1,
            Legacies_Empire::ID_SHIP_RECYCLER             => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE            => 5,
            Legacies_Empire::ID_SHIP_BOMBER               => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE      => 5,
            Legacies_Empire::ID_SHIP_DESTRUCTOR           => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR           => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER        => 1,
            Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER   => 8,
            Legacies_Empire::ID_DEFENSE_LIGHT_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_HEAVY_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_ION_CANNON        => 1,
            Legacies_Empire::ID_DEFENSE_GAUSS_CANNON      => 1,
            Legacies_Empire::ID_DEFENSE_PLASMA_TURRET     => 1,
            Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => 1,
            Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => 1
            )
        ),

    Legacies_Empire::ID_SHIP_COLONY_SHIP => array(
        Legacies_Empire::ID_COMBAT_SHIELDS    => 100,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 50,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_CRUISER              => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP           => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP          => 1,
            Legacies_Empire::ID_SHIP_RECYCLER             => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE            => 5,
            Legacies_Empire::ID_SHIP_BOMBER               => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE      => 5,
            Legacies_Empire::ID_SHIP_DESTRUCTOR           => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR           => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER        => 1,
            Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER   => 1,
            Legacies_Empire::ID_DEFENSE_LIGHT_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_HEAVY_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_ION_CANNON        => 1,
            Legacies_Empire::ID_DEFENSE_GAUSS_CANNON      => 1,
            Legacies_Empire::ID_DEFENSE_PLASMA_TURRET     => 1,
            Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => 1,
            Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => 1
            )
        ),

    Legacies_Empire::ID_SHIP_RECYCLER => array(
        Legacies_Empire::ID_COMBAT_SHIELDS    => 10,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 1,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_CRUISER              => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP           => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP          => 1,
            Legacies_Empire::ID_SHIP_RECYCLER             => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE            => 5,
            Legacies_Empire::ID_SHIP_BOMBER               => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE      => 5,
            Legacies_Empire::ID_SHIP_DESTRUCTOR           => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR           => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER        => 1,
            Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER   => 1,
            Legacies_Empire::ID_DEFENSE_LIGHT_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_HEAVY_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_ION_CANNON        => 1,
            Legacies_Empire::ID_DEFENSE_GAUSS_CANNON      => 1,
            Legacies_Empire::ID_DEFENSE_PLASMA_TURRET     => 1,
            Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => 1,
            Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => 1
            )
        ),

    Legacies_Empire::ID_SHIP_SPY_DRONE => array(
        Legacies_Empire::ID_COMBAT_SHIELDS    => 0,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 0,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT      => 0,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT      => 0,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER        => 0,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER        => 0,
            Legacies_Empire::ID_SHIP_CRUISER              => 0,
            Legacies_Empire::ID_SHIP_BATTLESHIP           => 0,
            Legacies_Empire::ID_SHIP_COLONY_SHIP          => 0,
            Legacies_Empire::ID_SHIP_RECYCLER             => 0,
            Legacies_Empire::ID_SHIP_SPY_DRONE            => 0,
            Legacies_Empire::ID_SHIP_BOMBER               => 0,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE      => 0,
            Legacies_Empire::ID_SHIP_DESTRUCTOR           => 0,
            Legacies_Empire::ID_SHIP_DEATH_STAR           => 0,
            Legacies_Empire::ID_SHIP_BATTLECRUISER        => 0,
            Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER   => 0,
            Legacies_Empire::ID_DEFENSE_LIGHT_LASER       => 0,
            Legacies_Empire::ID_DEFENSE_HEAVY_LASER       => 0,
            Legacies_Empire::ID_DEFENSE_ION_CANNON        => 0,
            Legacies_Empire::ID_DEFENSE_GAUSS_CANNON      => 0,
            Legacies_Empire::ID_DEFENSE_PLASMA_TURRET     => 0,
            Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => 0,
            Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => 0
            )
        ),

    Legacies_Empire::ID_SHIP_BOMBER => array(
        Legacies_Empire::ID_COMBAT_SHIELDS => 500,
        Legacies_Empire::ID_COMBAT_FIREPOWER => 1000,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_CRUISER              => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP           => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP          => 1,
            Legacies_Empire::ID_SHIP_RECYCLER             => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE            => 5,
            Legacies_Empire::ID_SHIP_BOMBER               => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE      => 5,
            Legacies_Empire::ID_SHIP_DESTRUCTOR           => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR           => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER        => 1,
            Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER   => 20,
            Legacies_Empire::ID_DEFENSE_LIGHT_LASER       => 20,
            Legacies_Empire::ID_DEFENSE_HEAVY_LASER       => 10,
            Legacies_Empire::ID_DEFENSE_ION_CANNON        => 1,
            Legacies_Empire::ID_DEFENSE_GAUSS_CANNON      => 10,
            Legacies_Empire::ID_DEFENSE_PLASMA_TURRET     => 1,
            Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => 1,
            Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => 1
            )
        ),

    Legacies_Empire::ID_SHIP_SOLAR_SATELLITE => array(
        Legacies_Empire::ID_COMBAT_SHIELDS    => 10,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 1,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_CRUISER              => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP           => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP          => 1,
            Legacies_Empire::ID_SHIP_RECYCLER             => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE            => 1,
            Legacies_Empire::ID_SHIP_BOMBER               => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE      => 0,
            Legacies_Empire::ID_SHIP_DESTRUCTOR           => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR           => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER        => 1,
            Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER   => 1,
            Legacies_Empire::ID_DEFENSE_LIGHT_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_HEAVY_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_ION_CANNON        => 1,
            Legacies_Empire::ID_DEFENSE_GAUSS_CANNON      => 1,
            Legacies_Empire::ID_DEFENSE_PLASMA_TURRET     => 1,
            Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => 1,
            Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => 1
            )
        ),

    Legacies_Empire::ID_SHIP_DESTRUCTOR => array(
        Legacies_Empire::ID_COMBAT_SHIELDS    => 500,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 2000,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT      => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_CRUISER              => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP           => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP          => 1,
            Legacies_Empire::ID_SHIP_RECYCLER             => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE            => 5,
            Legacies_Empire::ID_SHIP_BOMBER               => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE      => 5,
            Legacies_Empire::ID_SHIP_DESTRUCTOR           => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR           => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER        => 2,
            Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER   => 1,
            Legacies_Empire::ID_DEFENSE_LIGHT_LASER       => 10,
            Legacies_Empire::ID_DEFENSE_HEAVY_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_ION_CANNON        => 1,
            Legacies_Empire::ID_DEFENSE_GAUSS_CANNON      => 1,
            Legacies_Empire::ID_DEFENSE_PLASMA_TURRET     => 1,
            Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => 1,
            Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => 1
            )
        ),

    Legacies_Empire::ID_SHIP_DEATH_STAR => array(
        Legacies_Empire::ID_COMBAT_SHIELDS    => 50000,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 200000,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT      => 250,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT      => 250,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER        => 200,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER        => 100,
            Legacies_Empire::ID_SHIP_CRUISER              => 33,
            Legacies_Empire::ID_SHIP_BATTLESHIP           => 30,
            Legacies_Empire::ID_SHIP_COLONY_SHIP          => 250,
            Legacies_Empire::ID_SHIP_RECYCLER             => 250,
            Legacies_Empire::ID_SHIP_SPY_DRONE            => 1250,
            Legacies_Empire::ID_SHIP_BOMBER               => 25,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE      => 1250,
            Legacies_Empire::ID_SHIP_DESTRUCTOR           => 5,
            Legacies_Empire::ID_SHIP_DEATH_STAR           => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER        => 15,
            Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER   => 200,
            Legacies_Empire::ID_DEFENSE_LIGHT_LASER       => 200,
            Legacies_Empire::ID_DEFENSE_HEAVY_LASER       => 100,
            Legacies_Empire::ID_DEFENSE_ION_CANNON        => 50,
            Legacies_Empire::ID_DEFENSE_GAUSS_CANNON      => 100,
            Legacies_Empire::ID_DEFENSE_PLASMA_TURRET     => 1,
            Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => 1,
            Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => 1
            )
        ),

    Legacies_Empire::ID_SHIP_BATTLECRUISER => array(
        Legacies_Empire::ID_COMBAT_SHIELDS    => 400,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 700,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT      => 3,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT      => 3,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER        => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER        => 4,
            Legacies_Empire::ID_SHIP_CRUISER              => 4,
            Legacies_Empire::ID_SHIP_BATTLESHIP           => 7,
            Legacies_Empire::ID_SHIP_COLONY_SHIP          => 1,
            Legacies_Empire::ID_SHIP_RECYCLER             => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE            => 5,
            Legacies_Empire::ID_SHIP_BOMBER               => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE      => 5,
            Legacies_Empire::ID_SHIP_DESTRUCTOR           => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR           => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER        => 1,
            Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER   => 1,
            Legacies_Empire::ID_DEFENSE_LIGHT_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_HEAVY_LASER       => 1,
            Legacies_Empire::ID_DEFENSE_ION_CANNON        => 1,
            Legacies_Empire::ID_DEFENSE_GAUSS_CANNON      => 1,
            Legacies_Empire::ID_DEFENSE_PLASMA_TURRET     => 1,
            Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => 1,
            Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => 1
            )
        ),
// }}}

//
// Defense combat capacities
// {{{
    Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER => array (
        Legacies_Empire::ID_COMBAT_SHIELDS    => 20,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 80,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER   => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER   => 1,
            Legacies_Empire::ID_SHIP_CRUISER         => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP      => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP     => 1,
            Legacies_Empire::ID_SHIP_RECYCLER        => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE       => 5,
            Legacies_Empire::ID_SHIP_BOMBER          => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE => 0,
            Legacies_Empire::ID_SHIP_DESTRUCTOR      => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR      => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER   => 1
            )
        ),

    Legacies_Empire::ID_DEFENSE_LIGHT_LASER => array (
        Legacies_Empire::ID_COMBAT_SHIELDS    => 25,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 100,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER   => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER   => 1,
            Legacies_Empire::ID_SHIP_CRUISER         => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP      => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP     => 1,
            Legacies_Empire::ID_SHIP_RECYCLER        => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE       => 5,
            Legacies_Empire::ID_SHIP_BOMBER          => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE => 0,
            Legacies_Empire::ID_SHIP_DESTRUCTOR      => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR      => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER   => 1
            )
        ),

    Legacies_Empire::ID_DEFENSE_HEAVY_LASER => array (
        Legacies_Empire::ID_COMBAT_SHIELDS    => 100,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 250,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER   => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER   => 1,
            Legacies_Empire::ID_SHIP_CRUISER         => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP      => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP     => 1,
            Legacies_Empire::ID_SHIP_RECYCLER        => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE       => 5,
            Legacies_Empire::ID_SHIP_BOMBER          => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE => 0,
            Legacies_Empire::ID_SHIP_DESTRUCTOR      => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR      => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER   => 1
            )
        ),

    Legacies_Empire::ID_DEFENSE_ION_CANNON => array(
        Legacies_Empire::ID_COMBAT_SHIELDS    => 200,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 1100,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER   => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER   => 1,
            Legacies_Empire::ID_SHIP_CRUISER         => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP      => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP     => 1,
            Legacies_Empire::ID_SHIP_RECYCLER        => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE       => 5,
            Legacies_Empire::ID_SHIP_BOMBER          => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE => 0,
            Legacies_Empire::ID_SHIP_DESTRUCTOR      => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR      => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER   => 1
            )
        ),

    Legacies_Empire::ID_DEFENSE_GAUSS_CANNON => array (
        Legacies_Empire::ID_COMBAT_SHIELDS    => 500,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 150,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER   => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER   => 1,
            Legacies_Empire::ID_SHIP_CRUISER         => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP      => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP     => 1,
            Legacies_Empire::ID_SHIP_RECYCLER        => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE       => 5,
            Legacies_Empire::ID_SHIP_BOMBER          => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE => 0,
            Legacies_Empire::ID_SHIP_DESTRUCTOR      => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR      => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER   => 1
            )
        ),

    Legacies_Empire::ID_DEFENSE_PLASMA_TURRET => array(
        Legacies_Empire::ID_COMBAT_SHIELDS     => 300,
         Legacies_Empire::ID_COMBAT_FIREPOWER  => 3000,
         Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER   => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER   => 1,
            Legacies_Empire::ID_SHIP_CRUISER         => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP      => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP     => 1,
            Legacies_Empire::ID_SHIP_RECYCLER        => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE       => 5,
            Legacies_Empire::ID_SHIP_BOMBER          => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE => 0,
            Legacies_Empire::ID_SHIP_DESTRUCTOR      => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR      => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER   => 1
            )
        ),

    Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME => array(
        Legacies_Empire::ID_COMBAT_SHIELDS    => 2000,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 1,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER   => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER   => 1,
            Legacies_Empire::ID_SHIP_CRUISER         => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP      => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP     => 1,
            Legacies_Empire::ID_SHIP_RECYCLER        => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE       => 5,
            Legacies_Empire::ID_SHIP_BOMBER          => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE => 0,
            Legacies_Empire::ID_SHIP_DESTRUCTOR      => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR      => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER   => 1
            )
        ),

    Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME => array(
        Legacies_Empire::ID_COMBAT_SHIELDS    => 2000,
        Legacies_Empire::ID_COMBAT_FIREPOWER  => 1,
        Legacies_Empire::ID_COMBAT_RAPID_FIRE => array(
            Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT => 1,
            Legacies_Empire::ID_SHIP_LARGE_TRANSPORT => 1,
            Legacies_Empire::ID_SHIP_LIGHT_FIGHTER   => 1,
            Legacies_Empire::ID_SHIP_HEAVY_FIGHTER   => 1,
            Legacies_Empire::ID_SHIP_CRUISER         => 1,
            Legacies_Empire::ID_SHIP_BATTLESHIP      => 1,
            Legacies_Empire::ID_SHIP_COLONY_SHIP     => 1,
            Legacies_Empire::ID_SHIP_RECYCLER        => 1,
            Legacies_Empire::ID_SHIP_SPY_DRONE       => 5,
            Legacies_Empire::ID_SHIP_BOMBER          => 1,
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE => 0,
            Legacies_Empire::ID_SHIP_DESTRUCTOR      => 1,
            Legacies_Empire::ID_SHIP_DEATH_STAR      => 1,
            Legacies_Empire::ID_SHIP_BATTLECRUISER   => 1
            )
        ),


    Legacies_Empire::ID_SPECIAL_ANTIBALLISTIC_MISSILE => array(
        Legacies_Empire::ID_COMBAT_SHIELDS   => 1,
        Legacies_Empire::ID_COMBAT_FIREPOWER => 1
        ),

    Legacies_Empire::ID_SPECIAL_INTERPLANETARY_MISSILE => array(
        Legacies_Empire::ID_COMBAT_SHIELDS   => 1,
        Legacies_Empire::ID_COMBAT_FIREPOWER => 12000
        )
// }}}
    );

$ProdGrid = array(
//
// Buildings production
// {{{
    Legacies_Empire::ID_BUILDING_METAL_MINE => array(
        Legacies_Empire::RESOURCE_METAL      => 40,
        Legacies_Empire::RESOURCE_CRISTAL    => 10,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1.5,
        Legacies_Empire::RESOURCE_FORMULA    => array(
           	Legacies_Empire::RESOURCE_METAL     => 'return (30 * $BuildLevel * pow((1.1), $BuildLevel)) * (0.1 * $BuildLevelFactor);',
            Legacies_Empire::RESOURCE_CRISTAL   => 'return "0";',
            Legacies_Empire::RESOURCE_DEUTERIUM => 'return "0";',
            Legacies_Empire::RESOURCE_ENERGY    => 'return -(10 * $BuildLevel * pow((1.1), $BuildLevel)) * (0.1 * $BuildLevelFactor);'
            )
        ),

    Legacies_Empire::ID_BUILDING_CRISTAL_MINE => array(
        Legacies_Empire::RESOURCE_METAL      => 30,
        Legacies_Empire::RESOURCE_CRISTAL    => 15,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1.6,
        Legacies_Empire::RESOURCE_FORMULA    => array(
            Legacies_Empire::RESOURCE_METAL     => 'return "0";',
            Legacies_Empire::RESOURCE_CRISTAL   => 'return (20 * $BuildLevel * pow((1.1), $BuildLevel)) * (0.1 * $BuildLevelFactor);',
            Legacies_Empire::RESOURCE_DEUTERIUM => 'return "0";',
            Legacies_Empire::RESOURCE_ENERGY    => 'return -(10 * $BuildLevel * pow((1.1), $BuildLevel)) * (0.1 * $BuildLevelFactor);'
            )
        ),

    Legacies_Empire::ID_BUILDING_DEUTERIUM_SYNTHETISER => array(
        Legacies_Empire::RESOURCE_METAL      => 150,
        Legacies_Empire::RESOURCE_CRISTAL    => 50,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1.5,
        Legacies_Empire::RESOURCE_FORMULA    => array(
            Legacies_Empire::RESOURCE_METAL     => 'return "0";',
            Legacies_Empire::RESOURCE_CRISTAL   => 'return "0";',
            Legacies_Empire::RESOURCE_DEUTERIUM => 'return ((10 * $BuildLevel * pow((1.1), $BuildLevel)) * (-0.002 * $BuildTemp + 1.28)) * (0.1 * $BuildLevelFactor);',
            Legacies_Empire::RESOURCE_ENERGY    => 'return -(30 * $BuildLevel * pow((1.1), $BuildLevel)) * (0.1 * $BuildLevelFactor);'
            )
        ),

    Legacies_Empire::ID_BUILDING_SOLAR_PLANT => array(
        Legacies_Empire::RESOURCE_METAL      => 50,
        Legacies_Empire::RESOURCE_CRISTAL    => 20,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1.5,
        Legacies_Empire::RESOURCE_FORMULA    => array(
            Legacies_Empire::RESOURCE_METAL     => 'return "0";',
            Legacies_Empire::RESOURCE_CRISTAL   => 'return "0";',
            Legacies_Empire::RESOURCE_DEUTERIUM => 'return "0";',
            Legacies_Empire::RESOURCE_ENERGY    => 'return (20 * $BuildLevel * pow((1.1), $BuildLevel)) * (0.1 * $BuildLevelFactor);'
            )
        ),

    Legacies_Empire::ID_BUILDING_FUSION_REACTOR => array(
        Legacies_Empire::RESOURCE_METAL      => 500,
        Legacies_Empire::RESOURCE_CRISTAL    => 200,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 100,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1.8,
        Legacies_Empire::RESOURCE_FORMULA    => array(
            Legacies_Empire::RESOURCE_METAL     => 'return "0";',
            Legacies_Empire::RESOURCE_CRISTAL   => 'return "0";',
            Legacies_Empire::RESOURCE_DEUTERIUM => 'return -(10 * $BuildLevel * pow((1.1), $BuildLevel)) * (0.1 * $BuildLevelFactor);',
            Legacies_Empire::RESOURCE_ENERGY    => 'return (50 * $BuildLevel * pow((1.1), $BuildLevel)) * (0.1 * $BuildLevelFactor);'
            )
        ),
// }}}

//
// Ships production
// {{{
    Legacies_Empire::ID_SHIP_SOLAR_SATELLITE => array(
        Legacies_Empire::RESOURCE_METAL      => 0,
        Legacies_Empire::RESOURCE_CRISTAL    => 2000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 500,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 0.5,
        Legacies_Empire::RESOURCE_FORMULA    => array(
            Legacies_Empire::RESOURCE_METAL     => 'return "0";',
            Legacies_Empire::RESOURCE_CRISTAL   => 'return "0";',
            Legacies_Empire::RESOURCE_DEUTERIUM => 'return "0";',
            Legacies_Empire::RESOURCE_ENERGY    => 'return (($BuildTemp / 4) + 20) * $BuildLevel * (0.1 * $BuildLevelFactor);'
            )
        )
// }}}
    );

$reslist = array(
//
// Buildings type
// {{{
    Legacies_Empire::TYPE_BUILDING => array(
        Legacies_Empire::ID_BUILDING_METAL_MINE,
        Legacies_Empire::ID_BUILDING_CRISTAL_MINE,
        Legacies_Empire::ID_BUILDING_DEUTERIUM_SYNTHETISER,
        Legacies_Empire::ID_BUILDING_SOLAR_PLANT,
        Legacies_Empire::ID_BUILDING_FUSION_REACTOR,
        Legacies_Empire::ID_BUILDING_ROBOTIC_FACTORY,
        Legacies_Empire::ID_BUILDING_NANITE_FACTORY,
        Legacies_Empire::ID_BUILDING_SHIPYARD,
        Legacies_Empire::ID_BUILDING_METAL_STORAGE,
        Legacies_Empire::ID_BUILDING_CRISTAL_STORAGE,
        Legacies_Empire::ID_BUILDING_DEUTERIUM_TANK,
        Legacies_Empire::ID_BUILDING_RESEARCH_LAB,
        Legacies_Empire::ID_BUILDING_TERRAFORMER,
        Legacies_Empire::ID_BUILDING_ALLIANCE_DEPOT,
        Legacies_Empire::ID_BUILDING_LUNAR_BASE,
        Legacies_Empire::ID_BUILDING_SENSOR_PHALANX,
        Legacies_Empire::ID_BUILDING_JUMP_GATE,
        Legacies_Empire::ID_BUILDING_MISSILE_SILO
        ),
// }}}

//
// Research types
// {{{
    Legacies_Empire::TYPE_RESEARCH => array(
        Legacies_Empire::ID_RESEARCH_ESPIONAGE_TECHNOLOGY,
        Legacies_Empire::ID_RESEARCH_COMPUTER_TECHNOLOGY,
        Legacies_Empire::ID_RESEARCH_WEAPON_TECHNOLOGY,
        Legacies_Empire::ID_RESEARCH_SHIELDING_TECHNOLOGY,
        Legacies_Empire::ID_RESEARCH_ARMOUR_TECHNOLOGY,
        Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY,
        Legacies_Empire::ID_RESEARCH_HYPERSPACE_TECHNOLOGY,
        Legacies_Empire::ID_RESEARCH_COMBUSTION_DRIVE,
        Legacies_Empire::ID_RESEARCH_IMPULSE_DRIVE,
        Legacies_Empire::ID_RESEARCH_HYPERSPACE_DRIVE,
        Legacies_Empire::ID_RESEARCH_LASER_TECHNOLOGY,
        Legacies_Empire::ID_RESEARCH_ION_TECHNOLOGY,
        Legacies_Empire::ID_RESEARCH_PLASMA_TECHNOLOGY,
        Legacies_Empire::ID_RESEARCH_INTERGALACTIC_RESEARCH_NETWORK,
        Legacies_Empire::ID_RESEARCH_EXPEDITION_TECHNOLOGY,
        Legacies_Empire::ID_RESEARCH_GRAVITON_TECHNOLOGY
        ),
// }}}

//
// Ship types
// {{{
    Legacies_Empire::TYPE_SHIP => array(
        Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT,
        Legacies_Empire::ID_SHIP_LARGE_TRANSPORT,
        Legacies_Empire::ID_SHIP_LIGHT_FIGHTER,
        Legacies_Empire::ID_SHIP_HEAVY_FIGHTER,
        Legacies_Empire::ID_SHIP_CRUISER,
        Legacies_Empire::ID_SHIP_BATTLESHIP,
        Legacies_Empire::ID_SHIP_COLONY_SHIP,
        Legacies_Empire::ID_SHIP_RECYCLER,
        Legacies_Empire::ID_SHIP_SPY_DRONE,
        Legacies_Empire::ID_SHIP_BOMBER,
        Legacies_Empire::ID_SHIP_SOLAR_SATELLITE,
        Legacies_Empire::ID_SHIP_DESTRUCTOR,
        Legacies_Empire::ID_SHIP_DEATH_STAR,
        Legacies_Empire::ID_SHIP_BATTLECRUISER
        ),
// }}}

//
// Defense types
// {{{
    Legacies_Empire::TYPE_DEFENSE => array(
        Legacies_Empire::ID_DEFENSE_ROCKET_LAUNCHER,
        Legacies_Empire::ID_DEFENSE_LIGHT_LASER,
        Legacies_Empire::ID_DEFENSE_HEAVY_LASER,
        Legacies_Empire::ID_DEFENSE_ION_CANNON,
        Legacies_Empire::ID_DEFENSE_GAUSS_CANNON,
        Legacies_Empire::ID_DEFENSE_PLASMA_TURRET,
        Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME,
        Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME,
        Legacies_Empire::ID_SPECIAL_ANTIBALLISTIC_MISSILE,
        Legacies_Empire::ID_SPECIAL_INTERPLANETARY_MISSILE
        ),
// }}}

//
// Officers type
// {{{
    Legacies_Empire::TYPE_OFFICER => array(
        601, 602, 603,
        604, 605, 606,
        607, 608, 609,
        610, 611, 612,
        613, 614, 615
        ),
// }}}

//
// Production-able elements
// {{{
    Legacies_Empire::TYPE_PRODUCTION => array(
        Legacies_Empire::ID_BUILDING_METAL_MINE,
        Legacies_Empire::ID_BUILDING_CRISTAL_MINE,
        Legacies_Empire::ID_BUILDING_DEUTERIUM_SYNTHETISER,
        Legacies_Empire::ID_BUILDING_SOLAR_PLANT,
        Legacies_Empire::ID_BUILDING_FUSION_REACTOR,
        Legacies_Empire::ID_SHIP_SOLAR_SATELLITE
        )
// }}}
    );
