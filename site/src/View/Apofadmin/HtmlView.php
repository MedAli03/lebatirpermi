<?php
/**
 * @package     Batirpermi.Site
 * @subpackage  com_batirpermi
 *
 * @copyright   (C) 2022 Clifford E Ford
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace J4xdemos\Component\Batirpermi\Site\View\Apofadmin;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Uri\Uri;

/**
 * View class for Batirpermi.
 *
 * @since  4.0
 */
class HtmlView extends BaseHtmlView
{
	

	
	protected $item;
	protected $state;
	protected $canDo;

	
	public function display($tpl = null)
	{
		
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');


		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}
		$batirpermi = Factory::getBatirpermi();
		$batirpermi->addStyleSheet(URI::root() . "media/com_batirpermi/css/jquery.datetimepicker.css");
		$batirpermi->addScript(URI::root() . "media/com_batirpermi/js/jquery.datetimepicker.full.js");
		parent::display($tpl);
	}
}
