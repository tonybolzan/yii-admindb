<?php

/**
 * AdmindbModule Class
 */
class AdmindbModule extends CWebModule {

    /**
     * Path to save all sql files
     * @var string If null == Yii Runtime Path/admindb/
     */
    public $path;

    /**
     * If this property is set false, then Module can be accessed without password
     * (DO NOT DO THIS UNLESS YOU KNOW THE CONSEQUENCE!!!)
     * @var string the password that can be used to access Module.
     */
    public $password;

    /**
     * Algorithm to be used in password
     * @var string|false Algorithm to be used {@see hash_algos()} or false to plain text
     */
    public $passwordHashAlgo;

    /**
     * @var array the IP filters that specify which IP addresses are allowed to access Module.
     * Each array element represents a single filter. A filter can be either an IP address
     * or an address with wildcard (e.g. 192.168.0.*) to represent a network segment.
     * If you want to allow all IPs to access admindb, you may set this property to be false
     * (DO NOT DO THIS UNLESS YOU KNOW THE CONSEQUENCE!!!)
     * The default value is array('127.0.0.1', '::1'), which means Module can only be accessed
     * on the localhost.
     */
    public $ipFilters = array('127.0.0.1', '::1');

    /**
     * Default id pathOfAlias('admindb.assets')
     * @var string
     */
    private $assetsUrl;

    /**
     * Supported databases {@see CDbConnection::$driverName}
     * @var array List of supported databases
     */
    private $supportedDatabases = array(
        'mysql' => 'CMysqlHelper',
    );

    /**
     *
     * @var CDbHelper Dynamic class based on $supportedDatabases
     */
    private $dbhelper;

    /**
     * Initializes the admindb module.
     */
    public function init() {
        Yii::app()->setImport(array(
            'admindb.components.*',
            'admindb.components.helpers.*',
            'admindb.controllers.*',
            'admindb.models.*',
        ));

        Yii::app()->setComponents(array(
            'errorHandler' => array(
                'class' => 'CErrorHandler',
                'errorAction' => $this->id . '/default/error',
            ),
            'user' => array(
                'class' => 'CWebUser',
                'stateKeyPrefix' => 'admindb',
                'loginUrl' => Yii::app()->createUrl($this->id . '/default/login'),
            ),
            'widgetFactory' => array(
                'class' => 'CWidgetFactory',
                'widgets' => array(),
            ),
        ), false);

        if (!$this->hasSupport()) {
            throw new CException(Yii::t('AdmindbModule.core', "Your database is not supported"));
        }

        if ($this->path === null) {
            $this->path = Yii::app()->runtimePath . '/admindb/';
        }

        if (!is_dir($this->path)) {
            if (!mkdir($this->path, 0755, true)) {
                throw new CException(Yii::t('AdmindbModule.core', 'The directory "{path}" can not be created', array('{path}' => $this->path)));
            }
        }

        parent::init();
    }

    /**
     * @return string the base URL that contains all published asset files of admindb.
     */
    public function getAssetsUrl() {
        if ($this->assetsUrl === null) {
            $this->assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('admindb.assets'));
        }

        return $this->assetsUrl;
    }

    /**
     * @param string $value the base URL that contains all published asset files of admindb.
     */
    public function setAssetsUrl($value) {
        $this->assetsUrl = $value;
    }

    public function getDbhelper() {
        if (!($this->dbhelper instanceof CDbHelper)) {
            $this->dbhelper = new $this->supportedDatabases[Yii::app()->db->driverName]();
        }

        return $this->dbhelper;
    }

    /**
     * Performs access check to admindb.
     * This method will check to see if user IP and password are correct if they attempt
     * to access actions other than "default/login" and "default/error".
     * @param CController $controller the controller to be accessed.
     * @param CAction $action the action to be accessed.
     * @return boolean whether the action should be executed.
     */
    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            $route = $controller->id . '/' . $action->id;

            if (!$this->allowIp(Yii::app()->request->userHostAddress) && $route !== 'default/error') {
                throw new CHttpException(403, Yii::t('AdmindbModule.core', "You are not allowed to access this page."));
            }

            $publicPages = array(
                'default/login',
                'default/error',
            );

            if ($this->password !== false && Yii::app()->user->isGuest && !in_array($route, $publicPages)) {
                Yii::app()->user->loginRequired();
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks to see if the user IP is allowed by {@link ipFilters}.
     * @param string $ip the user IP
     * @return boolean whether the user IP is allowed by {@link ipFilters}.
     */
    protected function allowIp($ip) {
        if (empty($this->ipFilters)) {
            return true;
        }

        foreach ($this->ipFilters as $filter) {
            if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos))) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @return boolean If database is supported
     */
    public function hasSupport() {
        return ((Yii::app()->db instanceof CDbConnection) and isset($this->supportedDatabases[Yii::app()->db->driverName]));
    }

}