FROM nginx:1.18-alpine

# Copy Nginx config template
COPY ./nginx/*.conf /etc/nginx/conf.d/

# Set working directory
WORKDIR var/www/html