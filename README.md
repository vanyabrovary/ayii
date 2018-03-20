## Установка доп. пакетов
php, php-fpm, php-redis не выше 7.1
postgresql не ниже 9.6, postgresql-plperl
nginx, можно nginx-extras
redis-server
<pre>
  apt-get install postgresql-plperl redis-server nginx-extras php-redis php-redis libphp7.1-embed libphp7.1-embed-dbgsym php7.1-cgi php7.1-cli php7.1-common php7.1-curl php-redis php7.1-dba php7.1-dev php7.1-enchant php7.1-fpm php7.1-gd php7.1-imap php7.1-intl php7.1-json php7.1-mbstring php7.1-mcrypt php7.1-mysql php7.1-odbc php7.1-opcache php7.1-pgsql php7.1-readline php7.1-recode php7.1-snmp php7.1-xml php7.1-xmlrpc
</pre>


## Получение свежей версии
Хранилище: http://svn.ssh.in.ua/ce/trunk
Пользователь: cex
Пароль: xecxec
<pre>
  mkdir /var/www/ce
  svn checkout --username=cex http://svn.ssh.in.ua/ce/trunk /var/www/ce
  cd /var/www/ce/app/etc
</pre>

## БД. PostgreSQL
В конфигах Yii сейчас стоит:
БД: ce_dev, Пользователь: ce, Пароль: ecec
<pre>
  tar -xzf ce_dev_20-03-2018-08-01-00.sql.tar.gz && cp ce_dev_20-03-2018-08-01-00.sql ce_dev.sql

  sudo -u postgres createuser ce --pwprompt
  sudo -u postgres createdb ce_dev -O ce
  sudo -u postgres psql

  CREATE EXTENSION plperl;
  CREATE EXTENSION plperlu;

  \c ce_dev
  \i ce_dev.sql
  \q
</pre>


## Nginx
В конфиге по умолчанию
Сокет: 127.0.0.1:80  /  Root: /var/www/ce  /  php-fpm 7.1
<pre>
  cp nginx.conf /etc/nginx/sites-enabled/cbm.ce.nginx.conf
</pre>

Или в секцию http {} добавить include /var/www/ce/app/etc/cbm.ce.nginx.conf;

