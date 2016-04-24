FROM gliderlabs/alpine:3.3

RUN apk add --no-cache mariadb mariadb-client

# This.. sets up the users and whatnot?
ADD init.sh /init.sh
ADD run.sh /run.sh
RUN chmod 775 *.sh

ADD my.cnf /etc/mysql/my.cnf

VOLUME ["/var/lib/mysql"]

ENV TERM dumb

CMD ["sh", "run.sh"]
