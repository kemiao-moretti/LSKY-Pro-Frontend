FROM php:8.1 AS build
WORKDIR /build

# unzip 必须装：composer.json 里 preferred-install=dist，Composer 只下 zip 包、不走 source 回退，
# 而 php:8.1 基础镜像既无 zip 扩展也无 unzip 命令。删了会直接构建失败。
RUN apt-get update && \
    apt-get install -y curl unzip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# 先只拷依赖清单，这层可缓存：只改视图/样式时不会重装 158 个包
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-scripts --no-autoloader

# 再拷全量源码（含 resources/ 与已编译的 public/css），不再从上游拉取
COPY . .

# dump-autoload 会触发 post-autoload-dump → artisan package:discover
RUN php -r "file_exists('.env') || copy('.env.example', '.env');" \
    && composer dump-autoload --optimize --no-interaction

FROM php:8.1-apache

LABEL org.opencontainers.image.source=https://github.com/kemiao-moretti/LSKY-Pro-Frontend

# 如果构建速度慢可以换源
# RUN  sed -i -E "s@http://.*.debian.org@http://mirrors.cloud.tencent.com@g" /etc/apt/sources.list
# 安装相关拓展
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# 开启SSL
RUN a2enmod ssl && a2ensite default-ssl

RUN apt-get update && \
    apt-get install -y gettext && \
    apt-get clean && rm -rf /var/cache/apt/* && rm -rf /var/lib/apt/lists/* && rm -rf /tmp/*  && \
    a2enmod rewrite && chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions imagick bcmath pdo_mysql pdo_pgsql redis && \
    \
    { \
    echo 'post_max_size = 100M;';\
    echo 'upload_max_filesize = 100M;';\
    echo 'max_execution_time = 600S;';\
    } > /usr/local/etc/php/conf.d/docker-php-upload.ini; \
    \
    { \
    echo 'opcache.enable=1'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.save_comments=1'; \
    echo 'opcache.revalidate_freq=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini; \
    \
    echo 'apc.enable_cli=1' >> /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini; \
    \
    echo 'memory_limit=512M' > /usr/local/etc/php/conf.d/memory-limit.ini; \
    \
    mkdir /var/www/data; \
    chown -R www-data:root /var/www; \
    chmod -R g=u /var/www

COPY ./ssl /etc/ssl

COPY --from=build /build /var/www/lsky/
COPY ./000-default.conf.template /etc/apache2/sites-enabled/
COPY ./ports.conf.template /etc/apache2/
COPY entrypoint.sh /
WORKDIR /var/www/html/
VOLUME /var/www/html
ENV WEB_PORT=8089
ENV HTTPS_PORT=8088
EXPOSE 8089
EXPOSE 8088
RUN chmod a+x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
CMD ["apachectl","-D","FOREGROUND"]
