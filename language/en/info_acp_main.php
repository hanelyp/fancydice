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
	'FANCYDICE_SPECDEF'			=> 'see fancydice.php in this module for dice definition specs',
));
