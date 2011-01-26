<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-2010, XNova Support Team <http://www.xnova-ng.org>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */

$lang['Version']     = 'Release';
$lang['Description'] = 'Description';
$lang['changelog']   = array(


'<font color="lime">0.8e</font>' => '- ADD : Function SecureArray() for POST and GET variables (Bono)
- ADD : Administrators now choose the background of the banner... (Bono)
- ADD : Mode vacancy + Production to 0 + Construction forbidden (Prethorian)
',

'<font color="lime">0.8d</font>' => '- ADD : Administrators now see which page is accessed by any player (Bono)
- ADD : Bot anti multi-account, customization and enable / disable at will...  (Bono)
- ADD : Policy customization server started...  (Bono)
- ADD : Ability to enable / customize a personalized link (Bono)
- ADD : Ability to show / disable links in the menu (Bono)
- FIX : Link alliances corrected
- NEW: Destruction of moons (juju67)
- NEW: Staying in an ally (juju67)
- FIX: Link to the galaxy player in the search function',



'0.8c' => 'Modules and corrections (e-Zobar)
- NEW: Function extended copyright
- NEW: Builder Banner-profile (for forum signatures) in \'Overview\' (optional)
- ADD: Declaration of multi-accounts
- FIX: Many visual errors: admin chat, rules
- FIX: Variable root_path on all pages
- FIX: Security Admin Panel
- FIX: Illustrations officers missing
- ADD: Welcome message after the registration (Tom1991)
- ADD: Display points raids (Tom1991)',

'0.8b' => 'Bug fixes (Chlorel)
- ADD: Function reset the player who cheats
- FIX: List of planets sorted in the empire
- FIX: List of planets sorted in the overview also
- FIX: Update of all the planets pass through the overview and the view empire',

'0.8a' => 'Bug fixes (Chlorel)
- FIX: message.php does more SQL errors when there are no messages
- FIX: Correction page records in order to take into account or not admins
- NEW: phalanx recoded version ... be tested from every angle
- FIX: More opportunity to spy without probes
- MOD: Formatting numbers in the reports of fightin
- MOD: Changing template for login that requires display with only 1 <body>
- FIX: Removing a possible cause of error MySQL
- FIX: Extraction of the last chains of general view
- FIX: Surprise for the cheater to the merchant !
- FIX: DeleSelectedUser function also clears the planets now
- ADD: Page rules (XxmangaxX)',

'0.8' => 'News (Chlorel)
- FIX: Skin on new installer
- DIV: Aesthetic work on all files
- FIX: Forget change call to some functions newly modified',

'0.7m' => 'Bug fixes (Chlorel)
- ADD: Interface activation protection planets
- FIX: The moons are again a good player and not a "one" player when they are created from administration
- FIX: Events Overview fleet (the personal for the moment) now use the css (default.css)
- MOD: Adaption of various functions is the use of css
- FIX: Internat chat (various adjustments) (e-Zobar)',

'0.7k' => 'Bug fixes (Chlorel)
- FIX: Back to fleet transport
- ADD: Protection of the planets of Administrators
- MOD: List of players in the admin section links on headers to sort
- MOD: Page general admin section with links to the headers to sort
- FIX: When using a skin other than the XNova, it also applies in admin section
- FIX: Adding the moon in the administration panel (e-Zobar)
- ADD: Transfer mode in the installer (e-Zobar)',

'0.7j' => 'Bug fixes (Chlorel)
- FIX: You can remove a new construction of the tail manufacturing
- FIX: It may again send a fleet to travel between two planets
- FIX: The list of shortcuts in the selection of the target work again
- FIX: We can not destroy a building that you do not have
- ADD: All beautiful new installer (e-Zobar)
- FIX: hieroglyphs (e-Zobar)',

'0.7i' => 'Bug fixes (Chlorel)
- Remove cheat +1
- Adjusting length of flights / consumer fleets between PHP and Java code
- Sort colonies by the player options
- Preparation multiskin in options
- Various developments in the code for Administrators (List messages, List of Players)
- Work on the Skin (e-Zobar)
- Work on installer (e-Zobar)',

'0.7h' => 'Bug fixes (Chlorel)
- Interface Officer done
- Adding blocking "refresh meta"
- Adjusting various Bugs
- Fixed various texts (flousedid)
- Correction of visual defects (e-Zobar)',

'0.7g' => 'Fixed various (Chlorel)
- Changing the order of processing the list of construction of buildings
- Compliance code for a single command "echo"
- Some modules rewritten
- Fixed bug of doubling of fleet
- Automatic update of the size bins, output of mines and energy
- Various modifications in the admin section (e-Zobar)
- Changing heavy style XNova (e-Zobar)',

'0.7f' => 'Information and jump gate: (Chlorel)
- New information page completely redesigned
- New interface jumping gate is integrated information page
- New management of the display of rapid fire in the information page
- Multitude of correction made by e-Zobar',

'0.7e' => 'Everywhere and nowhere : (Chlorel)
- New Registration page (standard setting)
- New page records (compliance with the site)
- Edit kernel (there are not bad but can not explain all there and all manner not
  many people would be able to understand)',

'0.7d' => 'Part admin : (e-Zobar)
- menage in a lot of modules
- alignment menu style of operation of the site
- complete translation of what was not yet in French',

'0.7c' => 'Statistics : (Chlorel)
- Remove calls database from the old system of Statistics
- Bug Unable to make defenses or elements of the fleet does not use metal
- Limiting the number of ships or defenses constructible line
- Bug error when selecting the planet by combo
- Updating installer',

'0.7b' => 'Statistics : (Chlorel)
- Rewrite of the stats page (called by the user)
- The alliance stat displayed !
- Write the generator admin stats
- Separation of the stats of the user record (the stats on their own database)',

'0.7a' => 'Miscellaneous : (Chlorel)
- Bug Technologies (the search term appears again when we return in the laboratory)
- Bug Missiles (a dish made of the scope of interplanetary missiles, and establishing the limits of manufacturing over the size of the silo)
- Bug Scope phalanx corrected (you can not phalanger entire galaxy)
- Bug correction in the consumption of deuterium when passing by the menu galaxy',

'0.7' => 'Building :
- Rewrite of the page
- Modularization
- Fixed bug statistics
- Debug from the list of buildings construction
- Various alterations (Chlorel)
- Various debug (over water) (e-Zobar)
- Adding function on the main view (Tom1991)',

'0.6b' => 'Miscellaneous :
- Correction & Additions functions for officers (Tom1991)
- Menage in java scripts included (Chlorel)
- Fixed various bugs (Chlorel)
- Implementation version 0.5 of the list buildings (Chlorel)',

'0.6a' => 'Graphics :
- Adding Skin XNova (e-Zobar)
- Correction of adverse effects (e-Zobar)
- Adding unintended bugs (Chlorel)',

'0.6' => 'Galaxy (continue): (by Chlorel)
- Editing and rewriting of flottenajax.php
- Changing routine javascript and ajax to allow dynamic modification of the galaxy
- Fixes bug in some links popups
- Definition new call protocol, now even on the moon, the galaxy appears from a good position
- Fixed calls recycling
- Adding module Officer" (by Tom1991)',

'0.5' => 'Galaxy : (by Chlorel)
- Decoupage old module
- Changing system to generate the popup to the galaxy
- Modularization of the generation of page',

'0.4' => 'Overview : (by Chlorel)
- Formatting old module
- Managing the display of personal fleet
- Changing display of the moons when presentes
- Fixed bug rename the moons (so they are actually known)',

'0.3' => 'Fleet Management : (by Chlorel)
- Modification / modularization / documentation loop flight management
- Changing Mission of spying
- Changing Mission Colonization
- Changing Mission Transport
- Changing Mission Staying
- Changing Mission Recycling',

'0.2' => 'Corrections
- Additions to version 0.5 of Exploration (by Tom1991)
- Modification of the loop control fleets 10%(by Chlorel)',

'0.1' => 'Merge version of fleet :
- Implementation of the strategy development
- Establishment of new pages of fleet management',

'0.0' => 'Basic version:
- Basis of repack (Tom1991)',
);

