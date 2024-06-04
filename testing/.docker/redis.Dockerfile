FROM redis

RUN set -ex \
    && mkdir -p /usr/local/etc/redis /var/run/redis \
    && chown -R redis:redis /var/run/redis \
    && echo 'unixsocket /var/run/redis/redis-server.sock' > /usr/local/etc/redis/redis.conf \
    && echo 'unixsocketperm 777' >> /usr/local/etc/redis/redis.conf

CMD [ "/usr/local/etc/redis/redis.conf" ]
