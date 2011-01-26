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

function MessageForm ($title, $message, $goTo = '', $button = ' ok ', $twoLines = false) {

	if ($twoLines) {
		$addLines = <<<EOF
				<th colspan="2">{$message}</th>
			</tr><tr>
				<th colspan="2" align="center">
					<input type="submit" name="submit" value="{$button}" />
				</th>	
EOF;
	} else {
		$addLines = <<<EOF
			<th colspan="2">{$message}
			<input type="submit" name="submit" value="{$button}" /></th>	
EOF;
	}
	
	$form = <<<EOF
		<center>
		<form action="{$goTo}" method="post">
			<table width="519">
				<tr>
					<td class="c" colspan="2">{$title}</td>
				</tr><tr>
					{$addLines}
				</tr>
			</table>
		</form>
		</center>
EOF;

	return $form;
}
// Release History
// - 1.0 Mise en fonction, Documentation
?>