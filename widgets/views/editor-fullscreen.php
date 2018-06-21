<?php
use humhub\libs\Html;
\humhub\modules\onlydocuments\assets\Assets::register($this);
?>

<?= Html::beginTag('div', $options) ?>
<div id="iframeContainer"></div>
<?= Html::endTag('div'); ?>
