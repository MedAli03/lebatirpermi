<?php

namespace J4xdemos\Component\Batirpermi\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

class CategorieController extends BaseController
{
	public function ajouter(): void
	{
		$app = Factory::getApplication();
		$input = $app->input;

		if (!Session::checkToken()) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
			return;
		}

		if (!$this->authoriseAction('core.create', 'categories')) {
			return;
		}

		$title = trim($input->post->getString('title'));
		$state = $this->getStateValue($input->post->getString('state'));

		$model = $this->getModel('Categorie');
		if (!$model->create(['title' => $title, 'state' => $state])) {
			$app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
			return;
		}

		$app->enqueueMessage(Text::_('COM_BATIRPERMI_CATEGORIE_CREATED'));
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
	}

	public function edit(): void
	{
		$app = Factory::getApplication();
		$input = $app->input;

		if (!Session::checkToken()) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
			return;
		}

		if (!$this->authoriseAction('core.edit', 'categories')) {
			return;
		}

		$id = $input->getInt('id');
		$title = trim($input->post->getString('title'));
		$state = $this->getStateValue($input->post->getString('state'));

		$model = $this->getModel('Categorie');
		if (!$model->update($id, ['title' => $title, 'state' => $state])) {
			$app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
			return;
		}

		$app->enqueueMessage(Text::_('COM_BATIRPERMI_CATEGORIE_UPDATED'));
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
	}

	public function publish(): void
	{
		$app = Factory::getApplication();
		$input = $app->input;

		if (!Session::checkToken('get')) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
			return;
		}

		if (!$this->authoriseAction('core.edit.state', 'categories')) {
			return;
		}

		$id = $input->getInt('id');
		$currentState = $input->getInt('p');
		$newState = ($currentState === 1) ? 0 : 1;

		$model = $this->getModel('Categorie');
		if (!$model->setState($id, $newState)) {
			$app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
		}

		$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
	}

	public function delete(): void
	{
		$app = Factory::getApplication();
		$input = $app->input;

		if (!Session::checkToken('get')) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
			return;
		}

		if (!$this->authoriseAction('core.delete', 'categories')) {
			return;
		}

		$model = $this->getModel('Categorie');
		if (!$model->deleteById($input->getInt('id'))) {
			$app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
		}

		$app->redirect(Route::_('index.php?option=com_batirpermi&view=categories'));
	}

	private function authoriseAction(string $action, string $redirectView): bool
	{
		$app = Factory::getApplication();
		$user = $app->getIdentity();

		if ($user->authorise($action, 'com_batirpermi')) {
			return true;
		}

		$app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=' . $redirectView));

		return false;
	}

	private function getStateValue(string $stateValue): int
	{
		return ($stateValue === 'on' || $stateValue === '1') ? 1 : 0;
	}
}
