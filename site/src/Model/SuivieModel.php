<?php

namespace J4xdemos\Component\Batirpermi\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\Database\ParameterType;
use Joomla\CMS\Router\Route;


class SuivieModel extends ItemModel
{
	 protected $_context = 'com_batirpermi.suivie';
	 
	 
	public function __construct($config = array())
	{
		
		$app = Factory::getApplication();		
		if ($app->input->get('id')) {			
			$db = Factory::getDbo();
			$query = $db->getQuery(true);
			$query->update($db->quoteName('#__batirpermi_suivies'))
			->set('downloaded = (downloaded + 1)')
			->where($db->quoteName('id') . ' = ' . $app->input->getInt('id'));
			$db->setQuery($query);
			$db->execute();				
			//$app->redirect(Route::_(htmlspecialchars($_SERVER["HTTP_REFERER"])));			
			//Factory::getApplication()->enqueueMessage('leid constr = ' . $app->input->get('id') );
		}
		

	}

	
	 public function getItem($pk = null)
    	{
			$app = Factory::getApplication();	
			$pk = (int) ($pk ?: $app->input->getInt('id'));
			$db = $this->getDatabase();
			$query = $db->getQuery(true);
			$query->select(array('a.*', 'b.title as lacategorie'));
			$query->from($db->quoteName('#__batirpermi_suivies', 'a'))
				  ->leftjoin($db->quoteName('#__batirpermi_categories', 'b') . 'ON a.lacated = b.id');
			$query->where($db->quoteName('a.id') . ' LIKE ' . $db->quote($app->input->getInt('id')));
			$db->setQuery($query);
			$data = $db->loadObject();
		 	$this->_item[$pk] = $data;
			return $this->_item[$pk];
		
		}
	
	



}
