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

define('INSIDE' , true);
define('INSTALL' , false);
define('DISABLE_IDENTITY_CHECK', true);
require_once dirname(__FILE__) .'/common.php';
includeLang('reg');

if (!empty($_POST)) {
    $errors = array();

    $formFields = array('character', 'email', 'planet', 'passwrd', 'rgt', 'sex');
    $formData = array();
    foreach ($formFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $errors[] = $lang['Reg_MissingField'] . $lang['Reg_Errors'][$field];
        } else {
            $formData[$field] = $_POST[$field];
        }
    }

    if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = $lang['Reg_InvalidEmail'];
    }
    if (($length = strlen($formData['passwrd'])) < 4) {
        $errors[] = $lang['Reg_PasswordToShort'];
    }
    
    if (empty($errors)) {
        $nameAlreadyExist = $readConnection->select()
            ->from($readConnection->getDeprecatedTable('users'), array ('id' => 'id'))
            ->where('username =?', $formData['character'])
            ->orWhere('email =?', $formData['email'])
            ->query()->fetch()
          ;
          
        if ($nameAlreadyExist)
            $errors[] = $lang['Reg_AlreadyExist'];    
    }
    
    if (!empty($errors)) {  
        message(implode('<br>', $errors), $lang['Reg_InvalidForm']); 
    } else {
        $data = array(
            'username' => $formData['character'],
            'email' => $formData['email'],
            'email_2' => $formData['email'],
            'sex' => $formData['sex'],
            'ip_at_reg' => $_SERVER['REMOTE_ADDR'],
            'register_time' => new Zend_Db_Expr('UNIX_TIMESTAMP()'),
            'password' => md5($formData['passwrd'])
        );

        $readConnection->insert($readConnection->getDeprecatedTable('users'), $data);
        $userId = $readConnection->lastInsertId($readConnection->getDeprecatedTable('users'));

        $galaxy = ($game_config['LastSettedSystemPos'] == MAX_SYSTEM_IN_GALAXY) ?
            $game_config['LastSettedGalaxyPos'] + 1 : (int) $game_config['LastSettedGalaxyPos'];
        
        if ($galaxy != $game_config['LastSettedGalaxyPos'])
            $system = 1;
        else {
            $system = ($game_config['LastSettedPlanetPos'] == 12) ?
                $game_config['LastSettedSystemPos']+ 1 : (int) $game_config['LastSettedSystemPos'];
        }
        
        $planet = ($game_config['LastSettedPlanetPos'] == 12) ?
            4 : $game_config['LastSettedPlanetPos'] + 4;

        $planetId = CreateOnePlanetRecord($galaxy, $system, $planet, $userId, $formData['planet'], true);
        
        $readConnection->update($readConnection->getDeprecatedTable('config'), array ('config_value' => $galaxy), array ('config_name =?' => 'LastSettedGalaxyPos'));
        $readConnection->update($readConnection->getDeprecatedTable('config'), array ('config_value' => $system), array ('config_name =?' => 'LastSettedSystemPos'));
        $readConnection->update($readConnection->getDeprecatedTable('config'), array ('config_value' => $planet), array ('config_name =?' => 'LastSettedPlanetPos'));

        $planetData = array (
			'id_planet' => $planetId,
            'current_planet' => $planetId,
            'galaxy' => $galaxy,
            'system' => $system,
            'planet' => $planet
          );
        $readConnection->update($readConnection->getDeprecatedTable('users'), $planetData, array ('id =?' => $userId));
        
        SendSimpleMessage($userId, 'Admin', time(), 1, $lang['Reg_SenderMessageIg'], $lang['Reg_SubjectMessageIg'], $lang['Reg_TextMessageIg']);
        $readConnection->update($readConnection->getDeprecatedTable('config'), array ('config_value' => 'users_amount + 1'), array ('config_name =?' => 'users_amount'));
            

        if (($x = sendPassMail($formData['email'], $formData['passwrd'])) == true) {
             $message = $lang['Reg_ThanksForRegistry'] . " ({$formData['email']})";  
        } else {
            $message = $lang['Reg_ThanksForRegistry'] . " ({$formData['email']})"; 
            $message .= "<br><br>{$lang['error_mailsend']}<b>{$formData['passwrd']}</b>"; 
        }
        
        message($message, $lang['Reg_WellDone']);
    }
} else {
    
    $page = parsetemplate(gettemplate('registry_form'), $lang);

        display ($page, $lang['Reg_Registry'], false);
}

function sendPassMail($emailaddress, $password)
{
    global $lang;

    $parse['gameurl'] = GAMEURL;
    $parse['password'] = $password;
    $email = parsetemplate($lang['Reg_WelcomeMail'], $parse);
    $status = mymail($emailaddress, $lang['Reg_MailTitle'], $email);
    return $status;
}

function mymail($to, $title, $body, $from = '')
{

    $from = ($from) ? trim($from) : ADMINEMAIL;

    $head = '';
    $head .= "Content-Type: text/plain \r\n";
    $head .= "Date: " . date('r') . " \r\n";
    $head .= "From: $from \r\n";
    $head .= "Sender: $from \r\n";
    $head .= "Reply-To: $from \r\n";
    $head .= "X-Sender: $from \r\n";
    $head .= "X-Priority: 3 \r\n";

    return mail($to, $title, $body, $head);
}
