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

includeLang('infos');
includeLang('missile');

include ROOT_PATH . 'includes/raketenangriff.php' ;

$sql = Legacies_Database::getInstance()
    ->select()
    ->from(Legacies_Database::getTable('deprecated/iraks'))
    ->where('zeit <?', new Zend_Db_Expr('UNIX_TIMESTAMP()'))
    ;

/**
 * @var array Missile who have already impacted.
 */
$flyingMissile = Legacies_Database::getInstance()->fetchAssoc($sql);

foreach($flyingMissile as $missile) {
    
    $sql = Legacies_Database::getInstance()->select()
        ->from(Legacies_Database::getTable('deprecated/planets'))
        ->where('galaxy=?', $missile['galaxy'])
        ->where('system=?', $missile['system'])
        ->where('planet=?', $missile['planet'])
        ->where('planet_type=?', '1')
    ;
        /**
         * @var array Informations about the target planet
         */
        $targetPlanet = Legacies_Database::getInstance()->fetchRow($sql);

    $sql = Legacies_Database::getInstance()->select()
        ->from(Legacies_Database::getTable('deprecated/users'), array('defenderTech' => 'defence_tech'))
        ->where('id=?', $missile['zielid'])
        . ' UNION '. //FIXME Union Zend_Db
        Legacies_Database::getInstance()
        ->select()
        ->from(Legacies_Database::getTable('deprecated/users'), array('attackerTech' => 'military_tech'))
        ->where('id=?', $missile['owner'])
       ;
            /**
             * @var array Research level of defense_tech for the defender and military_tech for attacker
             */
            $fighterTech = Legacies_Database::getInstance()->fetchAll($sql);


	if ($targetPlanet && $fighterTech) {

		$defenderTech = (int) $fighterTech[0]['defenderTech'];
		$attackerTech = (int) $fighterTech[1]['defenderTech'];
		
		/**
		 * @var array List of defense on the target planet.
		 */
		foreach ($reslist[Legacies_Empire::TYPE_DEFENSE] as $id) {
		    $targetDef[$id] = $targetPlanet[$resource[$id]];
		}
		
		/**
		 * @see raketenangriff (includes/raketenangriff.php)
		 */
		$irak = raketenangriff($defenderTech, $attackerTech, $missile['anzahl'], $targetDef, (int) $missile['primaer']); 
		
		$message = '';
		foreach ($irak['destroyDefense'] as $id => $quantity) {
		    if ($quantity != 0)
		        $message .= $lang['info'][$id]['name'] . " : $quantity <br>";
		}
		
		$stayingDefense = array();
		foreach ($irak['stayingDefense'] as $id => $quantity) {
		    $stayingDefense[$resource[$id]] = $quantity;
		}
		Legacies_Database::getInstance()->update(Legacies_Database::getTable('deprecated/planets'), $stayingDefense, array ('id=?' => $targetPlanet['id']));
        
		$reciepient = array ('zielid', 'owner');
		foreach ($reciepient as $name) {
            $data = array(
				'message_owner' => $missile[$name],
				'message_sender' => '',
				'message_time' => new Zend_Db_Expr('UNIX_TIMESTAMP()'),
				'message_type' => '0',
		    	'message_from' => $lang['Missile_MessageFrom'],
		    	'message_subject' => $lang['Missile_Attack'],
		    	'message_text' => $lang['Missile_AttackMessage'][$name] . $message 
		    );
		    Legacies_Database::getInstance()->insert(Legacies_Database::getTable('deprecated/messages'), $data);
		}   
		
	    Legacies_Database::getInstance()->update(Legacies_Database::getTable('deprecated/users'), array('new_message' => 'new_message + 1'), array ('id=?' => $missile['zielid']));
	    Legacies_Database::getInstance()->update(Legacies_Database::getTable('deprecated/users'), array('new_message' => 'new_message + 1'), array ('id=?' => $missile['owner']));
        Legacies_Database::getInstance()->delete(Legacies_Database::getTable('deprecated/iraks'), array('id=?' => $missile['id'])); 

	}
		
}
