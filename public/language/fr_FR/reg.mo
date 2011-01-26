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


// Registration form
$lang['Reg_Registry']			= 'Inscription';
$lang['Reg_Form']				= 'Formulaire';
$lang['Reg_Username']			= 'Pseudo';
$lang['Reg_Password']			= 'Mot de passe';
$lang['Reg_Email']				= 'Adresse e-Mail';
$lang['Reg_PlanetName']			= 'Nom de la plan&egrave;te m&egrave;re';
$lang['Reg_Sexe']				= 'Sexe';
$lang['Reg_Men']				= 'Homme';
$lang['Reg_Women']				= 'Femme';
$lang['Reg_Undefined']			= 'ind&eacute;fini';
$lang['Reg_AcceptGamesRules']	= "J'acc&egrave;pte le r&egrave;glement";
$lang['Reg_SignUp']				= "S'enregister";

// Messages
$lang['Reg_WelcomeMail']		= 'Merci beaucoup de votre inscription &agrave; notre jeu ({gameurl}) \nVotre mot de passe est : {password}\n\nBon amusement !\n{gameurl}';
$lang['Reg_MailTitle']			= 'Enregistrment';
$lang['Reg_ThanksForRegistry']	= 'Merci de vous &ecirc;tre inscrit ! Vous allez recevoir un mail avec votre mot de passe.';
$lang['Reg_ErrorSendMail']		= 'Une erreur s\'est produite lors de l\'envoi du courriel! Votre mot de passe est : ';

$lang['Reg_SenderMessageIg']	= 'Admin';
$lang['Reg_SubjectMessageIg']	= 'Bienvenue';
$lang['Reg_TextMessageIg']		= 'Bienvenue sur XNova, nous vous souhaitons bon jeu et bonne chance !';

$lang['Reg_WellDone']			= 'Inscription termin&eacute;e !';

//Errors
$lang['Reg_InvalidForm']		= 'Erreurs dans le formulaire';
$lang['Reg_MissingField']		= 'Vous avez oubli&eacute; de remplir le champ : ';
$lang['Reg_Errors']['character']= $lang['Reg_Username'];
$lang['Reg_Errors']['email']	= $lang['Reg_Email'];
$lang['Reg_Errors']['planet']	= $lang['Reg_PlanetName'];
$lang['Reg_Errors']['passwrd']	= $lang['Reg_Password'];
$lang['Reg_Errors']['rgt']		= 'Reglement';
$lang['Reg_Errors']['sex']		= $lang['Reg_Sexe'];

$lang['Reg_InvalidEmail']		= 'Email invalide';
$lang['Reg_PasswordToShort']	= 'Le mot de passe est trop court';
$lang['Reg_AlreadyExist']		= "Le pseudo ou l'adresse email existent d&eacute;j&agrave;";