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
class LebatirpermisModel extends ListModel
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
		
		$app = Factory::getApplication();		
		if ($app->input->get('leid')) {			
			$db = Factory::getDbo();
			$query = $db->getQuery(true);
			$query->update($db->quoteName('#__batirpermi_lebatirpermis'))
			->set('downloaded = (downloaded + 1)')
			->where($db->quoteName('id') . ' = ' . $app->input->getInt('leid'));
			$db->setQuery($query);
			$db->execute();				
			$app->redirect(Route::_(htmlspecialchars($_SERVER["HTTP_REFERER"])));			
			
		}
		
		
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

	
	protected function populateState($ordering = 'id', $direction = 'DESC')
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
		
	$app = Factory::getApplication();			
		
		
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
		->from($db->quoteName('#__batirpermi_lebatirpermis', 'a'))
		->leftjoin($db->quoteName('#__batirpermi_categories', 'b') . 'ON a.lacated = b.id');
		
		
		
		// Filter by search in title.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
			$query->where('(a.title LIKE ' . $search . ')');
		}



		
		
		$lacated = $this->getState('filter.lacated');	
		if (!empty($lacated))
		{
			
			$query->where('(a.lacated = ' . (int) $this->getState('filter.lacated') . ')');
		}
		else
		{ $query->where('(a.lacated = ' . (int) $app->input->get('idm') . ')');}

		
       
		
		
		
		
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'ASC');
		//$app->enqueueMessage('order   = ' . $this->state->get('list.ordering', 'a.id') . ' - ' . $this->state->get('list.direction', 'ASC') );
		//$query->order('created DESC');
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		return $query;
	}

	public function getItemById(int $id): array
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select([
				'p.id',
				'p.title',
				'p.nom',
				'p.cin',
				'p.resultat',
				'p.typebatiment',
				'p.ingenieur',
				'c.title AS category_title',
			])
			->from($db->quoteName('#__batirpermi_lebatirpermis', 'p'))
			->join('LEFT', $db->quoteName('#__batirpermi_categories', 'c') . ' ON c.id = p.lacated')
			->where($db->quoteName('p.id') . ' = :id')
			->bind(':id', $id, ParameterType::INTEGER);

		$db->setQuery($query);
		return (array) $db->loadAssoc();
	}

}
