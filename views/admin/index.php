<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="panel panel-default">

    <div class="panel-heading"><?= Yii::t('OnlydocumentsModule.base', '<strong>OnlyOffice - DocumentServer</strong> module configuration'); ?></div>

    <div class="panel-body">

        <?php if (!empty($version)): ?>
            <div class="alert alert-success" role="alert"><?= Yii::t('OnlydocumentsModule.base', '<strong>DocumentServer</strong> successfully connected! - Installed version: {version}', ['version' => $version]); ?></div>
        <?php elseif (empty($model->serverUrl)): ?>
            <div class="alert alert-warning" role="alert"><?= Yii::t('OnlydocumentsModule.base', '<strong>DocumentServer</strong> not configured yet.'); ?></div>
        <?php else: ?>
            <div class="alert alert-danger" role="alert"><?= Yii::t('OnlydocumentsModule.base', '<strong>DocumentServer</strong> not accessible.'); ?></div>
        <?php endif; ?>

        <?php $form = ActiveForm::begin(['id' => 'configure-form']); ?>
        <div class="form-group">
            <?= $form->field($model, 'serverUrl'); ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'templates')->textarea(['rows' => 6, 'data-content' => 'json', 'style' => 'resize:vertical']); ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'data-ui-loader' => '']) ?>
            <a class="btn btn-default" href="<?= Url::to(['/admin/module']); ?>"><?= Yii::t('PagesModule.views_noteConfig_index', 'Back to modules'); ?></a>
        </div>

        <?= \humhub\widgets\DataSaved::widget(); ?>

        <?php ActiveForm::end(); ?>

        <script>
        $(document).ready(function () {
            var but = $('<a class="btn btn-info btn-xs validate-json" style="position:absolute;right:12px;">Validate</a>');
            but.on('click', function () {
                var self = $(this);
                var ta = self.siblings('textarea[data-content="json"]');
                try {
                    JSON.parse(ta.val());
                    ta.attr('style', 'resize:vertical;border-color:green !important');
                } catch(e) {ta.attr('style', 'resize:vertical;border-color:red !important');}
            });
            $('textarea[data-content="json"]').before(but);
            $('textarea[data-content="json"]').each(function () {
                var self = $(this);
                try {
                    var json = JSON.parse(self.val());
                    self.val(JSON.stringify(json, null, 2));
                    self.attr('style', 'resize:vertical;border-color:green !important');
                }
                catch (e) {self.attr('style', 'resize:vertical;border-color:red !important');}
            });
        });
        </script>
    </div>
</div>
