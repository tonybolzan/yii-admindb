<?php

class InformationController extends AController {

    public $layout = '/layouts/default';

    public function actionIndex() {
        $this->render('index',array(
            'db' => $this->module->dbhelper->db,
        ));
    }

}