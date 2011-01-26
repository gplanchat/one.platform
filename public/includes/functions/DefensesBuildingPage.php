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

function DefensesBuildingPage ( &$CurrentPlanet, $CurrentUser ) {
     global $lang, $resource, $dpath, $_POST;

    if (isset($_POST['fmenge'])) {
        // On vient de Cliquer ' Construire '

        // Et y a une liste de doléances
        // Ici, on sait precisement ce qu'on aimerait bien construire ...

        // Gestion de la place disponible dans les silos !
        $Missiles[502] = $CurrentPlanet[ $resource[502] ];
        $Missiles[503] = $CurrentPlanet[ $resource[503] ];
        $SiloSize      = $CurrentPlanet[ $resource[44] ];
        $MaxMissiles   = $SiloSize * 10;
        // On prend les missiles deja dans la queue de fabrication aussi (ca aide)
        $BuildQueue    = $CurrentPlanet['b_hangar_id'];
        $BuildArray    = explode (";", $BuildQueue);
        for ($QElement = 0; $QElement < count($BuildArray); $QElement++) {
            $ElmentArray = explode (",", $BuildArray[$QElement] );
            if       ($ElmentArray[502] != 0) {
                $Missiles[502] += $ElmentArray[502];
            } elseif ($ElmentArray[503] != 0) {
                $Missiles[503] += $ElmentArray[503];
            }
        }
        foreach($_POST['fmenge'] as $Element => $Count) {
            // Construction d'Element recuperés sur la page de Flotte ...
            // ATTENTION ! La file d'attente Flotte est Commune a celle des Defenses
            // Dans fmenge, on devrait trouver un tableau des elements constructibles etdu nombre d'elements souhaités

            $Element = intval($Element);
            $Count   = intval($Count);
            if ($Count > MAX_FLEET_OR_DEFS_PER_ROW) {
                $Count = MAX_FLEET_OR_DEFS_PER_ROW;
            }


            if ($Count != 0) {
                // Cas particulier (Petit Bouclier et Grand Bouclier
                // ne peuvent exister qu'une seule et unique fois
                $InQueue = strpos ( $CurrentPlanet['b_hangar_id'], $Element.",");
                $IsBuild = ($CurrentPlanet[$resource[407]] >= 1) ? true : false;
                if ($Element == 407 || $Element == 408) {
                    if ($InQueue === false && !$IsBuild) {
                        $Count = 1;
                    }
                }

                // On verifie si on a les technologies necessaires a la construction de l'element
                if ( IsTechnologieAccessible ($CurrentUser, $CurrentPlanet, $Element) ) {
                    // On verifie combien on sait faire de cet element au max
                    $MaxElements   = GetMaxConstructibleElements ( $Element, $CurrentPlanet );

                    // Testons si on a de la place pour ces nouveaux missiles !
                    if ($Element == 502 || $Element == 503) {
                        // Cas particulier des missiles
                        $ActuMissiles  = $Missiles[502] + ( 2 * $Missiles[503] );
                        $MissilesSpace = $MaxMissiles - $ActuMissiles;
                        if ($Element == 502) {
                            if ( $Count > $MissilesSpace ) {
                                $Count = $MissilesSpace;
                            }
                        } else {
                            if ( $Count > floor( $MissilesSpace / 2 ) ) {
                                $Count = floor( $MissilesSpace / 2 );
                            }
                        }
                        if ($Count > $MaxElements) {
                            $Count = $MaxElements;
                        }
                        $Missiles[$Element] += $Count;
                    } else {
                        // Si pas assez de ressources, on ajuste le nombre d'elements
                        if ($Count > $MaxElements) {
                            $Count = $MaxElements;
                        }
                    }

                    $Ressource = GetElementRessources ( $Element, $Count );
                    $BuildTime = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element);
                    if ($Count >= 1) {
                        $CurrentPlanet['metal']           -= $Ressource['metal'];
                        $CurrentPlanet['crystal']         -= $Ressource['crystal'];
                        $CurrentPlanet['deuterium']       -= $Ressource['deuterium'];
                        $CurrentPlanet['b_hangar_id']     .= "". $Element .",". $Count .";";
                    }
                }
            }
        }
    }

    // -------------------------------------------------------------------------------------------------------
    // S'il n'y a pas de Chantier ...
    if ($CurrentPlanet[$resource[21]] == 0) {
        // Veuillez avoir l'obligeance de construire le Chantier Spacial !!
        message($lang['need_hangar'], $lang['tech'][21]);
    }

    // -------------------------------------------------------------------------------------------------------
    // Construction de la page du Chantier (car si j'arrive ici ... c'est que j'ai tout ce qu'il faut pour ...
    $TabIndex  = 0;
    $PageTable = "";
    foreach($lang['tech'] as $Element => $ElementName) {
        if ($Element > 400 && $Element <= 599) {
            if (IsTechnologieAccessible($CurrentUser, $CurrentPlanet, $Element)) {
                // Disponible à la construction

                // On regarde si on peut en acheter au moins 1
                $CanBuildOne         = IsElementBuyable($CurrentUser, $CurrentPlanet, $Element, false);
                // On regarde combien de temps il faut pour construire l'element
                $BuildOneElementTime = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element);
                // Disponibilité actuelle
                $ElementCount        = $CurrentPlanet[$resource[$Element]];
                $ElementNbre         = ($ElementCount == 0) ? "" : " (".$lang['dispo'].": " . pretty_number($ElementCount) . ")";

                // Construction des 3 cases de la ligne d'un element dans la page d'achat !
                // Début de ligne
                $PageTable .= "\n<tr>";

                // Imagette + Link vers la page d'info
                $PageTable .= "<th class=l>";
                $PageTable .= "<a href=infos.".PHPEXT."?gid=".$Element.">";
                $PageTable .= "<img border=0 src=\"".$dpath."gebaeude/".$Element.".gif\" align=top width=120 height=120></a>";
                $PageTable .= "</th>";

                // Description
                $PageTable .= "<td class=l>";
                $PageTable .= "<a href=infos.".PHPEXT."?gid=".$Element.">".$ElementName."</a> ".$ElementNbre."<br>";
                $PageTable .= "".$lang['res']['descriptions'][$Element]."<br>";
                // On affiche le 'prix' avec eventuellement ce qui manque en ressource
                $PageTable .= GetElementPrice($CurrentUser, $CurrentPlanet, $Element, false);
                // On affiche le temps de construction (c'est toujours tellement plus joli)
                $PageTable .= ShowBuildTime($BuildOneElementTime);
                $PageTable .= "</td>";

                // Case nombre d'elements a construire
                $PageTable .= "<th class=k>";
                
                // Si ... Et Seulement si je peux construire je mets la p'tite zone de saisie
                if ($CanBuildOne) {
                    if ($Element == Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME ||
                      $Element == Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME) {
                        $InQueue = strpos($CurrentPlanet['b_hangar_id'], $Element.",");
                        $IsBuild = ($CurrentPlanet[$resource[$Element]] >= 1) ? true : false;
                        $BuildIt = ($InQueue === false && !$IsBuild) ? true : false;
                    } else {
                        $BuildIt = true;
                    }

                    if (!$BuildIt) {
                        $PageTable .= "<font color=\"red\">".$lang['only_one']."</font>";
                    } else {
                        $TabIndex++;
                        $PageTable .= "<input type=text name=fmenge[".$Element."] alt='".$lang['tech'][$Element]."' size=5 maxlength=5 value=0 tabindex=".$TabIndex.">";
                        $PageTable .= "</th>";
                    }
                } else {
                    $PageTable .= $lang['no_enought_res'] . "</th>";
                }

                // Fin de ligne (les 3 cases sont construites !!
                $PageTable .= "</tr>";
            }
        }
    }

    if ($CurrentPlanet['b_hangar_id'] != '') {
        $BuildQueue .= ElementBuildListBox( $CurrentUser, $CurrentPlanet );
    }

    $parse = $lang;
    // La page se trouve dans $PageTable;
    $parse['buildlist']    = $PageTable;
    // Et la liste de constructions en cours dans $BuildQueue;
    $parse['buildinglist'] = $BuildQueue;
    // fragmento de template
    $page .= parsetemplate(gettemplate('buildings_defense'), $parse);

    display($page, $lang['Defense']);

}
