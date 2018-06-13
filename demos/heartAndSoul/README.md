
扩展可以通过调用站点主服务器的PluginAPI来增强产品功能。

心有灵犀
====


* 需要修改配置文件(heart.ini)
	
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
	
	</tabel>
		