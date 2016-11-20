FROM debian:jessie

ENV DEBIAN_FRONTEND=noninteractive \
    SILVERSTRIPE_ROOT=/var/www/site \
    PATH=/usr/local/node/bin:$PATH \
    NGINX_VERSION=1.11.5-1~jessie \
    HHVM_VERSION=3.15

# add hhvm to apt sources
RUN apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0x5a16e7281be7a449 && \
    echo "deb http://dl.hhvm.com/debian jessie-lts-${HHVM_VERSION} main" > /etc/apt/sources.list.d/hhvm.list

# add nginx to apt sources
RUN apt-key adv --keyserver hkp://pgp.mit.edu:80 --recv-keys 573BFD6B3D8FBC641079A6ABABF5BD827BD9BF62 && \
    echo "deb http://nginx.org/packages/mainline/debian/ jessie nginx" > /etc/apt/sources.list.d/nginx.list

# install nginx, hhvm and other deps
RUN apt-get update -qq && \
    apt-get install -yqq \
        hhvm \
        nginx=${NGINX_VERSION} \
        ca-certificates \
        tar \
        xz-utils \
        bzip2 \
        curl \
        git-core && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# set hhvm as php alternative
RUN /usr/bin/update-alternatives --install /usr/bin/php php /usr/bin/hhvm 60

# nodejs
RUN mkdir -p /usr/local/node && \
    curl https://nodejs.org/dist/v6.9.1/node-v6.9.1-linux-x64.tar.xz | tar x --xz --strip-components=1 -C /usr/local/node

# composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === 'aa96f26c2b67226a324c27919f1eb05f21c248b987e6195cad9690d5c1ff713d53020a02ac8c217dbf90a7eacc9d141d') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# configure nginx
RUN rm /etc/nginx/conf.d/*
ADD ./conf.d/ /etc/nginx/conf.d/
ADD ./nginx.conf /etc/nginx/

# create directories and set permissions
RUN mkdir -p \
        $SILVERSTRIPE_ROOT \
        /var/run/nginx/ && \
    chown -R www-data:www-data \
        $SILVERSTRIPE_ROOT \
        /var/www \
        /var/cache/nginx/ \
        /var/log/nginx/ \
        /var/run/nginx/ \
        /var/log/hhvm \
        /var/run/hhvm

# add _ss_environment.php
COPY _ss_environment.php /var/www

# forward logs to docker
RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log && \
    ln -sf /dev/stderr /var/log/hhvm/error.log

# xdebug
RUN echo 'xdebug.enable=true' >> /etc/hhvm/server.ini && \
    echo 'xdebug.remote_enable=true' >> /etc/hhvm/server.ini && \
    echo 'xdebug.remote_port=9000' >> /etc/hhvm/server.ini && \
    echo 'xdebug.remote_autostart=true' >> /etc/hhvm/server.ini

COPY docker-entry.sh /
RUN chmod +x /docker-entry.sh

EXPOSE 8080
WORKDIR ${SILVERSTRIPE_ROOT}
USER www-data
CMD ["/docker-entry.sh"]


# EXPOSE 80 443
# CMD ["nginx", "-g", "daemon off;"]

