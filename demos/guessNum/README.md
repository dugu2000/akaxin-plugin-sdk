
扩展可以通过调用站点主服务器的PluginAPI来增强产品功能。

心有灵犀
====
* 添加扩展
    * [管理后台]->[扩展管理]->[添加扩展]
    
	<table>
	<tr>
		<th> 名字</th>
		<th> 说明</th>
	</tr>
	<tr>
		<td> 落地页URL </td>
		<td> 填写扩展的服务器地址，可以直接调用到页面 </td>
	</tr>
	<tr>
		<td> 扩展位置 </td>
		<td>消息帧扩展</td>
	</tr>
	<tr>
		<td> 展现方式 </td>
		<td>浮屏</td>
	</tr>
	
	</tabel>
	* 查看添加扩展的，ID, AuthKey
	
* 需要修改配置文件(config.php)
	
	<table>
	<tr>
		<th> 名字</th>
		<th> 说明</th>
	</tr>
	<tr>
		<td> site_address </td>
		<td> 对应的站点地址 </td>
	</tr>
	<tr>
		<td> inner_api_host </td>
		<td>对应启动服务器时的 -Dhttp.address 参数</td>
	</tr>
	<tr>
		<td> inner_api_port </td>
		<td>对应启动服务器时的 -Dhttp.port 参数</td>
	</tr>
	<tr>
		<td> plugin_id </td>
		<td>管理平台->扩展列表，点击相应的扩展获取。</td>
	</tr>
	<tr>
		<td> plugin_auth_key </td>
		<td>管理平台->扩展列表，点击相应的扩展获取。</td>
	</tr>
	<tr>
		<td> plugin_http_domain </td>
		<td> 扩展服务器地址 </td>
	</tr>
	<tr>
    		<td> game_expire_time </td>
    		<td> 在无人猜对的情况下，两局游戏的间隔，单位秒 </td>
    	</tr>
	</tabel>
		