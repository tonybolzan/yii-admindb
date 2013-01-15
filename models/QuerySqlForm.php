<?php

class QuerySqlForm extends CFormModel {

    public $sql;
    private $_regex = array(
        'select' => '/^SELECT.+FROM.+/i',
        'insert' => '/^INSERT.+INTO.+VALUES.+/i',
        'update' => '/^UPDATE.+SET.+WHERE.+/i',
        'delete' => '/^DELETE.+FROM.+WHERE.+/i',
        'show'   => '/^SHOW.+/i',
    );

    public function rules() {
        return array(
            array('sql', 'required'),
            array('sql', 'validateSql'),
        );
    }

    function attributeLabels() {
        return array(
            'sql' => Yii::t('AdmindbModule.core', 'SQL Code'),
        );
    }

    protected function beforeValidate() {
        $this->sql = trim($this->sql);

        return parent::beforeValidate();
    }

    public function validateSql($attribute, $params) {
        if ($this->getType() === false) {
            $this->addError($attribute, Yii::t('AdmindbModule.core', 'Invalid SQL code'));
        }
    }

    public function getType() {
        foreach ($this->_regex as $type => $regex) {
            if (preg_match($regex, $this->sql) == 1) {
                return $type;
            }
        }
        return false;
    }

}