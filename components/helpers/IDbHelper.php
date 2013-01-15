<?php

interface IDbHelper {

    public function getTables();

    public function getTableNames();

    public function __construct();

    public function getName();

    public function getHost();

    public function getPort();

    public function truncate($table = null);

    public function drop($table);

    public function dumpMode();

    public function restoreMode();

    public function restore($file);

    public function dump($file);
}