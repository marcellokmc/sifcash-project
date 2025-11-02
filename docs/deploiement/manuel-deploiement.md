# Manuel de déploiement – SIF (Laravel)

Ce guide décrit un déploiement de production fiable sur un serveur Linux (Ubuntu 22.04+), avec Nginx, PHP‑FPM, MySQL/MariaDB, Redis, Supervisor (queues) et Certbot (SSL). Des alternatives Apache/Docker et un encart Windows/IIS sont fournis.

Table des matières
1. Prérequis & architecture
2. Préparation du serveur (packages, utilisateurs, base de données)
3. Récupération du code & configuration (.env, clés)
4. Build & optimisation (Composer, npm/Vite, caches)
5. Permissions & liens symboliques
6. Configuration Nginx (ou Apache)
7. SSL Let’s Encrypt (Certbot)
8. Migrations & tâches programmées (cron)
9. Workers de file (Supervisor)
10. Déploiement zero‑downtime (stratégie releases)
11. Sauvegardes & restauration
12. Monitoring & logs
13. Sécurité & durcissement
14. Dépannage (FAQ)
15. Annexes (Apache, Docker, Windows/IIS)
16. cPanel (hébergement mutualisé/WHM)
17. Docker (compose production)

1) Prérequis & architecture
- OS: Ubuntu 22.04 LTS (ou équivalent Linux). CPU 2 vCPU+, RAM 4 Go+.
- Web: Nginx 1.20+ ou Apache 2.4+.
- PHP: 8.1/8.2 FPM avec extensions: bcmath, ctype, fileinfo, mbstring, openssl, pdo_mysql, tokenizer, xml, curl, gd, intl, redis, zip.
- Base de données: MySQL 8+ ou MariaDB 10.6+.
- Cache/queues: Redis 6+ (recommandé).
- Outils: git, Composer 2.7+, Node LTS (si build d’assets avec Vite), Supervisor, Certbot.
- Ports: 80/443 ouverts.

2) Préparation du serveur
2.1 Paquets
sudo apt update && sudo apt -y upgrade
sudo apt -y install nginx git curl unzip software-properties-common
sudo add-apt-repository ppa:ondrej/php -y && sudo apt update
sudo apt -y install php8.2 php8.2-fpm php8.2-{bcmath,ctype,xml,mbstring,gd,intl,redis,zip,curl,opcache,mysql}
sudo apt -y install mariadb-server redis-server supervisor

Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

Node (facultatif, si Vite)
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
sudo apt -y install nodejs

2.2 Utilisateur & arborescence
sudo adduser --disabled-password --gecos "" deploy
sudo usermod -aG www-data deploy
sudo mkdir -p /var/www/sif-project/shared/{storage,uploads} /var/www/sif-project/releases
sudo chown -R deploy:www-data /var/www/sif-project

2.3 Base de données
sudo mysql -e "CREATE DATABASE sif CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER 'sif'@'localhost' IDENTIFIED BY 'motdepasseFort!';"
sudo mysql -e "GRANT ALL PRIVILEGES ON sif.* TO 'sif'@'localhost'; FLUSH PRIVILEGES;"

3) Récupération du code & configuration
cd /var/www/sif-project/releases && sudo -u deploy git clone --depth=1 <URL_DU_REPO> $(date +%Y%m%d%H%M%S)
export RELEASE=$(ls -1 /var/www/sif-project/releases | sort | tail -n1)
cd /var/www/sif-project/releases/$RELEASE

Fichier .env
- Copier depuis .env.example s’il existe, ou créer à partir du modèle ci‑dessous.

Exemple .env (adapter valeurs)
APP_NAME="SIF"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://sif.exemple.com

LOG_CHANNEL=stack
LOG_LEVEL=info

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sif
DB_USERNAME=sif
DB_PASSWORD=motdepasseFort!

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.exemple.com
MAIL_PORT=587
MAIL_USERNAME=no-reply@sif.exemple.com
MAIL_PASSWORD=********
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@sif.exemple.com
MAIL_FROM_NAME="SIF"

Générer la clé applicative
sudo -u deploy php artisan key:generate --force

4) Build & optimisation
Dépendances PHP
sudo -u deploy composer install --no-dev --prefer-dist --optimize-autoloader

Assets (si Vite/Front)
sudo -u deploy npm ci
sudo -u deploy npm run build

Caches Laravel
sudo -u deploy php artisan config:cache
sudo -u deploy php artisan route:cache
sudo -u deploy php artisan view:cache

5) Permissions & liens symboliques
- Dossier runtime écrivable par PHP: storage et bootstrap/cache.
sudo chown -R deploy:www-data storage bootstrap/cache
find storage -type d -exec chmod 775 {} \;
find storage -type f -exec chmod 664 {} \;
chmod -R 775 bootstrap/cache

- Lien storage public (si nécessaire)
sudo -u deploy php artisan storage:link

- Lier répertoire partagé (uploads/logs persistants)
rm -rf storage/app/public
ln -s /var/www/sif-project/shared/storage storage/app/public

