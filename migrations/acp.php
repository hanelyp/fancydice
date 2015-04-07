<?php

namespace hanelyp\fancydice\migrations;

// this code is being ignored.
//die;

class acp extends \phpbb\db\migration\migration
{
	// needed to prevent an activation crash, even if it does nothing.
	public function effectively_installed()
	{
		// idiot core code is assuming something and not calling this.
		//die;
		echo 'effectively installed? ',$config['fancyDiceMacro_1'],'<br />';
		return false;
		global $config;
		return isset($config['fancyDiceMacro_1']);	//true;
	}
	
	// not needed?  must return something?
/*	public function update_schema()
	{
		return false;	//array();
	}
// */
	public function update_data()
	{
		echo "fetching update data<br />\n";
		//die;
		return array(
			//array('config.add', array('acme_demo_goodbye', 0)),
			array('config.add',
				array('fancyDiceMacro_1', json_encode(array('d'=>'@[1_>]')) )
			),
			array('config.add',
				array('fancyDiceSecure', rand() )
			),

			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_FANCYDICE_MACROS'
			)),
			array('module.add', array(
				'acp',
				'ACP_FANCYDICE_MACROS',
				array(
					'module_basename' => //'_hanelyp_fancydice_acp_main_module', //
										'\hanelyp\fancydice\acp\main_module',
					'modes' => array('settings'),
				),
			)),
			array('custom', array(array($this, 'add_bbcode'))),
		);
	}
	
	public function add_bbcode()
	{
		$sql_ary = array_merge($sql_ary, array(
			'bbcode_tag'				=> 'dice',
			'bbcode_match'				=> '#[dice\s+seed={number}\s+secure={simpletext}]{text}[/dice]#ie',
			'bbcode_tpl'				=> '<blockquote>hanelyp\fancydice\bbcode::singlet()->replace_dice("{text}",{number},"{simpletext}")</blockquote>',
			'display_on_posting'		=> 1,
			'bbcode_helpline'			=> '[dice]3d6+1[/dice]',
			'first_pass_match'			=> '#[dice](.+)[/dice]#ie',
			'first_pass_replace'		=> 'hanelyp\fancydice\bbcode::singlet()->prep_dice("$1","$uid")',
			'second_pass_match'			=> '[dice\s+seed=(\d+)\s+secure=(\w+)](.+)[/dice]#ie',
			'second_pass_replace'		=> '<blockquote>hanelyp\fancydice\bbcode::singlet()->replace_dice("$3",$1,"$2")</blockquote>',
		));
		$this->db->sql_query('INSERT INTO ' . BBCODES_TABLE . $this->db->sql_build_array('INSERT', $sql_ary));
	}
}
