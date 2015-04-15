<?php
/**
*
* @package phpBB Extension - Acme Demo
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace hanelyp\fancydice\acp;

class main_module
{
	var $u_action;

	// read config into handy array
	function get_macros()
	{
		global $config;
		$macros = false;
		if (isset($config['fancyDiceMacro_1']))
		{
			$macros = array();
			$i = 1;
			while (isset($config['fancyDiceMacro_'.$i]))
			{
				$macro = json_decode($config['fancyDiceMacro_'.$i]);
				//$macro = explode(':', $config['fancyDiceMacro_'.$i]);
				foreach ($macro as $key=>$value)
				{
					//echo "$key=>$value<br />";
					$macros[] = array('INDEX'=>$i, 'NAME'=>$key, 'DEF'=>htmlentities($value));
					//$macros[] = array('INDEX'=>$i, 'NAME'=>$macro[0], 'DEF'=>$macro[1]);
				}
				$i++;
			}
		}
		return $macros;
	}

	function set_macros()
	{
		global $config, $request;
		$i = 1;
		while ($name = $request->variable('macroName_'.$i,''))
		{
			$def = $request->variable('macroDef_'.$i,'');
			// $request->variable() is being 'helpful'
			$def = html_entity_decode($def);
			
			$dice = new \hanelyp\fancydice\fancydice(false);
			$res = $dice->roll($def);
			//echo "$name => $def<br />";
			if (!$dice)
			{
				trigger_error($user->lang('ACP_FANCYDICE_DEF_ERROR').$name.'=>'.$def . adm_back_link($this->u_action));
			}
			//echo json_encode(array($name=>$def),JSON_HEX_TAG),'<br />';
			$config->set('fancyDiceMacro_'.$i, json_encode(array($name=>$def), 0));
			//$config->set('fancyDiceMacro_'.$i, $name.':'.$def);
			$i++;
		}
	}

	function reset_BB_code()
	{
		global $db;
		$migration = new \hanelyp\fancydice\acp\defs();
		$db->sql_query('UPDATE ' . BBCODES_TABLE .
			' SET '.$db->sql_build_array('UPDATE', $migration->BB_sql_ary).
			' WHERE bbcode_tag = "'.$migration->BB_sql_ary['bbcode_tag'].'"');
		// need to clear cache here
		global $cache;
		$cache->purge();
	}
	
	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $cache, $request;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		//$user->add_lang('acp/common');
		$user->add_lang_ext('hanelyp/fancydice', 'common');
		$this->tpl_name = 'fancydice_body';
		$this->page_title = $user->lang('ACP_FANCYDICE_TITLE');
		add_form_key('hanelyp/fancydice');

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('hanelyp/fancydice'))
			{
				trigger_error('FORM_INVALID');
			}
			$action = $request->variable('action', '');
			if ($action == 'dicedef')
			{
				$this->set_macros();
				trigger_error($user->lang('ACP_FANCYDICE_SETTING_SAVED') . adm_back_link($this->u_action));
			}
			elseif ($action == 'presentation')
			{
				$config->set('fancyDicePresent', html_entity_decode($request->variable('display', '')), 0);
				trigger_error($user->lang('ACP_FANCYDICE_SETTING_PRESENT') . adm_back_link($this->u_action));
			}
			elseif ($action == 'resetbb')
			{
				$this->reset_BB_code();
				trigger_error($user->lang('ACP_FANCYDICE_RESET_BB') . adm_back_link($this->u_action));
			}
		}

		$macros = $this->get_macros();
		
		$i = 1;
		foreach ($macros as $macro)
		{
			//$macro['index'] = $i; 
			//echo json_encode($macro),'<br />';
			$template->assign_block_vars('macros', $macro );
			$i++;
		}
		$template->assign_vars(array(
			'U_ACTION'				=> $this->u_action,
			'U_NEXTINDEX'			=> $i,
			'U_DISPLAY_DEF'			=> htmlentities($config['fancyDicePresent']),
			//'ACP_FANCYDICE_MACROS'	=> $user->lang('ACP_FANCYDICE_MACROS'),
			//'ACP_FANCYDICE'			=> $user->lang('ACP_FANCYDICE'),
		));
	}
}
