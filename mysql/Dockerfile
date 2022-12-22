FROM mysql:8.0

#ENV MYSQL_ROOT_PASSWORD=root
#ENV MYSQL_DATABASE=app
#ENV MYSQL_USER=mysql_user
#ENV MYSQL_PASSWORD=mysql_pass

# using volumes in docker-compose instread
#COPY ./mysql/init.sql /init.sql

CMD ["mysqld","--init-file=/init.sql"]