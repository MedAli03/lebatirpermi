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
class SuiviesModel extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     \JController
	 * @since   1.6
	 */
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
					'id', 'a.id',
					'title', 'a.title',
					'created', 'a.created',
					'echeance', 'a.echeance',
					'params', 'a.params',
					'downloaded', 'a.downloaded',
					'state', 'a.state',
					'lacated', 'a.lacated',
					'image', 'a.image',
					'language', 'a.language',					
					'datcommenc', 'a.datcommenc',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   3.0.1
	 */
	protected function populateState($ordering = 'id', $direction = 'DESC')
	{
		// List state information.
		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');

		return parent::getStoreId($id);
	}

	/**
	 * Get the master query for retrieving a list of lebatirpermis subject to the model state.
	 *
	 * @return  \Joomla\Database\DatabaseQuery
	 *
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		
		
		
		
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				[
					$db->quoteName('a.id'),
					$db->quoteName('a.title'),
					$db->quoteName('a.created'),
					$db->quoteName('a.echeance'),
					$db->quoteName('a.params'),
					$db->quoteName('a.downloaded'),
					$db->quoteName('a.lacated'),
					$db->quoteName('a.image'),
					$db->quoteName('a.datcommenc'),
					$db->quoteName('a.state'),
					$db->quoteName('b.title') . ' AS categorie_title',
					$db->quoteName('a.language'),
				]
			)
		)
		->from($db->quoteName('#__batirpermi_suivies', 'a'))
		->leftjoin($db->quoteName('#__batirpermi_categories', 'b') . 'ON a.lacated = b.id');
		
		$query->where('(a.state = 1)');

		// Filter by search in title.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
			$query->where('(a.title LIKE ' . $search . ')');
		}
		
		// Filter by categorie.
		$lacated = $this->getState('filter.lacated');

		if (!empty($lacated))
		{
			$query->where('(a.lacated = ' . $lacated . ')');
		}

		// Filter by published state
		$published = (string) $this->getState('filter.published');

		if ($published !== '*')
		{
			if (is_numeric($published))
			{
				$state = (int) $published;
				$query->where($db->quoteName('a.state') . ' = :state')
				->bind(':state', $state, ParameterType::INTEGER);
			}
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'DESC');

		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}

}
