<?php

namespace hanelyp\fancydice\migrations;

// this code is being ignored.
//die;

class acp extends \phpbb\db\migration\migration
{
	// needed to prevent an activation crash, even if it does nothing.
	public function effectively_installed()
	{
		// idiot core code is following migrations database field and not calling this.
		//die;
		//echo 'effectively installed? ',$config['fancyDiceMacro_1'],'<br />';
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
		//echo "fetching update data<br />\n";
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

	// reflection on poor core design.
	function bbcodecount()
	{
		$sql = 'SELECT MAX(bbcode_id) as max_bbcode_id
							FROM ' . BBCODES_TABLE;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($row)
		{
			$bbcode_id = $row['max_bbcode_id'] + 1;

			// Make sure it is greater than the core bbcode ids...
			if ($bbcode_id <= NUM_CORE_BBCODES)
			{
				$bbcode_id = NUM_CORE_BBCODES + 1;
			}
		}
		else
		{
			$bbcode_id = NUM_CORE_BBCODES + 1;
		}
		return $bbcode_id;
	}
	
	// brute force, but best option with opaque cache
	function clearcache()
	{
		$cachedir = "cache";

		$dir = opendir($cachedir);
	// Delete everything but index.htm and .htaccess
		while( $file = readdir( $dir ) )
		{
			//if ((strpos($file, '.php') > 0) && (strpos($file, 'sql_') === 0))
			if (preg_match('#^sql_.*\.php$#', $file) )
			{
				unlink("$cachedir/$file");
			}
		}
	}
	
	public function add_bbcode()
	{
		// double check for existing definition
		$sql = 'SELECT * FROM ' . BBCODES_TABLE . " WHERE bbcode_tag = 'dice'";
		$result = $this->db->sql_query($sql);
		if (!$row = $this->db->sql_fetchrow($result))
		{
			$sql_ary = array(
				'bbcode_id'					=> $this->bbcodecount(),
				'bbcode_tag'				=> 'dice',
				'bbcode_match'				=> '#[dice\s+seed={number}\s+secure={simpletext}]{text}[/dice]#ie',
				'bbcode_tpl'				=> '<blockquote>hanelyp\fancydice\event\main_listener::singlet()->replace_dice("{text}",{number},"{simpletext}")</blockquote>',
				'display_on_posting'		=> 1,
				'bbcode_helpline'			=> '[dice]3d6+1[/dice]',
				'first_pass_match'			=> '#\[dice\](.+)\[/dice\]#ie',
				'first_pass_replace'		=> 'hanelyp\fancydice\event\main_listener::singlet()->prep_dice("$1","$uid")',
				'second_pass_match'			=> '#\[dice\s+seed=(\d+)\s+secure=(\w+):?\w*\](.+)\[/dice\]#i',
				'second_pass_replace'		=> 'hanelyp\fancydice\event\main_listener::singlet()->bb_replace_dice("$3",$1,"$2")',
			);
			$this->db->sql_query('INSERT INTO ' . BBCODES_TABLE . $this->db->sql_build_array('INSERT', $sql_ary));
			// need to clear cache here
			//$this->cache->destroy('sql', BBCODES_TABLE);
			$this->clearcache();
		}
		$this->db->sql_freeresult($result);
	}
}
