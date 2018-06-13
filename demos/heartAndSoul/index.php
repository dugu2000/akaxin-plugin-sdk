<?php

require_once(__DIR__ . "/../../sdk-php/AkaxinPluginApiClient.php");


class HeartAndSoul
{

    public $db;
    public $hrefUrl;
    public $dbName     = "openzaly_heartAndSoul.db";
    public $expirtTime = 10;//10分钟过期
    public $u2Type     = "u2_msg";
    public $groupType  = "group_msg";
    public $tableName  = "heart_and_soul";
    public $u2HrefUrl    = "zaly://192.168.1.103:2021/goto?page=plugin_for_u2_chat&site_user_id=chatSessionId&plugin_id=8&param=";
    public $groupHrefUrl = "zaly://192.168.1.103:2021/goto?page=plugin_for_group_chat&site_group_id=chatSessionId&plugin_id=8&&param=";

    public $pluginApiHost = "127.0.0.1";        // 对应启动服务器时的 -Dhttp.address 参数
    public $pluginApiPort = 8280;               // 对应启动服务器时的 -Dhttp.port 参数
    public $pluginAuthKey = "rJbef6iw3CypqWkp";// 管理平台->扩展列表，点击相应的扩展获取。
    public $pluginId = 8;

    public $msg_type_u2     = 1;
    public $msg_type_group  = 2;
    public $msg_type_notice = 3;
    public $akaxinApiClient;
    public $httpDomain = "http://192.168.3.43:5160";

