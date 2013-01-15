<?php

class LoginForm extends CFormModel {

    public $password;
    private $_identity;

    public function rules() {
        return array(
            array('password', 'required', 'message' => Yii::t('AdmindbModule.core', 'Password cannot be blank')),
            array('password', 'authenticate'),
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        $this->_identity = new UserIdentity('yiier', $this->password);

        if (!$this->_identity->authenticate()) {
            $this->addError('password', Yii::t('AdmindbModule.core', 'Incorrect password'));
        }
    }

    /**
     * Logs in the user using the given password in the model.
     * @return boolean whether login is successful
     */
    public function login() {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity('yiier', $this->password);
            $this->_identity->authenticate();
        }

        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            Yii::app()->user->login($this->_identity);
            return true;
        } else {
            return false;
        }
    }

    public function attributeLabels() {
        return array(
            'password' => Yii::t('AdmindbModule.core', 'Please enter your password'),
        );
    }

}
