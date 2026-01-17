<?php


namespace J4xdemos\Component\Batirpermi\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;
use \stdClass;
use Joomla\CMS\Table\Table;


class CategorieController extends BaseController
{
	
	
	public function ajouter()
	{		
		$app = Factory::getApplication();
		$input = $app->input;

		if (!Session::checkToken()) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
			return;
		}
		$object = new stdClass();
		$object->title  = $this->test_input($input->post->getString('title'));
		




		$stateValue = $input->post->get('state', '', 'string');
		$object->state  = ($stateValue === 'on' || $stateValue === '1') ? 1 : 0 ;
		$result = Factory::getDbo()->insertObject('#__batirpermi_categories', $object);
		$app->enqueueMessage('Catégorie ajoutée');
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
	}
	
	public function edit()
	{		
		$app = Factory::getApplication();
		$input = $app->input;

		if (!Session::checkToken()) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
			return;
		}
		$updateNulls = true;
		$object = new \stdClass;		
		$object->id = $input->getInt('id') ;
		$object->title  = $this->test_input($input->post->getString('title'));


		
		$stateValue = $input->post->get('state', '', 'string');
		$object->state  = ($stateValue === 'on' || $stateValue === '1') ? 1 : 0 ;
		$result = Factory::getDbo()->updateObject('#__batirpermi_categories', $object, 'id', $updateNulls);		
		$app->enqueueMessage('Modification effectuée ' . $input->post->getString('souscategs_id'));
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
	}
	

	public function publish()
	{
		$app = Factory::getApplication();
		if (!Session::checkToken('get')) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
			return;
		}
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
		if (!Session::checkToken('get')) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
			return;
		}
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
