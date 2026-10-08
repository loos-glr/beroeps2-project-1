# Stop de Ontkoking

Een responsive kookplatform voor Generatie Z (16–27 jaar): jongeren snel, makkelijk en betaalbaar laten koken in plaats van bestellen of kant-en-klaar. Gebouwd als schoolproject binnen **GLR Beroeps 2** in opdracht van **GLR Food Freaks**.

De inhoudelijke uitwerking staat in de projectdocumentatie:

- **Online documentatie (GitHub Pages):** <https://loos-glr.github.io/beroeps2-project-1/>
- **Projectplan (startpunt):** [docs/README.md](./docs/README.md)

## Projectstructuur

```
beroeps2-project-1/
├── README.md            # Projectoverzicht (dit bestand)
├── TEAM.md              # Team Charter: ambitie, rollen & afspraken
├── .gitignore
├── .github/
│   └── PULL_REQUEST_TEMPLATE.md
└── docs/                # Projectdocumentatie (Docsify-site)
    ├── index.html       # Docsify-configuratie
    ├── _sidebar.md      # Navigatie
    ├── README.md        # Projectplan / startpunt
    ├── 01-empathize.md  # Fase 1
    ├── 02-define.md     # Fase 2
    ├── 03-ideate.md     # Fase 3
    ├── 04-prototype.md  # Fase 4
    ├── 05-test.md       # Fase 5
    └── assets/          # Afbeeldingen (Empathy Map, User Flow, ERD, …)
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

De applicatiecode en database (PHP/MySQL) worden in een latere fase toegevoegd. Wat we bouwen en hoe staat in het [projectplan](./docs/README.md) en de [Prototype-fase](./docs/04-prototype.md).

---

## Bijdragen

- Gebruik de [PR-template](./.github/PULL_REQUEST_TEMPLATE.md) en koppel je PR aan een issue.
- Rollen, werkafspraken en de regels voor branches en pull requests staan in het [Team Charter](./TEAM.md).

## Status en roadmap

- [x] Design Thinking-fasen 1–4 uitgewerkt (Empathize, Define, Ideate, Prototype)
- [ ] Testfase (fase 5) uitwerken en documenteren
- [ ] Frontend bouwen (HTML/CSS/JS, mobile first)
- [ ] Backend + database (PHP/MySQL, PDO) koppelen
- [ ] Accounts, CRUD en admin-module implementeren
- [ ] Deployen via FTP

---

## Links

- **Repository:** <https://github.com/loos-glr/beroeps2-project-1>
- **Team Charter:** [TEAM.md](./TEAM.md)