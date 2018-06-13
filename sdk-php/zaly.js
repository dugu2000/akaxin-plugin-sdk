
var Zaly = {
    /**
     * 获取机器类型
     * @author zhangjun
     * @returns {string}
     */
    getOsType : function getOsType() {
        var u = navigator.userAgent;
        if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {
            return 'Android';
        } else if (u.indexOf('iPhone') > -1) {
            return 'IOS';
        } else {
            return 'PC';
        }
    },

    /**
     * 请求数据
     *
     * @param reqUri String  请求地址
     * @param params mix 请求参数
     * @param callbackName String  js执行完成之后，回调方法的名字
     */
    reqData : function reqData(reqUri, params, callbackName) {
        if (this.getOsType() == 'Android') {
            Android.requestPost(reqUri, params, callbackName);
        } else if (this.getOsType() == 'IOS') {
            ios_requestPost(reqUri, params, callbackName);
        } else {
            this.tip('暂时不支持该设备');
        }
    },

    /**
     * 加载渲染页面
     *
     * @param reqUri String 请求地址
     * @param params mix 请求参数
     */
    reqPage : function reqPage(reqUri, params) {
        if (this.getOsType() == 'Android') {
            Android.requestPage(reqUri, params)
        } else if (this.getOsType() == 'IOS') {
            ios_requestPage(reqUri, params);
        } else {
            this.tip('暂时不支持该设备');
        }
    },

    /**
     *
     * 图片上传, 直接加载渲染页面
     *
     * @param callback String js执行完成之后，回调方法的名字
     */
    reqImageUpload : function reqImageUpload(callback) {
        if (this.getOsType() == 'Android') {
            Android.imageUpload(callback);
        } else if (this.getOsType() == 'IOS') {
            ios_imageUpload(callback);
        } else {
            this.tip('暂时不支持该设备');
        }
    },

    /**
     * 客户端下载图片
     *
     * @param imageId String 图片id
     * @param callback String js执行完成之后，回调方法的名字
     */
    reqImageDownload : function reqImageDownload(imageId, callback) {
        if (this.getOsType() == 'Android') {
            Android.imageDownload(imageId, callback);
        } else if (this.getOsType()  == 'IOS') {
            ios_imageDownload(imageId, callback);
        } else {
            this.tip('暂时不支持该设备');
        }
    },

    /**
     * 客户端toast信息，用来提示用户
     *
     * @param strTipMsg
     */
    tip : function tip(strTipMsg) {
        if (this.getOsType() == 'Android') {
            Android.showToast(strTipMsg);
        } else if (this.getOsType() == 'IOS') {
            alert(strTipMsg);
        } else {
            console.log(strTipMsg);
        }
    },

    /**
     * 刷新当前的页面
     */
    refreshCurrentPage : function refreshCurrentPage() {
        if (this.getOsType() == 'Android') {
            Android.refreshCurrentPage();
        }
    },

    /**
     * 扩展跳转地址
     *
     * @param url String 跳转地址
     */
    gotoPage : function gotoPage(gotoUrl) {
        if (this.getOsType() == 'Android') {
            Android.gotoPage(gotoUrl);
        } else if (this.getOsType() == 'IOS') {
            ios_gotoPage(gotoUrl);
        }
    },
}