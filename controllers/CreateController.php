<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\onlydocuments\controllers;

use Yii;
use yii\web\HttpException;
use yii\helpers\Url;
use humhub\modules\file\libs\FileHelper;
use humhub\modules\onlydocuments\Module;

class CreateController extends \humhub\components\Controller
{

    public function actionIndex()
    {
        return $this->renderAjax('index', []);
    }

    public function actionDocument()
    {

        $model = new \humhub\modules\onlydocuments\models\CreateDocument();
        $model->documentType = Yii::$app->request->get('type');

        $ext = '';
        if ($model->documentType == Module::DOCUMENT_TYPE_TEXT) {
            $ext = '.docx';
        } elseif ($model->documentType == Module::DOCUMENT_TYPE_PRESENTATION) {
            $ext = '.pptx';
        } elseif ($model->documentType == Module::DOCUMENT_TYPE_SPREADSHEET) {
            $ext = '.xlsx';
        } else {
            throw new HttpException("Invalid document type!");
        }

        $c_ext = substr($ext, 1);
        $config = new \humhub\modules\onlydocuments\models\ConfigureForm();
        $config->loadSettings();
        $glob_templates = json_decode($config->templates, true);
        $templates = [];
        if (!empty($glob_templates) && isset($glob_templates[$c_ext])) {
            $templates = $glob_templates[$c_ext];
        }

        if ($model->load(Yii::$app->request->post())) {

            $file = $model->save();

            if ($file !== false) {
                return $this->asJson([
                            'success' => true,
                            'file' => FileHelper::getFileInfos($file),
                            'openFlag' => (boolean) $model->openFlag,
                            'openUrl' => Url::to(['/onlydocuments/open', 'guid' => $file->guid, 'mode' => Module::OPEN_MODE_EDIT])
                ]);
            } else {
                return $this->asJson([
                            'success' => false,
                            'output' => $this->renderAjax('document', ['model' => $model, 'ext' => $ext, 'templates' => $templates])
                ]);
            }
        }

        return $this->renderAjax('document', ['model' => $model, 'ext' => $ext, 'templates' => $templates]);
    }

    public function determineContentFileUrl($file)
    {
        $underlyingObject = $file->getPolymorphicRelation();

        if (method_exists($underlyingObject, 'getUrl')) {
            return $underlyingObject->getUrl();
        }

        return null;
    }

}
