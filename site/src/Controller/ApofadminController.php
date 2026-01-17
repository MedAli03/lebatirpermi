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


class ApofadminController extends BaseController
{
	
	
	public function ajouter()
	{		
		$app = Factory::getApplication();
		$input = $app->input;

		if (!Session::checkToken()) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
			return;
		}

		$Json_support = null;
		$leTitres = $input->post->get('LeTitre', [], 'array');
		$laRefs = $input->post->get('Laref', [], 'array');
		if (!empty($leTitres)) {
			$supportData = [];
			foreach ($leTitres as $LeTitre_cle => $LeTitre_element) {
				$supportData['support' . $LeTitre_cle] = [
					'lesupport' => $this->test_input($LeTitre_element),
					'lereference' => $this->test_input($laRefs[$LeTitre_cle] ?? ''),
				];
			}
			$Json_support = json_encode($supportData, JSON_UNESCAPED_UNICODE);
		}

		$Json_lesbatirpermis = null;
		$titdocs = $input->post->get('titdoc', [], 'array');
		if (!empty($titdocs)) {
			$lesbatirpermisData = [];
			$files = $input->files->get('leficher', [], 'array');
			foreach ($titdocs as $titdoc_cle => $titdoc_element) {
				$lenomdufichier = '';
				$fileError = $files['error'][$titdoc_cle] ?? 4;
				if ($fileError != 4) {
					$fileName = $files['name'][$titdoc_cle] ?? '';
					if (count(explode('.', $fileName)) < 2) {
						throw new RuntimeException(Text::_('sans extension'));
					}
					$timestamp = time();
					$safeName = str_replace(' ', '-', File::makeSafe($fileName));
					$filename = JPATH_SITE . '/images/pdf/' . $timestamp . '-' . $safeName;
					if (!File::upload($files['tmp_name'][$titdoc_cle] ?? '', $filename)) {
						$app->enqueueMessage(Text::_('non uploadé'));
						return false;
					}
					$lenomdufichier = 'images/pdf/' . $timestamp . '-' . $safeName;
				}

				$lesbatirpermisData['lesbatirpermis' . $titdoc_cle] = [
					'letitre' => $this->test_input($titdoc_element),
					'ledoc' => $lenomdufichier,
				];
			}
			$Json_lesbatirpermis = json_encode($lesbatirpermisData, JSON_UNESCAPED_UNICODE);
		}
	/*	if ($_POST['titdoc']) {
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
			$Json_lesbatirpermis .= '}' ;*/
			
		//	$Json_lesbatirpermis = 'qqqqqq';

	//	echo $Json_support . '<br>' . $Json_lesbatirpermis ; exit;



		$object = new stdClass();
		$object->title  = $this->test_input($input->post->getString('title'));
		$object->reference  = $this->test_input($input->post->getString('reference'));
		$object->type= $this->test_input($input->post->getString('type'));
		$object->lacated= $this->test_input($input->post->getString('lacated'));
        $object->caution= $this->test_input($input->post->getString('caution'));
		$object->created= $this->test_input($input->post->getString('created'));
		$object->echeance= $this->test_input($input->post->getString('echeance'));
		$object->datcommenc= $this->test_input($input->post->getString('datcommenc'));
		$object->datouvoffre= $this->test_input($input->post->getString('datouvoffre'));
		$object->params= $this->test_input($input->post->getString('params'));
		$object->lesbatirpermis= $Json_lesbatirpermis;
		$object->support= $Json_support ;
		$object->image= $this->test_input($input->post->getString('image'));
		$object->language= $this->test_input($input->post->getString('language'));
		$object->state= $this->test_input($input->post->getString('state'));
		
		// Ajout file
		$file = $input->files->get('image', null, 'array');
		if (isset($file) and ($file['error'] ?? 4) != 4 ) // and $file['size'] < 204800
			  {
			  
				  if (count(explode('.', $file['name'] ?? '')) < 2)
				  {
				  throw new RuntimeException(Text::_('sans extension'));
				  return false;
				  }
			  
			  $timestamp = time();
			  $safeName = str_replace(' ', '-', File::makeSafe($file['name'] ?? ''));
			  $filename = JPATH_SITE .'/images/pdf/' . $timestamp .'-' . $safeName;
			  
			  
			  if (!File::upload($file['tmp_name'] ?? '', $filename))
			  {
				  $app->enqueueMessage(Text::_('non uploadé'));
				  return false;
			  }
			  
			  $jsonname = new stdClass();
			  $jsonname->name = $file['name'] ?? '';
			  $jsonname->type = $file['type'] ?? '';
			  $jsonname->size = $file['size'] ?? 0; 
			  $jsonname->fname = $timestamp .'-' . $safeName ;
			  $object->image= json_encode($jsonname) ;	  
	    }

		// Fin Ajout File 




