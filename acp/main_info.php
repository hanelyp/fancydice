<?php
/**
*
* @package phpBB Extension - Acme Demo
* @copyright (c) 2015 Peter Hanely
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace hanelyp\fancydice\acp;
//die;
class main_info
{
	function module()
	{
		return array(
			'filename'	=> //'_hanelyp_fancydice_acp_main_module', //
							'\hanelyp\fancydice\acp\main_module',
			'title'		=> 'ACP_FANCYDICE_TITLE',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'settings'	=> array('title' => 'ACP_FANCYDICE', 'auth' => 'ext_hanelyp/fancydice && acl_a_board', 'cat' => array('ACP_FANCYDICE_TITLE')),
			),
		);
	}
}