6) Nginx (recommandé)
Créer /etc/nginx/sites-available/sif.conf
server {
    server_name sif.exemple.com;
    root /var/www/sif-project/current/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        try_files $uri =404;
        expires 7d;
        access_log off;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    client_max_body_size 20M;
    listen 80; # SSL ajouté plus bas
}

Activer le site
sudo ln -s /etc/nginx/sites-available/sif.conf /etc/nginx/sites-enabled/sif.conf
sudo nginx -t && sudo systemctl reload nginx

7) SSL Let’s Encrypt
sudo apt -y install certbot python3-certbot-nginx
sudo certbot --nginx -d sif.exemple.com --redirect -m admin@sif.exemple.com --agree-tos --no-eff-email
# Renouvellement auto via timer systemd certbot déjà activé

8) Migrations & scheduler
Exécuter les migrations (production)
cd /var/www/sif-project/releases/$RELEASE
sudo -u deploy php artisan migrate --force

Cron Laravel (toutes les minutes)
sudo crontab -u www-data -e
* * * * * php /var/www/sif-project/current/artisan schedule:run >> /dev/null 2>&1

9) Workers de file (Supervisor)
Créer /etc/supervisor/conf.d/laravel-queue.conf
[program:laravel-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/sif-project/current/artisan queue:work redis --sleep=3 --tries=3 --timeout=120 --queue=default
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/supervisor/laravel-queue.log
stopasgroup=true
killasgroup=true

Recharger Supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl status

10) Déploiement zero‑downtime (releases)
Arborescence
/var/www/sif-project/
  ├── current -> releases/202511022118
  ├── releases/202511022118
  └── shared/

Script de déploiement (exemple)
#!/usr/bin/env bash
set -euo pipefail
APP_DIR=/var/www/sif-project
RELEASE=$(date +%Y%m%d%H%M%S)
cd $APP_DIR/releases
sudo -u deploy git clone --depth=1 <URL_DU_REPO> $RELEASE
cd $RELEASE
sudo -u deploy cp $APP_DIR/shared/.env .env
sudo -u deploy composer install --no-dev --prefer-dist --optimize-autoloader
# npm ci && npm run build  # si front
sudo -u deploy php artisan config:cache route:cache view:cache
sudo -u deploy php artisan storage:link || true
cd $APP_DIR && ln -sfn releases/$RELEASE current
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx
sudo -u deploy php artisan migrate --force
# Option: purge des anciens releases
ls -1dt $APP_DIR/releases/* | tail -n +6 | xargs -r rm -rf

11) Sauvegardes & restauration
Base de données
Sauvegarde: mysqldump -u sif -p sif > /backups/sif_$(date +%F).sql
Restauration: mysql -u sif -p sif < /backups/sif_YYYY-MM-DD.sql

Fichiers
- Sauvegarder /var/www/sif-project/shared et .env.
- Option: mettre en place un job rsync/rclone vers un stockage externe chiffré.

12) Monitoring & logs
- Nginx: /var/log/nginx/{access,error}.log
- PHP‑FPM: /var/log/php8.2-fpm.log
- Laravel: storage/logs/laravel.log (déporter vers shared)
- Supervisor: /var/log/supervisor/laravel-queue.log
- Health‑check: surveiller code 200 sur / (et pages clés), temps de réponse, queue backlog.

13) Sécurité & durcissement
- APP_DEBUG=false, limiter versions détaillées d’erreurs.
- Droits minimum: www-data écriture uniquement sur storage/ et bootstrap/cache/.
- Pare‑feu UFW: autoriser 80,443; restreindre SSH; fail2ban.
- Mises à jour de sécurité automatiques (unattended-upgrades).
- Séparer utilisateurs: deploy (lecture) vs www-data (exécution).

14) Dépannage (FAQ)
- Erreur 500 après déploiement: vérifier APP_KEY, permissions, caches (php artisan config:clear), logs Laravel.
- 502 Bad Gateway: vérifier php-fpm actif et socket fastcgi_pass.
- 419/CSRF: domaine APP_URL correct, caches, clé app.
- Assets 404: vérifier root public/, npm build et try_files.
- Activation adhésion bloquée: vérifier que les frais d’ouverture sont validés (règle métier).

15) Annexes
A) Apache (virtualhost)
<VirtualHost *:80>
  ServerName sif.exemple.com
  DocumentRoot /var/www/sif-project/current/public
  <Directory /var/www/sif-project/current/public>
    AllowOverride All
    Require all granted
    Options FollowSymLinks MultiViews
  </Directory>
  ErrorLog ${APACHE_LOG_DIR}/sif_error.log
  CustomLog ${APACHE_LOG_DIR}/sif_access.log combined
</VirtualHost>
Activer: a2enmod rewrite proxy_fcgi setenvif && a2enconf php8.2-fpm && systemctl reload apache2

B) Docker (esquisse)
- Services: nginx, php-fpm, mysql, redis, supervisor.
- Monter volume de code en read‑only, storage et logs en volumes persistants.

C) Windows/IIS (résumé)
- Installer IIS + URL Rewrite + PHP Manager, pointer le site sur le dossier public/.
- FastCGI vers php-cgi.exe, règles de réécriture Laravel, droits en écriture sur storage/ et bootstrap/cache/.

16) cPanel (hébergement mutualisé/WHM)
Préambule
- cPanel utilise généralement Apache + PHP-FPM. Assurez-vous que PHP 8.1/8.2 et les extensions requises sont disponibles (via « Select PHP Version » ou « MultiPHP »).
- Recommandation: placer le code Laravel en dehors du document root public, et pointer le document root sur le sous-dossier public/.

Étapes (sans SSH, via interface cPanel)
1. Domaine/sous‑domaine
   - Créez un domaine ou sous‑domaine et définissez le « Document Root » sur un dossier public de l’appli, exemple: /home/USER/sif/current/public (si possible), ou sur /home/USER/sif/public.
   - À défaut, utilisez un .htaccess au root pour rediriger vers public/ (moins sécurisé).
2. Fichiers
   - Téléversez le projet (FTP/gestionnaire de fichiers) dans /home/USER/sif.
   - Placez public/ comme racine web. Vérifiez que storage/ et bootstrap/cache/ sont écrivable par PHP.
3. Composer
   - Si cPanel propose « Composer »: exécutez « composer install --no-dev --optimize-autoloader » depuis le répertoire du projet.
   - Sinon: installez vendor/ en local et uploadez-le, ou utilisez SSH (si autorisé) pour lancer Composer.
4. .env et clé
   - Copiez .env et configurez DB/MAIL/APP_URL. Générez la clé: « php artisan key:generate » (via Terminal cPanel/SSH) ou préparez-la en local.
5. Base de données
   - Utilisez « MySQL® Database Wizard » pour créer DB, utilisateur et droits. Renseignez les valeurs dans .env.
6. Permissions
   - storage/ et bootstrap/cache/ en 775 (ou 755 selon l’hébergeur). Vérifiez le propriétaire (souvent l’utilisateur cPanel).
7. Cron scheduler
   - cPanel > Cron Jobs > Nouveau cron: */1 * * * * php /home/USER/sif/artisan schedule:run >> /dev/null 2>&1
   - Adaptez le chemin si artisan est ailleurs, p.ex. /home/USER/sif/current/artisan.
