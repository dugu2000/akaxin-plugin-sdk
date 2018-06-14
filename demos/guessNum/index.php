<?php


//
// 心有灵犀 演示代码
//
//
// 这只是一个Demo代码，正式产品请勿参考此代码的组织结构。
//



require_once(__DIR__ . "/../../sdk-php/AkaxinPluginApiClient.php");

require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/dbHelper.php");
require_once(__DIR__ . "/zalyHelper.php");


class GuessNum
{

    public $db;
    public $hrefUrl;
    public $u2Type     = "u2_msg";
    public $groupType  = "group_msg";
    public $tableName  = "heart_and_soul";
    public $siteAddress  = "";//需要修改对应的站点
    public $u2HrefUrl    = "zaly://SiteAddress/goto?page=plugin_for_u2_chat&site_user_id=chatSessionId&plugin_id=PluginId&akaxin_param=";
    public $groupHrefUrl = "zaly://SiteAddress/goto?page=plugin_for_group_chat&site_group_id=chatSessionId&plugin_id=PluginId&&akaxin_param=";

    public $akaxinApiClient;
    public $pluginHttpDomain = ""; ////需要修改成对应的扩展服务器地址
    public static $instance = null;

    public $cssForWebmsg;

    public $dbHelper;
    public $zalyHelper;
    public $pluginId;

