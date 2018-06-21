<div class="modal-content onlyDocumentsModal" style="background-color:transparent; width: 100%; height: 100%; position: fixed; top:0; left:0; z-index: 9999;">
    <?=
    \humhub\modules\onlydocuments\widgets\EditorWidget::widget([
        'file' => $file,
        'mode' => $mode,
        'fullscreen' => $fullscreen,
        'mobile' => $mobile,
    ]);
    ?>
</div>
