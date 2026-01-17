<?php
/**
 * @package     Batirpermi.Administrator
 * @subpackage  com_batirpermi
 *
 * @copyright   (C) 2022 Clifford E Ford
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Button\PublishedButton;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

$user      = Factory::getUser();


?>
<style>
a[target="_blank"]::before {
  content: "";
}
</style>
<form action="<?php echo Route::_('index.php?option=com_batirpermi'); ?>" method="post" name="adminForm" id="adminForm">

<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

<div class="table-responsive">
	<table class="table table-striped table-sm">
	
	<thead>
	<tr>
		<td class="w-1 text-center">
			<?php echo HTMLHelper::_('grid.checkall'); ?>
		</td>
        <th scope="col" class="w-1 text-center d-none d-md-table-cell">
			<?php echo HTMLHelper::_('searchtools.sort', '', 'a.datcommenc', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-sort'); ?>
        </th>
		<th scope="col" class="w-1 text-center">
			<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
		</th>
		<th scope="col"><?php echo HTMLHelper::_('searchtools.sort', 'Réf Dossier', 'a.title', $listDirn, $listOrder); ?></th>        
        <th scope="col"><?php echo HTMLHelper::_('searchtools.sort', 'Created', 'a.created', $listDirn, $listOrder); ?></th>
        <th scope="col"><?php echo HTMLHelper::_('searchtools.sort', 'NOM', 'a.nom', $listDirn, $listOrder); ?></th>
        <th scope="col"><?php echo HTMLHelper::_('searchtools.sort', 'CIN', 'a.cin', $listDirn, $listOrder); ?></th>
        <th scope="col"><?php echo Text::_(' نوع المبنى '); ?></th>
		<th scope="col"><?php echo Text::_(' رأي الــلجنة '); ?></th>
        <th scope="col"><?php echo Text::_('العنوان'); ?></th>
		<th scope="col"><?php echo Text::_(' المساحةم² '); ?></th>
        	<th scope="col"><?php echo Text::_(' تاريخ الرخصة '); ?></th>
            <th scope="col"><?php echo Text::_(' معلوم الرخصة '); ?></th>
        <th scope="col"><?php echo Text::_(' عدد الرخصة '); ?></th>
		<th scope="col"><?php echo JHtml::_('searchtools.sort', 'id', 'id', $listDirn, $listOrder); ?></th>
		
		
	</tr>
	</thead>
	<tbody>
	<?php foreach ($this->items as $i => $item) :
		$canChange        = $user->authorise('core.edit.state', 'com_cuntrybase.lebatirpermi.' . $item->id);
	?>
	<tr>
		<td class="text-center">
			<?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->title); ?>
		</td>
        <td class="text-center d-none d-md-table-cell">
			<?php
            $iconClass = '';
            if (!$canChange) {
                $iconClass = ' inactive';
            } elseif (!$saveOrder) {
                $iconClass = ' inactive" title="' . Text::_('JORDERINGDISABLED');
            }
            ?>
            <span class="sortable-handler<?php echo $iconClass ?>">
                <span class="icon-ellipsis-v"></span>
            </span>
            <?php if ($canChange && $saveOrder) : ?>
                <input type="text" class="hidden" name="order[]" size="5" value="<?php echo $item->datcommenc; ?>">
            <?php endif; ?>
        </td>
		<td class="article-status text-center">
			<?php
				$options = [
					'task_prefix' => 'lebatirpermis.',
					//'disabled' => $workflow_state || !$canChange,
					'id' => 'state-' . $item->id
				];

				echo (new PublishedButton)->render((int) $item->state, $i, $options);
			?>
		</td>
		<td>
			<a href="<?php echo Route::_('index.php?option=com_batirpermi&task=lebatirpermi.edit&id=' . $item->id); ?>"
			title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->title); ?>">
			<?php echo $this->escape($item->title); ?></a>
		</td>
		  
        <td><?php echo $item->created; ?></td>
		<td><?php echo $item->nom; ?></td>        
        
        <td><?php echo $item->cin; ?></td>
        <td><?php echo $item->typebatiment; ?></td>
        <td><?php echo $item->aviscomission; ?></td>
        <td><?php echo $item->adresse; ?></td>
        <td><?php echo $item->superficie; ?></td>
        
        <td><?php echo $item->datepermi; ?></td>
		<td><?php echo $item->prix; ?></td>
        <td><?php echo $item->numpermi; ?></td>
        <td><?php echo $item->id; ?></td>
		
		
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
</div>

<?php echo $this->pagination->getListFooter(); ?>
<input type="hidden" name="task" value="">
<input type="hidden" name="boxchecked" value="0">
<?php echo HTMLHelper::_('form.token'); ?>

</form>