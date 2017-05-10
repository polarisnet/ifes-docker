When deploy make sure Dockerfile, uncomment following:

COPY code /var/www/html
RUN chmod -R 777 /var/www/html

Then run this command through powershell
docker build -t ifes-docker .
docker run -p 80:80 -d ifes-docker



When develop make sure Dockerfile comment following:

#COPY code /var/www/html
#RUN chmod -R 777 /var/www/html

Then run this command through powershell
docker build -t ifes-docker .
docker run -p 80:80 -v D:/ifes-docker/code:/var/www/html -d ifes-docker