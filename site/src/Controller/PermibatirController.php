<?php

namespace J4xdemos\Component\Batirpermi\Site\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

class PermibatirController extends BaseController
{
    public function search(): void
    {
        $input = $this->input;
        $cin = $input->post->getString('cin');
        $numdossier = $input->post->getString('numdossier');
        $isSubmitted = $input->post->get('submit') !== null;
        $result = [];
        $messageKey = '';

        if ($isSubmitted) {
            if ($cin !== '' && $numdossier !== '') {
                $db = Factory::getDbo();
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
                    ->join('LEFT', $db->quoteName('#__batirpermi_categories', 'c') . ' ON c.id = p.lacated')
                    ->where($db->quoteName('p.numdossier') . ' = ' . $db->quote($numdossier))
                    ->where($db->quoteName('p.cin') . ' = ' . $db->quote($cin));

                $db->setQuery($query);
                $result = (array) $db->loadAssoc();

                if (empty($result)) {
                    $messageKey = 'COM_PERMIBATIR_PERMIBATIRS_INCORRECT';
                }
            } else {
                $messageKey = 'COM_PERMIBATIR_PERMIBATIRS_INCORRECT';
            }
        }

        $view = $this->getView('Lebatirpermis', 'html');
        $view->setLayout('default');
        $view->set('result', $result);
        $view->set('isSubmitted', $isSubmitted);
        $view->set('messageKey', $messageKey);
        $view->set('cin', $cin);
        $view->set('numdossier', $numdossier);
        $view->display();
    }
}
