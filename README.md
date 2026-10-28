# Stop de Ontkoking

Een responsive kookplatform voor Generatie Z (16–27 jaar): jongeren snel, makkelijk en betaalbaar laten koken in plaats van bestellen of kant-en-klaar. Gebouwd als schoolproject binnen **GLR Beroeps 2** in opdracht van **GLR Food Freaks**.

De inhoudelijke uitwerking staat in de projectdocumentatie:

- **Online documentatie (GitHub Pages):** <https://loos-glr.github.io/beroeps2-project-1/>
- **Projectplan (startpunt):** [docs/README.md](./docs/README.md)

## Projectstructuur

```
beroeps2-project-1/
├── README.md              # Projectoverzicht (dit bestand)
├── TEAM.md                # Team Charter: ambitie, rollen & afspraken
├── router.php             # Router-script voor `php -S` (lokale dev)
├── .gitignore
├── .github/
│   └── PULL_REQUEST_TEMPLATE.md
├── .ddev/                 # Lokale dev-omgeving (simuleert de Plesk-server)
│   ├── config.yaml        # DDEV-configuratie + post-start hooks
│   ├── database/
│   │   └── schema.sql     # Databaseschema + seeddata (ERD uit fase 4)
│   └── plesk/
│       └── init_plesk_user.sql  # Database + gebruiker (Plesk-simulatie)
├── httpdocs/                # Document root (entrypoint)
│   ├── index.php          # Front controller / router
│   ├── .htaccess          # Apache-rewrite voor FTP-hosting
│   └── assets/
│       └── css/app.css    # Basisstijlen (mobile first)
├── src/                   # PHP-applicatiecode
│   ├── bootstrap.php      # Configuratie laden + helpers
│   ├── Database.php       # PDO-databaseverbinding
│   └── views/             # Gedeelde layout + pagina's
│       ├── header.php
│       ├── footer.php
│       ├── home.php       # Homepage: hero, zoekbalk, categorieën, uitgelicht
│       ├── recipes.php    # Receptenoverzicht (basis)
│       ├── 404.php
│       └── partials/
│           └── recipe-card.php  # Herbruikbare receptkaart
├── config/
│   ├── config.example.php # Voorbeeldconfiguratie (gecommit)
│   └── config.local.php   # Echte DB-gegevens (in .gitignore)
├── uploads/               # Gebruikersfoto's (leeg, via .gitkeep)
└── docs/                  # Projectdocumentatie (Docsify-site)
    ├── index.html         # Docsify-configuratie
    ├── _sidebar.md        # Navigatie
    ├── README.md          # Projectplan / startpunt
    ├── 01-empathize.md    # Fase 1
    ├── 02-define.md       # Fase 2
    ├── 03-ideate.md       # Fase 3
    ├── 04-prototype.md    # Fase 4
    ├── 05-test.md         # Fase 5
    └── assets/            # Afbeeldingen (Empathy Map, User Flow, ERD, …)
```

## Aan de slag

### Documentatie lokaal bekijken

De docs zijn een statische Docsify-site die de Markdown-bestanden in `docs/` direct rendert. Start eenvoudig een lokale webserver in de projectmap:

```bash
# Met Python 3
python3 -m http.server 3000

# of met Node.js (npx)
npx serve
```

Open daarna <http://localhost:3000/docs/> in je browser.

### Applicatie draaien

De applicatie is een PHP/MySQL-app met een front controller in `httpdocs/`. We draaien hem lokaal in [DDEV](https://ddev.com), ingericht als de Plesk-omgeving waar je site uiteindelijk op komt: PHP 8.3, MariaDB 10.11 met strikte SQL-modus, een eigen databasegebruiker en verbinden via host `localhost` (Unix-socket).

**1. Starten**

```bash
ddev start
```

Open <https://beroeps2-project-1.ddev.site>. Bij elke start worden de database `smaakmaatje` en de gebruiker `student_user` (wachtwoord `student_password`) aangemaakt (zie `.ddev/plesk/init_plesk_user.sql`). In development-modus toont de homepagina een statusblok dat laat zien of de databaseverbinding werkt.

**2. Configuratie**

`config/config.example.php` bevat al de DDEV-waarden en wordt automatisch gebruikt. Op Plesk kopieer je het bestand naar `config/config.local.php` (staat in `.gitignore`) en vul je de database, gebruiker en het wachtwoord in die je in Plesk hebt aangemaakt. De host blijft `localhost`.

**3. Database beheren**

Bij `ddev start` worden automatisch de database `smaakmaatje` en de gebruiker
aangemaakt én wordt `.ddev/database/schema.sql` geïmporteerd: de vijf tabellen
uit het ERD plus seeddata (6 categorieën, 10 recepten, ingrediënten en twee
demo-accounts `chef_sanne` en `admin` met wachtwoord `welkom123`). Beide scripts
zijn idempotent en draaien dus veilig bij elke start.

Handmatig opnieuw draaien of de tabellen bekijken:

```bash
ddev mysql -uroot -proot smaakmaatje < .ddev/database/schema.sql  # schema + seeddata
ddev phpmyadmin                                                   # phpMyAdmin openen
ddev import-db --database=smaakmaatje --file=dump.sql             # dump importeren
ddev export-db --database=smaakmaatje --file=dump.sql.gz          # dump exporteren
```

---

## Bijdragen

- Gebruik de [PR-template](./.github/PULL_REQUEST_TEMPLATE.md) en koppel je PR aan een issue.
- Rollen, werkafspraken en de regels voor branches en pull requests staan in het [Team Charter](./TEAM.md).

## Status en roadmap

- [x] Design Thinking-fasen 1–4 uitgewerkt (Empathize, Define, Ideate, Prototype)
- [x] Projectstructuur + PDO-databaseverbinding opgezet
- [ ] Testfase (fase 5) uitwerken en documenteren
- [ ] Frontend bouwen (HTML/CSS/JS, mobile first)
- [ ] Backend + database (PHP/MySQL, PDO) koppelen
- [ ] Accounts, CRUD en admin-module implementeren
- [ ] Deployen via FTP

---

## Links

- **Repository:** <https://github.com/loos-glr/beroeps2-project-1>
- **Team Charter:** [TEAM.md](./TEAM.md)