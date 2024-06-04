FROM varnish:latest

COPY ./docker/varnish/default.vcl /etc/varnish/default.vcl