<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\onlydocuments\models;

use Yii;
use yii\base\Model;
use humhub\modules\onlydocuments\Module;
use humhub\modules\file\models\File;

/**
 * Description of CreateDocument
 *
 * @author Luke
 */
class CreateDocument extends Model
{

    public $documentType;
    public $fileName;
    public $openFlag = true;
    public $template;

    public function rules()
    {
        return [
            ['fileName', 'required'],
            ['openFlag', 'boolean'],
            ['template', 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'openFlag' => Yii::t('OnlydocumentsModule.base', 'Open the new document in the next step'),
            'template' => Yii::t('OnlydocumentsModule.base', 'Sélectionner un modèle'),
        ];
    }

    public function save()
    {

        /* @var $module \humhub\modules\onlydocuments\Module */
        $module = Yii::$app->getModule('onlydocuments');


        if (empty($this->documentType)) {
            throw new Exception("Document type cannot be empty");
        }

        if ($this->validate()) {

            if ($this->documentType == Module::DOCUMENT_TYPE_TEXT) {
                $source = $module->getAssetPath() . '/new.docx';
                $newFile = $this->fileName . '.docx';
                $mime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
            } elseif ($this->documentType == Module::DOCUMENT_TYPE_PRESENTATION) {
                $source = $module->getAssetPath() . '/new.pptx';
                $newFile = $this->fileName . '.pptx';
                $mime = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
            } elseif ($this->documentType == Module::DOCUMENT_TYPE_SPREADSHEET) {
                $source = $module->getAssetPath() . '/new.xlsx';
                $newFile = $this->fileName . '.xlsx';
                $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            }

            if (!empty($this->template)) {
                $tmpl_file = File::findOne(['guid' => $this->template]);
                if (!empty($tmpl_file)) {
                    $source = $tmpl_file->getStore()->get();
                }
            }

            $file = new File();
            $file->file_name = $newFile;
            $file->size = filesize($source);
            $file->mime_type = $mime;
            $file->save();
            $file->store->setContent(file_get_contents($source));

            return $file;
        }

        return false;
    }

}
