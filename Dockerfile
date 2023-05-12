# syntax=docker/dockerfile:1

FROM node:18-alpine
WORKDIR /
COPY . .
RUN composer install
CMD ["php artisan serve"]
EXPOSE 8000
