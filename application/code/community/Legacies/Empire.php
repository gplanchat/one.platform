<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license Modified BSD
 * @see https://github.com/gplanchat/one.platform
 *
 * Copyright (c) 2009-2010, Grégory PLANCHAT <g.planchat at gmail.com>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *     - Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *
 *     - Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *
 *     - Neither the name of Grégory PLANCHAT nor the names of its
 *       contributors may be used to endorse or promote products derived from this
 *       software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing One.Platform.
 *
 */

/**
 *
 * @author Greg
 *
 */
class Legacies_Empire
{
    const TYPE_BUILDING   = 'build';
    const TYPE_RESEARCH   = 'tech';
    const TYPE_SHIP       = 'fleet';
    const TYPE_DEFENSE    = 'defense';
    const TYPE_SPECIAL    = 'defense';
    const TYPE_OFFICER    = 'officier';
    const TYPE_PRODUCTION = 'prod';

    const RESOURCE_METAL      = 'metal';
    const RESOURCE_CRISTAL    = 'crystal';
    const RESOURCE_DEUTERIUM  = 'deuterium';
    const RESOURCE_ENERGY     = 'energy';
    const RESOURCE_MULTIPLIER = 'factor';
    const RESOURCE_FORMULA    = 'formule';

    const SHIPS_CONSUMPTION_PRIMARY   = 'consumption';
    const SHIPS_CELERITY_PRIMARY      = 'speed';
    const SHIPS_CONSUMPTION_SECONDARY = 'consumption2';
    const SHIPS_CELERITY_SECONDARY    = 'speed2';
    const SHIPS_CAPACITY              = 'capacity';

    const ID_BUILDING_METAL_MINE            = 1;
    const ID_BUILDING_CRISTAL_MINE          = 2;
    const ID_BUILDING_DEUTERIUM_SYNTHETISER = 3;
    const ID_BUILDING_SOLAR_PLANT           = 4;
    const ID_BUILDING_FUSION_REACTOR        = 12;
    const ID_BUILDING_ROBOTIC_FACTORY       = 14;
    const ID_BUILDING_NANITE_FACTORY        = 15;
    const ID_BUILDING_SHIPYARD              = 21;
    const ID_BUILDING_METAL_STORAGE         = 22;
    const ID_BUILDING_CRISTAL_STORAGE       = 23;
    const ID_BUILDING_DEUTERIUM_TANK        = 24;
    const ID_BUILDING_RESEARCH_LAB          = 31;
    const ID_BUILDING_TERRAFORMER           = 33;
    const ID_BUILDING_ALLIANCE_DEPOT        = 34;
    const ID_BUILDING_LUNAR_BASE            = 41;
    const ID_BUILDING_SENSOR_PHALANX        = 42;
    const ID_BUILDING_JUMP_GATE             = 43;
    const ID_BUILDING_MISSILE_SILO          = 44;

    const ID_RESEARCH_ESPIONAGE_TECHNOLOGY           = 106;
    const ID_RESEARCH_COMPUTER_TECHNOLOGY            = 108;
    const ID_RESEARCH_WEAPON_TECHNOLOGY              = 109;
    const ID_RESEARCH_SHIELDING_TECHNOLOGY           = 110;
    const ID_RESEARCH_ARMOUR_TECHNOLOGY              = 111;
    const ID_RESEARCH_ENERGY_TECHNOLOGY              = 113;
    const ID_RESEARCH_HYPERSPACE_TECHNOLOGY          = 114;
    const ID_RESEARCH_COMBUSTION_DRIVE               = 115;
    const ID_RESEARCH_IMPULSE_DRIVE                  = 117;
    const ID_RESEARCH_HYPERSPACE_DRIVE               = 118;
    const ID_RESEARCH_LASER_TECHNOLOGY               = 120;
    const ID_RESEARCH_ION_TECHNOLOGY                 = 121;
    const ID_RESEARCH_PLASMA_TECHNOLOGY              = 122;
    const ID_RESEARCH_INTERGALACTIC_RESEARCH_NETWORK = 123;
    const ID_RESEARCH_EXPEDITION_TECHNOLOGY          = 124;
    const ID_RESEARCH_ASTROPHYSICS                   = 124;
    const ID_RESEARCH_GRAVITON_TECHNOLOGY            = 199;

    const ID_SHIP_LIGHT_TRANSPORT = 202;
    const ID_SHIP_LARGE_TRANSPORT = 203;
    const ID_SHIP_LIGHT_FIGHTER   = 204;
    const ID_SHIP_HEAVY_FIGHTER   = 205;
    const ID_SHIP_CRUISER         = 206;
    const ID_SHIP_BATTLESHIP      = 207;
    const ID_SHIP_COLONY_SHIP     = 208;
    const ID_SHIP_RECYCLER        = 209;
    const ID_SHIP_SPY_DRONE       = 210;
    const ID_SHIP_BOMBER          = 211;
    const ID_SHIP_SOLAR_SATELLITE = 212;
    const ID_SHIP_DESTRUCTOR      = 213;
    const ID_SHIP_DEATH_STAR      = 214;
    const ID_SHIP_BATTLECRUISER   = 215;

    const ID_DEFENSE_ROCKET_LAUNCHER   = 401;
    const ID_DEFENSE_LIGHT_LASER       = 402;
    const ID_DEFENSE_HEAVY_LASER       = 403;
    const ID_DEFENSE_ION_CANNON        = 404;
    const ID_DEFENSE_GAUSS_CANNON      = 405;
    const ID_DEFENSE_PLASMA_TURRET     = 406;
    const ID_DEFENSE_SMALL_SHIELD_DOME = 407;
    const ID_DEFENSE_LARGE_SHIELD_DOME = 408;

    const ID_SPECIAL_ANTIBALLISTIC_MISSILE  = 502;
    const ID_SPECIAL_INTERPLANETARY_MISSILE = 503;

    const ID_COMBAT_SHIELDS    = 'shield';
    const ID_COMBAT_FIREPOWER  = 'attack';
    const ID_COMBAT_RAPID_FIRE = 'sd';
}