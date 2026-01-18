<?php
\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$result = $this->get('result') ?? [];
$isSubmitted = (bool) ($this->get('isSubmitted') ?? false);
$messageKey = $this->get('messageKey') ?? '';
$cin = $this->get('cin') ?? '';
$numdossier = $this->get('numdossier') ?? '';
?>

<div class="container my-4">

    <h3 class="text-center text-danger mb-4">
        <?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_TITRE'); ?>
    </h3>

<?php if ($isSubmitted) : ?>
    <?php if (!empty($result)) : ?>
        <table class="table table-bordered table-striped w-75 mx-auto">
            <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_NUMUNIQUE'); ?></th><td><?php echo $result['id']; ?></td></tr>
            <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_NUMDOSSIER'); ?></th><td><?php echo $result['title']; ?></td></tr>
            <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_NAME'); ?></th><td><?php echo $result['name']; ?></td></tr>
            <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_CIN'); ?></th><td><?php echo $result['cin']; ?></td></tr>
            <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_TYPE'); ?></th><td><?php echo $result['typebatiment']; ?></td></tr>
            <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_INGENIEUR'); ?></th><td><?php echo $result['ingenieur']; ?></td></tr>
            <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_RESULT'); ?></th><td class="text-danger fw-bold"><?php echo $result['resultat']; ?></td></tr>
        </table>

        <div class="text-center my-4">
            <a class="btn btn-primary me-2" href="services/permi-batir"><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_SEARCHAGAIN'); ?></a>
            <a class="btn btn-secondary" href="<?php echo Route::_('components/com_batirpermi/tmpl/lebatirpermis/print.php?numdossier=' . $numdossier . '&cin=' . $cin); ?>" target="_blank">Imprimer</a>
        </div>
    <?php else : ?>
        <div class="alert alert-danger text-center"><?php echo Text::_($messageKey ?: 'COM_PERMIBATIR_PERMIBATIRS_INCORRECT'); ?></div>
        <div class="text-center my-3"><a class="btn btn-primary" href="services/permi-batir"><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_SEARCHAGAIN'); ?></a></div>
    <?php endif; ?>
<?php else : ?>

    <div class="card w-75 mx-auto">
        <div class="card-body">
            <form action="<?php echo Route::_('index.php?option=com_batirpermi&task=permibatir.search'); ?>" method="post" class="row g-3">
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

<?php endif; ?>

</div>
