<?php

namespace J4xdemos\Component\Batirpermi\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use RuntimeException;

class ApofadminController extends BaseController
{
	public function ajouter(): void
	{
		$app = Factory::getApplication();
		$input = $app->input;

		if (!Session::checkToken()) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
			return;
		}

		if (!$this->authoriseAction('core.create', 'apofadmins')) {
			return;
		}

		try {
			$data = $this->buildPayload($input);
		} catch (RuntimeException $exception) {
			$app->enqueueMessage($exception->getMessage(), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
			return;
		}

		$model = $this->getModel('Apofadmin');
		if (!$model->create($data)) {
			$app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
			return;
		}

		$app->enqueueMessage(Text::_('COM_BATIRPERMI_APOFADMIN_CREATED'));
		$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
	}

	public function edit(): void
	{
		$app = Factory::getApplication();
		$input = $app->input;
		$id = $input->getInt('id');

		if (!Session::checkToken()) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
			return;
		}

		if (!$this->authoriseAction('core.edit', 'apofadmins')) {
			return;
		}

		try {
			$data = $this->buildPayload($input);
		} catch (RuntimeException $exception) {
			$app->enqueueMessage($exception->getMessage(), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
			return;
		}

		$model = $this->getModel('Apofadmin');
		if (!$model->update($id, $data)) {
			$app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
			return;
		}

		$app->enqueueMessage(Text::_('COM_BATIRPERMI_APOFADMIN_UPDATED'));

		if ($input->post->getString('enregistrer') === 'Enregistrer') {
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmin&id=' . $id, false));
			return;
		}

		$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
	}

	public function publish(): void
	{
		$app = Factory::getApplication();
		$input = $app->input;

		if (!Session::checkToken('get')) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
			return;
		}

		if (!$this->authoriseAction('core.edit.state', 'apofadmins')) {
			return;
		}

		$id = $input->getInt('id');
		$currentState = $input->getInt('p');
		$newState = ($currentState === 1) ? 0 : 1;

		$model = $this->getModel('Apofadmin');
		if (!$model->setState($id, $newState)) {
			$app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
		}