    public function checkoutDB()
    {
        $this->db = new \PDO("sqlite:./$this->dbName");
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
     * 渲染页面
     *
     * @author 尹少爷 2018.6.11
     *
     */
    public function render($fileName, $params = [])
    {
        ob_start();
        $path = dirname(__DIR__)."/".basename(__DIR__).'/Views/'.$fileName.'.html';

        if ($params) {
            extract($params, EXTR_SKIP);
        }
        $htmlCode = file_get_contents($path);
        preg_match_all('/href-data="Public\/css\/(.*?)"/', $htmlCode, $matches, PREG_PATTERN_ORDER);
        $cssPath = array_pop($matches);
        if(isset($cssPath[0])) {
            $cssFile = dirname(__DIR__)."/".basename(__DIR__).'/Public/css/'.$cssPath[0];
            $cssCode = file_get_contents($cssFile);
            if ($cssCode) {
                extract(['css_code' => $cssCode], EXTR_SKIP);
            }
        }
        preg_match_all('/href-data="Public\/js\/(.*?)"/', $htmlCode, $matches, PREG_PATTERN_ORDER);
        $jsPath = array_pop($matches);
        if(isset($jsPath[0])) {
            $jsFile = dirname(__DIR__)."/".basename(__DIR__).'/Public/js/'.$jsPath[0];
            $jsCode = file_get_contents($jsFile);
            if ($jsCode) {
                extract(['js_code' => $jsCode], EXTR_SKIP);
            }
        }

        include($path);
        $var = ob_get_contents();
        ob_end_clean();
        return  $var;
    }


    /**
     * 返回跟随的referer
     * @param $url
     * @return mixed
     *
     * @author 尹少爷 2018.6.11
     */
    public function parseUrl($url)
    {
        $akaxinReferer = new AkaxinReferer($url);
        $akaxinReferer->isGroupChat();

        if($akaxinReferer->isU2Chat()){
            $chatSessionId = $akaxinReferer->getChatFriendId();
            $hrefType = "u2_msg";
        } else {
            $chatSessionId = $akaxinReferer->getChatGroupId();
            $hrefType = "group_msg";
        }
        return ['chat_session_id' => $chatSessionId, 'href_type' => $hrefType, 'akaxin_param' => $akaxinReferer->getAkaxinParam()];
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
        error_log(json_encode($results));
        if(is_array($results) && count($results)) {
            return $results['game_num'];
        }
        return 0;
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
            $sql = "select guess_num from `$this->tableName` where (chat_session_id=? or chat_session_id=?) and game_num = ? and is_sponsor = 1;";
            $prepare = $this->db->prepare($sql);
            $prepare->bindParam(1, $chatSessionId, \PDO::PARAM_STR);
            $prepare->bindParam(2, $siteUserId, \PDO::PARAM_STR);
            $prepare->bindParam(3, $gameNum, \PDO::PARAM_STR);
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
     * 是否有权限开启下一轮游戏
     * @param $siteUserId
     * @param $chatSessionId
     * @return bool
     */
    public function checkGameJurisdiction($siteUserId, $chatSessionId)
    {
        try{
            /////是否上一局是猜对者
            $sql = "select _id from `$this->tableName` where chat_session_id=? and  site_user_id = ? and is_right=1 order by _id desc limit 1 ;";
            $prepare = $this->db->prepare($sql);
            $prepare->bindParam(1, $chatSessionId, \PDO::PARAM_STR);
            $prepare->bindParam(2, $siteUserId, \PDO::PARAM_STR);
            $prepare->execute();
            $results = $prepare->fetch(\PDO::FETCH_ASSOC);
            error_log(json_encode($results));

            if(isset($results) && is_array($results) && count($results)) {
                error_log("我是上一局猜对者");
                return true;
            }
            /////判断时间是否已经超时
            $query   = $this->db->query("select  site_user_id, create_time from `$this->tableName` where chat_session_id='$chatSessionId' and is_sponsor=1 order by _id desc LIMIT 1;");
            $results = $query->fetch(\PDO::FETCH_ASSOC);
            error_log("sql ===="."select site_user_id, create_time from `$this->tableName` where chat_session_id='$chatSessionId' and is_sponsor=1 order by _id desc LIMIT 1;");
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
     * @param $chatSessionId
     * @param $gameNum
     * @return array
     */
    public function getGameUserInfo($chatSessionId, $siteSessionId, $hrefType, $gameNum)
    {
        try {
            if($hrefType == $this->u2Type) {
                $userProfile = $this->getSiteUserProfile($siteSessionId);
                $siteUserId  = $userProfile->getSiteUserId();
                $sql = "select site_user_id, site_user_photo, guess_num, is_right from `$this->tableName` where (chat_session_id=? or chat_session_id = ?) and game_num = ? and is_sponsor = 0;";
                $prepare = $this->db->prepare($sql);
                $prepare->bindParam(1, $chatSessionId, \PDO::PARAM_STR);
                $prepare->bindParam(2, $siteUserId, \PDO::PARAM_STR);
                $prepare->bindParam(3, $gameNum, \PDO::PARAM_STR);
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
            error_log(" sql === select site_user_id, create_time from `$this->tableName` where chat_session_id=$chatSessionId and game_num=$gameNum and is_sponsor =1 order by _id desc limit 1 ;");
            $prepare->execute();
            $results = $prepare->fetch(\PDO::FETCH_ASSOC);
            error_log(json_encode($results));

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
    protected  function checkoutIsGuess($chatSessionId, $siteUserId, $gameNum)
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
     * 处理猜测的数字
     * @param $siteSessionId
     * @param $chatSessionId
     * @param $guessNum
     * @param $gameNum
     * @param $hrefType
     * @return bool|void
     *
     * @author 尹少爷 2018.6.11
     */
    public function handleGuessNum($siteSessionId, $chatSessionId, $guessNum, $gameType, $gameNum, $hrefType, $isSponsor)
    {
        $userProfile = $this->getSiteUserProfile($siteSessionId);
        if(!$userProfile) {
            return json_encode(['error_code' => 'fail', 'error_msg' => '请稍候再试！']);
        }
        $siteUserId    = $userProfile->getSiteUserId();
        $siteUserPhoto = $userProfile->getUserPhoto();
        error_log('site_user_id === ' .$siteUserId );
        error_log('chat_session_id === ' .$chatSessionId );
        error_log('gameNum === ' .$gameNum );
        error_log("guess num === " .$guessNum);

        ////判断游戏是否是我开启的，自己开启的，无法参与
        if($gameNum) {
            $isSponsorMaster = $this->checkIsMineGame($chatSessionId, $siteUserId, $gameNum);

            if($isSponsorMaster) {
                return json_encode(['error_code' => 'fail', 'error_msg' => '无法参与自己开局的游戏']);
            }
            /////判断是否已经参与过本局游戏了
            $isGuess = $this->checkoutIsGuess($chatSessionId, $siteUserId, $gameNum);
            if($isGuess) {
                error_log('你已经参与过本局了！' );
                return json_encode(['error_code' => 'fail', 'error_msg' => '你已经参与过本局了！']);
            }
        }

        ////判断是否可以作为新开局
        if($isSponsor) {
            $isJuisdiction = $this->checkGameJurisdiction($siteUserId, $chatSessionId);
            if(!$isJuisdiction) {
                error_log('你不是上一局猜对的人，或者距离上一局没有超过10分钟，暂时无法开局！' );
                return json_encode(['error_code' => 'fail', 'error_msg' => '暂时无法开局！']);
            }
            $gameNum = $this->getGameNum($chatSessionId);
            $gameNum ++;
        }

        if($isSponsor) {
            $this->insertGuessNum($siteUserId, $siteUserPhoto, $chatSessionId, $gameNum, $gameType, $guessNum, $isSponsor, 0);
            $hrefUrl = $this->getHrefUrl($chatSessionId, $siteUserId, $gameNum, $gameType, $hrefType);
            $this->sendPluginMsg($chatSessionId, $siteSessionId, $siteUserId, $gameType, $hrefType, $hrefUrl);
            return json_encode(['error_code' => 'success', 'game_num' => $gameNum, 'is_right' => 0, 'site_user_photo' => ""]);
        } else {
            $hrefUrl = $this->getHrefUrl($chatSessionId, $siteUserId,  $gameNum, $gameType, $hrefType);
            $sponsorGuess    = $this->getSponsorGuessNum($chatSessionId, $siteUserId, $hrefType, $gameNum);
            $sponsorGuessNum = $sponsorGuess['guess_num'];
            $isRight = $guessNum == $sponsorGuessNum ? 1 : 0;
            if($isRight) {
                $this->insertGuessNum($siteUserId,  $siteUserPhoto,  $chatSessionId, $gameNum, $gameType, $guessNum, $isSponsor, 1);
                $this->sendSuccessMsg($chatSessionId, $siteSessionId, $siteUserId, $guessNum, $hrefType,$hrefUrl);
                ////$this->sendSuccessMsgNotice($chatSessionId, $siteSessionId, $siteUserId, $gameNum, $gameType, $hrefType, $hrefUrl);
                return json_encode(['error_code' => 'success', 'game_num' => $gameNum, 'is_right' => $isRight, 'site_user_photo' => $siteUserPhoto]);
            }
            $this->insertGuessNum($siteUserId,  $siteUserPhoto,  $chatSessionId, $gameNum, $gameType, $guessNum, $isSponsor, 0);
            $this->sendFailMsg($chatSessionId, $siteSessionId, $siteUserId, $guessNum, $hrefType, $hrefUrl);
            return json_encode(['error_code' => 'success', 'game_num' => $gameNum, 'is_right' => $isRight, 'site_user_photo' => $siteUserPhoto]);
        }
        return json_encode(['error_code' => 'success', 'game_num' => $gameNum, 'is_right' => 0, 'site_user_photo' => ""]);
    }

    /**
     *
     * @param $chatSessionId
     * @param $siteSessionId
     * @param $siteUserId
     * @param $gameNum
     * @param $gameType
     * @param $hrefType
     * @param $hrefUrl
     *
     */
    public function sendSuccessMsgNotice($chatSessionId, $siteSessionId, $siteUserId, $gameNum,  $gameType, $hrefType, $hrefUrl)
    {
        $row_num = sqrt($gameType);

        $gameUserInfo = $this->getGameUserInfo($chatSessionId, $siteSessionId, $hrefType, $gameNum);
        if($gameUserInfo) {
            $gameUserInfo = array_column($gameUserInfo, null, 'guess_num');
        }

        $startNum = 1;
        $webCode = '<!DOCTYPE html> <html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0"> <title>心有灵犀</title> </title> <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" /> <link rel="stylesheet" href="'.$this->httpDomain.'/Public/css/zaly.css" /> </head> </head> <body ontouchstart="" class="zaly-body">';
        for($i=0; $i<$row_num; $i++) {
            $webCode .= '<div class="d-flex flex-row justify-content-center" >';
            for($j=0; $j<$row_num; $j++) {
                if(isset($gameUserInfo[$startNum]) && $gameUserInfo[$startNum]>0 ) {
                    $gameSiteUserId = $gameUserInfo[$startNum]['site_user_id'];
                    $avatarBase64Content = $this->getUserBase64Avatar($gameSiteUserId, $siteSessionId);
                    $webCode .= '<div class="p-2  guess_num ">';
                    if(isset($gameUserInfo[$startNum]['is_right']) && $gameUserInfo[$startNum]['is_right']>0 ) {
                        $webCode .= '<div class="zaly_border zaly-num-right-style " ><img  src="data:image/png;base64,'.$avatarBase64Content.'" style="height:38px; width:38px;border-radius:50%; text-align: center;margin-top: 3px;" " /></div>';
                    } else {
                        $webCode .= '<div class="zaly_border zaly-num-wrong-style" ><img  src="data:image/png;base64,'.$avatarBase64Content.'" style="height:38px; width:38px;border-radius:50%; text-align: center;margin-top: 3px;" " /></div>';
                    }
                    $webCode .= '</div>';
                } else {
                    $webCode .= '<div class=" p-2 guess_num "> <button type="button" class="btn  zaly-border zaly-num-style new_game ">'.$startNum.'</button> </div>';
                }
                $startNum++;
            }
            $webCode .= '</div>';
        }
        $webCode .= "</body></html>";
        $height = 0;
        switch ($gameType) {
            case 4:
                $height = 400;
                break;
            case 9:
                $height = 600;
                break;
            case 16:
                $height = 800;
                break;
            default:
                $height = 800;
        }
        $this->setGroupWebNoticeMsgByApiClient($chatSessionId, $siteSessionId,$siteUserId, $webCode, $hrefUrl, $height);
    }

    /**
     * 获取头像
     *
     * @param $siteUserId
     * @param $siteSessionId
     * @return base64 string
     * @throws \Google\Protobuf\Internal\Exception
     *
     */
    public function getUserBase64Avatar($siteUserId, $siteSessionId)
    {
        $this->getAkaxinPluginApiClient($siteSessionId);
        $requestAvatar = new Akaxin\Proto\Plugin\HaiUserAvatarRequest();
        $requestAvatar->setSiteUserId($siteUserId);
        $resultData = $this->akaxinApiClient->request("/hai/user/avatar", $requestAvatar);

        $responseAvatar = new Akaxin\Proto\Plugin\HaiUserAvatarResponse();
        $responseAvatar->mergeFromString($resultData);

        $avatarContent = $responseAvatar->getPhotoContent();
        return base64_encode($avatarContent);
    }
    /**
     * 得到hrefUrl
     *
     * @param $chatSessionId
     * @param $gameNum
     * @param $gameType
     * @param $hrefType
     * @return mixed|string
     *
     */
    protected  function getHrefUrl($chatSessionId, $siteUserId, $gameNum, $gameType, $hrefType)
    {
        $params = [
            'is_sponsor' => 0,
            'page_type'  => "third",
            'game_num'   => $gameNum,
            'game_type'  => $gameType,
        ];
        if($hrefType == $this->u2Type) {
            $hrefUrl = str_replace("chatSessionId", $siteUserId, $this->u2HrefUrl);
        } else {
            $hrefUrl = str_replace("chatSessionId", $chatSessionId, $this->groupHrefUrl);
        }

        $hrefUrl .= json_encode($params);
        return $hrefUrl;
    }


    /**
     * @param $chatSessionId
     * @param $siteSessionId
     * @param $gameNum
     * @param $hrefType
     *
     * @author 尹少爷 2018.6.11
     */
    public function sendPluginMsg($chatSessionId, $siteSessionId, $siteUserId, $gameType, $hrefType, $hrefUrl)
    {
        switch ($gameType) {
            case 4:
                $webCode ='<!DOCTYPE html> <html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0"> <title>心有灵犀</title> </title> <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" /> <link rel="stylesheet" href="'.$this->httpDomain.'/Public/css/zaly_web.css" /> </head> <body ontouchstart=""> <div class="d-flex flex-column "> <div class="p-2 d-flex   zaly-margin-16px justify-content-center"> <label style="text-align: center;">我发起了一场心有灵犀：四猜一</label> </div> <div class="p-2 d-flex  justify-content-center"> <button type="button" class="btn zaly-btn zaly-btn-font">来猜我的神秘数字吧</button> </div> </div> </body> </html>';
                break;
            case 9:
                $webCode ='<!DOCTYPE html> <html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0"> <title>心有灵犀</title> </title> <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" /> <link rel="stylesheet" href="'.$this->httpDomain.'/Public/css/zaly_web.css" /> </head> <body ontouchstart=""> <div class="d-flex flex-column "> <div class="p-2 d-flex   zaly-margin-16px justify-content-center"> <label style="text-align: center;">我发起了一场心有灵犀：九猜一</label> </div> <div class="p-2 d-flex  justify-content-center"> <button type="button" class="btn zaly-btn zaly-btn-font">来猜我的神秘数字吧</button> </div> </div> </body> </html>';
                break;
            case 16:
                $webCode ='<!DOCTYPE html> <html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0"> <title>心有灵犀</title> </title> <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" /> <link rel="stylesheet" href="'.$this->httpDomain.'/Public/css/zaly_web.css" /> </head> <body ontouchstart=""> <div class="d-flex flex-column "> <div class="p-2 d-flex   zaly-margin-16px justify-content-center"> <label style="text-align: center;">我发起了一场心有灵犀：十六猜一</label> </div> <div class="p-2 d-flex  justify-content-center"> <button type="button" class="btn zaly-btn zaly-btn-font">来猜我的神秘数字吧</button> </div> </div> </body> </html>';
                break;
        }

        $this->setMsgByApiClient($chatSessionId, $siteSessionId, $siteUserId, $webCode, $hrefType, $hrefUrl, 100, 260);
    }

    /**
     * @param $chatSessionId
     * @param $siteSessionId
     * @param $gameNum
     * @param $hrefType
     *
     * @author 尹少爷 2018.6.11
     */
    public function sendSuccessMsg($chatSessionId, $siteSessionId, $siteUserId, $guessNum, $hrefType, $hrefUrl)
    {
        $webCode = '<!DOCTYPE html> <html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0"> <title>心有灵犀</title> </title> <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" /> <style> body, html {height: 100%; -webkit-tap-highlight-color: transparent; background-color: #F7CCE8; font-size: 10px; } .zaly-lable-fail {text-align: center; width: 150px; height: 23px; font-size: 14px; font-family: STYuanti-SC-Regular; color: rgba(0, 0, 0, 0.54); line-height: 43px; } </style> </head> <body ontouchstart=""> <div class="p-2 d-flex  justify-content-center"> <p class="zaly-lable-fail">我猜是：'.$guessNum.' ，猜对了！</p> </div> </body> </html>';
        $this->setMsgByApiClient($chatSessionId, $siteSessionId, $siteUserId, $webCode, $hrefType, $hrefUrl);
    }

    /**
     * @param $chatSessionId
     * @param $siteSessionId
     * @param $gameNum
     * @param $hrefType
     *
     * @author 尹少爷 2018.6.11
     */
    public function sendFailMsg($chatSessionId, $siteSessionId, $siteUserId, $guessNum, $hrefType, $hrefUrl)
    {
        $webCode = '<!DOCTYPE html> <html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0"> <title>心有灵犀</title> </title> <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" /> <style> body, html {height: 100%; -webkit-tap-highlight-color: transparent; background-color: #F7CCE8; font-size: 10px; } .zaly-lable-fail {text-align: center; width: 150px; height: 23px; font-size: 14px; font-family: STYuanti-SC-Regular; color: rgba(0, 0, 0, 0.54); line-height: 43px; } </style> </head> <body ontouchstart=""> <div class="p-2 d-flex  justify-content-center"> <p class="zaly-lable-fail">我猜是：'.$guessNum.' ，猜错了！</p> </div> </body> </html>';
        $this->setMsgByApiClient($chatSessionId, $siteSessionId, $siteUserId, $webCode, $hrefType, $hrefUrl);
    }

    /**
     * plugin 发送web消息
     *
     * @param $chatSessionId
     * @param $siteSessionId
     * @param $siteUserId
     * @param $webCode
     * @param $hrefType
     * @param $hrefUrl
     * @param int $height
     * @param int $width
     *
     * @author 尹少爷 2018.6.11
     */
    public function setMsgByApiClient($chatSessionId, $siteSessionId,$siteUserId, $webCode,  $hrefType, $hrefUrl, $height = 21, $width = 160)
    {
        error_log(" send web msg to === " . $hrefType);
        if($hrefType == $this->u2Type) {
            $this->setU2WebMsgByApiClient($chatSessionId, $siteSessionId,$siteUserId, $webCode, $hrefUrl, $height, $width );
            return;
        }
        $this->setGroupWebMsgByApiClient($chatSessionId, $siteSessionId,$siteUserId, $webCode, $hrefUrl, $height, $width);
    }
    /**
     * 站点代发消息
     * @param $chatSessionId
     * @param $siteSessionId
     * @param $webCode
     * @param $hrefType
     *
     * @author 尹少爷 2018.6.11
     */
    public function setGroupWebMsgByApiClient($chatSessionId, $siteSessionId,$siteUserId, $webCode, $hrefUrl, $height = 21, $width = 160)
    {
        $msgId = $this->generateMsgId($this->msg_type_group, $siteUserId);
        $groupWeb = new Akaxin\Proto\Core\GroupWeb();
        $groupWeb->setSiteUserId($siteUserId);
        $groupWeb->setSiteGroupId($chatSessionId);
        $groupWeb->setMsgId($msgId);
        $groupWeb->setHrefUrl($hrefUrl);
        $groupWeb->setHeight($height);
        $groupWeb->setWidth($width);
        $groupWeb->setWebCode($webCode);

        $message = new Akaxin\Proto\Site\ImCtsMessageRequest();
        $message->setType(\Akaxin\Proto\Core\MsgType::GROUP_WEB);
        $message->setGroupWeb($groupWeb);

        $requestMessage = new Akaxin\Proto\Plugin\HaiMessageProxyRequest();
        $requestMessage->setProxyMsg($message);
        $this->getAkaxinPluginApiClient($siteSessionId);
        $this->akaxinApiClient->request("/hai/message/proxy", $requestMessage);

    }

    /**
     * 发送groupWebNotice
     *
     * @param $chatSessionId
     * @param $siteSessionId
     * @param $siteUserId
     * @param $webCode
     * @param $hrefUrl
     * @param int $height
     *
     * @author 尹少爷 2018.6.12
     */
    public function setGroupWebNoticeMsgByApiClient($chatSessionId, $siteSessionId,$siteUserId, $webCode, $hrefUrl, $height = 21)
    {
        error_log(" setGroupWebNoticeMsgByApiClient ");
        $msgId = $this->generateMsgId($this->msg_type_notice, $siteUserId);
        $groupWebNotice = new Akaxin\Proto\Core\GroupWebNotice();
        $groupWebNotice->setSiteUserId($siteUserId);
        $groupWebNotice->setSiteGroupId($chatSessionId);
        $groupWebNotice->setMsgId($msgId);
        $groupWebNotice->setHrefUrl($hrefUrl);
        $groupWebNotice->setHeight($height);
        $groupWebNotice->setWebCode($webCode);

        $message = new Akaxin\Proto\Site\ImCtsMessageRequest();
        $message->setType(\Akaxin\Proto\Core\MsgType::GROUP_WEB_NOTICE);
        $message->setGroupWebNotice($groupWebNotice);

        $requestMessage = new Akaxin\Proto\Plugin\HaiMessageProxyRequest();
        $requestMessage->setProxyMsg($message);
        $this->getAkaxinPluginApiClient($siteSessionId);
        $this->akaxinApiClient->request("/hai/message/proxy", $requestMessage);
        error_log(" setGroupWebNoticeMsgByApiClient end=== ");

    }

    /**
     * 站点代发消息
     * @param $chatSessionId
     * @param $siteSessionId
     * @param $webCode
     * @param $hrefType
     *
     * @author 尹少爷 2018.6.11
     */
    public function setU2WebMsgByApiClient($chatSessionId, $siteSessionId, $siteUserId, $webCode, $hrefUrl, $height = 21, $width = 160)
    {
        $msgId = $this->generateMsgId($this->msg_type_u2, $siteUserId);
        $u2Web = new Akaxin\Proto\Core\U2Web();
        $u2Web->setSiteUserId($siteUserId);
        $u2Web->setSiteFriendId($chatSessionId);
        $u2Web->setMsgId($msgId);
        $u2Web->setHrefUrl($hrefUrl);
        $u2Web->setHeight($height);
        $u2Web->setWidth($width);
        $u2Web->setWebCode($webCode);

        $message = new Akaxin\Proto\Site\ImCtsMessageRequest();
        $message->setType(\Akaxin\Proto\Core\MsgType::U2_WEB);
        $message->setU2Web($u2Web);

        $requestMessage = new Akaxin\Proto\Plugin\HaiMessageProxyRequest();
        $requestMessage->setProxyMsg($message);
        $this->getAkaxinPluginApiClient($siteSessionId);
        $this->akaxinApiClient->request("/hai/message/proxy", $requestMessage);

    }


    /**
     * @param $siteSessionId
     *
     * @author 尹少爷 2018.6.11
     */
    public function getAkaxinPluginApiClient($siteSessionId){
        $this->akaxinApiClient = new AkaxinPluginApiClient($this->pluginApiHost, $this->pluginApiPort, $this->pluginId, $this->pluginAuthKey);
        $this->akaxinApiClient->setSessionSiteUserId($siteSessionId);
    }

    /**
     * @param $siteSessionId
     * @return bool|string
     * @throws \Google\Protobuf\Internal\Exception
     *
     * @author 尹少爷 2018.6.11
     */
    public function getSiteUserProfile($siteSessionId)
    {
        $profileRequest = new Akaxin\Proto\Plugin\HaiSessionProfileRequest();

        $profileRequest->setBase64SafeUrlSessionId($siteSessionId);
        $this->getAkaxinPluginApiClient($siteSessionId);
        $responseData = $this->akaxinApiClient->request("/hai/session/profile", $profileRequest);
        $profileResponse = new Akaxin\Proto\Plugin\HaiSessionProfileResponse();
        $profileResponse->mergeFromString($responseData);
        $userProfile = $profileResponse->getUserProfile();
        if(!$userProfile) {
            return false;
        }

        return $userProfile;
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

$heartAndSoulObj = new HeartAndSoul();
$heartAndSoulObj->checkoutDB();

$pageType  = isset($_GET['page_type']) ? $_GET['page_type'] : "first";
$gameType  = isset($_GET['game_type']) ? $_GET['game_type'] : 4;
$hrefType  = isset($_GET['href_type']) ? $_GET['href_type'] : "";
$gameNum   = isset($_GET['game_num']) ? $_GET['game_num'] : "";
$guessNum  = isset($_GET['guess_num']) ? $_GET['guess_num'] : "";
$isSponsor = isset($_GET['is_sponsor']) ? $_GET['is_sponsor'] : 0;

$chatSessionId = isset($_GET['chat_session_id']) ? $_GET['chat_session_id'] :"";

/////默认第四步骤是post请求
if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){
    $guessNum  = isset($_POST['guess_num']) ? $_POST['guess_num'] : "";
    $pageType  = isset($_POST['page_type']) ? $_POST['page_type'] : "four";
    $gameNum   = isset($_POST['game_num']) ? $_POST['game_num'] : "";
    $gameType  = isset($_POST['game_type']) ? $_POST['game_type'] : 4;
    $hrefType  = isset($_POST['href_type']) ? $_POST['href_type'] : "";
    $isSponsor = isset($_POST['is_sponsor']) ? $_POST['is_sponsor'] : 0;
    $chatSessionId = isset($_POST['chat_session_id']) ? $_POST['chat_session_id'] :"";
}

$httpCookie = isset($_COOKIE) ?  $_COOKIE : "";
if(!$httpCookie) {
    return false;
}
$siteSessionId = $httpCookie;
if(!isset($siteSessionId['akaxin_site_session_id'])) {
    return false;
}

$siteSessionId = isset($siteSessionId['akaxin_site_session_id']) ? $siteSessionId['akaxin_site_session_id'] : '';

$httpReferer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

////第一次进来需要处理chatSession, 以及hrefType, akaxin_param其他的时候
$urlParams   = $heartAndSoulObj->parseUrl($httpReferer);

if(isset($urlParams['akaxin_param']) && $urlParams['akaxin_param']) {
    $params = json_decode($urlParams['akaxin_param'], true);
    $pageType  = isset($params['page_type']) ? $params['page_type'] : "third" ;
    $isSponsor = $params['is_sponsor'];
    $gameType  = $params['game_type'];
    $gameNum   = $params['game_num'];
    $hrefType  = $urlParams['href_type'];
    $chatSessionId = $urlParams['chat_session_id'];
}

switch ($pageType) {
    case "first":
        $urlParams['http_domain'] = $heartAndSoulObj->httpDomain;
        $urlParams['href_url'] = $heartAndSoulObj->httpDomain."/heartAndSoul/?is_sponsor=1&page_type=second&chat_session_id=".$urlParams['chat_session_id']."&href_type=".$urlParams['href_type'];
        echo $heartAndSoulObj->render("heartAndSoul", $urlParams);
        break;

    case "second":
        $urlParams = [
            'four_url'    => $heartAndSoulObj->httpDomain."/heartAndSoul/?is_sponsor=".$isSponsor."&page_type=third&game_type=4&chat_session_id=".$chatSessionId."&href_type=".$hrefType,
            'nine_url'    => $heartAndSoulObj->httpDomain."/heartAndSoul/?is_sponsor=".$isSponsor."&page_type=third&game_type=9&chat_session_id=".$chatSessionId."&href_type=".$hrefType,
            'sixteen_url' => $heartAndSoulObj->httpDomain."/heartAndSoul/?is_sponsor=".$isSponsor."&page_type=third&game_type=16&chat_session_id=".$chatSessionId."&href_type=".$hrefType,
            'http_domain' => $heartAndSoulObj->httpDomain,
        ];
        echo $heartAndSoulObj->render("chooseGameType", $urlParams);
        break;

    case "third":
        $rowNum    = sqrt($gameType);
        $gameUserInfo = [];
        if($gameNum) {
            $gameUserInfo = $heartAndSoulObj->getGameUserInfo($chatSessionId, $siteSessionId, $hrefType, $gameNum);
            if($gameUserInfo) {
                $gameUserInfo = array_column($gameUserInfo, null, 'guess_num');
            }
        }
        $guessType = ['game_type' => $gameType, 'game_user_info' => $gameUserInfo, 'game_num' => $gameNum,'row_num' => $rowNum, "start_num" => 1, 'href_type' => $hrefType, 'chat_session_id' => $chatSessionId, 'is_sponsor' => $isSponsor, 'http_domain' => $heartAndSoulObj->httpDomain];
        echo $heartAndSoulObj->render("chooseNumForGame", $guessType);
        break;

    case "four":
        echo $heartAndSoulObj->handleGuessNum($siteSessionId, $chatSessionId, $guessNum, $gameType, $gameNum, $hrefType, $isSponsor);
        break;
}
