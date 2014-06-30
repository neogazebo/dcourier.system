<?php

/**
 * MyCDbHttpSession
 * 
 * @package Yii
 * @author Twisted1919
 * @copyright 2011
 * @version 1.3
 * @access public
 */
class MyCDbHttpSession extends CDbHttpSession {

    public $_ip;
    public $_userid;

    /**
     * MyCDbHttpSession::createSessionTable()
     * 
     * @param mixed $db
     * @param mixed $tableName
     * @return
     */
    protected function createSessionTable($db, $tableName) {
        $sql = "CREATE TABLE IF NOT EXISTS `{$tableName}` (
          `id` varchar(40) NOT NULL,
          `ip_address` varchar(45) DEFAULT NULL,
          `user_id` int(10) NOT NULL,
          `timestamp` int(11) DEFAULT NULL,
          `session` longtext,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $db->createCommand($sql)->execute();
    }

    /**
     * MyCDbHttpSession::gcSession
     * @param 
     */
    public function gcSession($maxLifetime) {
        $sql = "DELETE FROM {$this->sessionTableName} WHERE timestamp<" . time();
        $this->getDbConnection()->createCommand($sql)->execute();
        return true;
    }

    /**
     * MyCDbHttpSession::readSession()
     * 
     * @param mixed $id
     * @return mixed $session on success, empty string on failure
     */
    public function readSession($id) {
        $ip = Yii::app()->request->getUserHostAddress();
        $db = $this->getDbConnection();
        $toBind = array();

        $sql = "SELECT `session` FROM `{$this->sessionTableName}` WHERE `id`=:id ";
        $sql.="AND `timestamp`>:timestamp LIMIT 1";
        $toBind[':id'] = $id;
        $toBind[':timestamp'] = time();

        $session = $db->createCommand($sql)->queryScalar($toBind);
        return (false === $session) ? '' : $session;
    }

    /**
     * MyCDbHttpSession::writeSession()
     * 
     * @param mixed $id
     * @param mixed $session
     * @return boolean
     */
    public function writeSession($id, $session) {
        try {
            $db = $this->getDbConnection();
            $toBind = array();
            $timestamp = time() + $this->getTimeout();
            $ip = Yii::app()->request->getUserHostAddress();
            $userid =  Yii::app()->user->id;
            $sql = "SELECT `id` FROM `{$this->sessionTableName}` WHERE `id`=:id ";
            $toBind[':id'] = $id;
            $sql.='LIMIT 1';

            if (false === $db->createCommand($sql)->queryScalar($toBind)) {
                $sql = "DELETE FROM `{$this->sessionTableName}` WHERE `id`=:id AND `ip_address`=:ip LIMIT 1";
                $db->createCommand($sql)->bindValue(':id', $id)->bindValue(':ip',$ip)->execute();

                $toBind = array();
                $sql = "INSERT INTO `{$this->sessionTableName}` (`id`,`ip_address`";
                $toBind[':id'] = $id;
                $toBind[':ip_address'] = $ip;
                if(!Yii::app()->user->isGuest){
                    $sql .=',`user_id`';
                    $toBind[':user_id']=$userid;
                }
                $toBind[':timestamp'] = $timestamp;
                $toBind[':session'] = $session;
                $sql.=',`timestamp`,`session`) VALUES(' . implode(',', array_keys($toBind)) . ')';
                $db->createCommand($sql)->execute($toBind);
            } else {
                $toBind = array();
                $sql = "UPDATE `{$this->sessionTableName}` SET `timestamp`=:timestamp, `session`=:session";
                $toBind[':timestamp'] = $timestamp;
                $toBind[':session'] = $session;

                $sql.=' WHERE `id`=:id LIMIT 1';
                $toBind[':id'] = $id;

                $db->createCommand($sql)->execute($toBind);
            }
        } catch (Exception $e) {
            if (YII_DEBUG)
                echo $e->getMessage();
            return false;
        }
        return true;
    }

    public function setSessionItem($id, $key, $val) {
        $timestamp = time() + $this->getTimeout();
        $db = $this->getDbConnection();
        $sql = "SELECT id FROM {$this->sessionTableName} WHERE id=:id";
        if ($db->createCommand($sql)->bindValue(':id', $id)->queryScalar() === false)
            $sql = "INSERT INTO {$this->sessionTableName} (id, $key, timestamp) VALUES (:id, :val, $timestamp)";
        else
            $sql = "UPDATE {$this->sessionTableName} SET $key=:val, timestamp =$timestamp WHERE id = :id";
        $db->createCommand($sql)->bindValue(':id', $id)->bindValue(':val', $val)->execute();
        return true;
    }

}

?>