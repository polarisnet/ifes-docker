FROM ubuntu

MAINTAINER ferdie.putrawan@polarisnet.com.my

RUN apt-get update
RUN apt-get install -y nano nginx php7.0 php7.0-cli php7.0-cgi php7.0-fpm php7.0-gd php7.0-mbstring php7.0-imap php7.0-odbc php7.0-mysqli php7.0-curl php7.0-mcrypt php7.0-zip

COPY nginx.conf /etc/nginx/nginx.conf

COPY default /etc/nginx/sites-available/default

COPY php.ini /etc/php/7.0/fpm/php.ini

COPY www.conf /etc/php/7.0/fpm/pool.d/www.conf

#COPY code /var/www/html
#RUN chmod -R 777 /var/www/html

COPY startphpfpm /etc/startphpfpm

COPY startnginx /etc/startnginx

COPY start.sh /etc/start.sh
RUN chmod 777 /etc/nginx/nginx.conf && chmod 777 /etc/nginx/sites-available/default && chmod 777 /etc/php/7.0/fpm/php.ini && chmod 777 /etc/php/7.0/fpm/pool.d/www.conf && chmod 777 /etc/startphpfpm && chmod 777 /etc/startnginx && chmod 777 /etc/start.sh

EXPOSE 80

CMD /etc/start.sh