<?php
/**
 * Tis file is part of XNova:Legacies
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
 * GNU General Publiqdsdqc License for more details.
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

require dirname(__FILE__) . DS . 'functions/FlyingFleetHandler.php';
require dirname(__FILE__) . DS . 'functions/MissionCaseAttack.php';
require dirname(__FILE__) . DS . 'functions/MissionCaseStay.php';
require dirname(__FILE__) . DS . 'functions/MissionCaseStayAlly.php';
require dirname(__FILE__) . DS . 'functions/MissionCaseTransport.php';
require dirname(__FILE__) . DS . 'functions/MissionCaseSpy.php';
require dirname(__FILE__) . DS . 'functions/MissionCaseRecycling.php';
require dirname(__FILE__) . DS . 'functions/MissionCaseDestruction.php';
require dirname(__FILE__) . DS . 'functions/MissionCaseColonisation.php';
require dirname(__FILE__) . DS . 'functions/MissionCaseExpedition.php';
require dirname(__FILE__) . DS . 'functions/SendSimpleMessage.php';
require dirname(__FILE__) . DS . 'functions/SpyTarget.php';
require dirname(__FILE__) . DS . 'functions/RestoreFleetToPlanet.php';
require dirname(__FILE__) . DS . 'functions/StoreGoodsToPlanet.php';
require dirname(__FILE__) . DS . 'functions/CheckPlanetBuildingQueue.php';
require dirname(__FILE__) . DS . 'functions/CheckPlanetUsedFields.php';
require dirname(__FILE__) . DS . 'functions/CreateOneMoonRecord.php';
require dirname(__FILE__) . DS . 'functions/CreateOnePlanetRecord.php';
require dirname(__FILE__) . DS . 'functions/InsertJavaScriptChronoApplet.php';
require dirname(__FILE__) . DS . 'functions/IsTechnologieAccessible.php';
require dirname(__FILE__) . DS . 'functions/GetBuildingTime.php';
require dirname(__FILE__) . DS . 'functions/GetBuildingTimeLevel.php';
require dirname(__FILE__) . DS . 'functions/GetRestPrice.php';
require dirname(__FILE__) . DS . 'functions/GetElementPrice.php';
require dirname(__FILE__) . DS . 'functions/GetBuildingPrice.php';
require dirname(__FILE__) . DS . 'functions/IsElementBuyable.php';
require dirname(__FILE__) . DS . 'functions/CheckCookies.php';
require dirname(__FILE__) . DS . 'functions/ChekUser.php';
require dirname(__FILE__) . DS . 'functions/InsertGalaxyScripts.php';
require dirname(__FILE__) . DS . 'functions/GalaxyCheckFunctions.php';
require dirname(__FILE__) . DS . 'functions/ShowGalaxyRows.php';
require dirname(__FILE__) . DS . 'functions/GetPhalanxRange.php';
require dirname(__FILE__) . DS . 'functions/GetMissileRange.php';
require dirname(__FILE__) . DS . 'functions/GalaxyRowPos.php';
require dirname(__FILE__) . DS . 'functions/GalaxyRowPlanet.php';
require dirname(__FILE__) . DS . 'functions/GalaxyRowPlanetName.php';
require dirname(__FILE__) . DS . 'functions/GalaxyRowMoon.php';
require dirname(__FILE__) . DS . 'functions/GalaxyRowDebris.php';
require dirname(__FILE__) . DS . 'functions/GalaxyRowUser.php';
require dirname(__FILE__) . DS . 'functions/GalaxyRowAlly.php';
require dirname(__FILE__) . DS . 'functions/GalaxyRowActions.php';
require dirname(__FILE__) . DS . 'functions/ShowGalaxySelector.php';
require dirname(__FILE__) . DS . 'functions/ShowGalaxyMISelector.php';
require dirname(__FILE__) . DS . 'functions/ShowGalaxyTitles.php';
require dirname(__FILE__) . DS . 'functions/GalaxyLegendPopup.php';
require dirname(__FILE__) . DS . 'functions/ShowGalaxyFooter.php';
require dirname(__FILE__) . DS . 'functions/GetMaxConstructibleElements.php';
require dirname(__FILE__) . DS . 'functions/GetElementRessources.php';
require dirname(__FILE__) . DS . 'functions/ElementBuildListBox.php';
require dirname(__FILE__) . DS . 'functions/ElementBuildListQueue.php';
require dirname(__FILE__) . DS . 'functions/FleetBuildingPage.php';
require dirname(__FILE__) . DS . 'functions/DefensesBuildingPage.php';
require dirname(__FILE__) . DS . 'functions/ResearchBuildingPage.php';
require dirname(__FILE__) . DS . 'functions/BatimentBuildingPage.php';
require dirname(__FILE__) . DS . 'functions/CheckLabSettingsInQueue.php';
require dirname(__FILE__) . DS . 'functions/InsertBuildListScript.php';
require dirname(__FILE__) . DS . 'functions/AddBuildingToQueue.php';
require dirname(__FILE__) . DS . 'functions/ShowBuildingQueue.php';
require dirname(__FILE__) . DS . 'functions/HandleTechnologieBuild.php';
require dirname(__FILE__) . DS . 'functions/BuildingSavePlanetRecord.php';
require dirname(__FILE__) . DS . 'functions/BuildingSaveUserRecord.php';
require dirname(__FILE__) . DS . 'functions/RemoveBuildingFromQueue.php';
require dirname(__FILE__) . DS . 'functions/CancelBuildingFromQueue.php';
require dirname(__FILE__) . DS . 'functions/SetNextQueueElementOnTop.php';
require dirname(__FILE__) . DS . 'functions/ShowTopNavigationBar.php';
require dirname(__FILE__) . DS . 'functions/SetSelectedPlanet.php';
require dirname(__FILE__) . DS . 'functions/MessageForm.php';
require dirname(__FILE__) . DS . 'functions/PlanetResourceUpdate.php';
require dirname(__FILE__) . DS . 'functions/BuildFlyingFleetTable.php';
require dirname(__FILE__) . DS . 'functions/SendNewPassword.php';
require dirname(__FILE__) . DS . 'functions/HandleElementBuildingQueue.php';
require dirname(__FILE__) . DS . 'functions/UpdatePlanetBatimentQueueList.php';
require dirname(__FILE__) . DS . 'functions/IsOfficierAccessible.php';
require dirname(__FILE__) . DS . 'functions/CheckInputStrings.php';
require dirname(__FILE__) . DS . 'functions/MipCombatEngine.php';
require dirname(__FILE__) . DS . 'functions/DeleteSelectedUser.php';
require dirname(__FILE__) . DS . 'functions/SortUserPlanets.php';
require dirname(__FILE__) . DS . 'functions/BuildFleetEventTable.php';
require dirname(__FILE__) . DS . 'functions/ResetThisFuckingCheater.php';
require dirname(__FILE__) . DS . 'functions/IsVacationMode.php';
require dirname(__FILE__) . DS . 'functions/BBcodeFunction.php';

