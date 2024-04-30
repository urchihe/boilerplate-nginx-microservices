FROM nginx:latest

# Copy Nginx config template
COPY ./nginx/*.conf /etc/nginx/conf.d/

# Expose port 80
EXPOSE 80
# Start Nginx
CMD ["nginx", "-g", "daemon off;"]