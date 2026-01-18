<?php
\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$result = $this->result ?? null;
$error = $this->error ?? null;
$form = is_array($this->form ?? null) ? $this->form : ['cin' => '', 'numdossier' => ''];
$lang = Factory::getApplication()->getLanguage();
$isRtl = $lang->isRtl();
$printUrl = '';
if (is_array($result) && !empty($result['id'])) {
    $printUrl = Route::_(
        'index.php?option=com_batirpermi&task=permibatir.print&id=' . (int) $result['id'] . '&tmpl=component'
    );
}
?>

<div class="container my-4 permibatir-wrapper" <?php echo $isRtl ? 'dir="rtl"' : ''; ?>>
    <style>
        .permibatir-wrapper .permibatir-card {
            max-width: 860px;
            margin: 0 auto;
        }
        .permibatir-wrapper .permibatir-section {
            margin-bottom: 2rem;
        }
        .permibatir-wrapper .permibatir-table th {
            width: 40%;
        }
    </style>

    <div class="permibatir-section text-center">
        <h3 class="text-danger mb-2">
            <?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_TITRE'); ?>
        </h3>
    </div>

    <div class="card permibatir-card permibatir-section shadow-sm">
        <div class="card-body">
            <form action="<?php echo Route::_('index.php?option=com_batirpermi&task=permibatir.search'); ?>" method="post" class="row g-3">
                <div class="col-12 col-md-6">
                    <label for="cin" class="form-label fw-semibold"><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_CIN'); ?></label>
                    <input
                        type="text"
                        name="cin"
                        id="cin"
                        class="form-control"
                        placeholder="<?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_CIN'); ?>"
                        value="<?php echo htmlspecialchars($form['cin'], ENT_QUOTES, 'UTF-8'); ?>"
                        required
                    >
                    <div class="form-text"><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_CIN'); ?></div>
                </div>
                <div class="col-12 col-md-6">
                    <label for="numdossier" class="form-label fw-semibold"><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_NUMDOSSIER'); ?></label>
                    <input
                        type="text"
                        name="numdossier"
                        id="numdossier"
                        class="form-control"
                        placeholder="<?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_NUMDOSSIER'); ?>"
                        value="<?php echo htmlspecialchars($form['numdossier'], ENT_QUOTES, 'UTF-8'); ?>"
                        required
                    >
                    <div class="form-text"><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_NUMDOSSIER'); ?></div>
                </div>
                <div class="col-12 text-center mt-3">
                    <button type="submit" name="submit" class="btn btn-primary px-4">
                        <?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_SEARCH'); ?>
                    </button>
                </div>
                <?php echo HTMLHelper::_('form.token'); ?>
            </form>
        </div>
    </div>

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger text-center permibatir-card" role="alert">
            <?php echo htmlspecialchars(Text::_($error), ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($result)) : ?>
        <div class="card permibatir-card permibatir-section shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-striped permibatir-table mb-4">
                    <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_NUMUNIQUE'); ?></th><td><?php echo htmlspecialchars($result['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                    <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_NUMDOSSIER'); ?></th><td><?php echo htmlspecialchars($result['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                    <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_NAME'); ?></th><td><?php echo htmlspecialchars($result['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                    <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_CIN'); ?></th><td><?php echo htmlspecialchars($result['cin'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                    <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_TYPE'); ?></th><td><?php echo htmlspecialchars($result['typebatiment'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                    <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_INGENIEUR'); ?></th><td><?php echo htmlspecialchars($result['ingenieur'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                    <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_RESULT'); ?></th><td class="text-danger fw-bold"><?php echo htmlspecialchars($result['resultat'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                </table>

                <div class="text-center">
                    <a class="btn btn-outline-primary me-2" href="services/permi-batir"><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_SEARCHAGAIN'); ?></a>
                    <?php if ($printUrl !== '') : ?>
                        <a class="btn btn-secondary" href="<?php echo htmlspecialchars($printUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">Imprimer</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php elseif (empty($error)) : ?>
        <div class="card permibatir-card permibatir-section shadow-sm">
            <div class="card-body text-center">
                <p class="mb-0 text-muted"><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_SEARCH'); ?></p>
            </div>
        </div>
    <?php endif; ?>

</div>
