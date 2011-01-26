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

function FleetBuildingPage ( &$CurrentPlanet, $CurrentUser ) {
    global $pricelist, $lang, $resource, $dpath, $reslist;

    if (isset($_POST['fmenge'])) {
        // On vient de Cliquer ' Construire '
        // Ici, on sait precisement ce qu'on aimerait bien construire ...

        foreach($_POST['fmenge'] as $Element => $Count) {
            // Construction d'Element recuper�s sur la page de Flotte ...
            // ATTENTION ! La file d'attente Flotte est Commune a celle des Defenses
            // Dans fmenge, on devrait trouver un tableau des elements constructibles et du nombre d'elements souhait�s
			
            $Element = (int) $Element;
            $Count   = (int) $Count;
            $Count = ($Count > MAX_FLEET_OR_DEFS_PER_ROW) ? MAX_FLEET_OR_DEFS_PER_ROW : $Count;

            if (!in_array($Element, $reslist[Legacies_Empire::TYPE_SHIP]) || $Count < 1 ||
                !IsTechnologieAccessible ($CurrentUser, $CurrentPlanet, $Element) ) {
                continue;
            }


            // On verifie combien on sait faire de cet element au max
            $MaxElements   = GetMaxConstructibleElements ( $Element, $CurrentPlanet );

            // Si pas assez de ressources, on ajuste le nombre d'elements
            $Count = ($Count > $MaxElements) ? $MaxElements : $Count;

            $Ressource = GetElementRessources ( $Element, $Count );
            $BuildTime = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element);

            //Correctif en attendant mieux.
            if ($CurrentPlanet['b_hangar_id'] == '') {
                $CurrentPlanet['b_hangar'] = 0;
            }

            if ($BuildTime > 0) {

                $CurrentPlanet['metal']           -= $Ressource['metal'];
                $CurrentPlanet['crystal']         -= $Ressource['crystal'];
                $CurrentPlanet['deuterium']       -= $Ressource['deuterium'];

                if ($Element == 214 && $CurrentUser['rpg_destructeur'] == 1) {
                    $Count = $Count * 2; //On multiplie les EDLM par 2
                }

                $CurrentPlanet['b_hangar_id']     .= "". $Element .",". $Count .";";

            } else {

                $res=doquery("SELECT ".$resource[$Element]." FROM {{table}} WHERE id = '". $CurrentPlanet['id'] ."'  ;",'planets');
                $NombreVaisseauxActuel=mysql_result($res,0,$resource[$Element]);

                $CurrentPlanet['metal'] -= $Ressource['metal'];
                $CurrentPlanet['crystal'] -= $Ressource['crystal'];
                $CurrentPlanet['deuterium'] -= $Ressource['deuterium'];
                $NewFleetNumber = $CurrentPlanet[$resource[$Element]] + $Count;

                if ($Element == 214 && $CurrentUser['rpg_destructeur'] == 1) {
                    $Count = $Count * 2; //On multiplie les EDLM par 2
                }

                $QryUpdatefleet = "UPDATE {{table}} SET ";
                $QryUpdatefleet .= "`$resource[$Element]` = '".$NombreVaisseauxActuel."' + '".$Count."' ";
                $QryUpdatefleet .= "WHERE ";
                $QryUpdatefleet .= "`id` = '". $CurrentPlanet['id'] ."'";
                    doquery ( $QryUpdatefleet, 'planets');
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
	$TabIndex = 0;
	foreach($lang['tech'] as $Element => $ElementName) {
		if ($Element > 201 && $Element <= 399) {
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
                if ($CanBuildOne)
				{
					$TabIndex++;
					$PageTable .= "<input type=text id=fmenge[".$Element."] name=fmenge[".$Element."] alt='".$lang['tech'][$Element]."' value=0 tabindex=".$TabIndex.">";
						
					$MaxElements   = GetMaxConstructibleElements ( $Element, $CurrentPlanet );

					if($MaxElements>MAX_FLEET_OR_DEFS_PER_ROW)
						$MaxElements=MAX_FLEET_OR_DEFS_PER_ROW;

					$PageTable.='<BR><BR><A ONCLICK="document.getElementById(\'fmenge['.$Element.']\').value=\''.intval($MaxElements).'\';" STYLE="cursor:pointer;">Nombre max ('.intval($MaxElements).')</A></th>';
				}
				else
				{
					$PageTable .= $lang['no_enought_res'] . '</th>';
				}

				$MaxElements   = GetMaxConstructibleElements ( $Element, $CurrentPlanet );

                if($MaxElements>MAX_FLEET_OR_DEFS_PER_ROW)
                 $MaxElements=MAX_FLEET_OR_DEFS_PER_ROW;

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
	$page .= parsetemplate(gettemplate('buildings_fleet'), $parse);

	display($page, $lang['Fleet']);
}
