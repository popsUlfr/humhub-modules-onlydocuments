<?php

/**
 * @author Philipp Richter
 */

namespace humhub\modules\onlydocuments\filehandler;

use Yii;
use humhub\libs\Html;
use humhub\modules\onlydocuments\Module;
use humhub\modules\file\handler\BaseFileHandler;
use yii\helpers\Url;

/**
 * @author Philipp Richter
 */
class ShareFileHandler extends BaseFileHandler
{

    /**
     * @inheritdoc
     */
    public function getLinkAttributes()
    {
        return [
            'label' => Yii::t('FileModule.base', 'Share document'),
            'data-action-url' => Url::to(['/onlydocuments/share',
                'guid' => $this->file->guid,
                'mode' => Module::OPEN_MODE_EDIT]),
            'data-action-click' => 'ui.modal.load',
            'data-modal-id' => 'onlydocuments-modal',
            'data-modal-close' => ''
        ];
    }

}
