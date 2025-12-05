FROM nginx:1.19-alpine

ARG USER_ID
ARG USER_GROUP_ID
ARG EXTERNAL_HOST
ARG EXTERNAL_NGINX_PORT

RUN set -x; \
    addgroup -g $USER_GROUP_ID -S www-data; \
    adduser -u $USER_ID -D -S -G www-data www-data \
    && apk update \
    && apk add nano \
        bash \
        bash-completion \
    && apk add --no-cache --virtual .buildDeps \
        tzdata \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone \
    && apk del \
        .buildDeps \
    && rm -rf /var/cache/apk/* /var/tmp/* /tmp/*

COPY conf /etc/nginx/conf.d

RUN cat /etc/nginx/nginx.conf \
    && sed -i "s/{{port}}/${EXTERNAL_NGINX_PORT}/g" /etc/nginx/conf.d/app.conf \
    && sed -i "s/{{host}}/${EXTERNAL_HOST}/g" /etc/nginx/conf.d/app.conf \
    && rm -f /etc/nginx/conf.d/default.conf

VOLUME /var/www
WORKDIR /var/www
