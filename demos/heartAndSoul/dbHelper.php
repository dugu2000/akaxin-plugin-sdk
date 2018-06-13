<?php
class DBHelper
{
    public $db;
    public $dbName = "openzaly_heartAndSoul.db";
    public $tableName  = "heart_and_soul";
    public $u2Type     = "u2_msg";
    public $groupType  = "group_msg";
    public $expirtTime = 10;//10分钟过期
    public static $instance = null;

    protected function __construct()
    {
        $this->db = new \PDO("sqlite:./".$this->dbName);
    }

    public static function getInstance()
    {
        if(!self::$instance) {
            self::$instance = new DBHelper();
        }
        return self::$instance;
    }

    public function checkDBExists(){
        $createDBString = " CREATE TABLE IF NOT EXISTS  heart_and_soul (".
        " _id INTEGER PRIMARY KEY, ".
        " site_user_id VARCHAR(100)  NOT NULL ,".
        " site_user_photo VARCHAR(100)  NOT NULL ,".
        " game_num INTEGER, ".
        " game_type INTEGER,".
        " guess_num INTEGER,".
        " is_sponsor BOOLEAN,".
        " is_right BOOLEAN,".
        " chat_session_id VARCHAR(100)  NOT NULL ,".
        " create_time DATETIME,".
        " unique(site_user_id, chat_session_id, game_num) );";
        $this->db->exec($createDBString);
    }

    /**
     * 写入数据表
     * @param $siteSessionId
     * @param $chatSessionId
     * @param $guessNum
     * @return mixed
     *
     * @author 尹少爷 2018.6.11
     */
    public function insertGuessNum($siteUserId, $siteUserPhoto, $chatSessionId, $gameNum, $gameType, $guessNum, $isSponsor, $isRight)
    {
        try{
            $createTime = date('Y-m-d H:i:s', time());
            $sql = "insert into  `$this->tableName`(site_user_id, site_user_photo, chat_session_id, game_num, game_type, guess_num, is_sponsor, is_right, create_time) values(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $prepare = $this->db->prepare($sql);
            $prepare->bindParam(1, $siteUserId, \PDO::PARAM_STR);
            $prepare->bindParam(2, $siteUserPhoto, \PDO::PARAM_STR);
            $prepare->bindParam(3, $chatSessionId, \PDO::PARAM_STR);
            $prepare->bindParam(4, $gameNum, \PDO::PARAM_STR);
            $prepare->bindParam(5, $gameType, \PDO::PARAM_STR);
            $prepare->bindParam(6, $guessNum, \PDO::PARAM_STR);
            $prepare->bindParam(7, $isSponsor, \PDO::PARAM_BOOL);
            $prepare->bindParam(8, $isRight, \PDO::PARAM_BOOL);
            $prepare->bindParam(9, $createTime, \PDO::PARAM_STR);

            return $prepare->execute();
        }catch (Exception $ex) {
            error_log($ex->getMessage());
        }
    }

