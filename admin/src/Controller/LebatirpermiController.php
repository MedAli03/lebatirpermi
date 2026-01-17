<?php
/**
 * @package     Batirpermi.Administrator
 * @subpackage  com_batirpermi
 *
 * @copyright   (C) 2022 Clifford E Ford
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace J4xdemos\Component\Batirpermi\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
/**
 * Controller for a single record
 *
 * @since  1.6
 */
class LebatirpermiController extends FormController
{
	
	
	public function addemploi()
    {$this->setRedirect(Route::_('index.php?option=com_batirpermi&view=lebatirpermi&layout=emploi', false));}
	
	public function cancel($key = null)
    {
		 $app = Factory::getApplication();
		if($app->input->get('layout') == 'emploi'){
			$this->setRedirect(Route::_('index.php?option=com_batirpermi&view=lebatirpermis&layout=emplois', false));
			}
			else {
				
				$result = parent::cancel($key);

        return $result;}

       
		
     
	  
		
    }
	
	 
}
