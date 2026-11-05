# Deployment via Plesk Git (zonder SSH)

Doel: `https://<domein>/beroeps2/project-1/` ← `/httpdocs/beroeps2/project-1`

Het project is een zelfstandige PHP-app: geen Composer, geen npm-build en
geen post-deploy scripts nodig. Wat in Git staat, draait direct.

## 1. Plesk → Git

- **Repository path / Deployment path:** `/httpdocs/beroeps2/project-1`
- Deployment mode: *Automatic* of *Manual*; "Additional deployment actions" leeg laten.
- De hele repo wordt uitgerold in die map; de root-`.htaccess` blokkeert
  gevoelige onderdelen en stuurt verkeer intern naar `public/`.

## 2. Configuratie (`config.local.php`, geen `.env`)

De app leest `config/config.local.php` (staat in `.gitignore`, dus niet in Git).
Zonder dit bestand valt de app terug op `config.example.php` (development-modus
met `root` zonder wachtwoord) — **maak dit bestand dus altijd aan**:

1. Plesk → *Bestanden* → `/httpdocs/beroeps2/project-1/config/`
2. Nieuw bestand `config.local.php` met bijvoorbeeld:

```php
<?php
return [
    'app' => [
        'env'      => 'production',
        'basePath' => '/beroeps2/project-1',   // of null voor auto-detectie
        'baseUrl'  => '',
    ],
    'session' => ['name' => 'smaakmaatje_sid', 'path' => null],
    'db' => [
        'host' => 'localhost', 'port' => 3306,
        'name' => '...', 'user' => '...', 'pass' => '...',
        'charset' => 'utf8mb4',
    ],
];
```

Het bestand wordt bij een volgende Git-deploy niet overschreven of verwijderd
(het is niet getrackt). `config/` is via `.htaccess` niet publiek bereikbaar.

## 3. Schrijfrechten (Plesk Bestandsbeheer → Machtigingen)

| Map        | Reden                              |
|------------|------------------------------------|
| `uploads/` | gebruikersuploads (schrijfbaar)    |

Er zijn geen cache-, log- of storage-mappen. Alleen `uploads/` heeft
schrijfrechten nodig (bij Plesk: de website-gebruiker moet *Schrijven* hebben).
`uploads/` is niet publiek serveerbaar; wil je uploads tonen, serveer ze dan
via PHP of verplaats ze naar `public/`.

## 4. Verificatie

- `/beroeps2/project-1/` → homepage; `/beroeps2/project-1/recipes` → recepten.
- Moeten 403/404 geven: `/.git/config`, `/config/config.example.php`,
  `/src/bootstrap.php`, `/README.md`, `/DEPLOYMENT.md`.
- Sessiecookie heeft `Path=/beroeps2/project-1`.

## 5. Onderhoudsendpoint

Bewust niet aangemaakt: de app heeft geen compiled caches, migraties of
storage-links. Databasewijzigingen voer je uit via phpMyAdmin in Plesk.

## 6. Alleen nginx (Proxy-modus uit / geen `.htaccess`)

Plesk → Domein → *Apache & nginx-instellingen* → **Aanvullende nginx-richtlijnen**:

```nginx
location ~ ^/beroeps2/project-1/(\.git|vendor|storage|app|src|config|database|tests|docs|node_modules|uploads|var|logs)(/|$) {
    deny all;
    return 404;
}

location ~ ^/beroeps2/project-1/(.*/)?(\.|composer\.(json|lock)|package(-lock)?\.json|router\.php|.*\.(md|sh|lock|bak|log|sql|ini)$) {
    deny all;
    return 404;
}

location ^~ /beroeps2/project-1/ {
    root /var/www/vhosts/<domein>/httpdocs;   # pas aan naar je vhost-pad
    # Statische bestanden uit public/
    try_files /beroeps2/project-1/public$uri @project1_front;
}

location @project1_front {
    fastcgi_pass unix:/var/www/vhosts/system/<domein>/php-fpm.sock; # of via Plesk-standaard
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME /var/www/vhosts/<domein>/httpdocs/beroeps2/project-1/public/index.php;
    fastcgi_param SCRIPT_NAME /beroeps2/project-1/public/index.php;
}
```

Let op: de `fastcgi_pass`-regel verschilt per Plesk-installatie; bij nginx als
reverse proxy voor Apache (standaard Plesk) is dit blok **niet** nodig — gebruik
dan alleen de eerste twee `location`-blokken en laat `.htaccess` het routeren doen.
Deze nginx-configuratie is niet getest.
