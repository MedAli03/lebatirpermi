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
		
		if ($_POST['LeTitre']) {
			$derniereCle = end($_POST['LeTitre']);
			$Json_support = '{' ;
			foreach($_POST['LeTitre'] as  $LeTitre_cle => $LeTitre_element){
				if($LeTitre_element === $derniereCle) {$levirgule='';}else{$levirgule=',';}
				$Json_support .= '"support'.$LeTitre_cle.'":{"lesupport":"'.$LeTitre_element.'","lereference":"'.$_POST['Laref'][$LeTitre_cle].'"}'.$levirgule ;		
			}
			$Json_support .= '}' ;	
		}
		if ($_POST['titdoc']) {
		$derniereCle = end($_POST['titdoc']);
		$Json_lesbatirpermis = '{' ;
			foreach($_POST['titdoc'] as  $titdoc_cle => $titdoc_element){
				
				 
				/////////////////////////  debuté  file
				
				$file = $_FILES["leficher"];
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
						  $object->lesbatirpermis= json_encode($jsonname) ;	  
					}
				
				
				//////////////////////////    fin file
				
				
				
				
				
				if($titdoc_element === $derniereCle) {$levirgule='';}else{$levirgule=',';}
				$Json_lesbatirpermis .= '"lesbatirpermis'.$titdoc_cle.'":{"letitre":"'.$titdoc_element.'","ledoc":"'.$_POST['leficher'][$titdoc_cle].'"}'.$levirgule ;	
			}
				
				
				
				
					
			}
			$Json_lesbatirpermis .= '}' ;





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
	//	$object->lesbatirpermis= $Json_lesbatirpermis;
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
	    if ($_POST['LeTitre']) {
		$derniereCle = end($_POST['LeTitre']);
		$Json_support = '{' ;
		foreach($_POST['LeTitre'] as  $LeTitre_cle => $LeTitre_element){
			if($LeTitre_element === $derniereCle) {$levirgule='';}else{$levirgule=',';}
			$Json_support .= '"support'.$LeTitre_cle.'":{"lesupport":"'.$LeTitre_element.'","lereference":"'.$_POST['Laref'][$LeTitre_cle].'"}'.$levirgule ;		
		}
		$Json_support .= '}' ;	
		}
		if ($_POST['titdoc']) {
			$dernieredoc = end($_POST['titdoc']);
			$Json_lesbatirpermis = '{' ;
				foreach($_POST['titdoc'] as  $titdoc_cle => $titdoc_element){
					$file = $_FILES["leficher"];					
					if (isset($file) and $file['error'][$titdoc_cle] != 4 ) // and $file['size'] < 204800
						  {					  
							  if (count(explode('.', $file['name'][$titdoc_cle])) < 2)
							  {
							  throw new RuntimeException(Text::_('sans extension'));
							  return false;
							  }					  
						  $filename = JPATH_SITE .'/images/pdf/' . time() .'-' . str_replace(' ', '-',File::makeSafe($file['name'][$titdoc_cle]));					  
						  if (!File::upload($file['tmp_name'][$titdoc_cle], $filename))
						  {
							  $app->enqueueMessage(Text::_('non uploadé'));
							  return false;
						  }					  
						  $jsonname = new stdClass();
						  $jsonname->name = $file['name'][$titdoc_cle] ;
						  $jsonname->type = $file['type'][$titdoc_cle] ;
						  $jsonname->size = $file['size'][$titdoc_cle] ;
						  $jsonname->fname = time() .'-' . str_replace(' ', '-', File::makeSafe($file['name'][$titdoc_cle])) ;	
						  $lenomdufichier = 'images/pdf/' . time() .'-' . str_replace(' ', '-', File::makeSafe($file['name'][$titdoc_cle])) ;	
					}	
					
					
					if($titdoc_element === $dernieredoc) {$levirgule='';}else{$levirgule=',';}
					$Json_lesbatirpermis .= '"lesbatirpermis'.$titdoc_cle.'":{"letitre":"'.$titdoc_element.'","ledoc":"'.$lenomdufichier.'"}'.$levirgule ;		
				}
				$Json_lesbatirpermis .= '}' ;
		}
		



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
		$object->lesbatirpermis= $Json_lesbatirpermis ;
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
		$app->enqueueMessage('Modification effectuée ');
		if ($_POST["enregistrer"] == 'Enregistrer') {
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmin&id='.$app->input->getInt('id')));
			} else {$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));}
		
		
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
		//$data = htmlentities($data);
		return $data;
	}
	
	public function deleteimg()
	{		
		
		$app = Factory::getApplication();							
		$db = Factory::getDbo();
		$query = $db
			->getQuery(true)
			->select('image')
			->from($db->quoteName('#__batirpermi_suivies'))
			->where($db->quoteName('id') . " = " . (int) $app->input->get('id') );	
		$db->setQuery($query);
		$result = $db->loadResult();
		$file = JPATH_SITE . '/images/pdf/' . json_decode($result)->fname ;
		$app->enqueueMessage($file);
		
		File::delete($file);
		

			
		$updateNulls = true;
		$object = new \stdClass();
		$object->id  =  $app->input->get('id') ;
		$object->image = '' ;				
		$result = Factory::getDbo()->updateObject('#__batirpermi_suivies', $object, 'id', $updateNulls);							
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmin&id='.$app->input->getInt('id'))) ;
		
		

	}

}
