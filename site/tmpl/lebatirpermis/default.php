<?php
\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

?>

<div class="container my-4">

    <h3 class="text-center text-danger mb-4">
        <?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_TITRE'); ?>
    </h3>

<?php
$cin = $this->cin ?? '';
$numdossier = $this->numdossier ?? '';
$result = $this->result ?? null;

$searchAgainUrl = Route::_('index.php?option=com_batirpermi&view=lebatirpermis');
?>

<?php if ($this->isSubmitted) : ?>
    <?php if (!$this->tokenValid) : ?>
        <div class="alert alert-danger text-center" role="alert">
            <?php echo Text::_('JINVALID_TOKEN'); ?>
        </div>
        <div class="text-center my-3">
            <a class="btn btn-primary" href="<?php echo $searchAgainUrl; ?>">
                <?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_SEARCHAGAIN'); ?>
            </a>
        </div>
    <?php elseif ($cin !== '' && $numdossier !== '') : ?>
        <?php if (!empty($result)) : ?>
            <?php
            $safeId = htmlspecialchars((string) ($result['id'] ?? ''), ENT_QUOTES, 'UTF-8');
            $safeTitle = htmlspecialchars((string) ($result['title'] ?? ''), ENT_QUOTES, 'UTF-8');
            $safeName = htmlspecialchars((string) ($result['name'] ?? $result['nom'] ?? ''), ENT_QUOTES, 'UTF-8');
            $safeCin = htmlspecialchars((string) ($result['cin'] ?? ''), ENT_QUOTES, 'UTF-8');
            $safeType = htmlspecialchars((string) ($result['typebatiment'] ?? ''), ENT_QUOTES, 'UTF-8');
            $safeIngenieur = htmlspecialchars((string) ($result['ingenieur'] ?? ''), ENT_QUOTES, 'UTF-8');
            $safeResultat = htmlspecialchars((string) ($result['resultat'] ?? ''), ENT_QUOTES, 'UTF-8');
            $printUrl = Route::_('index.php?option=com_batirpermi&view=lebatirpermis&layout=print&' . http_build_query(['numdossier' => $numdossier, 'cin' => $cin]));
            ?>
            <table class="table table-bordered table-striped w-75 mx-auto">
                <tr>
                    <th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_NUMUNIQUE'); ?></th>
                    <td><?php echo $safeId; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_NUMDOSSIER'); ?></th>
                    <td><?php echo $safeTitle; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_NAME'); ?></th>
                    <td><?php echo $safeName; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_CIN'); ?></th>
                    <td><?php echo $safeCin; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_TYPE'); ?></th>
                    <td><?php echo $safeType; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_INGENIEUR'); ?></th>
                    <td><?php echo $safeIngenieur; ?></td>
                </tr>
                <tr>
                    <th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_RESULT'); ?></th>
                    <td class="text-danger fw-bold"><?php echo $safeResultat; ?></td>
                </tr>
            </table>

            <div class="text-center my-4">
                <a class="btn btn-primary me-2" href="<?php echo $searchAgainUrl; ?>">
                    <?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_SEARCHAGAIN'); ?>
                </a>
                <a class="btn btn-secondary" href="<?php echo $printUrl; ?>" target="_blank" rel="noopener">
                    <?php echo Text::_('COM_BATIRPERMI_PRINT_BUTTON'); ?>
                </a>
            </div>
        <?php else : ?>
            <div class="alert alert-danger text-center" role="alert">
                <?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_INCORRECT'); ?>
            </div>
            <div class="text-center my-3">
                <a class="btn btn-primary" href="<?php echo $searchAgainUrl; ?>">
                    <?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_SEARCHAGAIN'); ?>
                </a>
            </div>
        <?php endif; ?>
    <?php else : ?>
        <div class="alert alert-warning text-center" role="alert">
            <?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_INCORRECT'); ?>
        </div>
        <div class="text-center my-3">
            <a class="btn btn-primary" href="<?php echo $searchAgainUrl; ?>">
                <?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_SEARCHAGAIN'); ?>
            </a>
        </div>
    <?php endif; ?>
<?php else : ?>

    <div class="card w-75 mx-auto">
        <div class="card-body">
            <form action="<?php echo $searchAgainUrl; ?>" method="post" class="row g-3">
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
