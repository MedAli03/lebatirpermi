<?php
\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$cin       = isset($this->form['cin']) ? (string) $this->form['cin'] : '';
$numdossier = isset($this->form['numdossier']) ? (string) $this->form['numdossier'] : '';

$isResultArray  = is_array($this->result);
$isResultObject = is_object($this->result);
?>
<div class="bp-wrap">

  <div class="container py-4">
    <div class="bp-head mb-4">
      <h2 class="bp-title">
        <?php echo Text::_('COM_PERMIBATIR_PERMIBATIRS_TITRE'); ?>
      </h2>
      <p class="bp-sub">
        <?php echo Text::_('COM_BATIRPERMI_PERMIBATIR_HELP') ?: 'Entrez votre CIN et le numéro de dossier pour afficher le résultat.'; ?>
      </p>
    </div>

    <?php if (!empty($this->error)) : ?>
      <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
        <span class="bp-dot bp-dot-danger" aria-hidden="true"></span>
        <div>
          <div class="fw-semibold mb-1"><?php echo Text::_('JERROR_AN_ERROR_HAS_OCCURRED'); ?></div>
          <div><?php echo htmlspecialchars($this->error, ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
      </div>
    <?php endif; ?>

    <div class="card bp-card mb-4">
      <div class="card-body p-4">
        <form method="post" action="<?php echo Route::_('index.php?option=com_batirpermi&task=permibatir.search'); ?>" class="row g-3">
          <div class="col-12 col-md-5">
            <label class="form-label bp-label" for="bpCin">
              <?php echo Text::_('COM_BATIRPERMI_FIELD_CIN_LABEL') ?: 'CIN'; ?>
            </label>
            <input
              id="bpCin"
              name="cin"
              type="text"
              class="form-control bp-input"
              value="<?php echo htmlspecialchars($cin, ENT_QUOTES, 'UTF-8'); ?>"
              inputmode="numeric"
              autocomplete="off"
              placeholder="<?php echo Text::_('COM_BATIRPERMI_FIELD_CIN_PLACEHOLDER') ?: 'Ex: 12345678'; ?>"
              required
            >
            <div class="form-text bp-hint">
              <?php echo Text::_('COM_BATIRPERMI_FIELD_CIN_HINT') ?: '8 chiffres.'; ?>
            </div>
          </div>

          <div class="col-12 col-md-5">
            <label class="form-label bp-label" for="bpDossier">
              <?php echo Text::_('COM_BATIRPERMI_FIELD_NUMDOSSIER_LABEL') ?: 'Numéro de dossier'; ?>
            </label>
            <input
              id="bpDossier"
              name="numdossier"
              type="text"
              class="form-control bp-input"
              value="<?php echo htmlspecialchars($numdossier, ENT_QUOTES, 'UTF-8'); ?>"
              autocomplete="off"
              placeholder="<?php echo Text::_('COM_BATIRPERMI_FIELD_NUMDOSSIER_PLACEHOLDER') ?: 'Ex: 2024/0158'; ?>"
              required
            >
            <div class="form-text bp-hint">
              <?php echo Text::_('COM_BATIRPERMI_FIELD_NUMDOSSIER_HINT') ?: 'Comme indiqué sur votre reçu.'; ?>
            </div>
          </div>

          <div class="col-12 col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100 bp-btn">
              <?php echo Text::_('COM_BATIRPERMI_ACTION_SEARCH') ?: 'Rechercher'; ?>
            </button>
          </div>
        </form>
      </div>
    </div>

    <?php if ($this->result) : ?>
      <div class="card bp-card">
        <div class="card-header bg-transparent py-3 px-4">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
              <span class="bp-dot bp-dot-ok" aria-hidden="true"></span>
              <div class="bp-result-title">
                <?php echo Text::_('COM_BATIRPERMI_RESULT_TITLE') ?: 'Résultat'; ?>
              </div>
            </div>

            <?php
              // Allow print if the result has an id
              $id = null;
              if ($isResultArray && isset($this->result['id'])) {
                $id = (int) $this->result['id'];
              } elseif ($isResultObject && isset($this->result->id)) {
                $id = (int) $this->result->id;
              }
            ?>
            <?php if (!empty($id)) : ?>
              <a class="btn btn-outline-secondary btn-sm"
                 href="<?php echo Route::_('index.php?option=com_batirpermi&task=permibatir.print&id=' . (int) $id . '&tmpl=component'); ?>"
                 target="_blank" rel="noopener">
                <?php echo Text::_('COM_BATIRPERMI_ACTION_PRINT') ?: 'Imprimer'; ?>
              </a>
            <?php endif; ?>
          </div>
        </div>

        <div class="card-body p-4">
          <div class="row g-3">
            <?php
              // Helper to read a field from array or object
              $get = function (string $key) use ($isResultArray, $isResultObject) {
                if ($isResultArray && isset($this->result[$key])) {
                  return $this->result[$key];
                }
                if ($isResultObject && isset($this->result->$key)) {
                  return $this->result->$key;
                }
                return null;
              };

              $fields = [
                ['label' => 'Titre',            'key' => 'title'],
                ['label' => 'Nom',              'key' => 'nom'],
                ['label' => 'CIN',              'key' => 'cin'],
                ['label' => 'Résultat',          'key' => 'resultat'],
                ['label' => 'Type de bâtiment', 'key' => 'typebatiment'],
                ['label' => 'Ingénieur',        'key' => 'ingenieur'],
                ['label' => 'Catégorie',        'key' => 'category_title'],
              ];
            ?>

            <?php foreach ($fields as $f) :
              $val = $get($f['key']);
              if ($val === null || $val === '') { continue; }
            ?>
              <div class="col-12 col-md-6">
                <div class="bp-field">
                  <div class="bp-field-label"><?php echo htmlspecialchars($f['label'], ENT_QUOTES, 'UTF-8'); ?></div>
                  <div class="bp-field-value"><?php echo htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

    <?php elseif (empty($this->error)) : ?>
      <div class="bp-empty">
        <div class="bp-empty-ico" aria-hidden="true">⌁</div>
        <div class="bp-empty-title"><?php echo Text::_('COM_BATIRPERMI_EMPTY_TITLE') ?: 'Aucun résultat pour le moment'; ?></div>
        <div class="bp-empty-text"><?php echo Text::_('COM_BATIRPERMI_EMPTY_TEXT') ?: 'Remplissez le formulaire puis lancez la recherche.'; ?></div>
      </div>
    <?php endif; ?>
  </div>
</div>

<style>
.bp-wrap{--bp-bg:#f6f8fc;--bp-card:#fff;--bp-text:#0f172a;--bp-muted:#475569;--bp-line:#e9eef6;--bp-shadow:0 10px 26px rgba(15,23,42,.06);--bp-radius:18px;--bp-focus:0 0 0 .2rem rgba(13,110,253,.15)}
.bp-wrap{background:var(--bp-bg); color:var(--bp-text)}
.bp-head{max-width:900px}
.bp-title{margin:0; font-weight:750; letter-spacing:.2px}
.bp-sub{margin:.5rem 0 0; color:var(--bp-muted)}
.bp-card{border:1px solid var(--bp-line); border-radius:var(--bp-radius); box-shadow:var(--bp-shadow); overflow:hidden}
.bp-label{font-weight:650}
.bp-input{border-radius:14px}
.bp-input:focus{box-shadow:var(--bp-focus)}
.bp-btn{border-radius:14px; font-weight:650}
.bp-dot{display:inline-block; width:10px; height:10px; border-radius:999px; margin-top:.35rem}
.bp-dot-danger{background:#dc3545}
.bp-dot-ok{background:#198754}
.bp-result-title{font-weight:750}
.bp-field{border:1px solid var(--bp-line); background:var(--bp-card); border-radius:16px; padding:12px 14px}
.bp-field-label{font-size:.85rem; color:var(--bp-muted); margin-bottom:4px}
.bp-field-value{font-size:1rem; font-weight:650; word-break:break-word}
.bp-empty{border:1px dashed var(--bp-line); border-radius:18px; padding:22px; text-align:center; color:var(--bp-muted)}
.bp-empty-ico{font-size:28px; margin-bottom:8px; opacity:.7}
.bp-empty-title{font-weight:750; color:var(--bp-text)}
.bp-empty-text{margin-top:6px}
</style>
