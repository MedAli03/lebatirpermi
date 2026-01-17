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


class ApofadminController extends BaseController
{
	
	
	public function ajouter()
	{		
		$app = Factory::getApplication();
		$object = new stdClass();
		$object->title  = $this->test_input($_POST["title"]);
		$object->reference  = $this->test_input($_POST["reference"]);
		$object->type= $this->test_input($_POST["type"]);
		$object->lacated= $this->test_input($_POST["lacated"]);
        $object->caution= $this->test_input($_POST["caution"]);
		$object->created= $this->test_input($_POST["created"]);
		$object->echeance= $this->test_input($_POST["echeance"]);
		$object->datcommenc= $this->test_input($_POST["datcommenc"]);
		$object->datouvoffre= $this->test_input($_POST["datouvoffre"]);
		$object->params= $this->test_input($_POST["params"]);
		$object->support= $this->test_input($_POST["support"]);
		$object->lesbatirpermis= $this->test_input($_POST["lesbatirpermis"]);
		$object->downloaded= $this->test_input($_POST["downloaded"]);
		$object->image= $this->test_input($_POST["image"]);
		$object->language= $this->test_input($_POST["language"]);
		$object->state= $this->test_input($_POST["state"]);





		$object->state  = ($_POST["state"] == 'on') ? 1 : 0 ;
		$result = Factory::getDbo()->insertObject('#__batirpermi_suivies', $object);
		$app->enqueueMessage('Analyse ajoutée');
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
	}
	
	public function edit()
	{		
		$app = Factory::getApplication();	
		$updateNulls = true;
		$object = new \stdClass;		
		$object->id = $app->input->getInt('id') ;
		$object->title  = $this->test_input($_POST["title"]);
		$object->reference  = $this->test_input($_POST["reference"]);
		$object->type= $this->test_input($_POST["type"]);
		$object->lacated= $this->test_input($_POST["lacated"]);
        $object->caution= $this->test_input($_POST["caution"]);
		$object->created= $_POST["created"];
		$object->echeance= $_POST["echeance"];
		$object->datcommenc= $_POST["datcommenc"];
		$object->datouvoffre= $this->test_input($_POST["datouvoffre"]);
		$object->params= $this->test_input($_POST["params"]);
		$object->support= $this->test_input($_POST["support"]);
		$object->lesbatirpermis= $this->test_input($_POST["lesbatirpermis"]);
		$object->downloaded= $this->test_input($_POST["downloaded"]);
		$object->image= $this->test_input($_POST["image"]);
		$object->language= $this->test_input($_POST["language"]);
		$object->state= $this->test_input($_POST["state"]);



$app->enqueueMessage('creatd =  ' . $_POST["created"]);
		
		$object->state  = ($_POST["state"] == 'on') ? 1 : 0 ;
		$result = Factory::getDbo()->updateObject('#__batirpermi_suivies', $object, 'id', $updateNulls);		
		$app->enqueueMessage('Modification effectuée ' . $_POST["souscategs_id"]);
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
	}
	

	public function publish()
	{
		$app = Factory::getApplication();		
		$updateNulls = true;
		$object = new \stdClass;
		$object->id = $app->input->getInt('id') ;
		$object->state = ($app->input->getInt('p') == 1 ) ? 0 : 1 ;
		$result = Factory::getDbo()->updateObject('#__batirpermi_suivies', $object, 'id', $updateNulls);
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
	}
	
	public function delete()
	{
		$app = Factory::getApplication();		
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__batirpermi_suivies'));
		$query->where($db->quoteName('id') . ' = ' . $app->input->getInt('id'));		
		$db->setQuery($query);		
		$result = $db->execute();
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
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
