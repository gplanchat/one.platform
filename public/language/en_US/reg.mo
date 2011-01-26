<?php
/**
 * Tis file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-Present, XNova Support Team <http://www.xnova-ng.org>
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


if (!defined('INSIDE')) {
    die("attemp hacking");
}

// Registration form
$lang['Reg_Registry']           = 'Registration';
$lang['Reg_Form']               = 'Form';
$lang['Reg_Username']           = 'Username';
$lang['Reg_Password']           = 'Password';
$lang['Reg_Email']              = 'E-mail address';
$lang['Reg_PlanetName']         = 'Homeplanet name';
$lang['Reg_Sexe']               = 'Gender';
$lang['Reg_Men']                = 'Male';
$lang['Reg_Women']              = 'Female';
$lang['Reg_Undefined']          = 'Undefined';
$lang['Reg_AcceptGamesRules']   = 'I accept license agreement';
$lang['Reg_SignUp']             = 'Register';

// Messages
$lang['Reg_WelcomeMail']        = 'Thank you for signing up on our game ({gameurl}) \nYour password is: {password}\n\nGood luck!\n{gameurl}';
$lang['Reg_MailTitle']          = 'Registration';
$lang['Reg_ThanksForRegistry']  = 'Thank you for signing up ! You will receive an email with your password.';
$lang['Reg_ErrorSendMail']      = 'An error occured while sending you the email, your password is: ';

$lang['Reg_SenderMessageIg']    = 'Admin';
$lang['Reg_SubjectMessageIg']   = 'Welcome';
$lang['Reg_TextMessageIg']      = "Welcome at XNova. We hope you'll have a good game.";

$lang['Reg_WellDone']           = 'Registration complete !';

//Errors
$lang['Reg_InvalidForm']            = 'Invalid form data';
$lang['Reg_MissingField']           = "You didn't fill properly the field";
$lang['Reg_Errors']['character']    = $lang['Reg_Username'];
$lang['Reg_Errors']['email']        = $lang['Reg_Email'];
$lang['Reg_Errors']['planet']       = $lang['Reg_PlanetName'];
$lang['Reg_Errors']['passwrd']      = $lang['Reg_Password'];
$lang['Reg_Errors']['rgt']          = 'Rules';
$lang['Reg_Errors']['sex']          = $lang['Reg_Sexe'];

$lang['Reg_InvalidEmail']       = 'Invalid email';
$lang['Reg_PasswordToShort']    = 'The password is too short';
$lang['Reg_AlreadyExist']       = 'This email or username is already used';