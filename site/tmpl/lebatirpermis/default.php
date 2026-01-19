<?php
\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$app   = Factory::getApplication();
$lang  = $app->getLanguage();
$tag = $lang->getTag();
$isArabic = (strpos($tag, 'ar-') === 0);
$isRtl = $lang->isRtl();

$result = $this->result ?? null;
$error  = $this->error ?? null;
$form   = is_array($this->form ?? null) ? $this->form : ['cin' => '', 'numdossier' => ''];

$cin        = isset($form['cin']) ? (string) $form['cin'] : '';
$numdossier = isset($form['numdossier']) ? (string) $form['numdossier'] : '';

$getVal = function (string $key) use ($result) {
    if (is_array($result)) {
        return $result[$key] ?? '';
    }
    if (is_object($result)) {
        return $result->$key ?? '';
    }
    return '';
};

// name/nom compatibility without touching backend
$nameValue = $getVal('name');
if ($nameValue === '') {
    $nameValue = $getVal('nom');
}

$resultId = 0;
if (is_array($result) && isset($result['id'])) {
    $resultId = (int) $result['id'];
} elseif (is_object($result) && isset($result->id)) {
    $resultId = (int) $result->id;
}

$printUrl = '';
if ($resultId > 0) {
    $printUrl = Route::_('index.php?option=com_batirpermi&task=permibatir.print&id=' . $resultId . '&tmpl=component');
}

// Small helper: try language key, else fallback by current language tag
$t = function (string $key, array $fallbacks) use ($tag, $isArabic) {
    $txt = Text::_($key);
    if ($txt !== $key) {
        return $txt;
    }
    if ($isArabic) {
        return $fallbacks['ar'] ?? $key;
    }
    if (strpos($tag, 'fr-') === 0) {
        return $fallbacks['fr'] ?? $key;
    }
    if (strpos($tag, 'en-') === 0) {
        return $fallbacks['en'] ?? $key;
    }
    return $fallbacks['en'] ?? ($fallbacks['fr'] ?? ($fallbacks['ar'] ?? $key));
};
?>

