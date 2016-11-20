FROM debian:jessie

ENV DEBIAN_FRONTEND=noninteractive \
    SILVERSTRIPE_ROOT=/var/www/site \
    PATH=/usr/local/node/bin:$PATH

RUN apt-get update -qq && \
    apt-get install -yqq \
        tar \
        xz-utils \
        bzip2 \
        curl \
        git-core \
        apache2 \
        php5 \
        php5-mcrypt \
        php5-tidy \
        php5-curl \
        php5-gd \
        php5-mysqlnd \
        php-pear \
        php5-xdebug && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# nodejs
RUN mkdir -p /usr/local/node && \
    curl https://nodejs.org/dist/v6.9.1/node-v6.9.1-linux-x64.tar.xz | tar x --xz --strip-components=1 -C /usr/local/node

# composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === 'aa96f26c2b67226a324c27919f1eb05f21c248b987e6195cad9690d5c1ff713d53020a02ac8c217dbf90a7eacc9d141d') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# create directories and set permissions
RUN mkdir -p $SILVERSTRIPE_ROOT && \
    chown -R www-data:www-data \
        $SILVERSTRIPE_ROOT \
        /var/www \
        /var/log/apache2 \
        /var/run/apache2 \
        /var/lock/apache2

# add _ss_environment.php
COPY _ss_environment.php /var/www

# configure apache2
COPY ./sites-available /etc/apache2/sites-available
COPY ./ports.conf /etc/apache2/ports.conf
RUN a2dissite "*" && \
    a2ensite silverstripe && \
    a2enmod rewrite

COPY docker-entry.sh /
RUN chmod +x /docker-entry.sh

EXPOSE 8080
WORKDIR ${SILVERSTRIPE_ROOT}
USER www-data
CMD ["/docker-entry.sh"]
