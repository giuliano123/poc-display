#!/bin/bash
/bin/sed -i "s/%SYMFONY__FRONTEND_HOSTNAME%/${SYMFONY__FRONTEND_HOSTNAME}/" /etc/nginx/nginx.conf
/bin/sed -i "s/%SYMFONY__STATIC_HOSTNAME%/${SYMFONY__STATIC_HOSTNAME}/" /etc/nginx/nginx.conf
/bin/sed -i "s/%SYMFONY__BACKEND_HOSTNAME%/${SYMFONY__BACKEND_HOSTNAME}/" /etc/nginx/nginx.conf
/bin/sed -i "s/%PORT%/${PORT}/" /etc/nginx/nginx.conf
/bin/sed -i "s/%APPLICATION_ENV%/${APPLICATION_ENV}/" /etc/nginx/nginx.conf

if [ "${APPLICATION_ENV}" = "int" ]
then
   /bin/sed -i "s/#auth_basic/auth_basic/" /etc/nginx/nginx.conf
   /bin/sed -i "s/#auth_basic_user_file/auth_basic/" /etc/nginx/nginx.conf
fi

php /var/www/poc-display/app/console cache:clear --env=${APPLICATION_ENV}
#php /var/www/poc-display/app/console assets:install --env=${APPLICATION_ENV}
#php /var/www/poc-display/app/console assetic:dump --env=${APPLICATION_ENV} --force
chown -R www-data:www-data /var/www/poc-display/var/cache/ /var/www/poc-display/var/logs/ /var/www/poc-display/var/sessions/
rm -rf /var/www/poc-display/app/cache/*
supervisord -c "/etc/supervisor/conf.d/supervisord.conf"