		$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
	}

	public function delete(): void
	{
		$app = Factory::getApplication();
		$input = $app->input;

		if (!Session::checkToken('get')) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
			return;
		}

		if (!$this->authoriseAction('core.delete', 'apofadmins')) {
			return;
		}

		$model = $this->getModel('Apofadmin');
		if (!$model->deleteById($input->getInt('id'))) {
			$app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
		}

		$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
	}

	public function deleteimg(): void
	{
		$app = Factory::getApplication();
		$input = $app->input;
		$id = $input->getInt('id');

		if (!Session::checkToken('get')) {
			$app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmins'));
			return;
		}

		if (!$this->authoriseAction('core.edit', 'apofadmins')) {
			return;
		}

		$model = $this->getModel('Apofadmin');
		$imageJson = $model->getImageJson($id);

		if ($imageJson) {
			$imageData = json_decode($imageJson, true);
			$filename = $imageData['fname'] ?? '';
			if ($filename !== '') {
				File::delete(JPATH_SITE . '/images/pdf/' . $filename);
			}
		}

		if (!$model->clearImage($id)) {
			$app->enqueueMessage(Text::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
		}

		$app->redirect(Route::_('index.php?option=com_batirpermi&view=apofadmin&id=' . $id));
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

	private function buildPayload($input): array
	{
		$stateValue = $input->post->getString('state');
		$state = ($stateValue === 'on' || $stateValue === '1') ? 1 : 0;

		$payload = [
			'title' => trim($input->post->getString('title')),
			'reference' => trim($input->post->getString('reference')),
			'type' => trim($input->post->getString('type')),
			'lacated' => trim($input->post->getString('lacated')),
			'caution' => trim($input->post->getString('caution')),
			'created' => $this->normalizeNullableDate($input->post->getString('created')),
			'echeance' => $this->normalizeNullableDate($input->post->getString('echeance')),
			'datcommenc' => $this->normalizeNullableDate($input->post->getString('datcommenc')),
			'datouvoffre' => $this->normalizeNullableDate($input->post->getString('datouvoffre')),
			'params' => trim($input->post->getString('params')),
			'language' => trim($input->post->getString('language')),
			'image' => trim($input->post->getString('image')),
			'state' => $state,
		];

		$payload['support'] = $this->buildSupportJson(
			$input->post->get('LeTitre', [], 'array'),
			$input->post->get('Laref', [], 'array')
		);

		$payload['lesbatirpermis'] = $this->buildPermisJson(
			$input->post->get('titdoc', [], 'array'),
			$input->files->get('leficher', [], 'array'),
			$input->post->get('leficherh', [], 'array')
		);

		$imageJson = $this->buildImageJson($input->files->get('image', null, 'array'));
		if ($imageJson !== null) {
			$payload['image'] = $imageJson;
		}

		return $payload;
	}

	private function buildSupportJson(array $titles, array $refs): ?string
	{
		if (empty($titles)) {
			return null;
		}

		$supportData = [];
		foreach ($titles as $index => $title) {
			$supportData['support' . $index] = [
				'lesupport' => trim((string) $title),
				'lereference' => trim((string) ($refs[$index] ?? '')),
			];
		}

		return json_encode($supportData, JSON_UNESCAPED_UNICODE);
	}

	private function buildPermisJson(array $titles, array $files, array $existing = []): ?string
	{
		if (empty($titles)) {
			return null;
		}

		$payload = [];
		foreach ($titles as $index => $title) {
			$filename = $existing[$index] ?? '';
			$fileData = [
				'error' => $files['error'][$index] ?? 4,
				'name' => $files['name'][$index] ?? '',
				'tmp_name' => $files['tmp_name'][$index] ?? '',
			];

			if ($fileData['error'] !== 4) {
				$uploadedName = $this->uploadFile($fileData);
				if ($uploadedName === null) {
					throw new RuntimeException(Text::_('COM_BATIRPERMI_UPLOAD_FAILED'));
				}
				$filename = $uploadedName;
			}

			$payload['lesbatirpermis' . $index] = [
				'letitre' => trim((string) $title),
				'ledoc' => $filename,
			];
		}

		return json_encode($payload, JSON_UNESCAPED_UNICODE);
	}

	private function buildImageJson(?array $file): ?string
	{
		if (!isset($file) || ($file['error'] ?? 4) === 4) {
			return null;
		}

		$uploadedName = $this->uploadFile($file);
		if ($uploadedName === null) {
			throw new RuntimeException(Text::_('COM_BATIRPERMI_UPLOAD_FAILED'));
		}

		return json_encode([
			'name' => $file['name'] ?? '',
			'type' => $file['type'] ?? '',
			'size' => $file['size'] ?? 0,
			'fname' => basename($uploadedName),
		], JSON_UNESCAPED_UNICODE);
	}

	private function uploadFile(array $file): ?string
	{
		$fileName = $file['name'] ?? '';
		if ($fileName === '' || strpos($fileName, '.') === false) {
			throw new RuntimeException(Text::_('COM_BATIRPERMI_UPLOAD_MISSING_EXTENSION'));
		}

		$timestamp = time();
		$safeName = str_replace(' ', '-', File::makeSafe($fileName));
		$relativePath = 'images/pdf/' . $timestamp . '-' . $safeName;
		$targetPath = JPATH_SITE . '/' . $relativePath;

		if (!File::upload($file['tmp_name'] ?? '', $targetPath)) {
			return null;
		}

		return $relativePath;
	}

	private function normalizeNullableDate(string $value): ?string
	{
		$trimmed = trim($value);

		return $trimmed === '' ? null : $trimmed;
	}
}
