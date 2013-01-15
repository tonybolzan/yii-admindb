<?php

class CMysqlHelper extends CDbHelper {

    /**
     *
     * @param string $cmd Command to be verified if exists
     * @return boolean If command exists
     */
    private function commandExist($cmd) {
        system("$cmd --help > /dev/null", $output);
        return $output === 0;
    }

    /**
     * The Dump mode
     * @return string Explanation
     */
    public function dumpMode() {
        if ($this->commandExist('mysqldump')) {
            return 'system("mysqldump ...")';
        } else {
            return Yii::t('AdmindbModule.core', 'Not implemented (yet)');
        }
    }

    /**
     * The Restore mode
     * @return string Explanation
     */
    public function restoreMode() {
        if ($this->commandExist('mysql')) {
            return 'system("mysql ...")';
        } else {
            return Yii::t('AdmindbModule.core', 'PHP method (experimental)');
        }
    }

    /**
     * Dump Database to file
     * @param string $file
     * @return boolean If dump was succeeded successfully
     */
    public function dump($file) {
        if ($this->commandExist('mysqldump')) {
            $name = $this->getName();
            $host = $this->getHost();
            $port = $this->getPort();
            $user = $this->db->username;
            $pass = $this->db->password;

            system("mysqldump --opt --single-transaction --routines --triggers --events --host={$host} --port={$port} --user={$user} --password={$pass} --databases {$name} > {$file}", $output);
            if ($output > 0) {
                throw new CException(Yii::t('AdmindbModule.core', 'Error creating file with system("mysqldump ...")'));
            }
            return true;
        } else {
            throw new CException(Yii::t('AdmindbModule.core', 'Not implemented (yet)')); // TODO Implement this action (PHP version of mysqldump)
        }

        return false;
    }

    /**
     * Restore Database from file
     * @param string $file
     * @return boolean If restore was succeeded successfully
     */
    public function restore($file) {
        if ($this->commandExist('mysql')) {
            $name = $this->getName();
            $host = $this->getHost();
            $port = $this->getPort();
            $user = $this->db->username;
            $pass = $this->db->password;

            system("mysql --host={$host} --port={$port} --user={$user} --password={$pass} {$name} < {$file}", $output);
            if ($output > 0) {
                throw new CException(Yii::t('AdmindbModule.core', 'Error importing file with system("mysql ...")'));
            }
        } else {
            $transaction = $this->db->beginTransaction(); // fixme It may not work
            try {
                $content = file_get_contents($file);
                $content = preg_replace_callback("/\((.*)\)/", create_function('$matches', 'return str_replace(";"," $$$ ",$matches[0]);'), $content);
                $content = explode(";", $content);

                foreach ($content as $value) {
                    if (!empty($value)) {
                        $sql = str_replace(" $$$ ", ";", $value) . ";";
                        $this->db->pdoInstance->exec($sql);
                    }
                }

                $transaction->commit(); // fixme It may not work
                return true;
            } catch (CException $e) {
                $transaction->rollback(); // fixme It may not work
                return false;
            }
        }
        return true;
    }

}