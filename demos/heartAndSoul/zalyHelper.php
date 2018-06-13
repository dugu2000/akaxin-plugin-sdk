<?php

class ZalyHelper
{
    public $u2Type       = "u2_msg";
    public $groupType    = "group_msg";

    public $msg_type_u2     = 1;
    public $msg_type_group  = 2;
    public $msg_type_notice = 3;
    public $expirtTime = 10*60;//10分钟过期

    public $akxinApiClient;
    public static $instance;
    public $innerApiHost;
    public $innerApiPort;

    public static function getInstance()
    {
        if(!self::$instance) {
            self::$instance = new ZalyHelper();
        }
        return self::$instance;
    }

    /**
     * ZalyHelper constructor.
     *
     * @author 尹少爷 2018.6.13
     */
    protected function __construct()
    {
        $config = parse_ini_file(__DIR__."/heart.ini");
        $this->pluginId      = $config['plugin_id'];
        $this->pluginAuthKey = $config['plugin_auth_key'];
        $this->innerApiHost = $config['inner_api_host'];
        $this->innerApiPort = $config['inner_api_port'];
        $this->akaxinApiClient = new AkaxinPluginApiClient($this->innerApiHost, $this->innerApiPort, $this->pluginId, $this->pluginAuthKey);
    }

    /**
     * set site session id
     * @param $siteSessionId
     *
     * @author 尹少爷 2018.6.13
     */
    public function setSiteSessionId($siteSessionId)
    {
        $this->akaxinApiClient->setSessionSiteUserId($siteSessionId);
    }

    /**
     * @param $siteSessionId
     * @return bool|string
     * @throws \Google\Protobuf\Internal\Exception
     *
     * @author 尹少爷 2018.6.11
     */
    public  function getSiteUserProfile($siteSessionId)
    {
        $profileRequest = new Akaxin\Proto\Plugin\HaiSessionProfileRequest();
        $profileRequest->setBase64SafeUrlSessionId($siteSessionId);
        $this->setSiteSessionId($siteSessionId);
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
     * 站点代发消息
     * @param $chatSessionId
     * @param $siteSessionId
     * @param $webCode
     * @param $hrefType
     *
     * @author 尹少爷 2018.6.11
     */
    public function setGroupWebMsgByApiClient($chatSessionId, $siteSessionId, $siteUserId, $webCode, $hrefUrl, $height = 21, $width = 160)
    {
        $msgId = $this->generateMsgId($this->msg_type_group, $siteUserId);
        $msgTime = mb_convert_encoding($this->getMsectime(), 'UTF-8');

        $groupWeb = new Akaxin\Proto\Core\GroupWeb();
        $groupWeb->setSiteUserId($siteUserId);
        $groupWeb->setSiteGroupId($chatSessionId);
        $groupWeb->setMsgId($msgId);
        $groupWeb->setHrefUrl($hrefUrl);
        $groupWeb->setHeight($height);
        $groupWeb->setWidth($width);
        $groupWeb->setWebCode($webCode);
        $groupWeb->setTime($msgTime);

        $message = new Akaxin\Proto\Site\ImCtsMessageRequest();
        $message->setType(\Akaxin\Proto\Core\MsgType::GROUP_WEB);
        $message->setGroupWeb($groupWeb);

        $requestMessage = new Akaxin\Proto\Plugin\HaiMessageProxyRequest();
        $requestMessage->setProxyMsg($message);
        $this->setSiteSessionId($siteSessionId);
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
        $msgId = $this->generateMsgId($this->msg_type_notice, $siteUserId);
        $msgTime = mb_convert_encoding($this->getMsectime(), 'UTF-8');

        $groupWebNotice = new Akaxin\Proto\Core\GroupWebNotice();
        $groupWebNotice->setSiteUserId($siteUserId);
        $groupWebNotice->setSiteGroupId($chatSessionId);
        $groupWebNotice->setMsgId($msgId);
        $groupWebNotice->setHrefUrl($hrefUrl);
        $groupWebNotice->setHeight($height);
        $groupWebNotice->setWebCode($webCode);
        $groupWebNotice->setTime($msgTime);

        $message = new Akaxin\Proto\Site\ImCtsMessageRequest();
        $message->setType(\Akaxin\Proto\Core\MsgType::GROUP_WEB_NOTICE);
        $message->setGroupWebNotice($groupWebNotice);

        $requestMessage = new Akaxin\Proto\Plugin\HaiMessageProxyRequest();
        $requestMessage->setProxyMsg($message);
        $this->setSiteSessionId($siteSessionId);
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
    public function setU2WebNoticeMsgByApiClient($chatSessionId, $siteSessionId,$siteUserId, $webCode, $hrefUrl, $height = 21)
    {
        $msgId = $this->generateMsgId($this->msg_type_notice, $siteUserId);
        $msgTime = mb_convert_encoding($this->getMsectime(), 'UTF-8');

        $u2WebNotice = new Akaxin\Proto\Core\U2WebNotice();
        $u2WebNotice->setSiteUserId($siteUserId);
        $u2WebNotice->setSiteFriendId($chatSessionId);
        $u2WebNotice->setMsgId($msgId);
        $u2WebNotice->setTime($msgTime);
        $u2WebNotice->setHrefUrl($hrefUrl);
        $u2WebNotice->setHeight($height);
        $u2WebNotice->setWebCode($webCode);

        $message = new Akaxin\Proto\Site\ImCtsMessageRequest();
        $message->setType(\Akaxin\Proto\Core\MsgType::U2_WEB_NOTICE);
        $message->setU2WebNotice($u2WebNotice);

        $requestMessage = new Akaxin\Proto\Plugin\HaiMessageProxyRequest();
        $requestMessage->setProxyMsg($message);
        $this->setSiteSessionId($siteSessionId);
        $this->akaxinApiClient->request("/hai/message/proxy", $requestMessage);
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
        $msgTime = mb_convert_encoding($this->getMsectime(), 'UTF-8');

        $u2Web = new Akaxin\Proto\Core\U2Web();
        $u2Web->setSiteUserId($siteUserId);
        $u2Web->setSiteFriendId($chatSessionId);
        $u2Web->setMsgId($msgId);
        $u2Web->setHrefUrl($hrefUrl);
        $u2Web->setHeight($height);
        $u2Web->setWidth($width);
        $u2Web->setWebCode($webCode);
        $u2Web->setTime($msgTime);

        $message = new Akaxin\Proto\Site\ImCtsMessageRequest();
        $message->setType(\Akaxin\Proto\Core\MsgType::U2_WEB);
        $message->setU2Web($u2Web);

        $requestMessage = new Akaxin\Proto\Plugin\HaiMessageProxyRequest();
        $requestMessage->setProxyMsg($message);
        $this->setSiteSessionId($siteSessionId);
        $this->akaxinApiClient->request("/hai/message/proxy", $requestMessage);

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
    public function getUserAvatar($siteUserId)
    {
        $requestAvatar = new Akaxin\Proto\Plugin\HaiUserAvatarRequest();
        $requestAvatar->setSiteUserId($siteUserId);
        $resultData = $this->akaxinApiClient->request("/hai/user/avatar", $requestAvatar);

        $responseAvatar = new Akaxin\Proto\Plugin\HaiUserAvatarResponse();
        $responseAvatar->mergeFromString($resultData);

        $avatarContent = $responseAvatar->getPhotoContent();
        return $avatarContent;
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