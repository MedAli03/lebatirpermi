<?php
\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\Database\ParameterType;

?>

<div class="container my-4">

    <h3 class="text-center text-danger mb-4">
        <?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_TITRE'); ?>
    </h3>

<?php
$app = Factory::getApplication();
$input = $app->input;
$cin = $input->post->getString('cin');
$numdossier = $input->post->getString('numdossier');

if ($input->post->get('submit')) {
    if (!Session::checkToken()) {
        echo '<div class="alert alert-danger text-center">' . Text::_('JINVALID_TOKEN') . '</div>';
        echo '<div class="text-center my-3"><a class="btn btn-primary" href="services/permi-batir">' . Text::_('COM_PERMIBATIR_PERMIBATIRS_SEARCHAGAIN') . '</a></div>';
    } elseif (!empty($cin) && !empty($numdossier)) {
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
                'c.title AS category_title'
            ])
            ->from($db->quoteName('#__batirpermi_lebatirpermis', 'p'))
            ->join('LEFT', $db->quoteName('#__batirpermi_categories', 'c') . ' ON c.id = p.lacated')
            ->where($db->quoteName('p.numdossier') . ' = :numdossier')
            ->where($db->quoteName('p.cin') . ' = :cin')
            ->bind(':numdossier', $numdossier, ParameterType::STRING)
            ->bind(':cin', $cin, ParameterType::STRING);

        $db->setQuery($query);
        $result = $db->loadAssoc();

        if (!empty($result)) {
            $safeId = htmlspecialchars((string) ($result['id'] ?? ''), ENT_QUOTES, 'UTF-8');
            $safeTitle = htmlspecialchars((string) ($result['title'] ?? ''), ENT_QUOTES, 'UTF-8');
            $safeName = htmlspecialchars((string) ($result['name'] ?? $result['nom'] ?? ''), ENT_QUOTES, 'UTF-8');
            $safeCin = htmlspecialchars((string) ($result['cin'] ?? ''), ENT_QUOTES, 'UTF-8');
            $safeType = htmlspecialchars((string) ($result['typebatiment'] ?? ''), ENT_QUOTES, 'UTF-8');
            $safeIngenieur = htmlspecialchars((string) ($result['ingenieur'] ?? ''), ENT_QUOTES, 'UTF-8');
            $safeResultat = htmlspecialchars((string) ($result['resultat'] ?? ''), ENT_QUOTES, 'UTF-8');
            echo '<table class="table table-bordered table-striped w-75 mx-auto">';
            echo '<tr><th>' . Text::_('COM_PERMIBATIR_PERMIBATIRS_NUMUNIQUE') . '</th><td>' . $safeId . '</td></tr>';
            echo '<tr><th>' . Text::_('COM_PERMIBATIR_PERMIBATIRS_NUMDOSSIER') . '</th><td>' . $safeTitle . '</td></tr>';
            echo '<tr><th>' . Text::_('COM_PERMIBATIR_PERMIBATIRS_NAME') . '</th><td>' . $safeName . '</td></tr>';
            echo '<tr><th>' . Text::_('COM_PERMIBATIR_PERMIBATIRS_CIN') . '</th><td>' . $safeCin . '</td></tr>';
            echo '<tr><th>' . Text::_('COM_PERMIBATIR_PERMIBATIRS_TYPE') . '</th><td>' . $safeType . '</td></tr>';
            echo '<tr><th>' . Text::_('COM_PERMIBATIR_PERMIBATIRS_INGENIEUR') . '</th><td>' . $safeIngenieur . '</td></tr>';
            echo '<tr><th>' . Text::_('COM_PERMIBATIR_PERMIBATIRS_RESULT') . '</th><td class="text-danger fw-bold">' . $safeResultat . '</td></tr>';
            echo '</table>';

            echo '<div class="text-center my-4">';
            echo '<a class="btn btn-primary me-2" href="services/permi-batir">' . Text::_('COM_PERMIBATIR_PERMIBATIRS_SEARCHAGAIN') . '</a>';
            echo '<a class="btn btn-secondary" href="' . Route::_('components/com_batirpermi/tmpl/lebatirpermis/print.php?' . http_build_query(['numdossier' => $numdossier, 'cin' => $cin])) . '" target="_blank">Imprimer</a>';
            echo '</div>';
        } else {
            echo '<div class="alert alert-danger text-center">' . Text::_('COM_PERMIBATIR_PERMIBATIRS_INCORRECT') . '</div>';
            echo '<div class="text-center my-3"><a class="btn btn-primary" href="services/permi-batir">' . Text::_('COM_PERMIBATIR_PERMIBATIRS_SEARCHAGAIN') . '</a></div>';
        }
    } else {
        echo '<div class="alert alert-warning text-center">' . Text::_('COM_PERMIBATIR_PERMIBATIRS_INCORRECT') . '</div>';
        echo '<div class="text-center my-3"><a class="btn btn-primary" href="services/permi-batir">' . Text::_('COM_PERMIBATIR_PERMIBATIRS_SEARCHAGAIN') . '</a></div>';
    }
} else {
?>

    <div class="card w-75 mx-auto">
        <div class="card-body">
            <form action="https://www.msaken.site/espaces/fda-almwatn/mtabt-rkhs-albna?view=lebatirpermis" method="post" class="row g-3">
                <div class="col-md-6">
                    <label for="cin" class="form-label"><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_CIN'); ?></label>
                    <input type="text" name="cin" id="cin" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="numdossier" class="form-label"><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_NUMDOSSIER'); ?></label>
                    <input type="text" name="numdossier" id="numdossier" class="form-control" required>
                </div>
                <div class="col-12 text-center mt-3">
                    <input type="submit" name="submit" class="btn btn-primary" value="<?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_SEARCH'); ?>">
                </div>
                <?php echo HTMLHelper::_('form.token'); ?>
            </form>
        </div>
    </div>

<?php } ?>

</div>
