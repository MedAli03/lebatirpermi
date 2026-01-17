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
		$derniereCle = end($_POST['LeTitre']);
		$Json_support = '{' ;
		foreach($_POST['LeTitre'] as  $LeTitre_cle => $LeTitre_element){
			if($LeTitre_element === $derniereCle) {$levirgule='';}else{$levirgule=',';}
			$Json_support .= '"support'.$LeTitre_cle.'":{"lesupport":"'.$LeTitre_element.'","lereference":"'.$_POST['Laref'][$LeTitre_cle].'"}'.$levirgule ;		
		}
		$Json_support .= '}' ;	
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
		$object->lesbatirpermis= $this->test_input($_POST["lesbatirpermis"]);
		$object->support= $Json_support ;
		$object->image= $this->test_input($_POST["image"]);
		$object->language= $this->test_input($_POST["language"]);
		$object->state= $this->test_input($_POST["state"]);
		
		// Ajout file
		$file = $_FILES["image"];
		if (isset($file) and $file['error'] != 4 ) // and $file['size'] < 204800
			  {
			  
				  if (count(explode('.', $file['name'])) < 2)
				  {
				  throw new RuntimeException(Text::_('sans extension'));
				  return false;
				  }
			  
			  $filename = JPATH_SITE .'/images/pdf/' . time() .'-' . str_replace(' ', '-',File::makeSafe($file['name']));
			  
			  
			  if (!File::upload($file['tmp_name'], $filename))
			  {
				  $app->enqueueMessage(Text::_('non uploadé'));
				  return false;
			  }
			  
			  $jsonname = new stdClass();
			  $jsonname->name = $file['name'] ;
			  $jsonname->type = $file['type'] ;
			  $jsonname->size = $file['size'] ;
			  $jsonname->fname = time() .'-' . str_replace(' ', '-', File::makeSafe($file['name'])) ;
			  $object->image= json_encode($jsonname) ;	  
	    }

		// Fin Ajout File 




		$object->state  = ($_POST["state"] == 'on') ? 1 : 0 ;
		$result = Factory::getDbo()->insertObject('#__batirpermi_suivies', $object);
		$app->enqueueMessage('Analyse ajoutée');
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
	}
	
	public function edit()
	{		
		
		$app = Factory::getApplication();		
	//	echo '<pre>'; print_r($_POST['LeTitre']);  echo '</pre>';	 exit;
		$derniereCle = end($_POST['LeTitre']);
		$Json_support = '{' ;
		foreach($_POST['LeTitre'] as  $LeTitre_cle => $LeTitre_element){
			if($LeTitre_element === $derniereCle) {$levirgule='';}else{$levirgule=',';}
			$Json_support .= '"support'.$LeTitre_cle.'":{"lesupport":"'.$LeTitre_element.'","lereference":"'.$_POST['Laref'][$LeTitre_cle].'"}'.$levirgule ;		
		}
		$Json_support .= '}' ;		
		$updateNulls = true;
		$object = new \stdClass;		
		$object->id = $app->input->getInt('id') ;
		$object->title  = $this->test_input($_POST["title"]);
		$object->reference  = $this->test_input($_POST["reference"]);
		$object->type= $this->test_input($_POST["type"]);
		$object->lacated= $this->test_input($_POST["lacated"]);
        $object->caution= $this->test_input($_POST["caution"]);
		$object->created= (!$_POST["created"]) ? NULL : $_POST["created"];
		$object->echeance= (!$_POST["echeance"]) ? NULL : $_POST["echeance"]; 
		$object->datcommenc= (!$_POST["datcommenc"]) ? NULL : $_POST["datcommenc"]; 
		$object->datouvoffre= (!$_POST["datouvoffre"]) ? NULL : $_POST["datouvoffre"]; 
		$object->params= $this->test_input($_POST["params"]);
		$object->lesbatirpermis= $this->test_input($_POST["lesbatirpermis"]);
		$object->support= $Json_support ;
		$object->image= $this->test_input($_POST["image"]);
		$object->language= $this->test_input($_POST["language"]);
		$object->state= $this->test_input($_POST["state"]);



		// Ajout file
		$file = $_FILES["image"];
		if (isset($file) and $file['error'] != 4 ) // and $file['size'] < 204800
			  {
			  
				  if (count(explode('.', $file['name'])) < 2)
				  {
				  throw new RuntimeException(Text::_('sans extension'));
				  return false;
				  }
			  
			  $filename = JPATH_SITE .'/images/pdf/' . time() .'-' . str_replace(' ', '-',File::makeSafe($file['name']));
			  
			  
			  if (!File::upload($file['tmp_name'], $filename))
			  {
				  $app->enqueueMessage(Text::_('non uploadé'));
				  return false;
			  }
			  
			  $jsonname = new stdClass();
			  $jsonname->name = $file['name'] ;
			  $jsonname->type = $file['type'] ;
			  $jsonname->size = $file['size'] ;
			  $jsonname->fname = time() .'-' . str_replace(' ', '-', File::makeSafe($file['name'])) ;
			  $object->image= json_encode($jsonname) ;	  
	    }

		// Fin Ajout File 
		
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
