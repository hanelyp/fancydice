<?php
/**
*
* @package phpBB Extension - hanelyp fancydice
* @copyright (c) 2015 Peter Hanely
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ACP_FANCYDICE_TITLE'		=> 'Fancy Dice Config',
	'ACP_FANCYDICE'				=> 'Settings',
	'ACP_FANCYDICE_MACROS'		=> 'Define house dice',
	'ACP_FANCYDICE_DIE_NAME'	=> 'base name of die',
	'ACP_FANCYDICE_DIE_DEF'		=> 'definition of die',
	'ACP_FANCYDICE_DEF_ERROR'	=> 'Error encountered with dice definition: ',
	'ACP_FANCYDICE_SETTING_SAVED'	=> 'Fancy Dice Config Saved',
	'ACP_FANCYDICE_SETTING_PRESENT'	=> 'Presentation template set',
	'ACP_FANCYDICE_RESET_BB'	=> 'BBCode def reset',
	'ACP_FANCYDICE_PRESENTATION'	=> 'Presentation HTML for roll results',
	'ACP_FANCYDICE_PRESENTATION_HELP'	=> '&lt;div class="dicebox">{SPEC} => {DICE} => {TOTAL} {VALID}&lt;/div>',
	'ACP_FANCYDICE_RESET'		=> 'Reset [dice] BBcode definition',
	'FANCYDICE_SPECDEF'			=> 'see fancydice.php in this module for dice definition specs',
	'FANCYDICE_EXCESS_TOKENS'	=> 'Too Many Dice Tokens',
	'FANCYDICE_FIDDLED'			=> 'invalid',
));
