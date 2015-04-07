<?php
/**
*
* @package hanelyp\fancydice
* @copyright (c) 2015 Peter Hanely
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace hanelyp\fancydice;

class bbcode
{
	private dice;
	static myself = false;
	var $config;
	var $macros;
	
	function setup($config)
	{
		$this->config = $config;
		$this->macros = $this->get_macros();
	}
	
	function get_macros()
	{
		//global $config;
		$macros = false;
		if (isset($this->config['fancyDiceMacro_1']))
		{
			$macros = array();
			$i = 1;
			while (isset($config['fancyDiceMacro_'.$i]))
			{
				$macro = json_decode($this->config['fancyDiceMacro_'.$i]);
				foreach ($macro as $key=>$value)
				{
					$macros[$key] = $value;
				}
				$i++;
			}
		}
		return $macros;
	}
	
	static function singlet()
	{
		if (!self::$myself)
		{
			self::$myself = new bbcode($user->config);
		}
		return self::$myself;
	}

	public function prep_dice($spec, $uid)
	{
		$seed = rand();
		$secure = $this->validate($seed);
		return '[dice seed='.$seed.' secure='.$secure.':'.$uid.']'.$spec.'[/dice]';
	}

	// not the most secure, but enough to discourage fiddling with the seed
	function validate($seed)
	{
		return sha1($this->config['fancyDiceSecure'].$seed);
	}
			
	public function replace_dice($spec, $seed, $secure)
	{
		// validate seed against secure
		$valid = $this->validate($seed)==$secure?'':' invalid';

		$dice = new \hanelyp\fancydice\fancydice($this->macros, $seed);
		$roll = $dice->roll($spec);
		$total = $dice->sum($roll);

		return $spec.' => '.join(', ', $roll).' => '.$total.$valid;
	}
}