    /**
     * @param $chatSessionId
     * @param $gameNum
     * @return array
     */
    public function getGameUserInfo($chatSessionId, $siteUserId, $hrefType, $gameNum)
    {
        try {
            if($hrefType == $this->u2Type) {
                $sql = "select site_user_id, site_user_photo, guess_num, is_right from `$this->tableName` where ((chat_session_id=? and  site_user_id=?) or (chat_session_id=? and  site_user_id=?)) and game_num = ? and is_sponsor = 0;";
                $prepare = $this->db->prepare($sql);
                $prepare->bindParam(1, $chatSessionId, \PDO::PARAM_STR);
                $prepare->bindParam(2, $siteUserId, \PDO::PARAM_STR);
                $prepare->bindParam(3, $siteUserId, \PDO::PARAM_STR);
                $prepare->bindParam(4, $chatSessionId, \PDO::PARAM_STR);
                $prepare->bindParam(5, $gameNum, \PDO::PARAM_STR);
            }else {
                $sql = "select site_user_id, site_user_photo, guess_num, is_right from `$this->tableName` where chat_session_id=? and game_num = ? and is_sponsor = 0;";
                $prepare = $this->db->prepare($sql);
                $prepare->bindParam(1, $chatSessionId, \PDO::PARAM_STR);
                $prepare->bindParam(2, $gameNum, \PDO::PARAM_STR);
            }

            $prepare->execute();
            $results = $prepare->fetchAll(\PDO::FETCH_ASSOC);
            if(isset($results) && is_array($results) && count($results)) {
                return $results;
            }
            return [];
        }catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * 是否是我开启的游戏
     *
     * @param $chatSessionId
     * @param $siteUserId
     * @param $gameNum
     * @return bool
     */
    public function checkIsMineGame($chatSessionId, $siteUserId, $gameNum)
    {
        try{
            $sql = "select site_user_id, create_time from `$this->tableName` where chat_session_id=? and game_num=? and is_sponsor =1 order by _id desc limit 1 ;";
            $prepare = $this->db->prepare($sql);
            $prepare->bindParam(1, $chatSessionId, \PDO::PARAM_STR);
            $prepare->bindParam(2, $gameNum, \PDO::PARAM_STR);
            $prepare->execute();
            $results = $prepare->fetch(\PDO::FETCH_ASSOC);

            if(isset($results) && is_array($results) && count($results)) {
                if($results['site_user_id'] == $siteUserId) {
                    return true;
                }
            }
        }catch (Exception $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    /**
     * 是否已经参与过该轮游戏的猜测了
     *
     * @param $chatSessionId
     * @param $siteUserId
     * @param $gameNum
     * @return bool
     */
    public  function checkIsGuess($chatSessionId, $siteUserId, $gameNum)
    {
        $sql = "select _id from `$this->tableName` where chat_session_id=? and  site_user_id = ? and game_num = ?  order by _id desc limit 1 ;";
        $prepare = $this->db->prepare($sql);
        $prepare->bindParam(1, $chatSessionId, \PDO::PARAM_STR);
        $prepare->bindParam(2, $siteUserId, \PDO::PARAM_STR);
        $prepare->bindParam(3, $gameNum, \PDO::PARAM_STR);
        $prepare->execute();
        $results = $prepare->fetch(\PDO::FETCH_ASSOC);
        if(isset($results) && is_array($results) && count($results)) {
            return true;
        }
        return false;
    }

    /**
     * 检查该数字是否已经被人选择过了
     *
     * @param $chatSessionId
     * @param $gameNum
     * @param $guessNum
     * @return bool
     */
    public  function checkIsNumGuess($chatSessionId, $gameNum, $guessNum)
    {
        $sql = "select _id from `$this->tableName` where chat_session_id=? and game_num = ? and guess_num = ? and is_sponsor=0 order by _id desc limit 1 ;";
        $prepare = $this->db->prepare($sql);
        $prepare->bindParam(1, $chatSessionId, \PDO::PARAM_STR);
        $prepare->bindParam(2, $gameNum, \PDO::PARAM_STR);
        $prepare->bindParam(3, $guessNum, \PDO::PARAM_STR);
        $prepare->execute();
        $results = $prepare->fetch(\PDO::FETCH_ASSOC);
        if(isset($results) && is_array($results) && count($results)) {
            return true;
        }
        return false;
    }

    public function checkIsGameOver($chatSessionId, $gameNum)
    {
        $sql = "select _id from `$this->tableName` where chat_session_id=? and game_num = ? and is_right=1 order by _id desc limit 1 ;";
        $prepare = $this->db->prepare($sql);
        $prepare->bindParam(1, $chatSessionId, \PDO::PARAM_STR);
        $prepare->bindParam(2, $gameNum, \PDO::PARAM_STR);
        $prepare->execute();
        $results = $prepare->fetch(\PDO::FETCH_ASSOC);
        if(isset($results) && is_array($results) && count($results)) {
            return true;
        }
        return false;
    }


    /**
     * 获取发起者的数字
     * @param $chatSessionId
     * @param $gameNum
     * @return mixed
     *
     * @author 尹少爷 2018.6.11
     */
    public function getSponsorGuessNum($chatSessionId, $siteUserId, $hrefType, $gameNum)
    {
        if($hrefType == $this->u2Type) {
            $sql = "select guess_num from `$this->tableName` where ((chat_session_id=? and site_user_id=?) or (chat_session_id=? and site_user_id=?)) and game_num = ? and is_sponsor = 1;";
            $prepare = $this->db->prepare($sql);
            $prepare->bindParam(1, $chatSessionId, \PDO::PARAM_STR);
            $prepare->bindParam(2, $siteUserId, \PDO::PARAM_STR);
            $prepare->bindParam(3, $siteUserId, \PDO::PARAM_STR);
            $prepare->bindParam(4, $chatSessionId, \PDO::PARAM_STR);
            $prepare->bindParam(5, $gameNum, \PDO::PARAM_STR);
        }else {
            $sql = "select guess_num from `$this->tableName` where chat_session_id=? and game_num = ? and is_sponsor = 1;";
            $prepare = $this->db->prepare($sql);
            $prepare->bindParam(1, $chatSessionId, \PDO::PARAM_STR);
            $prepare->bindParam(2, $gameNum, \PDO::PARAM_STR);
        }
        $prepare->execute();
        return $prepare->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * 获取当前chat下面的游标
     * @param $chatSessionId
     * @param $gameNum
     * @return bool
     *
     * @author 尹少爷 2018.6.11
     */
    public function getGameNum($chatSessionId)
    {
        $sql = "select game_num from `$this->tableName` where chat_session_id=?  order by game_num DESC LIMIT 1;";
        $prepare = $this->db->prepare($sql);
        $prepare->bindParam(1, $chatSessionId, \PDO::PARAM_STR);
        $prepare->execute();
        $results = $prepare->fetch(\PDO::FETCH_ASSOC);
        if(is_array($results) && count($results)) {
            return $results['game_num'];
        }
        return 0;
    }

    /**
     * 是否有权限开启下一轮游戏
     * @param $siteUserId
     * @param $chatSessionId
     * @return bool
     */
    public function checkGameJurisdiction($siteUserId, $chatSessionId, $hrefType)
    {
        try{
            /////是否上一局是猜对者
            $sql = "select _id, is_right from `$this->tableName` where chat_session_id=? and  site_user_id = ?  order by _id desc limit 1 ;";
            $prepare = $this->db->prepare($sql);
            $prepare->bindParam(1, $chatSessionId, \PDO::PARAM_STR);
            $prepare->bindParam(2, $siteUserId, \PDO::PARAM_STR);
            $prepare->execute();
            $results = $prepare->fetch(\PDO::FETCH_ASSOC);
            error_log(json_encode($results));

            if(isset($results) && is_array($results) && count($results)) {
                if($results['is_right'] == 1) {
                    error_log("我是上一局猜对者");
                    return true;
                }
            }
            /////判断时间是否已经超时s
            if($hrefType == $this->u2Type) {
                $sql = "select  site_user_id,chat_session_id, create_time from `$this->tableName` where ((chat_session_id='$chatSessionId' and site_user_id='$siteUserId') or (chat_session_id='$siteUserId' and site_user_id='$chatSessionId')) and is_sponsor=1 order by _id desc LIMIT 1;";
            } else {
                $sql = "select  site_user_id,chat_session_id, create_time from `$this->tableName` where chat_session_id='$chatSessionId' and is_sponsor=1 order by _id desc LIMIT 1;";
            }
            $query   = $this->db->query($sql);
            $results = $query->fetch(\PDO::FETCH_ASSOC);
            error_log("sql ====$sql");
            error_log("results ===".json_encode($results));
            if(isset($results) && is_array($results) && count($results)) {
                if(time()-strtotime($results['create_time'])<$this->expirtTime) {
                    return false;
                }
                return true;
            }
            return true;
        }catch (Exception $e) {
            error_log($e->getMessage());
        }
        return true;
    }

    /**
     *
     * @param $type
     * @param $siteUserId
     * @return string
     *
     * @author 尹少爷 2018.6.11
     */
    public function generateMsgId($type, $siteUserId)
    {
        $msgId = "";
        switch ($type) {
            case $this->msg_type_u2:
                $msgId .= "U2-";
                break;
            case $this->msg_type_group:
                $msgId .= "GROUP-";
                break;
            case $this->msg_type_notice:
                $msgId .= "NOTICE-";
                break;
        }
        if (strlen($siteUserId) > 8) {
            $msgId .= mb_substr($siteUserId, 0, 8);
        } else {
            $msgId .= $siteUserId;
        }
        $msgId .= "-";
        $msgId .= $this->getMsectime();
        return $msgId;
    }

    /*
     * php 毫秒
     * @author 尹少爷 2018.6.11
     */
    public  function getMsectime()
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectime;
    }

}