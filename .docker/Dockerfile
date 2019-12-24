FROM debian:stretch

MAINTAINER mgpapelaria.com.br

RUN apt update && apt dist-upgrade --yes

RUN apt-get install --reinstall systemd -y \
    && apt-get install -y \
    lsb-release \
    apt-transport-https \
    wget \
    nginx \
    vim \
    rsync \
    curl \
    && systemctl enable nginx

RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
    && sh -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list' \
    && apt update

RUN apt install -y php7.1-cli \
    php7.1-fpm \
    php7.1-pgsql \
    php7.1-gd \
    php7.1-soap \
    php7.1-curl \
    php7.1-mbstring \
    php7.1-xml

RUN update-alternatives --set php /usr/bin/php7.1 \
    && apt install -y memcached \
    php-pear \
    php7.1-dev \
    composer \
    pkg-config \
    zlib1g-dev \
    libmemcached-dev \
    && pecl install memcached

RUN systemctl enable memcached.service

RUN mv /var/www/html/index.nginx-debian.html /var/www/html/index.html

COPY config/php/php.ini /etc/php/7.1/fpm/php.ini
COPY config/nginx/default.conf /etc/nginx/sites-available/default

RUN service nginx start \
    && service php7.1-fpm start \
    && service memcached start

CMD /bin/bash
