<?php


namespace J4xdemos\Component\Batirpermi\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Filesystem\File;
use \stdClass;
use Joomla\CMS\Table\Table;


class CategorieController extends BaseController
{
	
	
	public function ajouter()
	{		
		$app = Factory::getApplication();
		$object = new stdClass();
		$object->title  = $this->test_input($_POST["title"]);
		




		$object->state  = ($_POST["state"] == 'on') ? 1 : 0 ;
		$result = Factory::getDbo()->insertObject('#__batirpermi_categories', $object);
		$app->enqueueMessage('Catégorie ajoutée');
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
	}
	
	public function edit()
	{		
		$app = Factory::getApplication();	
		$updateNulls = true;
		$object = new \stdClass;		
		$object->id = $app->input->getInt('id') ;
		$object->title  = $this->test_input($_POST["title"]);


		
		$object->state  = ($_POST["state"] == 'on') ? 1 : 0 ;
		$result = Factory::getDbo()->updateObject('#__batirpermi_categories', $object, 'id', $updateNulls);		
		$app->enqueueMessage('Modification effectuée ' . $_POST["souscategs_id"]);
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
	}
	

	public function publish()
	{
		$app = Factory::getApplication();		
		$updateNulls = true;
		$object = new \stdClass;
		$object->id = $app->input->getInt('id') ;
		$object->state = ($app->input->getInt('p') == 1 ) ? 0 : 1 ;
		$result = Factory::getDbo()->updateObject('#__batirpermi_categories', $object, 'id', $updateNulls);
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
	}
	
	public function delete()
	{
		$app = Factory::getApplication();		
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__batirpermi_categories'));
		$query->where($db->quoteName('id') . ' = ' . $app->input->getInt('id'));		
		$db->setQuery($query);		
		$result = $db->execute();
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
	}
	
	public function test_input($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		//$data = htmlspecialchars($data);
		$data = htmlentities($data);
		return $data;
	}

}
