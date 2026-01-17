<?php

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

?>

<form action="<?php echo Route::_('index.php?option=com_batirpermi&view=lebatirpermi&layout=emploi&id=' . (int) $this->item->id); ?>"
	method="post" name="adminForm" id="lebatirpermi-form" class="form-validate">

	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div>
		<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'details', Text::_('COM_BATIRPERMI_LEBATIRPERMI_TAB_DETAILS')); ?>
		<div class="row">
			<div class="col-md-9">
						<?php echo $this->form->renderField('lacated'); ?>
                        <?php echo $this->form->renderField('image'); ?>
						
						<?php echo $this->form->renderField('params'); ?>
						<?php echo $this->form->renderField('downloaded'); ?>
						
						<?php echo $this->form->renderField('created'); ?>
						<?php echo $this->form->renderField('id'); ?>
			</div>
			<div class="col-md-3">
				<div class="card card-light">
					<div class="card-body">
						<?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
					</div>
				</div>
			</div>
		</div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="">
	<?php echo HTMLHelper::_('form.token'); ?>
</form>
