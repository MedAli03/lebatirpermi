<?php
\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$item = $this->item ?? null;
if (is_object($item)) {
    $item = get_object_vars($item);
}
if (!is_array($item)) {
    $item = [];
}
?>

<div class="permibatir-print">
    <style>
        .permibatir-print {
            font-family: Arial, sans-serif;
            color: #1b1b1b;
            padding: 24px;
        }
        .permibatir-print .print-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 24px;
            max-width: 760px;
            margin: 0 auto;
        }
        .permibatir-print h2 {
            margin: 0 0 16px;
            text-align: center;
        }
        .permibatir-print table {
            width: 100%;
            border-collapse: collapse;
        }
        .permibatir-print th,
        .permibatir-print td {
            border: 1px solid #d9d9d9;
            padding: 10px 12px;
            text-align: start;
        }
        .permibatir-print th {
            width: 40%;
            background: #f5f5f5;
        }
        .permibatir-print .result {
            color: #b00;
            font-weight: 700;
        }
        @media print {
            .permibatir-print {
                padding: 0;
            }
            .permibatir-print .print-card {
                border: none;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>

    <div class="print-card">
        <h2><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_TITRE'); ?></h2>
        <table>
            <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_NUMUNIQUE'); ?></th><td><?php echo htmlspecialchars($item['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td></tr>
            <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_NUMDOSSIER'); ?></th><td><?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td></tr>
            <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_NAME'); ?></th><td><?php echo htmlspecialchars($item['name'] ?? $item['nom'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td></tr>
            <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_CIN'); ?></th><td><?php echo htmlspecialchars($item['cin'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td></tr>
            <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_TYPE'); ?></th><td><?php echo htmlspecialchars($item['typebatiment'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td></tr>
            <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_INGENIEUR'); ?></th><td><?php echo htmlspecialchars($item['ingenieur'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td></tr>
            <tr><th><?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_RESULT'); ?></th><td class="result"><?php echo htmlspecialchars($item['resultat'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td></tr>
        </table>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        window.print();
    });
</script>
