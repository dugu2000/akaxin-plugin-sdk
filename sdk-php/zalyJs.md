ZalyJs
=====

基本引入
=====
>
>  引用 Zaly Js
>


```
<script type="text/javascript" src="./zaly.js"></script>

```

Zaly Js提供的方法
=====

1. getOsType
=====
>  
> 获取机器类型
> 
> 返回类型 : String 
> 
> 返回值分别是 : Android, IOS, PC

```
var osType = Zaly.osType();
console.log(osType);
```

2. reqData 请求数据
=====


```
Zaly.reqData(reqUri, params, callbackName);
```

>  
> 请求参数

<table>
<tr>
<td>名字</td>
<td>类型</td>
<td>说明</td>
</tr>
<tr>
<td> reqUri </td>
<td> String </td>
<td> 请求地址</td>
</tr>
<tr>
<td> params </td>
<td> mix </td>
<td> 请求参数(建议json，或者序列化的数据)</td>
</tr>
<tr>
<td> callbackName </td>
<td> String </td>
<td> js执行完成之后，回调方法的名字， 回调方法中，客户端会原封不动的返回server端的返回值。</td>
</tr>
</table>



3. reqPage 加载渲染页面
=====


```
Zaly.reqUrl(reqUri, params);
```

>  
> 请求参数

<table>
<tr>
<td>名字</td>
<td>类型</td>
<td>说明</td>
</tr>
<tr>
<td> reqUri </td>
<td> String </td>
<td> 请求地址</td>
</tr>
<tr>
<td> params </td>
<td> mix </td>
<td> 请求参数(建议json，或者序列化的数据)</td>
</tr>
</table>



4. reqImageUpload 图片上传
=====

```
Zaly.reqImageUpload(callbackName);

```

>  
> 请求参数

<table>
<tr>
<td>名字</td>
<td>类型</td>
<td>说明</td>
</tr>

<tr>
<td> callback </td>
<td> String </td>
<td> js执行完成之后，回调方法的名字</td>
</tr>
</table>


##### 回调方法中：

```
假如回调方法叫做： imageUpload

则回调方法中，写为

	function imageUpload(isUpload, imageId, imageSrcPath){
	
	}
```

>  
> 返回参数

<table>
<tr>
<td>名字</td>
<td>类型</td>
<td>说明</td>
</tr>

<tr>
<td> isUpload </td>
<td> Boolean </td>
<td> 是否上传成功，1 成功， 0 失败</td>
</tr>
<tr>
<td> imageId </td>
<td> String </td>
<td> 图片的id </td>
</tr>
<tr>
<td> imageSrcPath </td>
<td> String </td>
<td> 图片的地址<br/>  Android 使用方式是：
src = "http://akaxin/img' + imageSrcPath + '"
<br/>
IOS 使用方式是： src="' + imageSrcPath + '"</td>
</tr>
</table>



5. reqImageDownload 图片下载
=====


```
Zaly.reqImageDownload(callbackName);

```
>  
> 请求参数

<table>
<tr>
<td>名字</td>
<td>类型</td>
<td>说明</td>
</tr>

<tr>
<td> imageId </td>
<td> String </td>
<td> 图片id </td>
</tr>
<tr>
<td> callback </td>
<td> String </td>
<td> js执行完成之后，回调方法的名字</td>
</tr>
</table>


#####  回调方法中的处理：
```
假如回调方法叫做： imageDownload

则回调方法中，写为

	function imageDownload(isDownload, imageId, imageSrcPath){
	
	}
```

>  
> 回调方法中，参数


<table>
<tr>
<td>名字</td>
<td>类型</td>
<td>说明</td>
</tr>

<tr>
<td> isDownload </td>
<td> Boolean </td>
<td> 是否下载成功，1 成功， 0 失败</td>
</tr>
<tr>
<td> imageId </td>
<td> String </td>
<td> 图片的id </td>
</tr>
<tr>
<td> imageSrcPath </td>
<td> String </td>
<td> 图片的地址<br/>  Android 使用方式是：
src = "http://akaxin/img' + imageSrcPath + '"
<br/>
IOS 使用方式是： src="' + imageSrcPath + '"
</td>
</tr>
</table>



6. tip 客户端toast信息，用来提示用户
=====

```
Zaly.tip(strTipMsg);
```

>  
> 请求参数

<table>
<tr>
<td>名字</td>
<td>类型</td>
<td>说明</td>
</tr>
<tr>
<td> strTipMsg </td>
<td> String </td>
<td>提示信息</td>
</tr>
</table>




7. refreshCurrentPage 刷新当前页面
=====

```
Zaly.refreshCurrentPage();
```
* 目前只支持安卓客户端


8. gotoPage 扩展跳转地址
=====

```
Zaly.tip(strTipMsg);
```

>  
> 请求参数

<table>
<tr>
<td>名字</td>
<td>类型</td>
<td>说明</td>
</tr>
<tr>
<td> gotoUrl </td>
<td> String </td>
<td>跳转地址， 目前仅支持zaly|zalys跳转</td>
</tr>
</table>