8. Queue (sans Supervisor)
   - Sur mutualisé, Supervisor est rarement disponible. Alternatives:
     a) Cron queue: */1 * * * * php /home/USER/sif/artisan queue:work --stop-when-empty
     b) Lancer manuellement en tâche de fond via Terminal si autorisé (moins fiable).
   - Si votre offre inclut « Process Manager »/« Application Manager », configurez un processus permanent exécutant: php artisan queue:work redis --sleep=3 --tries=3.
9. SSL
   - Activez « SSL/TLS Status » ou « AutoSSL ». Pour un domaine dédié, installez Let’s Encrypt si disponible.
10. Optimisations
   - cPanel Terminal (si dispo): php artisan config:cache route:cache view:cache.
   - Désactivez APP_DEBUG en production.

Astuce sécurisation
- Idéalement, code applicatif dans /home/USER/app/sif et « Document Root » pointant vers /home/USER/app/sif/public. Évitez d’exposer .env, vendor, etc.

17) Docker (compose production)
Structure fournie
- Des fichiers sont inclus dans deploy/docker/ pour un déploiement rapide avec docker compose.
- Lancement recommandé depuis la racine du projet:
  docker compose -f deploy/docker/docker-compose.yml up -d --build

Services
- web: Nginx servant public/ et proxy vers app:9000.
- app: PHP-FPM 8.2 avec extensions nécessaires; exécute Laravel.
- db: MySQL 8 (stockage persistant).
- redis: cache + queues.
- queue: worker Laravel (queue:work redis).
- scheduler: exécute schedule:run chaque minute.

Préparation
1) Copier l’exemple d’environnement
   cp deploy/docker/.env.docker.example .env
   - Mettez APP_KEY, DB_*, REDIS_* et APP_URL.
2) Droits
   - Le conteneur app utilise www-data (uid 33). Assurez storage/ et bootstrap/cache/ écrivable par ce user (le Dockerfile l’ajuste).
3) Build & run
   docker compose -f deploy/docker/docker-compose.yml up -d --build
4) Initialisation
   docker compose -f deploy/docker/docker-compose.yml exec app php artisan key:generate --force
   docker compose -f deploy/docker/docker-compose.yml exec app php artisan migrate --force
   docker compose -f deploy/docker/docker-compose.yml exec app php artisan storage:link

Notes
- Exposez le port 80 du service web; pour SSL en production, placez Traefik/Caddy ou un Nginx frontal avec certificats.
- Pour assets Vite: le Dockerfile prévoit un build optionnel; vous pouvez aussi builder en local et committer public/build.

Notes finales
- Toujours tester sur un staging avant production.
- Mettre en place un plan de rollback (releases + sauvegardes BDD).
- Documenter les secrets et les rotations (mots de passe DB/SMTP).
