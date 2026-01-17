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

<form action="<?php echo Route::_('index.php?option=com_batirpermi&view=suivies'); ?>" method="post" name="adminForm" id="adminForm">

<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

<div class="table-responsive">
	<table class="table table-striped">
	
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
		<th scope="col">
			<?php echo HTMLHelper::_('searchtools.sort', 'COM_BATIRPERMI_LEBATIRPERMIS_SUIVIE', 'a.title', $listDirn, $listOrder); ?>
		</th>
		<th scope="col"><?php echo Text::_('categorie'); ?></th>
        <th scope="col"><?php echo Text::_('COM_BATIRPERMI_LEBATIRPERMIS_CREATED'); ?></th>
		<th scope="col"><?php echo Text::_('écheance'); ?></th>
        	<th scope="col"><?php echo Text::_('Batirpermi'); ?></th>
            <th scope="col"><?php echo Text::_('hits'); ?></th>
        <th scope="col"><?php echo Text::_('Language'); ?></th>
		<th scope="col">
			<?php echo HTMLHelper::_('searchtools.sort', 'id', 'a.id', $listDirn, $listOrder); ?>
		</th>
        
		
		
	</tr>
	</thead>
	<tbody>
	<?php foreach ($this->items as $i => $item) :
		$canChange        = $user->authorise('core.edit.state', 'com_cuntrybase.suivie.' . $item->id);
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
					'task_prefix' => 'suivies.',
					//'disabled' => $workflow_state || !$canChange,
					'id' => 'state-' . $item->id
				];

				echo (new PublishedButton)->render((int) $item->state, $i, $options);
			?>
		</td>
		<td>
			<a href="<?php echo Route::_('index.php?option=com_batirpermi&task=suivie.edit&id=' . $item->id); ?>"
			title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->title); ?>">
			<?php echo $this->escape($item->title); ?></a>
		</td>
		<td><?php echo $item->categorie_title; ?></td>        
        <td><?php echo $item->created; ?></td>
		<td><?php echo $item->echeance; ?></td>
        <td><a href="<?php echo  Uri::root() . $item->image; ?>" target="_blank"><i class="far fa-file-alt"></i></a></td>
		<td><?php echo $item->downloaded; ?></td>
        <td><?php echo $item->language; ?></td>
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