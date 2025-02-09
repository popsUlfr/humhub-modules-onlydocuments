<?php

use humhub\modules\onlydocuments\Module;
use yii\helpers\Url;
use humhub\widgets\ActiveForm;
use humhub\libs\Html;

$real_templates = [];
$form_templates = [0 => 'Sans modèle'];
$default_template = 0;

if (is_array($templates)) {
    $real_templates = array_filter(array_map(function ($info) {
        if (empty($info))
            return null;
        $ret = [
            'title' => '',
            'description' => '',
            'default' => false,
        ];
        if (is_array($info)) {
            if (!isset($info['title']) || empty($info['title']))
                return null;
            $ret['title'] = (string)$info['title'];
            if (isset($info['description']) && !empty($info['description']))
                $ret['description'] = (string)$info['description'];
            if (isset($info['default']) && $info['default'] === true)
                $ret['default'] = true;
        }
        else {
            $ret['title'] = (string)$info;
        }
        return $ret;
    }, $templates), function ($info) { return !empty($info); });
    foreach ($real_templates as $key => $info) {
        if ($info['default'])
            $default_template = $key;
    }
    $form_templates = array_merge($form_templates, array_map(function ($info) {
        return $info['title'];
    }, $real_templates));
}

\humhub\modules\onlydocuments\assets\Assets::register($this);

$modal = \humhub\widgets\ModalDialog::begin([
            'header' => Yii::t('OnlydocumentsModule.base', '<strong>Create</strong> document'),
            'showClose' => false
        ])
?>

<?php $form = ActiveForm::begin(); ?>

<div class="modal-body">
    <?= $form->field($model, 'fileName', ['template' => '{label}<div class="input-group">{input}<div class="input-group-addon">' . $ext . '</div></div>{hint}{error}']); ?>
    <?= $form->field($model, 'openFlag')->checkbox(['style' => 'display:none']); ?>
    <?= $form->field($model, 'template')->dropDownList($form_templates, ['options' => [$default_template => ['Selected'=>'selected']]]); ?>
    <p id="description" style="height: 4em; overflow: hidden;"><?= (isset($real_templates[$default_template]))?$real_templates[$default_template]['description']:''; ?></p>
</div>

<div class="modal-footer">
    <?= Html::button('Fermer', ['data-modal-close' => '', 'class' => 'btn btn-default']); ?>
    <?= Html::submitButton('Créer', ['data-action-click' => 'onlydocuments.createSubmit', 'data-ui-loader' => '', 'class' => 'btn btn-primary']); ?>
</div>

<?php ActiveForm::end(); ?>

<?php \humhub\widgets\ModalDialog::end(); ?>
<script>
$(document).ready(function () {
    var templates = <?= json_encode($real_templates); ?>;
    $('input[id="createdocument-filename"]').change(function () {
        $(this).removeData('template');
    });
    $('select[id="createdocument-template"]').change(function () {
        var value = $('select[id="createdocument-template"] > option:selected').val();
        var desc = $('#description');
        if (templates[value] && templates[value].description) {
            desc.text(templates[value].description);
        }
        else {
            desc.text('');
        }
        var titleEl = $('input[id="createdocument-filename"]');
        if (titleEl.length) {
            var title = templates[value] && templates[value].title;
            if (titleEl.data('template')) {
                if (title) {
                    titleEl.val(title);
                    titleEl.data('template', value);
                }
                else {
                    titleEl.val(null);
                    titleEl.removeData('template');
                }
            }
            else if ((!titleEl.val() || !titleEl.val().length) && title) {
                titleEl.val(title);
                titleEl.data('template', value);
            }
        }
    });
});
</script>
