FROM gliderlabs/alpine:3.3

RUN apk add --no-cache php \
                        php-phar \
                        php-iconv \
                        php-pdo \
                        php-mcrypt \
                        php-common \
                        php-mysqli \
                        php-pdo_mysql \
                        php-pdo_pgsql \
                        php-gd \
                        php-dom \
                        php-json \
                        php-imap \ 
                        php-ldap \
                        php-pgsql \ 
                        php-sqlite3 \
                        php-xmlrpc \
                        php-openssl \
                        php-ctype \
                        php-curl \
                        ssmtp  \
                        php-fpm && \
    ln -sf /dev/stderr /var/log/php-fpm.log

RUN php -r "copy('http://getcomposer.org/download/1.1.0/composer.phar', 'composer.phar');"

RUN sed -i "s/;date.timezone =.*/date.timezone = UTC/" /etc/php/php.ini && \
    sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php/php.ini && \
    sed -i "s/display_errors = Off/display_errors = stderr/" /etc/php/php.ini && \
    sed -i "s/upload_max_filesize = 2M/upload_max_filesize = 10M/" /etc/php/php.ini && \
    sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php/php-fpm.conf && \
    sed -i '/^listen = /clisten = 9000' /etc/php/php-fpm.conf && \
    sed -i '/^listen.allowed_clients/c;listen.allowed_clients =' /etc/php/php-fpm.conf && \
    sed -i '/^;catch_workers_output/ccatch_workers_output = yes' /etc/php/php-fpm.conf

WORKDIR /data

CMD ["php-fpm", "-O"]
