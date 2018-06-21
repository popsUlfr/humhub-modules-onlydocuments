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
use humhub\modules\onlydocuments\components\BaseFileController;

class OpenController extends BaseFileController
{

    /**
     * @var boolean fullscreen
     */
    public $fullscreen;

    /**
     * @var boolean mobile
     */
    public $mobile;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->fullscreen = filter_var(
            Yii::$app->request->get('fullscreen', false),
            FILTER_VALIDATE_BOOLEAN);
        $this->mobile = filter_var(
            Yii::$app->request->get('mobile', false),
            FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Opens the document in modal
     * 
     * @return string
     * @throws HttpException
     */
    public function actionIndex()
    {
        if (!Yii::$app->request->isAjax) {
            //return $this->redirectToModal();
            $this->fullscreen = true;
            return $this->render('fullscreen', [
                        'file' => $this->file,
                        'mode' => $this->mode,
                        'fullscreen' => $this->fullscreen,
                        'mobile' => $this->mobile,
            ]);
        }

        return $this->renderAjax(($this->fullscreen)?'fullscreen':'index', [
                    'file' => $this->file,
                    'mode' => $this->mode,
                    'fullscreen' => $this->fullscreen,
                    'mobile' => $this->mobile,
        ]);
    }

    /**
     * Returns file informations
     * 
     * @return type
     * @throws HttpException
     */
    public function actionGetInfo()
    {
        return $this->asJson(['file' => FileHelper::getFileInfos($this->file)]);
    }

    /**
     * If not opened in ajax mode - redirect to the correct page and open modal

     * @return type
     * @throws HttpException
     */
    protected function redirectToModal()
    {
        $url = $this->determineContentFileUrl();
        if ($url === null) {
            throw new HttpException(400, 'Invalid request. Could not find file content url!');
        }

        if ($this->shareSecret) {
            $openUrl = Url::to(['/onlydocuments/open',
                'share' => $this->shareSecret,
                'fullscreen' => $this->fullscreen,
                'mobile' => $this->mobile,]);
        } else {
            $openUrl = Url::to(['/onlydocuments/open',
                'guid' => $this->file->guid,
                'mode' => $this->mode,
                'fullscreen' => $this->fullscreen,
                'mobile' => $this->mobile,]);
        }

        $jsCode = 'var modalOO = humhub.require("ui.modal"); modalOO.get("#onlydocuments-modal").load("' . $openUrl . '");';
        Yii::$app->session->setFlash('executeJavascript', $jsCode);

        return $this->redirect($url);
    }

}
