<?php
/**
 * @var $model UploadForm
 * @var $files DirectoryIterator
 * @var $info array
 */
$assetsUrl = $this->module->assetsUrl;
$cs = Yii::app()->clientScript;
$cs->registerScriptFile($assetsUrl . '/js/bootstrap-fileupload.min.js');
$cs->registerCssFile($assetsUrl . '/css/bootstrap-fileupload.min.css');
Yii::app()->clientScript->registerCss(1, <<<CSS
    #UploadForm-form * {
        margin: 0 !important;
    }
CSS
);
?>

<table class="table table-bordered table-condensed table-striped">
    <caption><div class="alert alert-info"><strong><?php echo Yii::t('AdmindbModule.core', "Informations about server"); ?></strong></div></caption>
    <thead>
        <tr>
            <th><?php echo Yii::t('AdmindbModule.core', 'Configuration'); ?></th>
            <th><?php echo Yii::t('AdmindbModule.core', 'Value'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($info as $row): ?>
            <tr>
                <td><?php echo $row['conf']; ?></td>
                <td><?php echo $row['value']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="well well-small">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'UploadForm-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data',
            'style' => 'margin: 0;',
        ),
    ));
    ?>
    <div class="fileupload fileupload-new" data-provides="fileupload">
        <div class="input-append">
            <?php echo CHtml::link(Yii::t('AdmindbModule.core', 'Create new backup'), array('backup'), array('class' => 'btn btn-primary')) ?>
            <span class="btn disabled"><?php echo Yii::t('AdmindbModule.core', 'Or choose the file to upload'); ?></span>
            <div class="uneditable-input input-xlarge">
                <i class="icon-file fileupload-exists"></i>
                <span class="fileupload-preview"></span>
            </div>
            <span class="btn btn-file">
                <span class="fileupload-new"><?php echo Yii::t('AdmindbModule.core', 'Select file'); ?></span>
                <span class="fileupload-exists"><?php echo Yii::t('AdmindbModule.core', 'Change'); ?></span>
                <?php echo $form->fileField($model, 'sql_file'); ?>
            </span>
            <a href="#" class="btn fileupload-exists" data-dismiss="fileupload"><?php echo Yii::t('AdmindbModule.core', 'Remove'); ?></a>
            <?php echo CHtml::submitButton(Yii::t('AdmindbModule.core', 'Send File'), array('class' => 'btn btn-primary fileupload-exists')); ?>
        </div>
    </div>
    <?php
    echo $form->error($model, 'sql_file', array('class' => 'alert alert-error'));
    $this->endWidget();
    ?>
</div>

<table class="table table-bordered table-condensed table-striped">
    <caption><div class="alert alert-info"><strong><?php echo Yii::t('AdmindbModule.core', "Files on server"); ?></strong></div></caption>
    <thead>
        <tr>
            <th><?php echo Yii::t('AdmindbModule.core', 'File'); ?></th>
            <th><?php echo Yii::t('AdmindbModule.core', 'Size'); ?></th>
            <th><?php echo Yii::t('AdmindbModule.core', 'Created on'); ?></th>
            <th><?php echo Yii::t('AdmindbModule.core', 'Actions'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($files as $file): ?>
            <tr>
                <td><?php echo $file['name']; ?></td>
                <td><?php echo $this->bytesToHuman($file['size']); ?></td>
                <td><?php echo Yii::app()->dateFormatter->formatDateTime($file['ctime'], 'short', 'short'); ?></td>
                <td>
                    <div class="btn-group">
                        <?php echo CHtml::link(Yii::t('AdmindbModule.core', 'Download'), array('download', 'file' => $file['link']), array('class' => 'btn btn-mini btn-info')) ?>
                        <?php echo CHtml::link(Yii::t('AdmindbModule.core', 'Remove'), array('remove', 'file' => $file['link']), array('class' => 'btn btn-mini btn-danger')) ?>
                    </div>
                </td>
                <td>
                    <?php echo CHtml::link(Yii::t('AdmindbModule.core', 'Restore'), array('restore', 'file' => $file['link']), array('class' => 'btn btn-mini btn-warning')) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>