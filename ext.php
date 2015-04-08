<?php
/**
*
* @package phpBB Extension - Acme Demo
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace hanelyp\fancydice;

/**
* @ignore
*/

// functionality moved to migrations
class ext extends \phpbb\extension\base
{

	function enable_step($old_state)
	{
		//echo 'step ',$old_state,'<br />';
		return parent::enable_step($old_state);
		
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet
				
/*				// Get config
				$config = $this->container->get('config');
				if (!isset($config['fancyDiceMacro_1']))
				{
					// if nothing else has been set, support basic dice
					set_config('fancyDiceMacro_1', json_encode(array('d'=>'@[1_>]')));
				}
				*/
				return 'fancyDice_config_changed';

			break;

			default:

				// Run parent enable step method
				return parent::enable_step($old_state);

			break;
		}
	}
	
	function purge_step($old_state)
	{
		return false;
	}
	
}
// */
