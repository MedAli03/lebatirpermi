<?php

namespace J4xdemos\Component\Batirpermi\Site\View\Lebatirpermis;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
    public array $form   = ['cin' => '', 'numdossier' => ''];
    public mixed $result = null;
    public ?string $error = null;

    public bool $isRtl = false;
    public string $langTag = 'en-GB';

    public function display($tpl = null)
    {
        $app  = Factory::getApplication();
        $lang = Factory::getLanguage();

        $this->langTag = (string) $lang->getTag();
        $this->isRtl   = str_starts_with($this->langTag, 'ar');

        // Read state prepared by the controller (recommended pattern)
        $this->result = $app->getUserState('com_batirpermi.lebatirpermis.result', null);
        $this->error  = $app->getUserState('com_batirpermi.lebatirpermis.error', null);

        $savedForm = $app->getUserState('com_batirpermi.lebatirpermis.form', []);
        if (is_array($savedForm)) {
            $this->form['cin']       = isset($savedForm['cin']) ? (string) $savedForm['cin'] : '';
            $this->form['numdossier'] = isset($savedForm['numdossier']) ? (string) $savedForm['numdossier'] : '';
        }

        // Optional: set a nicer page title (does not change layout, safe UI improvement)
        $doc = $app->getDocument();
        $doc->setTitle(Text::_('COM_BATIRPERMI_LEBATIRPERMIS_TITRE'));

        // Optional: clear flash-like states so refresh does not repeat messages
        // Comment these out if you want results to remain after refresh.
        // $app->setUserState('com_batirpermi.lebatirpermis.result', null);
        // $app->setUserState('com_batirpermi.lebatirpermis.error', null);

        return parent::display($tpl);
    }
}
