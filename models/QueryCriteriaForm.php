<?php

class QuerySqlForm extends CFormModel {

    public $class;
    public $methods = array();
    public $attributes = array();
    private $_commands = array(
        'findAll',
        'find',
        'save',
        'delete',
        'deleteAll',
    );

    public function rules() {
        return array(
            array('class, methods, attributes', 'required'),
            array('methods, attributes', 'type', 'type' => 'array'),
        );
    }

    function attributeLabels() {
        return array(
            'class' => Yii::t('AdmindbModule.core', 'Class name of database table'),
            'methods' => Yii::t('AdmindbModule.core', 'CDbCriteria Methods'),
            'attributes' => Yii::t('AdmindbModule.core', 'CDbCriteria Attributes'),
        );
    }

}