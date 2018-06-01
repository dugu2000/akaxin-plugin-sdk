(function ($) {
    /**
     * 获取机器类型
     * @author zhangjun
     * @returns {string}
     */
    var osType = function getOsType() {
        var u = navigator.userAgent;
        if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {
            return 'Android';
        } else if (u.indexOf('iPhone') > -1) {
            return 'IOS';
        } else {
            return 'PC';
        }
    };

    var toast = function toast(msgStr) {
        if (osType == 'Android') {
            Android.showToast(msgStr);
        } else if (osType == 'IOS') {
            $.toast(msgStr);
        } else {
            console.log(msgStr);
        }
    };

    var zalyMethods = {
        /**
         * 请求数据
         *
         * @param reqUri String  请求地址
         * @param params mix 请求参数
         * @param callbackName String  js执行完成之后，回调方法的名字
         */
        reqUrl : function reqUrl(reqUri, params, callbackName) {
            if (osType == 'Android') {
                Android.requestPost(reqUri, params, callbackName);
            } else if (osType == 'IOS') {
                ios_requestPost(reqUri, params, callbackName);
            } else {
                toast('暂时不支持该设备');
            }
        },

        /**
         * 加载渲染页面
         *
         * @param reqUri String 请求地址
         * @param params mix 请求参数
         */
        reqHtml : function reqHtml(reqUri, params) {
            if (osType == 'Android') {
                Android.requestPage(reqUri, params)
            } else if (osType == 'IOS') {
                ios_requestPage(reqUri, params);
            } else {
                toast('暂时不支持该设备');
            }
        },

        /**
         *
         * 图片上传, 直接加载渲染页面
         *
         * @param callback String js执行完成之后，回调方法的名字
         */
        reqImageUpload : function reqImageUpload(callback) {
            var type = $.extend(getOsType)
            if (type == 'Android') {
                Android.imageUpload(callback);
            } else if (type == 'IOS') {
                ios_imageUpload(callback);
            } else {
                toast('暂时不支持该设备');
            }
        },

        /**
         * 客户端下载图片
         *
         * @param imageid String 图片id
         * @param callback String js执行完成之后，回调方法的名字
         */
        reqImageDownload : function reqImageDownload(imageid, callback) {
            var type = $.extend(getOsType)
            if (type == 'Android') {
                Android.imageDownload(imageid, callback);
            } else if (type == 'IOS') {
                ios_imageDownload(imageid, callback);
            } else {
                toast('暂时不支持该设备');
            }
        },


        /**
         * 客户端toast信息，用来提示用户
         *
         * @param msgStr
         */
        tip :  function tip(msgStr) {
            if (osType == 'Android') {
                Android.showToast(msgStr);
            } else if (osType == 'IOS') {
                $.toast(msgStr);
            } else {
                toast('暂时不支持该设备');
            }
        },

        /**
         * 刷新当前的页面
         */
        refreshCurrentPage : function refreshCurrentPage() {
            if (osType == 'Android') {
                Android.refreshCurrentPage();
            }
        },

        /**
         * 扩展跳转地址
         *
         * @param url String 跳转地址
         */
        gotoPage : function gotoPage(url) {
            if (osType == 'Android') {
                Android.gotoPage(url);
            } else if (osType == 'IOS') {
                ios_gotoPage(url);
            }
        },
    };

    $.fn.zaly = function(method) {
        if(zalyMethods[method]) {
            return zalyMethods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return zalyMethods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.zaly' );
        }
    }
})(jQuery);