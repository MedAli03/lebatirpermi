<?php

namespace J4xdemos\Component\Batirpermi\Site\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

class PermibatirController extends BaseController
{
    public function search(): void
    {
        $app = Factory::getApplication();
        $input = $this->input;

        if (!$input->isMethod('POST')) {
            $app->enqueueMessage('Requête invalide. Veuillez utiliser le formulaire de recherche.', 'warning');
            $this->setRedirect(Route::_('index.php?option=com_batirpermi&view=lebatirpermis', false));
            return;
        }

        if (!Session::checkToken('post')) {
            $app->enqueueMessage(Text::_('JINVALID_TOKEN'), 'error');
            $this->setRedirect(Route::_('index.php?option=com_batirpermi&view=lebatirpermis', false));
            return;
        }

        $cin = trim((string) $input->post->getString('cin'));
        $numdossier = trim((string) $input->post->getString('numdossier'));
        $messageKey = '';
        $error = null;
        $result = null;

        $app->setUserState('com_batirpermi.lebatirpermis.form', [
            'cin' => $cin,
            'numdossier' => $numdossier,
        ]);
        $app->setUserState('com_batirpermi.lebatirpermis.result', null);
        $app->setUserState('com_batirpermi.lebatirpermis.error', null);
        $app->setUserState('com_batirpermi.lebatirpermis.messageKey', null);

        if (!preg_match('/^\d{8}$/', $cin) || $numdossier === '') {
            $messageKey = 'COM_PERMIBATIR_PERMIBATIRS_INCORRECT';
        } else {
            $db = Factory::getDbo();
            $tableColumns = $db->getTableColumns('#__batirpermi_lebatirpermis', false);
            $categoryColumns = $db->getTableColumns('#__batirpermi_categories', false);

            $numdossierCandidates = ['numdossier', 'num_dossier', 'dossier', 'numDossier'];
            $joinCandidates = ['lacated', 'located', 'catid', 'category_id'];
            $numdossierColumn = null;
            $joinColumn = null;

            if (is_array($tableColumns)) {
                foreach ($numdossierCandidates as $candidate) {
                    if (array_key_exists($candidate, $tableColumns)) {
                        $numdossierColumn = $candidate;
                        break;
                    }
                }
                foreach ($joinCandidates as $candidate) {
                    if (array_key_exists($candidate, $tableColumns)) {
                        $joinColumn = $candidate;
                        break;
                    }
                }
            }

            $hasCinColumn = is_array($tableColumns) && array_key_exists('cin', $tableColumns);
            $hasCategoryTable = is_array($categoryColumns)
                && array_key_exists('id', $categoryColumns)
                && array_key_exists('title', $categoryColumns);

            if (!$hasCinColumn || $numdossierColumn === null || $joinColumn === null || !$hasCategoryTable) {
                $error = 'Configuration de la base de données incomplète. Veuillez contacter l’administrateur.';
                $details = [
                    'hasCin' => $hasCinColumn,
                    'numdossierColumn' => $numdossierColumn,
                    'joinColumn' => $joinColumn,
                    'hasCategoryTable' => $hasCategoryTable,
                ];
                Log::add('Batirpermi search schema mismatch: ' . json_encode($details), Log::ERROR, 'com_batirpermi');
            } else {
                $query = $db->getQuery(true)
                    ->select([
                        'p.id',
                        'p.title',
                        'p.nom',
                        'p.cin',
                        'p.resultat',
                        'p.typebatiment',
                        'p.ingenieur',
                        'c.title AS category_title',
                    ])
                    ->from($db->quoteName('#__batirpermi_lebatirpermis', 'p'))
                    ->join('LEFT', $db->quoteName('#__batirpermi_categories', 'c') . ' ON ' . $db->quoteName('c.id') . ' = ' . $db->quoteName('p.' . $joinColumn))
                    ->where($db->quoteName('p.' . $numdossierColumn) . ' = ' . $db->quote($numdossier))
                    ->where($db->quoteName('p.cin') . ' = ' . $db->quote($cin));

                try {
                    $db->setQuery($query);
                    $result = $db->loadAssoc();
                } catch (\RuntimeException $exception) {
                    $error = 'Erreur lors de la recherche. Veuillez vérifier vos données.';
                    Log::add('Batirpermi search error: ' . $exception->getMessage(), Log::ERROR, 'com_batirpermi');
                }

                if (empty($result) && $error === null) {
                    $messageKey = 'COM_PERMIBATIR_PERMIBATIRS_INCORRECT';
                }
            }
        }

        if ($messageKey !== '') {
            $app->enqueueMessage(Text::_($messageKey), 'warning');
        }

        if ($error !== null) {
            $app->enqueueMessage($error, 'error');
        }

        $app->setUserState('com_batirpermi.lebatirpermis.result', $result);
        $app->setUserState('com_batirpermi.lebatirpermis.error', $error);
        $app->setUserState('com_batirpermi.lebatirpermis.messageKey', $messageKey);

        $view = $this->getView('Lebatirpermis', 'html');
        $view->setLayout('default');
        $view->display();
    }

    public function print(): void
    {
        $app = Factory::getApplication();
        $rawId = $this->input->get('id', 0, 'raw');
        $id = 0;

        if (is_array($rawId)) {
            $id = (int) ($rawId[0] ?? 0);
        } else {
            $id = (int) $rawId;
        }

        if ($id <= 0) {
            $cid = $this->input->get('cid', [], 'array');
            $id = (int) ($cid[0] ?? 0);
        }

        if ($id <= 0) {
            $app->enqueueMessage(Text::_('COM_PERMIBATIR_PERMIBATIRS_INCORRECT'), 'error');
            $this->setRedirect(Route::_('index.php?option=com_batirpermi&view=lebatirpermis', false));
            return;
        }

        $model = $this->getModel('Lebatirpermis');
        $item = $model->getItemById($id);

        if (empty($item)) {
            $app->enqueueMessage(Text::_('COM_PERMIBATIR_PERMIBATIRS_INCORRECT'), 'error');
            $this->setRedirect(Route::_('index.php?option=com_batirpermi&view=lebatirpermis', false));
            return;
        }

        $view = $this->getView('Lebatirpermis', 'html');
        $view->setLayout('print');
        $view->set('item', $item);
        $view->display();
    }
}
