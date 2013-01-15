<?php

class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $module = Yii::app()->getModule('admindb');

        if ($module->password === null) {
            throw new CException(Yii::t('AdmindbModule.core', 'Please configure the "password" property of the "admindb" module.'));
        } elseif ($module->passwordHashAlgo === null) {
            throw new CException(Yii::t('AdmindbModule.core', 'Please configure the "passwordHashAlgo" property of the "admindb" module.'));
        } elseif ($module->passwordHashAlgo !== false && !in_array($module->passwordHashAlgo, hash_algos())) {
            throw new CException(Yii::t('AdmindbModule.core', 'The "passwordHashAlgo" {hash} is not recognized.',array('{hash}'=>$module->passwordHashAlgo)));
        } elseif ($module->password === false
                || ($module->passwordHashAlgo === false && $module->password === $this->password)
                || ($module->passwordHashAlgo !== false && $module->password === hash($module->passwordHashAlgo, $this->password))) {
            $this->errorCode = self::ERROR_NONE;
        } else {
            $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
        }

        return !$this->errorCode;
    }

}