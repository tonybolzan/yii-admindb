<?php

class QueryController extends AController {

    public $layout = '/layouts/default';
    public $defaultAction = 'sql';

    public function actionSql() {
        $model = new QuerySqlForm();
        $providers = array();

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'QuerySqlForm-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['QuerySqlForm'])) {
            $model->setAttributes($_POST['QuerySqlForm']);

            if ($model->validate()) {
                $transaction = $this->module->dbhelper->db->beginTransaction();
                try {
                    if ($model->type == 'select') {
                        $queries[] = $this->module->dbhelper->db->createCommand('EXPLAIN EXTENDED ' . $model->sql)->queryAll();
                        $queries[] = $this->module->dbhelper->db->createCommand($model->sql)->queryAll();

                        foreach ($queries as $query) {
                            $providers[] = new CArrayDataProvider($query, array(
                                'pagination' => false,
                                'keyField' => false,
                            ));
                        }
                    } elseif ($model->type == 'show') {
                        $query = $this->module->dbhelper->db->createCommand($model->sql)->queryAll();
                        $providers[] = new CArrayDataProvider($query, array(
                            'pagination' => false,
                            'keyField' => false,
                        ));
                    } else {
                        $affected = $this->module->dbhelper->db->createCommand($model->sql)->execute();
                        Yii::app()->user->setFlash('success', Yii::t('AdmindbModule.core', 'Number of rows affected by the execution: ' . $affected));
                    }

                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollback();
                    Yii::app()->user->setFlash('error', $e->getMessage());
                }
            }
        }

        $this->render('sql', array(
            'model' => $model,
            'tables' => $this->module->dbhelper->getTables(),
            'providers' => $providers,
        ));
    }

    public function actionCriteria() {
        throw new CHttpException(404, Yii::t('AdmindbModule.core', 'Not implemented (yet)')); // TODO Implement this action


        $model = new QueryCriteriaForm();
        $providers = array();

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'QueryCriteriaForm-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (Yii::app()->request->isPostRequest and isset($_POST['QueryCriteriaForm'])) {
            $model->setAttributes($_POST['QueryCriteriaForm']);

            if ($model->validate()) {
                $transaction = $this->module->dbhelper->db->beginTransaction();
                try {
                    if ($model->type == 'select') {
                        $criteria = new CDbCriteria();

                        foreach ($model->methods as $name => $parameters) {
                            if (method_exists($criteria, $name)) {
                                call_user_func_array(array($criteria, $name), $parameters);
                            }
                        }

                        foreach ($model->attributes as $name => $parameters) {
                            if (isset($criteria->$name)) {
                                $this->$name = $parameters;
                            }
                        }

                        $providers[] = new CActiveDataProvider($model->class, array(
                            'criteria' => $criteria,
                            'pagination' => false,
                        ));
                    } elseif ($model->type == 'insert') {
                        throw new CException('Operation not implemented');
                    } elseif ($model->type == 'update') {
                        throw new CException('Operation not implemented');
                    } elseif ($model->type == 'delete') {
                        throw new CException('Operation not implemented');
                    } else {
                        throw new CException(Yii::t('AdmindbModule.core', 'Unknown Database Operation'));
                    }

                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollback();
                    Yii::app()->user->setFlash('error', $e->getMessage());
                }
            }
        }

        $this->render('criteria', array(
            'model' => $model,
            'tables' => $this->module->dbhelper->getTables(),
            'providers' => $providers,
        ));
    }

}