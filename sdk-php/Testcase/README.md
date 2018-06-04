## 环境准备

1. 放置一个 `openzaly-server.jar` 在当前目录。
2. 下载测试专用DB，在当前目录
3. 执行以下命令

```
wget --output-document phpunit https://phar.phpunit.de/phpunit-7.0.phar
chmod u+x phpunit
phpunit --bootstrap autoload_for_doc.php ./
```
