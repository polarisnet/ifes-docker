FROM ubuntu

RUN apt-get update
RUN apt-get install -y nginx php7.0 php7.0-cli php7.0-cgi php7.0-fpm

COPY nginx.conf /etc/nginx/nginx.conf
COPY default /etc/nginx/sites-available/default

COPY php.ini /etc/php/7.0/fpm/php.ini

COPY code /var/www/html

#RUN echo "daemon off;" >> /etc/nginx/nginx.conf

EXPOSE 80

COPY start.sh /etc/start.sh
RUN chmod 777 /etc/start.sh

CMD /etc/start.sh