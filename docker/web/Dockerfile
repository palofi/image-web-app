FROM nginx:alpine

RUN rm /etc/nginx/conf.d/default.conf /etc/nginx/nginx.conf

COPY docker/web/project.conf /etc/nginx/conf.d/project.conf
COPY docker/web/nginx.conf /etc/nginx/nginx.conf
