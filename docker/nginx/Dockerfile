FROM    nginx:1.20.1

 # Не позволяет динамично изменять nginx
RUN     ["rm", "-rf", "/etc/nginx/conf.d"]
COPY    ["./conf.d","/etc/nginx/conf.d"]