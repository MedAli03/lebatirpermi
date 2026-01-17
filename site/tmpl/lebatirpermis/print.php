<?php
\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$result = $this->printItem ?? null;
$safeId = htmlspecialchars((string) ($result['id'] ?? ''), ENT_QUOTES, 'UTF-8');
$safeTitle = htmlspecialchars((string) ($result['title'] ?? ''), ENT_QUOTES, 'UTF-8');
$safeName = htmlspecialchars((string) ($result['nom'] ?? ''), ENT_QUOTES, 'UTF-8');
$safeCin = htmlspecialchars((string) ($result['cin'] ?? ''), ENT_QUOTES, 'UTF-8');
$safeType = htmlspecialchars((string) ($result['typebatiment'] ?? ''), ENT_QUOTES, 'UTF-8');
$safeIngenieur = htmlspecialchars((string) ($result['ingenieur'] ?? ''), ENT_QUOTES, 'UTF-8');
$safeResult = htmlspecialchars((string) ($result['category_title'] ?? ''), ENT_QUOTES, 'UTF-8');
?>

<style>
#MnihlaTable {
	font-family: 'Droid Arabic Kufi', Tahoma, Geneva, sans-serif !important;
	border-collapse: collapse;
	width: 50%;
	margin: 0 auto;
}

#MnihlaTable td, #MnihlaTable th {
	border: 1px solid #ddd;
	padding: 8px;
}

#MnihlaTable tr:nth-child(even) {
	background-color: #f2f2f2;
}

#MnihlaTable tr:hover {
	background-color: #ddd;
}

#MnihlaTable th {
	padding-top: 12px;
	padding-bottom: 12px;
	text-align: center;
	background-color: #09F;
	color: white;
}
</style>

<?php if (!empty($result)) : ?>
	<table id="MnihlaTable">
		<tr>
			<th><?php echo Text::_('COM_BATIRPERMI_PRINT_UNIQUE'); ?></th>
			<td><?php echo $safeId; ?></td>
		</tr>
		<tr>
			<th><?php echo Text::_('COM_BATIRPERMI_PRINT_DOSSIER'); ?></th>
			<td><?php echo $safeTitle; ?></td>
		</tr>
		<tr>
			<th><?php echo Text::_('COM_BATIRPERMI_PRINT_NAME'); ?></th>
			<td><?php echo $safeName; ?></td>
		</tr>
		<tr>
			<th><?php echo Text::_('COM_BATIRPERMI_PRINT_CIN'); ?></th>
			<td><?php echo $safeCin; ?></td>
		</tr>
		<tr>
			<th><?php echo Text::_('COM_BATIRPERMI_PRINT_TYPE'); ?></th>
			<td><?php echo $safeType; ?></td>
		</tr>
		<tr>
			<th><?php echo Text::_('COM_BATIRPERMI_PRINT_ENGINEER'); ?></th>
			<td><?php echo $safeIngenieur; ?></td>
		</tr>
		<tr>
			<th><?php echo Text::_('COM_BATIRPERMI_PRINT_RESULT'); ?></th>
			<td style="color:#F00;"><?php echo $safeResult; ?></td>
		</tr>
	</table>
	<br>
	<div align="center">
		<input type="button" value="<?php echo Text::_('COM_BATIRPERMI_PRINT_BUTTON'); ?>" onClick="window.print()">
	</div>
	<div align="center">
		<a type="button" href="<?php echo Route::_('index.php?option=com_batirpermi&view=lebatirpermis'); ?>">
			<?php echo Text::_('COM_BATIRPERMI_PRINT_BACK'); ?>
		</a>
	</div>
<?php else : ?>
	<div class="alert alert-warning" role="alert">
		<?php echo Text::_('COM_BATIRPERMI_PRINT_NOT_FOUND'); ?>
	</div>
<?php endif; ?>
