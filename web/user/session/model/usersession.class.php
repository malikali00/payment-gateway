<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 8/28/2016
 * Time: 1:32 PM
 */
namespace User\Session\Model;

use System\Config\DBConfig;
use System\Config\SiteConfig;
use User\Model\UserRow;

class UserSession
{
    const _CLASS = __CLASS__;

    const ENUM_TYPE_COOKIE = 'cookie';
    const ENUM_TYPE_SESSION = 'session';
    const ENUM_TYPE_PASSWORD_RESET = 'reset';

    const SQL_SELECT = "SELECT * FROM user_session us";

    protected $uid;
    protected $user_id;
    protected $type;
    protected $date;
    protected $data;

    public function getUID()        { return $this->uid; }

    public function getUserID()     { return $this->user_id; }
    public function getType()       { return $this->type; }
    public function getDate()       { return $this->date; }
    public function getData()       { return $this->data; }
    // Static


    public static function insert(UserRow $User, $type=self::ENUM_TYPE_COOKIE, $data=null) {
        $uid = self::generateGUID();
        $values = array(
            ':uid' => $uid,
            ':user_id' => $User->getID(),
            ':type' => $type,
            ':data' => $data,
        );

        $SQL = '';
        foreach($values as $key=>$value)
            $SQL .= ($SQL ? ',' : '') . "\n\t`" . substr($key, 1) . "` = " . $key;

        $SQL = "INSERT INTO user_session SET `date` = UTC_TIMESTAMP(),\n" . $SQL;

        $DB = DBConfig::getInstance();
        $stmt = $DB->prepare($SQL);
        $ret = $stmt->execute($values);
        if(!$ret)
            throw new \PDOException("Failed to insert new row");

        return self::fetchByUID($uid);
    }


    public static function delete(UserRow $User) {

        $SQL = "DELETE FROM user_session WHERE user_id = ?";

        $DB = DBConfig::getInstance();
        $stmt = $DB->prepare($SQL);
        $ret = $stmt->execute(array($User->getID()));
        if(!$ret)
            throw new \PDOException("Failed to delete row");
    }


    /**
     * @param $uid
     * @param bool $throwException
     * @return UserSession
     */
    public static function fetchByUID($uid, $throwException=true) {
        $DB = DBConfig::getInstance();
        $stmt = $DB->prepare(static::SQL_SELECT . "\nWHERE us.uid = ?");
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $stmt->setFetchMode(\PDO::FETCH_CLASS, self::_CLASS);
        $stmt->execute(array($uid));
        $UserSession = $stmt->fetch();
        if(!$UserSession && $throwException)
            throw new \InvalidArgumentException("Session not found: " . $uid);
        return $UserSession;
    }




    public static function generateGUID() {
        return SiteConfig::$SITE_UID_PREFIX . '-US-' . sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }
}

