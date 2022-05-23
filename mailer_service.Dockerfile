FROM php:7.4.4-fpm
RUN apt update && apt upgrade -y && apt install -y mailutils msmtp msmtp-mta
COPY ./msmtprc /etc/msmtprc