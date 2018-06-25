<?php

namespace humhub\modules\onlydocuments\models;

use Yii;

/**
 * ConfigureForm defines the configurable fields.

 */
class ConfigureForm extends \yii\base\Model
{

    public $serverUrl;
    public $templates;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['serverUrl', 'string'],
            ['templates', 'string', 'encoding' => 'UTF-8'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'serverUrl' => Yii::t('OnlydocumentsModule.base', 'Hostname'),
            'templates' => Yii::t('OnlydocumentsModule.base', 'Page templates'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'serverUrl' => Yii::t('OnlydocumentsModule.base', 'e.g. http://documentserver'),
            'templates' => Yii::t('OnlydocumentsModule.base', 'JSON format like {"docx": {"%file-uuid%": {"title":..., "description":...}, ...}, "pptx": {...}, "xlsx": {...}}'),
        ];
    }

    public function loadSettings()
    {
        $this->serverUrl = Yii::$app->getModule('onlydocuments')->settings->get('serverUrl');
        $this->templates = Yii::$app->getModule('onlydocuments')->settings->get('templates', '{}');

        return true;
    }

    public function save()
    {
        Yii::$app->getModule('onlydocuments')->settings->set('serverUrl', $this->serverUrl);
        Yii::$app->getModule('onlydocuments')->settings->set('templates', $this->templates);

        return true;
    }

}
