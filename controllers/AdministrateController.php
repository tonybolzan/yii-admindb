<?php

class AdministrateController extends AController {

    public $layout = '/layouts/default';

    public function actionIndex() {
        $model = new UploadForm();

        if (isset($_FILES['UploadForm'])) {
            $model->setAttributes($_FILES['UploadForm']);
            if ($model->validate()) {
                $model->sql_file = CUploadedFile::getInstance($model, 'sql_file');
                if (!$model->sql_file->saveAs($this->module->path . 'uploaded-' . date('YmdHis') . '-' . $model->sql_file)) {
                    Yii::app()->user->setFlash('error', Yii::t('AdmindbModule.core', 'Error saving file'));
                } else {
                    Yii::app()->user->setFlash('success', Yii::t('AdmindbModule.core', 'Upload complete'));
                }
            }
        }

        $files = array();
        foreach (new DirectoryIterator($this->module->path) as $entry) {
            if ($entry->isFile()) {
                $files[] = array(
                    'name' => $entry->getFilename(),
                    'size' => $entry->getSize(),
                    'ctime' => $entry->getCTime(),
                    'link' => base64_encode($entry->getFilename()),
                );
            }
        }

        $info = array(
            array(
                'conf' => Yii::t('AdmindbModule.core', 'Memory limit'),
                'value' => ini_get('memory_limit'),
            ),
            array(
                'conf' => Yii::t('AdmindbModule.core', 'Post max size'),
                'value' => ini_get('post_max_size'),
            ),
            array(
                'conf' => Yii::t('AdmindbModule.core', 'Upload max filesize'),
                'value' => ini_get('upload_max_filesize'),
            ),
            array(
                'conf' => Yii::t('AdmindbModule.core', 'Dump/Backup mode'),
                'value' => $this->module->dbhelper->dumpMode(),
            ),
            array(
                'conf' => Yii::t('AdmindbModule.core', 'Restore mode'),
                'value' => $this->module->dbhelper->restoreMode(),
            ),
        );

        $this->render('index', array(
            'model' => $model,
            'files' => $files,
            'info' => $info,
        ));
    }

    public function actionDownload($file) {
        $file = base64_decode($file);

        if (file_exists($this->module->path . $file)) {
            Yii::app()->request->sendFile($file, file_get_contents($this->module->path . $file), 'text/plain', true);
        } else {
            Yii::app()->user->setFlash('error', Yii::t('AdmindbModule.core', 'No files found'));
            $this->redirect(array('index'));
        }
    }

    public function actionRemove($file) {
        $file = base64_decode($file);

        if (file_exists($this->module->path . $file)) {
            if (unlink($this->module->path . $file)) {
                Yii::app()->user->setFlash('success', Yii::t('AdmindbModule.core', 'File "{file}" has been removed successfully', array('{file}' => $file)));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('AdmindbModule.core', 'Error removing file: {file}', array('{file}' => $file)));
            }
        } else {
            Yii::app()->user->setFlash('error', Yii::t('AdmindbModule.core', 'File "{file}" not found', array('{file}' => $file)));
        }
        $this->redirect(array('index'));
    }

    public function actionBackup() {
        try {
            $dbname = $this->module->dbhelper->getName();
            $file = 'backup-' . $dbname . '-' . date('YmdHis') . '.sql';
            if ($this->module->dbhelper->dump($this->module->path . $file)) {
                $this->redirect(array('download', 'file' => base64_encode($file)));
            } else {
                throw new CException(Yii::t('AdmindbModule.core', 'Error creating backup file'));
            }
        } catch (CException $e) {
            Yii::app()->user->setFlash('error', $e->getMessage());
            $this->redirect(array('index'));
        }
    }

    public function actionRestore($file) {
        try {
            $file = base64_decode($file);

            if ($this->module->dbhelper->restore($this->module->path . $file)) {
                Yii::app()->user->setFlash('success', Yii::t('AdmindbModule.core', 'File "{file}" has been restored successfully', array('{file}' => $file)));
            } else {
                throw new CException(Yii::t('AdmindbModule.core', 'Error restoring database'));
            }
        } catch (CException $e) {
            Yii::app()->user->setFlash('error', $e->getMessage());
        }
        $this->redirect(array('index'));
    }

}