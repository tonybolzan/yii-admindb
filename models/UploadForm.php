<?php

class UploadForm extends CFormModel {

    public $sql_file;

    public function rules() {
        $max_upload = (int) (ini_get('upload_max_filesize'));
        $max_post = (int) (ini_get('post_max_size'));
        $memory_limit = (int) (ini_get('memory_limit'));
        $limit_mb = min($max_upload, $max_post, $memory_limit);
        $limit = 1024 * 1024 * $limit_mb;

        return array(
            array('sql_file', 'file', 'types' => 'sql', 'maxSize' => $limit,
                'tooLarge' => Yii::t('AdmindbModule.core', 'The file was larger than {size}MB. Please upload a smaller file.', array('{size}' => $limit_mb)),
            ),
        );
    }

    function attributeLabels() {
        return array(
            'sql_file' => Yii::t('AdmindbModule.core', 'Upload File'),
        );
    }

}