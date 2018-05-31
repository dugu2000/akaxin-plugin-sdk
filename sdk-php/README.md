## Table of contents


## API目录

### 好友

- [HaiFriendAddRequest](#class-akaxinprotopluginhaifriendaddrequest)
    - [HaiFriendAddResponse](#class-akaxinprotopluginhaifriendaddresponse)
- [HaiFriendApplyRequest](#class-akaxinprotopluginhaifriendapplyrequest)
    - [HaiFriendApplyResponse](#class-akaxinprotopluginhaifriendapplyresponse)
- [HaiFriendRelationsRequest](#class-akaxinprotopluginhaifriendrelationsrequest)
    - [HaiFriendRelationsResponse](#class-akaxinprotopluginhaifriendrelationsresponse)

### 群组

- [HaiGroupAddMemberRequest](#class-akaxinprotopluginhaigroupaddmemberrequest)
    - [HaiGroupAddMemberResponse](#class-akaxinprotopluginhaigroupaddmemberresponse)
- [HaiGroupCheckMemberRequest](#class-akaxinprotopluginhaigroupcheckmemberrequest)
    - [HaiGroupCheckMemberResponse](#class-akaxinprotopluginhaigroupcheckmemberresponse)
- [HaiGroupDeleteRequest](#class-akaxinprotopluginhaigroupdeleterequest)
    - [HaiGroupDeleteResponse](#class-akaxinprotopluginhaigroupdeleteresponse)
- [HaiGroupListRequest](#class-akaxinprotopluginhaigrouplistrequest)
    - [HaiGroupListResponse](#class-akaxinprotopluginhaigrouplistresponse)
- [HaiGroupMembersRequest](#class-akaxinprotopluginhaigroupmembersrequest)
    - [HaiGroupMembersResponse](#class-akaxinprotopluginhaigroupmembersresponse)
- [HaiGroupNonmembersRequest](#class-akaxinprotopluginhaigroupnonmembersrequest)
    - [HaiGroupNonmembersResponse](#class-akaxinprotopluginhaigroupnonmembersresponse)
- [HaiGroupProfileRequest](#class-akaxinprotopluginhaigroupprofilerequest)
    - [HaiGroupProfileResponse](#class-akaxinprotopluginhaigroupprofileresponse)
- [HaiGroupRemoveMemberRequest](#class-akaxinprotopluginhaigroupremovememberrequest)
    - [HaiGroupRemoveMemberResponse](#class-akaxinprotopluginhaigroupremovememberresponse)
- [HaiGroupUpdateRequest](#class-akaxinprotopluginhaigroupupdaterequest)
    - [HaiGroupUpdateResponse](#class-akaxinprotopluginhaigroupupdateresponse)

### 消息
- [HaiMessageProxyRequest](#class-akaxinprotopluginhaimessageproxyrequest)
    - [HaiMessageProxyResponse](#class-akaxinprotopluginhaimessageproxyresponse)

### 其他

- [HaiPushNoticesRequest](#class-akaxinprotopluginhaipushnoticesrequest)
    - [HaiPushNoticesResponse](#class-akaxinprotopluginhaipushnoticesresponse)
- [HaiSiteGetConfigRequest](#class-akaxinprotopluginhaisitegetconfigrequest)    
    - [HaiSiteGetConfigResponse](#class-akaxinprotopluginhaisitegetconfigresponse)

### 用户

- [HaiUserFriendsRequest](#class-akaxinprotopluginhaiuserfriendsrequest)
    - [HaiUserFriendsResponse](#class-akaxinprotopluginhaiuserfriendsresponse)
- [HaiUserGroupsRequest](#class-akaxinprotopluginhaiusergroupsrequest)
    - [HaiUserGroupsResponse](#class-akaxinprotopluginhaiusergroupsresponse)
- [HaiUserListRequest](#class-akaxinprotopluginhaiuserlistrequest)
    - [HaiUserListResponse](#class-akaxinprotopluginhaiuserlistresponse)
- [HaiUserPhoneRequest](#class-akaxinprotopluginhaiuserphonerequest)
    - [HaiUserPhoneResponse](#class-akaxinprotopluginhaiuserphoneresponse)
- [HaiUserProfileRequest](#class-akaxinprotopluginhaiuserprofilerequest)
    - [HaiUserProfileResponse](#class-akaxinprotopluginhaiuserprofileresponse)
- [HaiUserUpdateRequest](#class-akaxinprotopluginhaiuserupdaterequest)
    - [HaiUserUpdateResponse](#class-akaxinprotopluginhaiuserupdateresponse)

<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiFriendAddRequest

#### 功能
 添加A、B两人为好友
 #### 接口名
 /hai/friend/add
 #### 错误码
 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setFriendSiteUserId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>用户2</em> |
| public | <strong>setSiteUserId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>用户1</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiFriendAddResponse

> 无内容




<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiFriendApplyRequest


 #### A->B 发送好友请求

 #### 接口名

 /hai/friend/apply

 #### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setApplyReason(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>申请理由</em> |
| public | <strong>setFriendSiteUserId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>目标用户</em> |
| public | <strong>setSiteUserId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>发起好友请求的用户</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiFriendApplyResponse

| Visibility | Function |
|:-----------|:---------|




<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiFriendRelationsRequest


####  获取某用户与其他用户的好友关系

#### 接口名

 /hai/friend/relations
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setSiteUserId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em> |
| public | <strong>setTargetSiteUserId(</strong><em>string[]/\Google\Protobuf\Internal\RepeatedField</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>需要查询关系的用户ID</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiFriendRelationsResponse

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setUserProfile(</strong><em>\Akaxin\Proto\Core\UserRelationProfile[]/\Google\Protobuf\Internal\RepeatedField</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>查询的结果</em> |



<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupAddMemberRequest


####  向群里添加群成员

 > 如果此人已在群里，返回成功而不是失败
#### 接口名

 /hai/group/addMember
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setGroupId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>群组ID</em> |
| public | <strong>setMemberSiteUserId(</strong><em>string[]/\Google\Protobuf\Internal\RepeatedField</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>群组中需要增加的用户ID</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupAddMemberResponse

| Visibility | Function |
|:-----------|:---------|




<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupCheckMemberRequest


####  检查用户是否在某群里

#### 接口名

 /hai/group/checkMember
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setGroupId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>群组ID</em> |
| public | <strong>setSiteUserId(</strong><em>string[]/\Google\Protobuf\Internal\RepeatedField</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>待检查的成员id</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupCheckMemberResponse

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getMembersSiteUserId()</strong> : <em>\Google\Protobuf\Internal\RepeatedField</em><br /><em>请求在这个群里的成员( 肯定是request参数的子集 )</em> |



<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupDeleteRequest


####  删除群组

#### 接口名

 /hai/group/delete
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setGroupId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>删除的群组ID</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupDeleteResponse

| Visibility | Function |
|:-----------|:---------|




<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupListRequest


#### 获取群组列表

#### 接口名

 /hai/group/list
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setPageNumber(</strong><em>int</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>第几页</em> |
| public | <strong>setPageSize(</strong><em>int</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>每页的条数，默认100</em> |
| public | <strong>setSiteUserId(</strong><em>string[]/\Google\Protobuf\Internal\RepeatedField</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>查询谁的群组，为空则查询所有。</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupListResponse

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getGroupProfile()</strong> : <em>\Google\Protobuf\Internal\RepeatedField</em><br /><em>用户的群列表</em> |
| public | <strong>getPageTotalNum()</strong> : <em>int</em><br /><em>一共多少页</em> |



<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupMembersRequest


#### 获取群成员

#### 接口名

 /hai/group/members
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setGroupId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>群组ID</em> |
| public | <strong>setPageNumber(</strong><em>int</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>分页：第几页</em> |
| public | <strong>setPageSize(</strong><em>int</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>分页：每页条数，默认100</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupMembersResponse

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getGroupMember()</strong> : <em>\Google\Protobuf\Internal\RepeatedField</em><br /><em>群组成员信息</em> |
| public | <strong>getPageTotalNum()</strong> : <em>int</em><br /><em>分页总数</em> |



<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupNonmembersRequest


####  分页获非群成员

#### 接口名

 /hai/group/nonmembers
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setGroupId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>群组ID</em> |
| public | <strong>setPageNumber(</strong><em>int</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>分页：第几页</em> |
| public | <strong>setPageSize(</strong><em>int</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>分页：每页条数，默认100</em> |
| public | <strong>setSiteUserId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>以谁的视角获取这份数据</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupNonmembersResponse

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getGroupMember()</strong> : <em>\Google\Protobuf\Internal\RepeatedField</em><br /><em>群组成员信息</em> |
| public | <strong>getPageTotalNum()</strong> : <em>int</em><br /><em>分页总数</em> |



<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupProfileRequest


####  获取群组资料

#### 接口名

 /hai/group/profile
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setGroupId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>当前用户获取群组ID的资料信息</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupProfileResponse

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getProfile()</strong> : <em>\Akaxin\Proto\Core\GroupProfile</em><br /><em>群组资料页信息</em> |



<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupRemoveMemberRequest


####  删除群成员

#### 接口名

 /hai/group/removeMember
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setGroupId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>群组ID</em> |
| public | <strong>setGroupMember(</strong><em>string[]/\Google\Protobuf\Internal\RepeatedField</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>需要删除的群组成员！！不能删除管理员</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupRemoveMemberResponse

| Visibility | Function |
|:-----------|:---------|




<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupUpdateRequest


####  更新群资料

#### 接口名

 /hai/group/update
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setGroupId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>显示的设置群组ID</em> |
| public | <strong>setProfile(</strong><em>\Akaxin\Proto\Core\GroupProfile</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>需要更新的群组资料</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiGroupUpdateResponse

| Visibility | Function |
|:-----------|:---------|




<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiMessageProxyRequest


####  代发消息

#### 接口名

 /hai/message/proxy
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setProxyMsg(</strong><em>\Akaxin\Proto\Site\ImCtsMessageRequest</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>代理发送的消息请求</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiMessageProxyResponse

| Visibility | Function |
|:-----------|:---------|




<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiPushNoticesRequest


####  向全员推送消息通知

#### 接口名

 /hai/push/notices
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setContent(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>通知的内容</em> |
| public | <strong>setPushGoto(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>通知的跳转，可以为空。</em> |
| public | <strong>setSubtitle(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>push通知的title(副标题)，客户端主标题展示站点名称</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiPushNoticesResponse

| Visibility | Function |
|:-----------|:---------|




<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiSiteGetConfigRequest


####  获取站点的配置

#### 接口名

 /hai/site/getConfig
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|




## Class: \\Akaxin\\Proto\\Plugin\\HaiSiteGetConfigResponse

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getSiteConfig()</strong> : <em>\Akaxin\Proto\Core\SiteBackConfig</em><br /><em>信息配置，Key为SiteConfigKey</em> |



<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiUserFriendsRequest


####  获取用户的好友列表

#### 接口名

 /hai/user/friends
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setPageNumber(</strong><em>int</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>默认0</em> |
| public | <strong>setPageSize(</strong><em>int</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>默认100</em> |
| public | <strong>setSiteUserId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>需要查找的用户ID</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiUserFriendsResponse

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getPageTotalNum()</strong> : <em>int</em><br /><em>总页数</em> |
| public | <strong>getProfile()</strong> : <em>\Google\Protobuf\Internal\RepeatedField</em><br /><em>需要查找的用户</em> |



<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiUserGroupsRequest


####  获取用户的群组列表

#### 接口名

 /hai/user/groups
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setPageNumber(</strong><em>int</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>默认0</em> |
| public | <strong>setPageSize(</strong><em>int</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>默认100</em> |
| public | <strong>setSiteUserId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>需要查找的用户ID</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiUserGroupsResponse

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getPageTotalNum()</strong> : <em>int</em><br /><em>总页数</em> |
| public | <strong>getProfile()</strong> : <em>\Google\Protobuf\Internal\RepeatedField</em><br /><em>需要查找的个人群组</em> |



<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiUserListRequest


####  获取站点上用户

#### 接口名

 /hai/user/list
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setPageNumber(</strong><em>int</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>分页：第几页</em> |
| public | <strong>setPageSize(</strong><em>int</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>分页：每页条数</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiUserListResponse

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getPageTotalNum()</strong> : <em>int</em> |
| public | <strong>getUserProfile()</strong> : <em>\Google\Protobuf\Internal\RepeatedField</em><br /><em>查询的结果</em> |



<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiUserPhoneRequest


####  获取用户手机号

 只有实名站点，才能获取此数据。
#### 接口名

 /hai/user/phone
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setSiteUserId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>用户的站点ID</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiUserPhoneResponse

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getCountryCode()</strong> : <em>string</em><br /><em>国际区号+86</em> |
| public | <strong>getPhoneId()</strong> : <em>string</em><br /><em>手机号</em> |



<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiUserProfileRequest


####  获取用户资料

#### 接口名

 /hai/user/profile
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setSiteUserId(</strong><em>string</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>需要查找的用户ID</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiUserProfileResponse

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getUserProfile()</strong> : <em>\Akaxin\Proto\Core\UserProfile</em><br /><em>需要查找的用户</em> |



<hr />

## Class: \\Akaxin\\Proto\\Plugin\\HaiUserUpdateRequest


####  更新用户信息

#### 接口名

 /hai/user/update
#### 错误码

 - success
 - error.alert

| Visibility | Function |
|:-----------|:---------|
| public | <strong>setUserProfile(</strong><em>\Akaxin\Proto\Core\UserProfile</em> <strong>$var</strong>)</strong> : <em>\Akaxin\Proto\Plugin\$this</em><br /><em>需要更新的用户信息</em> |



## Class: \\Akaxin\\Proto\\Plugin\\HaiUserUpdateResponse

| Visibility | Function |
|:-----------|:---------|
