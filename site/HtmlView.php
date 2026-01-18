<?php

namespace J4xdemos\Component\Batirpermi\Site\View\Lebatirpermis;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Session\Session;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;


class HtmlView extends BaseHtmlView
{
	public $state = null;
	public $result = null;
	public $printItem = null;
	public $isSubmitted = false;
	public $tokenValid = true;
	public $cin = '';
	public $numdossier = '';
	public $isPrint = false;

	
	public function display($tpl = null)
	{
		$app = Factory::getApplication();
		$input = $app->input;
		$layout = $input->getCmd('layout', 'default');
		$this->isPrint = ($layout === 'print');

		$this->cin = $this->isPrint ? $input->getString('cin') : $input->post->getString('cin');
		$this->numdossier = $this->isPrint ? $input->getString('numdossier') : $input->post->getString('numdossier');

		if ($this->isPrint) {
			if ($this->cin !== '' && $this->numdossier !== '') {
				$this->printItem = $this->getModel()->searchByCinAndDossier($this->cin, $this->numdossier);
			}
		} else {
			$this->isSubmitted = $input->post->get('submit') !== null;
			if ($this->isSubmitted) {
				if (!Session::checkToken()) {
					$this->tokenValid = false;
				} elseif ($this->cin !== '' && $this->numdossier !== '') {
					$this->result = $this->getModel()->searchByCinAndDossier($this->cin, $this->numdossier);
				}
			}
		}

		parent::display($tpl);
	}
}
