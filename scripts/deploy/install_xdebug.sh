yum -y install php-devel
yum -y install gcc
wget http://xdebug.org/files/xdebug-2.9.8.tgz
tar -xvzf xdebug-2.9.8.tgz
cd xdebug-2.9.8
phpize
./configure
make
cp modules/xdebug.so /usr/lib64/php/modules
cd ..
rm -f xdebug-2.9.8.tgz
rm -rf xdebug-2.9.8
cd /etc/php.d
cat > 90-xdebug.ini << EOF
[xdebug]
zend_extension = /usr/lib64/php/modules/xdebug.so
xdebug.remote_host = 127.0.0.1
xdebug.remote_enable = 1
EOF
