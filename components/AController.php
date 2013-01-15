<?php

class AController extends CController {

    public $layout = '/layouts/main';

    public function getPageTitle() {
        $title = 'AdminDB - ' . ucfirst($this->id);

        if ($this->action->id != $this->defaultAction) {
            $title .= '/' . ucfirst($this->action->id);
        }

        return $title;
    }

    // human readable format -- powers of 1024
    public function bytesToHuman($bytes, $precision = 2) {
        $unit = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
        return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision) . ' ' . $unit[$i];
    }

}