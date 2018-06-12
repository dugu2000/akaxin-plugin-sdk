# Akaxin 扩展开发教程

> 通过自定义扩展开发，可以极大的增强站点能力，本文档用于介绍Akaxin 扩展开发的基本流程与方法。
>
> 本文，以消息帧扩展————动态表情为例，介绍扩展开发的初级教程。

本教程使用的源码获取方式：

>
> 直接下载 https://github.com/akaxincom/akaxin-plugin-sdk/demo/gif-emotion.php
>

一、启动服务器
----

> 请参考：https://www.akaxin.com/docs/install/index.html

二、添加扩展
----

进入管理平台 -> 扩展管理 -> 添加扩展

* 名称
* ApiServer地址
    * 留空即可。
* 落地页URL：
    * 请输入页面的完整URL（此URL需要可以通过网页访问到）
* Logo
    * 自行设置即可。
* 扩展位置
    * 聊天界面
* 扩展状态
    * 全员展示
* 展现方式
    * 浮屏

三、部署扩展程序（网页）
----

扩展程序源码为：https://github.com/akaxincom/akaxin-plugin-sdk/demo/gif.php

建议把 https://github.com/akaxincom/akaxin-plugin-sdk/ 整体clone下来，然后配置Web服务器。


四、打开应用，使用扩展
----

打开应用，随便进入一个群组，点击 + 号，便会出来刚才新建的扩展。

点击扩展后，便会出现一个浮屏窗口，此时点击图片，图片便会发到客户端。

五、讲解gif.php
----

> 请参考 gif.php 里的注释。
