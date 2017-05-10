FROM ubuntu

MAINTAINER ferdie.putrawan@polarisnet.com.my

RUN apt-get update
RUN apt-get install -y nano nginx php7.0 php7.0-cli php7.0-cgi php7.0-fpm php7.0-gd php7.0-mbstring php7.0-imap php7.0-mysqli php7.0-curl php7.0-zip

COPY nginx.conf /etc/nginx/nginx.conf && site.conf /etc/nginx/sites-available/default && php.ini /etc/php/7.0/fpm/php.ini && startphpfpm /etc/startphpfpm && startnginx /etc/startnginx && start.sh /etc/start.sh && code /var/www/html
RUN chmod 777 /etc/nginx/nginx.conf && chmod 777 /etc/nginx/sites-available/default && chmod 777 /etc/php/7.0/fpm/php.ini && chmod 777 /etc/startphpfpm && chmod 777 /etc/startnginx && chmod 777 /etc/start.sh

#RUN chmod -R 777 /var/www/html

EXPOSE 80

CMD /etc/start.sh