<div class="container my-4 pb-ui" <?php echo $isRtl ? 'dir="rtl"' : 'dir="ltr"'; ?>>

  <style>
    .pb-ui{
      --bg:#f6f8fc;
      --card:#ffffff;
      --text:#0f172a;
      --muted:#4b5563;
      --line:#e6edf6;
      --shadow:0 12px 30px rgba(15,23,42,.07);
      --radius:20px;
      --focus:0 0 0 .2rem rgba(13,110,253,.14);
      --danger:#b02a37;
      --ok:#198754;
    }
    .pb-ui{ color:var(--text); }
    .pb-ui .pb-wrap{ max-width: 920px; margin: 0 auto; }

    .pb-ui .pb-hero{
      background: linear-gradient(180deg, #ffffff 0%, var(--bg) 100%);
      border:1px solid var(--line);
      border-radius: 24px;
      box-shadow: var(--shadow);
      padding: 22px 18px;
      margin-bottom: 14px;
      text-align: center;
    }
    .pb-ui .pb-title{
      margin:0;
      font-weight: 900;
      font-size: 1.4rem;
      color: var(--danger);
      letter-spacing:.2px;
    }
    .pb-ui .pb-sub{
      margin: 8px 0 0;
      color: var(--muted);
      font-size: .98rem;
      line-height: 1.6;
    }

    .pb-ui .pb-card{
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
    }
    .pb-ui .pb-card + .pb-card{ margin-top: 14px; }

    .pb-ui .pb-head{
      padding: 14px 16px;
      border-bottom: 1px solid var(--line);
      display:flex;
      align-items:center;
      justify-content: space-between;
      gap: 10px;
      flex-wrap: wrap;
    }
    .pb-ui .pb-head h4{
      margin:0;
      font-weight: 850;
      font-size: 1.05rem;
    }
    .pb-ui .pb-pill{
      display:inline-flex;
      align-items:center;
      gap: 8px;
      padding: 7px 12px;
      border-radius: 999px;
      border: 1px solid var(--line);
      background:#fff;
      color: var(--muted);
      font-size: .92rem;
      white-space: nowrap;
    }
    .pb-ui .pb-dot{
      width:10px; height:10px; border-radius:999px;
      background: var(--ok);
    }
    .pb-ui .pb-dot-danger{ background: var(--danger); }

    .pb-ui .pb-body{ padding: 16px; }

    .pb-ui .form-label{ font-weight: 800; }
    .pb-ui .form-control{
      border-radius: 14px;
      border-color: var(--line);
      padding: .7rem .9rem;
    }
    .pb-ui .form-control:focus{ box-shadow: var(--focus); }
    .pb-ui .form-text{ color: var(--muted); }

    .pb-ui .pb-actions{
      display:flex;
      gap: 10px;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 8px;
    }
    .pb-ui .btn{
      border-radius: 14px;
      font-weight: 750;
      padding: .62rem 1.05rem;
    }

    .pb-ui .pb-grid{
      display:grid;
      grid-template-columns: repeat(12, 1fr);
      gap: 12px;
    }
    .pb-ui .pb-field{
      grid-column: span 6;
      border: 1px solid var(--line);
      border-radius: 16px;
      padding: 12px 14px;
      background: #fff;
    }
    .pb-ui .pb-field small{
      display:block;
      color: var(--muted);
      margin-bottom: 4px;
      font-size: .86rem;
    }
    .pb-ui .pb-field div{
      font-weight: 850;
      word-break: break-word;
    }
    .pb-ui .pb-result{
      grid-column: span 12;
      border: 1px solid var(--line);
      border-radius: 16px;
      padding: 14px 14px;
      background: #fff;
    }
    .pb-ui .pb-result small{
      display:block;
      color: var(--muted);
      margin-bottom: 6px;
      font-size: .86rem;
    }
    .pb-ui .pb-result div{
      font-weight: 950;
      color: var(--danger);
      font-size: 1.05rem;
      word-break: break-word;
    }

    .pb-ui .pb-alert{
      border:1px solid rgba(176,42,55,.25);
      background: rgba(176,42,55,.06);
      border-radius: 18px;
      padding: 14px 16px;
      margin-bottom: 14px;
    }
    .pb-ui .pb-alert .pb-alert-title{
      font-weight: 900;
      margin-bottom: 4px;
      color: var(--danger);
    }
    .pb-ui .pb-empty{
      text-align:center;
      color: var(--muted);
      padding: 18px 10px;
    }
    .pb-ui .pb-empty-ico{
      width: 46px;
      height: 46px;
      border-radius: 16px;
      margin: 0 auto 10px;
      border: 1px dashed var(--line);
      display:flex;
      align-items:center;
      justify-content:center;
      font-weight: 900;
      opacity:.75;
    }

    @media (max-width: 768px){
      .pb-ui .pb-body{ padding: 14px; }
      .pb-ui .pb-field{ grid-column: span 12; }
    }
  </style>

  <div class="pb-wrap">

    <div class="pb-hero">
      <h3 class="pb-title"><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_TITRE', ['ar' => 'متابعة مطلب رخصة البناء', 'fr' => 'Suivi de la demande de permis de construire', 'en' => 'Building permit application tracking']), ENT_QUOTES, 'UTF-8'); ?></h3>
      <p class="pb-sub"><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_SUBTITLE', ['ar' => 'أدخل رقم بطاقة التعريف الوطنية ورقم الملف للاطلاع على نتيجة مطلبك.', 'fr' => 'Saisissez le numéro de la carte d\'identité nationale et le numéro du dossier pour consulter le résultat de votre demande.', 'en' => 'Enter the national ID number and the file number to view your application result.']), ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

    <?php if (!empty($error)) : ?>
      <div class="pb-alert" role="alert">
        <div class="d-flex align-items-start gap-2">
          <span class="pb-dot pb-dot-danger" aria-hidden="true"></span>
          <div>
            <div class="pb-alert-title"><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_ERROR_TITLE', ['ar' => 'حدث خطأ', 'fr' => 'Une erreur est survenue', 'en' => 'An error occurred']), ENT_QUOTES, 'UTF-8'); ?></div>
            <div><?php echo htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8'); ?></div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <div class="pb-card">
      <div class="pb-head">
        <h4><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_SEARCH', ['ar' => 'البحث', 'fr' => 'Recherche', 'en' => 'Search']), ENT_QUOTES, 'UTF-8'); ?></h4>
        <span class="pb-pill"><span class="pb-dot" aria-hidden="true"></span><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_SERVICE_PILL', ['ar' => 'خدمة التحقق الإلكتروني', 'fr' => 'Service de vérification en ligne', 'en' => 'Online verification service']), ENT_QUOTES, 'UTF-8'); ?></span>
      </div>

      <div class="pb-body">
        <form action="<?php echo Route::_('index.php?option=com_batirpermi&task=permibatir.search'); ?>" method="post" class="row g-3">
          <div class="col-12 col-md-6">
            <label for="cin" class="form-label"><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_CIN', ['ar' => 'رقم بطاقة التعريف الوطنية', 'fr' => 'Numéro de carte d\'identité nationale', 'en' => 'National ID number']), ENT_QUOTES, 'UTF-8'); ?></label>
            <input
              type="text"
              name="cin"
              id="cin"
              class="form-control"
              value="<?php echo htmlspecialchars($cin, ENT_QUOTES, 'UTF-8'); ?>"
              placeholder="<?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_CIN_PLACEHOLDER', ['ar' => 'مثال: 12345678', 'fr' => 'Exemple : 12345678', 'en' => 'Example: 12345678']), ENT_QUOTES, 'UTF-8'); ?>"
              inputmode="numeric"
              autocomplete="off"
              required
            >
            <div class="form-text"><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_CIN_HELP', ['ar' => 'ثمانية أرقام.', 'fr' => 'Huit chiffres.', 'en' => 'Eight digits.']), ENT_QUOTES, 'UTF-8'); ?></div>
          </div>

          <div class="col-12 col-md-6">
            <label for="numdossier" class="form-label"><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_NUMDOSSIER', ['ar' => 'رقم الملف', 'fr' => 'Numéro de dossier', 'en' => 'File number']), ENT_QUOTES, 'UTF-8'); ?></label>
            <input
              type="text"
              name="numdossier"
              id="numdossier"
              class="form-control"
              value="<?php echo htmlspecialchars($numdossier, ENT_QUOTES, 'UTF-8'); ?>"
              placeholder="<?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_NUMDOSSIER_PLACEHOLDER', ['ar' => 'مثال: 2024/0158', 'fr' => 'Exemple : 2024/0158', 'en' => 'Example: 2024/0158']), ENT_QUOTES, 'UTF-8'); ?>"
              autocomplete="off"
              required
            >
            <div class="form-text"><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_NUMDOSSIER_HELP', ['ar' => 'كما هو مذكور في الوصل.', 'fr' => 'Tel qu\'indiqué sur le reçu.', 'en' => 'As indicated on the receipt.']), ENT_QUOTES, 'UTF-8'); ?></div>
          </div>

          <div class="col-12">
            <div class="pb-actions">
              <button type="submit" name="submit" class="btn btn-primary">
                <?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_SEARCH', ['ar' => 'بحث', 'fr' => 'Rechercher', 'en' => 'Search']), ENT_QUOTES, 'UTF-8'); ?>
              </button>

              <?php if ($cin !== '' || $numdossier !== '') : ?>
                <a class="btn btn-outline-secondary" href="<?php echo Route::_('index.php?option=com_batirpermi&view=permibatir'); ?>">
                  <?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_CLEAR', ['ar' => 'مسح', 'fr' => 'Effacer', 'en' => 'Clear']), ENT_QUOTES, 'UTF-8'); ?>
                </a>
              <?php endif; ?>
            </div>
          </div>

          <?php echo HTMLHelper::_('form.token'); ?>
        </form>
      </div>
    </div>

    <?php if (!empty($result)) : ?>
      <div class="pb-card">
        <div class="pb-head">
          <h4><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_RESULT_TITLE', ['ar' => 'نتيجة البحث', 'fr' => 'Résultat de la recherche', 'en' => 'Search result']), ENT_QUOTES, 'UTF-8'); ?></h4>
          <span class="pb-pill"><span class="pb-dot" aria-hidden="true"></span><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_RESULT_FOUND', ['ar' => 'تم العثور على الملف', 'fr' => 'Dossier trouvé', 'en' => 'File found']), ENT_QUOTES, 'UTF-8'); ?></span>
        </div>

        <div class="pb-body">
          <div class="pb-grid">
            <div class="pb-field">
              <small><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_NUMUNIQUE', ['ar' => 'المعرّف', 'fr' => 'Identifiant', 'en' => 'Identifier']), ENT_QUOTES, 'UTF-8'); ?></small>
              <div><?php echo htmlspecialchars((string) $getVal('id'), ENT_QUOTES, 'UTF-8'); ?></div>
            </div>

            <div class="pb-field">
              <small><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_NUMDOSSIER', ['ar' => 'رقم الملف', 'fr' => 'Numéro de dossier', 'en' => 'File number']), ENT_QUOTES, 'UTF-8'); ?></small>
              <div><?php echo htmlspecialchars((string) $getVal('title'), ENT_QUOTES, 'UTF-8'); ?></div>
            </div>

            <div class="pb-field">
              <small><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_NAME', ['ar' => 'الاسم', 'fr' => 'Nom', 'en' => 'Name']), ENT_QUOTES, 'UTF-8'); ?></small>
              <div><?php echo htmlspecialchars((string) $nameValue, ENT_QUOTES, 'UTF-8'); ?></div>
            </div>

            <div class="pb-field">
              <small><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_CIN', ['ar' => 'رقم ب.ت.و', 'fr' => 'Numéro CNI', 'en' => 'ID number']), ENT_QUOTES, 'UTF-8'); ?></small>
              <div><?php echo htmlspecialchars((string) $getVal('cin'), ENT_QUOTES, 'UTF-8'); ?></div>
            </div>

            <div class="pb-field">
              <small><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_TYPE', ['ar' => 'نوع البناية', 'fr' => 'Type de bâtiment', 'en' => 'Building type']), ENT_QUOTES, 'UTF-8'); ?></small>
              <div><?php echo htmlspecialchars((string) $getVal('typebatiment'), ENT_QUOTES, 'UTF-8'); ?></div>
            </div>

            <div class="pb-field">
              <small><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_INGENIEUR', ['ar' => 'المهندس', 'fr' => 'Ingénieur', 'en' => 'Engineer']), ENT_QUOTES, 'UTF-8'); ?></small>
              <div><?php echo htmlspecialchars((string) $getVal('ingenieur'), ENT_QUOTES, 'UTF-8'); ?></div>
            </div>

            <div class="pb-result">
              <small><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_RESULT', ['ar' => 'النتيجة', 'fr' => 'Résultat', 'en' => 'Result']), ENT_QUOTES, 'UTF-8'); ?></small>
              <div><?php echo htmlspecialchars((string) $getVal('resultat'), ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
          </div>

          <div class="pb-actions" style="margin-top: 14px;">
            <?php if ($printUrl !== '') : ?>
              <a class="btn btn-outline-primary" href="<?php echo htmlspecialchars($printUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                <?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_PRINT', ['ar' => 'طباعة', 'fr' => 'Imprimer', 'en' => 'Print']), ENT_QUOTES, 'UTF-8'); ?>
              </a>
            <?php endif; ?>

            <a class="btn btn-secondary" href="<?php echo Route::_('index.php?option=com_batirpermi&view=permibatir'); ?>">
              <?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_SEARCHAGAIN', ['ar' => 'بحث جديد', 'fr' => 'Nouvelle recherche', 'en' => 'New search']), ENT_QUOTES, 'UTF-8'); ?>
            </a>
          </div>

        </div>
      </div>

    <?php elseif (empty($error)) : ?>
      <div class="pb-card">
        <div class="pb-body pb-empty">
          <div class="pb-empty-ico" aria-hidden="true">⌁</div>
          <div style="font-weight:900;color:var(--text);margin-bottom:4px;"><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_EMPTY_TITLE', ['ar' => 'ابدأ بالبحث', 'fr' => 'Commencez par une recherche', 'en' => 'Start your search']), ENT_QUOTES, 'UTF-8'); ?></div>
          <div><?php echo htmlspecialchars($t('COM_PERMIBATIR_PERMIBATIRS_EMPTY_BODY', ['ar' => 'قم بإدخال المعطيات أعلاه ثم اضغط على زر البحث.', 'fr' => 'Saisissez les informations ci-dessus, puis cliquez sur Rechercher.', 'en' => 'Enter the information above, then click Search.']), ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
      </div>
    <?php endif; ?>

  </div>
</div>
