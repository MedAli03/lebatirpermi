<?php

namespace J4xdemos\Component\Batirpermi\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\Database\ParameterType;
use Joomla\CMS\Router\Route;


class CategorieModel extends ItemModel
{
	 protected $_context = 'com_batirpermi.categorie';
	 
	 
	public function __construct($config = array())
	{
				

	}

	
	 public function getItem($pk = null)
    	{
			$app = Factory::getApplication();	
			$pk = (int) ($pk ?: $app->input->getInt('id'));
			$db = $this->getDatabase();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__batirpermi_categories'));
			$query->where($db->quoteName('id') . ' = ' . $db->quote($app->input->getInt('id')));
			$db->setQuery($query);
			$data = $db->loadObject();
		 	$this->_item[$pk] = $data;
			return $this->_item[$pk];
		
		}
	
	



}
