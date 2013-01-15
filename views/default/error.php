<div class="alert alert-error">
    <h1 class="alert-heading"><?php echo Yii::t('AdmindbModule.core', 'Error') .' '. $code; ?></h1>
    <p><?php echo CHtml::encode($message); ?></p>
    <button class="btn-large btn-danger" onClick="history.back();"><?php echo Yii::t('AdmindbModule.core', 'Back'); ?></button>
</div>