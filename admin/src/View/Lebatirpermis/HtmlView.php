<?php
/**
 * @package     Batirpermi.Administrator
 * @subpackage  com_batirpermi
 *
 * @copyright   (C) 2022 Clifford E Ford
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace J4xdemos\Component\Batirpermi\Administrator\View\Lebatirpermis;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View class for batirpermi.
 *
 * @since  4.0
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The search tools form
	 *
	 * @var    Form
	 * @since  1.6
	 */
	public $filterForm;

	/**
	 * The active search filters
	 *
	 * @var    array
	 * @since  1.6
	 */
	public $activeFilters = [];

	/**
	 * Category data
	 *
	 * @var    array
	 * @since  1.6
	 */
	protected $categories = [];

	/**
	 * An array of items
	 *
	 * @var    array
	 * @since  1.6
	 */
	protected $items = [];

	/**
	 * The pagination object
	 *
	 * @var    Pagination
	 * @since  1.6
	 */
	protected $pagination;

	/**
	 * The model state
	 *
	 * @var    Registry
	 * @since  1.6
	 */
	protected $state;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  void
	 *
	 * @since   1.6
	 * @throws  Exception
	 */
	public function display($tpl = null): void
	{

		$this->state      = $this->get('State');
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		// Chec
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar(): void
	{
		$app = Factory::getApplication();
		
		if (Factory::getApplication()->input->get('layout') == 'emplois'){
			ToolbarHelper::title(Text::_('Emplois du Temps') );
			}
		else{
			ToolbarHelper::title(Text::_('COM_BATIRPERMI_LEBATIRPERMIS_TITLE'));
			
		}
		

		

		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		$user  = Factory::getUser();

		if ($user->authorise('core.admin', 'com_batirpermi'))
		{
			
			if (Factory::getApplication()->input->get('layout') == 'emplois'){
			$toolbar->addNew('lebatirpermi.addemploi');
			}
		else{$toolbar->addNew('lebatirpermi.add');}
			
			
			
		}

		if ($user->authorise('core.edit.state', 'com_batirpermi'))
		{
			$dropdown = $toolbar->dropdownButton('status-group')
			->text('JTOOLBAR_CHANGE_STATUS')
			->toggleSplit(false)
			->icon('icon-ellipsis-h')
			->buttonClass('btn btn-action')
			->listCheck(true);

			$childBar = $dropdown->getChildToolbar();

			$childBar->publish('lebatirpermis.publish')->listCheck(true);

			$childBar->unpublish('lebatirpermis.unpublish')->listCheck(true);

			$childBar->archive('lebatirpermis.archive')->listCheck(true);

			if ($this->state->get('filter.published') != -2)
			{
				$childBar->trash('lebatirpermis.trash')->listCheck(true);
			}
		}

		if ($this->state->get('filter.published') == -2 && $user->authorise('core.delete', 'com_batirpermi'))
		{
			$toolbar->delete('lebatirpermis.delete')
			->text('JTOOLBAR_EMPTY_TRASH')
			->message('JGLOBAL_CONFIRM_DELETE')
			->listCheck(true);
		}

		if ($user->authorise('core.admin', 'com_batirpermi') || $user->authorise('core.options', 'com_batirpermi'))
		{
			$toolbar->preferences('com_batirpermi');
		}

		$tmpl = $app->input->getCmd('tmpl');
		if ($tmpl !== 'component')
		{
			ToolbarHelper::help('batirpermi', true);
		}
	}
}

