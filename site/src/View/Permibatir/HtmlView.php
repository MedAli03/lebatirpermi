<?php

namespace J4xdemos\Component\Batirpermi\Site\View\Permibatir;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
    public $result = null;
    public $error = null;
    public $form = [
        'cin' => '',
        'numdossier' => '',
    ];

    public function display($tpl = null)
    {
        $app = Factory::getApplication();

        $result = $app->getUserState('com_batirpermi.permibatir.result');
        $error = $app->getUserState('com_batirpermi.permibatir.error');
        $form = $app->getUserState('com_batirpermi.permibatir.form', []);

        $this->result = is_array($result) || is_object($result) ? $result : null;
        $this->error = is_string($error) && $error !== '' ? $error : null;

        if (!is_array($form)) {
            $form = [];
        }

        $this->form = [
            'cin' => isset($form['cin']) ? (string) $form['cin'] : '',
            'numdossier' => isset($form['numdossier']) ? (string) $form['numdossier'] : '',
        ];

        $app->setUserState('com_batirpermi.permibatir.result', null);
        $app->setUserState('com_batirpermi.permibatir.error', null);

        parent::display($tpl);
    }
}
