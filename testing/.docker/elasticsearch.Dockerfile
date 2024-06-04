FROM elasticsearch:7.17.9

RUN set -ex \
    && elasticsearch-plugin install analysis-phonetic \
    && elasticsearch-plugin install analysis-icu
