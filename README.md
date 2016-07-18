#OT
##一个简单的网站用来记录OT，防止冲突发生
##另外带一个小纯文本论坛用于闲聊，希望大家生活快乐..

#搭建方法:

* 在Linux上搭建好nginx + php-fpm环境
* 修改数据库配置文件dbconfig.php，配置好host, user, password和使用的数据库名

#nginx配置示例
    server {
        server_name zfu-gv;
        listen 80; 

        root /usr2/zfu/OT;
        location / { 
            index index.html index.php;
        }   
        location ~ \.php$ {
            try_files $uri =404;
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index index.php;
            include fastcgi_params;
        }
    }