		$stateValue = $input->post->get('state', '', 'string');
		$object->state  = ($stateValue === 'on' || $stateValue === '1') ? 1 : 0 ;
		$result = Factory::getDbo()->insertObject('#__batirpermi_suivies', $object);
		$app->enqueueMessage('Analyse ajoutée');
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
	}
	
	public function edit()
	{		
		
		$app = Factory::getApplication();
		$input = $app->input;

		if (!Session::checkToken()) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
			return;
		}

	    $Json_support = null;
		$leTitres = $input->post->get('LeTitre', [], 'array');
		$laRefs = $input->post->get('Laref', [], 'array');
		if (!empty($leTitres)) {
			$supportData = [];
			foreach ($leTitres as $LeTitre_cle => $LeTitre_element) {
				$supportData['support' . $LeTitre_cle] = [
					'lesupport' => $this->test_input($LeTitre_element),
					'lereference' => $this->test_input($laRefs[$LeTitre_cle] ?? ''),
				];
			}
			$Json_support = json_encode($supportData, JSON_UNESCAPED_UNICODE);
		}

		$Json_lesbatirpermis = null;
		$titdocs = $input->post->get('titdoc', [], 'array');
		$leficherHidden = $input->post->get('leficherh', [], 'array');
		if (!empty($titdocs)) {
			$lesbatirpermisData = [];
			$files = $input->files->get('leficher', [], 'array');
			foreach ($titdocs as $titdoc_cle => $titdoc_element) {
				$lenomdufichier = $leficherHidden[$titdoc_cle] ?? '';
				$fileError = $files['error'][$titdoc_cle] ?? 4;
				if ($fileError != 4) {
					$fileName = $files['name'][$titdoc_cle] ?? '';
					if (count(explode('.', $fileName)) < 2) {
						throw new RuntimeException(Text::_('sans extension'));
					}
					$timestamp = time();
					$safeName = str_replace(' ', '-', File::makeSafe($fileName));
					$filename = JPATH_SITE . '/images/pdf/' . $timestamp . '-' . $safeName;
					if (!File::upload($files['tmp_name'][$titdoc_cle] ?? '', $filename)) {
						$app->enqueueMessage(Text::_('non uploadé'));
						return false;
					}
					$lenomdufichier = 'images/pdf/' . $timestamp . '-' . $safeName;
				}

				$lesbatirpermisData['lesbatirpermis' . $titdoc_cle] = [
					'letitre' => $this->test_input($titdoc_element),
					'ledoc' => $lenomdufichier,
				];
			}
			$Json_lesbatirpermis = json_encode($lesbatirpermisData, JSON_UNESCAPED_UNICODE);
		}
		



		$updateNulls = true;
		$object = new \stdClass;		
		$object->id = $input->getInt('id') ;
		$object->title  = $this->test_input($input->post->getString('title'));
		$object->reference  = $this->test_input($input->post->getString('reference'));
		$object->type= $this->test_input($input->post->getString('type'));
		$object->lacated= $this->test_input($input->post->getString('lacated'));
        $object->caution= $this->test_input($input->post->getString('caution'));
		$createdValue = $input->post->getString('created');
		$echeanceValue = $input->post->getString('echeance');
		$datcommencValue = $input->post->getString('datcommenc');
		$datouvoffreValue = $input->post->getString('datouvoffre');
		$object->created= ($createdValue === '') ? null : $createdValue;
		$object->echeance= ($echeanceValue === '') ? null : $echeanceValue; 
		$object->datcommenc= ($datcommencValue === '') ? null : $datcommencValue; 
		$object->datouvoffre= ($datouvoffreValue === '') ? null : $datouvoffreValue; 
		$object->params= $this->test_input($input->post->getString('params'));
		$object->lesbatirpermis= $Json_lesbatirpermis ;
		$object->support= $Json_support ;
		$object->image= $this->test_input($input->post->getString('image'));
		$object->language= $this->test_input($input->post->getString('language'));
		$object->state= $this->test_input($input->post->getString('state'));



		// Ajout file
		$file = $input->files->get('image', null, 'array');
		if (isset($file) and ($file['error'] ?? 4) != 4 ) // and $file['size'] < 204800
			  {
			  
				  if (count(explode('.', $file['name'] ?? '')) < 2)
				  {
				  throw new RuntimeException(Text::_('sans extension'));
				  return false;
				  }
			  
			  $timestamp = time();
			  $safeName = str_replace(' ', '-', File::makeSafe($file['name'] ?? ''));
			  $filename = JPATH_SITE .'/images/pdf/' . $timestamp .'-' . $safeName;
			  
			  
			  if (!File::upload($file['tmp_name'] ?? '', $filename))
			  {
				  $app->enqueueMessage(Text::_('non uploadé'));
				  return false;
			  }
			  
			  $jsonname = new stdClass();
			  $jsonname->name = $file['name'] ?? '';
			  $jsonname->type = $file['type'] ?? '';
			  $jsonname->size = $file['size'] ?? 0;
			  $jsonname->fname = $timestamp .'-' . $safeName ;
			  $object->image= json_encode($jsonname) ;	  
	    }

		// Fin Ajout File 
		
		$stateValue = $input->post->get('state', '', 'string');
		$object->state  = ($stateValue === 'on' || $stateValue === '1') ? 1 : 0 ;
		$result = Factory::getDbo()->updateObject('#__batirpermi_suivies', $object, 'id', $updateNulls);		
		$app->enqueueMessage('Modification effectuée ');
		if ($input->post->getString('enregistrer') === 'Enregistrer') {
			$uri = Uri::getInstance();
			$url = $uri->toString();
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmin&id='.$app->input->getInt('id'),false));
			//$app->redirect($url);
			} else {$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));}
		
		
	}
	

	public function publish()
	{
		$app = Factory::getApplication();
		if (!Session::checkToken('get')) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
			return;
		}
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
		if (!Session::checkToken('get')) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
			return;
		}
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
		if (!Session::checkToken('get')) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
			return;
		}
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
