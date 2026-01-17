<?php
/**
 * @package     Batirpermi.Site
 * @subpackage  com_batirpermi
 *
 * @copyright   (C) 2022 Clifford E Ford
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace J4xdemos\Component\Batirpermi\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\ParameterType;
use Joomla\CMS\Router\Route;

/**
 * Methods to list records.
 *
 * @since  1.6
 */
class CategoriesModel extends ListModel
{
	
	public function __construct($config = array())
	{
		
		/*$app = Factory::getApplication();		
		if ($app->input->get('leid')) {			
			$db = Factory::getDbo();
			$query = $db->getQuery(true);
			$query->update($db->quoteName('#__batirpermi_suivies'))
			->set('downloaded = (downloaded + 1)')
			->where($db->quoteName('id') . ' = ' . $app->input->getInt('leid'));
			$db->setQuery($query);
			$db->execute();				
			$app->redirect(Route::_(htmlspecialchars($_SERVER["HTTP_REFERER"])));			
			//Factory::getApplication()->enqueueMessage('leid constr = ' . $app->input->get('leid') );
		}*/
		
		
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
					'id', 
					'title', 					
			);
		}

		parent::__construct($config);
	}

	
	protected function populateState($ordering = 'title', $direction = 'ASC')
	{
		// List state information.
		parent::populateState($ordering, $direction);
	}

	
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');

		return parent::getStoreId($id);
	}

	
	protected function getListQuery()
	{
		
		
		
		
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('*')
		->from($db->quoteName('#__batirpermi_categories'));
		
	//	$query->where('(a.state = 1)');

		// Filter by search in title.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
			$query->where('(title LIKE ' . $search . ')');
		}
		
		

		// Filter by published state
		$published = (string) $this->getState('filter.published');

		if ($published !== '*')
		{
			if (is_numeric($published))
			{
				$state = (int) $published;
				$query->where($db->quoteName('state') . ' = :state')
				->bind(':state', $state, ParameterType::INTEGER);
			}
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'title');
		$orderDirn = $this->state->get('list.direction', 'ASC');

		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}

}
