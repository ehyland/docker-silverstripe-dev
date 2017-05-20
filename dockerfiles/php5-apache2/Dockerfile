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
RUN curl -fsSL https://raw.githubusercontent.com/composer/getcomposer.org/a488222dad0b6eaaa211ed9a21f016bb706b2980/web/installer | php -- --quiet --install-dir=/usr/local/bin --filename=composer

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