    /**
     * @return GuessNum|null
     *
     * @author 尹少爷 2018.6.13
     */
    public static function getInstance()
    {
        if(!self::$instance) {
            self::$instance = new GuessNum();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->dbHelper   = DBHelper::getInstance();
        $this->zalyHelper = ZalyHelper::getInstance();
        $config = getConf();
        $this->pluginId = $config['plugin_id'];
        $this->siteAddress = $config['site_address'];
        $this->pluginHttpDomain = $config['plugin_http_domain'];

        ////

        $this->cssForWebmsg = <<<eot
            <link rel="stylesheet" href="{$this->pluginHttpDomain}/Public/css/zaly.css" />
eot;
    }

    /**
     * 检查数据库以及表
     *
     * @author 尹少爷 2018.6.13
     */
    public function checkoutDB()
    {
        $this->dbHelper->checkDBExists();
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
        $userProfile = $this->zalyHelper->getSiteUserProfile($siteSessionId);
        if(!$userProfile) {
            return json_encode(['error_code' => 'fail', 'error_msg' => '请稍候再试！']);
        }
        $siteUserId    = $userProfile->getSiteUserId();
        $siteUserPhoto = $userProfile->getUserPhoto();

        if($gameNum) {
            ////判断游戏是否是我开启的，自己开启的，无法参与
            $isSponsorMaster = $this->dbHelper->checkIsMineGame($chatSessionId, $siteUserId, $gameNum);
            if($isSponsorMaster) {
                return json_encode(['error_code' => 'fail', 'error_msg' => '无法参与自己开局的游戏']);
            }

            ///check 该局是否已经结束
            $isGameOver = $this->dbHelper->checkIsGameOver($chatSessionId, $siteUserId, $hrefType, $gameNum);
            if($isGameOver) {
                return json_encode(['error_code' => 'fail', 'error_msg' => '该局游戏已经结束！']);
            }

            /////判断是否已经参与过本局游戏了
            $isGuess = $this->dbHelper->checkIsGuess($chatSessionId, $siteUserId, $gameNum);
            if($isGuess) {
                return json_encode(['error_code' => 'fail', 'error_msg' => '你已经参与过本局了！']);
            }

            ////check该数字是否已经被人选择过
            $isNumGuess = $this->dbHelper->checkIsNumGuess($chatSessionId, $gameNum, $guessNum);
            if($isNumGuess) {
                return json_encode(['error_code' => 'fail', 'error_msg' => '该数字已经被人选择过了！']);
            }

        }

        ////判断是否可以作为新开局
        if($isSponsor) {
            $isJuisdiction = $this->dbHelper->checkGameJurisdiction($siteUserId, $chatSessionId, $hrefType);
            if(!$isJuisdiction) {
                error_log('你不是上一局猜对的人，或者距离上一局没有超过10分钟，暂时无法开局！' );
                return json_encode(['error_code' => 'fail', 'error_msg' => '暂时无法开局！']);
            }
            $gameNum = $this->dbHelper->getGameNum($chatSessionId);
            $gameNum ++;
        }

        if($isSponsor) {
            $this->dbHelper->insertGuessNum($siteUserId, $siteUserPhoto, $chatSessionId, $gameNum, $gameType, $guessNum, $isSponsor, 0);
            $hrefUrl = $this->getHrefUrl($chatSessionId, $siteUserId, $gameNum, $gameType, $hrefType);
            $this->sendPluginMsg($chatSessionId, $siteSessionId, $siteUserId, $gameType, $hrefType, $hrefUrl);
            return json_encode(['error_code' => 'success', 'game_num' => $gameNum, 'is_right' => 0, 'site_user_photo' => ""]);
        } else {
            $hrefUrl = $this->getHrefUrl($chatSessionId, $siteUserId,  $gameNum, $gameType, $hrefType);
            $sponsorGuess    = $this->dbHelper->getSponsorGuessNum($chatSessionId, $siteUserId, $hrefType, $gameNum);
            $sponsorGuessNum = $sponsorGuess['guess_num'];
            $isRight = $guessNum == $sponsorGuessNum ? 1 : 0;
            if($isRight) {
                $this->dbHelper->insertGuessNum($siteUserId, $siteUserPhoto, $chatSessionId, $gameNum, $gameType, $guessNum, $isSponsor, $isRight);

                $this->sendSuccessMsg($chatSessionId, $siteSessionId, $siteUserId, $guessNum, $hrefType,$hrefUrl);
                $this->sendSuccessMsgNotice($chatSessionId, $siteSessionId, $siteUserId, $gameNum, $gameType, $hrefType, $hrefUrl);
                return json_encode(['error_code' => 'success', 'game_num' => $gameNum, 'is_right' => $isRight, 'site_user_photo' => $siteUserPhoto]);
            }
            $this->dbHelper->insertGuessNum($siteUserId, $siteUserPhoto, $chatSessionId, $gameNum, $gameType, $guessNum, $isSponsor, $isRight);

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

        $gameUserInfo = $this->dbHelper->getGameUserInfo($chatSessionId, $siteUserId, $hrefType, $gameNum);
        if($gameUserInfo) {
            $gameUserInfo = array_column($gameUserInfo, null, 'guess_num');
        }

        $startNum = 1;
        $webCode = <<<eot
        <!DOCTYPE html><html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" />
                {$this->cssForWebmsg}
            </head>
            <body style="background: #eee;">
                <div class="wrapper"><div>
eot;

        for($i=0; $i<$row_num; $i++) {
            $webCode .= '<div class="d-flex flex-row justify-content-center">';
            for($j=0; $j<$row_num; $j++) {
                if(isset($gameUserInfo[$startNum]) && $gameUserInfo[$startNum]>0 ) {
                    $gameSiteUserId = $gameUserInfo[$startNum]['site_user_id'];
                    $webCode .= '<div class="p-2 guess_num">';
                    if(isset($gameUserInfo[$startNum]['is_right']) && $gameUserInfo[$startNum]['is_right']>0 ) {
                        $webCode .= <<<eot
                        <div class="zaly_border zaly-num-right-style " >
                            <img src="{$this->pluginHttpDomain}/index.php?page_type=imageDownload&game_site_user_id={$gameSiteUserId}"
                                style="height:30px; width:30px;border-radius:50%; text-align: center;margin-top: 3px;"
                            />
                        </div>
eot;
                    } else {
                        $webCode .= <<<eot
                         <div class="zaly_border zaly-num-wrong-style" >
                            <img  src="{$this->pluginHttpDomain}/index.php?page_type=imageDownload&game_site_user_id={$gameSiteUserId}"
                                style="height:30px; width:30px;border-radius:50%; text-align: center;margin-top: 3px;"
                            />
                        </div>
eot;
                    }
                    $webCode .= '</div>';
                } else {
                    $webCode .= '<div class=" p-2 guess_num "> <button type="button" class="btn  zaly-border zaly-num-style new_game ">'.$startNum.'</button> </div>';
                }
                $startNum++;
            }
            $webCode .= '</div>';
        }

        $webCode .= "</div></div></body></html>";

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
        if($hrefType == $this->u2Type) {
            $this->zalyHelper->setU2WebNoticeMsgByApiClient($chatSessionId, $siteSessionId,$siteUserId, $webCode, $hrefUrl, $height);
        } else {
            $this->zalyHelper->setGroupWebNoticeMsgByApiClient($chatSessionId, $siteSessionId,$siteUserId, $webCode, $hrefUrl, $height);
        }
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
            $hrefUrl = str_replace(["SiteAddress", "chatSessionId", "PluginId"], [$this->siteAddress, $siteUserId, $this->pluginId], $this->u2HrefUrl);
        } else {
            $hrefUrl = str_replace(["SiteAddress", "chatSessionId", "PluginId"], [$this->siteAddress, $chatSessionId, $this->pluginId], $this->groupHrefUrl);
        }
        error_log("href_url ==" . $hrefUrl);
        $hrefUrl .= urlencode(json_encode($params));
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
        $text = "";
        switch ($gameType) {
            case 4:
                $text = "四猜一";
                break;
            case 9:
                $text = "九猜一";
                break;
            case 16:
                $text = "十六猜一";
                // $webCode = '<!DOCTYPE html> <html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0"> <title>心有灵犀</title> </title> <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" /> <style> body, html {height: 100%; -webkit-tap-highlight-color: transparent; background-color: #F7CCE8; font-size: 10px; } .zaly-margin-16px{margin-top: 16px; } .zaly-btn, .zaly-btn:hover,.zaly-btn:active, .zaly-btn:focus, .zaly-btn:active:focus, .zaly-btn:active:hover{width:209px; height:46px; background:rgba(226,130,179,1); box-shadow:0px 8px 4px -8px rgba(242,234,165,1); border-radius:4px; border:4px solid rgba(188,83,131,1); } .zaly-btn-font{font-size:14px; font-family:PingFangSC-Regular; color:rgba(255,255,255,1); line-height:20px; margin-bottom: 10px; } </style> </head> <body ontouchstart=""> <div class="d-flex flex-column "> <div class="p-2 d-flex   zaly-margin-16px justify-content-center"> <label style="text-align: center;">我发起了一场心有灵犀：十六猜一</label> </div> <div class="p-2 d-flex justify-content-center"> <button type="button" class="btn zaly-btn zaly-btn-font">来猜我的神秘数字吧</button> </div> </div> </body> </html>';
                break;
        }
        $webCode = <<<eot
        <!DOCTYPE html><html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
            {$this->cssForWebmsg}
            <style>

                .zaly-btn, .zaly-btn:hover,.zaly-btn:active, .zaly-btn:focus, .zaly-btn:active:focus, .zaly-btn:active:hover {
                    width:209px; height:46px;
                    background:rgba(226,130,179,1);
                    box-shadow:0px 8px 4px -8px rgba(242,234,165,1);
                    border-radius:4px; border:4px solid rgba(188,83,131,1);
                }
                .zaly-btn-font{
                    font-size:14px; font-family:PingFangSC-Regular;
                    color:rgba(255,255,255,1);
                    line-height:20px;
                    margin-bottom: 10px;
                }

            </style>
        </head>
        <body>
        <div class="wrapper">
            <div>
                <div style="text-align: center; margin: 16px auto 10px auto; color:rgba(188,83,131,1); font-weight: bold;">
                    我发起了一场心有灵犀：{$text}
                </div>
                <div>
                    <button type="button" class="btn zaly-btn zaly-btn-font">来猜我的神秘数字吧</button>
                </div>
            </div>
        </div></body></html>
eot;

        $this->setMsgByApiClient($chatSessionId, $siteSessionId, $siteUserId, $webCode, $hrefType, $hrefUrl, 100, 300);
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
        $webCode = <<<eot
        <!DOCTYPE html><html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
            {$this->cssForWebmsg}
        </head>
        <body ontouchstart="">
            <div class="wrapper">
                我猜是：{$guessNum} 猜对了！赢得了开局机会。</p>
            </div>
        </body></html>
eot;
        $this->setMsgByApiClient($chatSessionId, $siteSessionId, $siteUserId, $webCode, $hrefType, $hrefUrl, 40, 200);
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
        $webCode = <<<EOT
        <!DOCTYPE html><html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
                {$this->cssForWebmsg}
            </head>
            <body>
                <div class="wrapper">
                    <p>我猜是：{$guessNum}！猜错了！/(ㄒoㄒ)/~~ </p>
                </div>
            </body>
        </html>
EOT;
        $this->setMsgByApiClient($chatSessionId, $siteSessionId, $siteUserId, $webCode, $hrefType, $hrefUrl, 40, 200);
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
    public function setMsgByApiClient($chatSessionId, $siteSessionId,$siteUserId, $webCode,  $hrefType, $hrefUrl, $height = 30, $width = 160)
    {
        if($hrefType == $this->u2Type) {
            $this->zalyHelper->setU2WebMsgByApiClient($chatSessionId, $siteSessionId,$siteUserId, $webCode, $hrefUrl, $height, $width );
            return;
        }
        $this->zalyHelper->setGroupWebMsgByApiClient($chatSessionId, $siteSessionId,$siteUserId, $webCode, $hrefUrl, $height, $width);
    }
}


$guessNumObj =  GuessNum::getInstance();
$guessNumObj->checkoutDB();

$pageType  = isset($_GET['page_type']) ? $_GET['page_type'] : "first";
$gameType  = isset($_GET['game_type']) ? $_GET['game_type'] : 4;
$hrefType  = isset($_GET['href_type']) ? $_GET['href_type'] : "";
$gameNum   = isset($_GET['game_num']) ? $_GET['game_num'] : "";
$guessNum  = isset($_GET['guess_num']) ? $_GET['guess_num'] : "";
$isSponsor = isset($_GET['is_sponsor']) ? $_GET['is_sponsor'] : 0;

$chatSessionId = isset($_GET['chat_session_id']) ? $_GET['chat_session_id'] :"";

////如果是下载图片，则直接返回数据
if($pageType == 'imageDownload') {
    $gameSiteUserId = isset($_GET['game_site_user_id']) ? $_GET['game_site_user_id'] : "";
    $userAvatar     = $guessNumObj->zalyHelper->getUserAvatar($gameSiteUserId);
    header('Content-Type: image/png');
    echo $userAvatar;
    return false;
}

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
$urlParams   = $guessNumObj->parseUrl($httpReferer);

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
        $urlParams['http_domain'] = $guessNumObj->pluginHttpDomain;
        $urlParams['href_url'] = $guessNumObj->pluginHttpDomain."/index.php?is_sponsor=1&page_type=second&chat_session_id=".$urlParams['chat_session_id']."&href_type=".$urlParams['href_type'];
        echo $guessNumObj->render("guessNum", $urlParams);
        break;

    case "second":
        $urlParams = [
            'four_url'    => $guessNumObj->pluginHttpDomain."/index.php?is_sponsor=".$isSponsor."&page_type=third&game_type=4&chat_session_id=".$chatSessionId."&href_type=".$hrefType,
            'nine_url'    => $guessNumObj->pluginHttpDomain."/index.php?is_sponsor=".$isSponsor."&page_type=third&game_type=9&chat_session_id=".$chatSessionId."&href_type=".$hrefType,
            'sixteen_url' => $guessNumObj->pluginHttpDomain."/index.php?is_sponsor=".$isSponsor."&page_type=third&game_type=16&chat_session_id=".$chatSessionId."&href_type=".$hrefType,
            'http_domain' => $guessNumObj->pluginHttpDomain,
        ];
        echo $guessNumObj->render("chooseGameType", $urlParams);
        break;

    case "third":
        $rowNum    = sqrt($gameType);
        $gameUserInfo = [];
        if($gameNum) {
            $userProfile = $guessNumObj->zalyHelper->getSiteUserProfile($siteSessionId);
            $siteUserId  = $userProfile->getSiteUserId();
            $gameUserInfo = $guessNumObj->dbHelper->getGameUserInfo($chatSessionId, $siteUserId, $hrefType, $gameNum);
            if($gameUserInfo) {
                $gameUserInfo = array_column($gameUserInfo, null, 'guess_num');
            }
        }
        $guessType = ['game_type' => $gameType, 'game_user_info' => $gameUserInfo, 'game_num' => $gameNum,'row_num' => $rowNum, "start_num" => 1, 'href_type' => $hrefType, 'chat_session_id' => $chatSessionId, 'is_sponsor' => $isSponsor, 'http_domain' => $guessNumObj->pluginHttpDomain];
        echo $guessNumObj->render("chooseNumForGame", $guessType);
        break;

    case "four":
        echo $guessNumObj->handleGuessNum($siteSessionId, $chatSessionId, $guessNum, $gameType, $gameNum, $hrefType, $isSponsor);
        break;

}
