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
	'ACP_FANCYDICE_TITLE'		=> 'Configuración Fancy Dice',
	'ACP_FANCYDICE'				=> 'Preferencias',
	'ACP_FANCYDICE_MACROS'		=> 'Definición de la tirada',
	'ACP_FANCYDICE_DIE_NAME'	=> 'Nombre de la base de la tirada',
	'ACP_FANCYDICE_DIE_DEF'		=> 'Definición del dado',
	'ACP_FANCYDICE_DEF_ERROR'	=> 'Error encontrado en la definición del dado: ',
	'ACP_FANCYDICE_SETTING_SAVED'	=> 'Configuración de Fancy Dice guardada',
	'FANCYDICE_SPECDEF'			=> 'Revise fancydice.php en este modulo para las definciones de dados',
));
