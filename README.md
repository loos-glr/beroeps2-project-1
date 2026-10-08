# Stop de Ontkoking 🍳

> **Van "ik bestel toch maar even" naar "dit kan ik ook!"**

Een responsive webplatform waarmee jongeren (Generatie Z, 16–27 jaar) snel, makkelijk en betaalbaar een verse, gezonde maaltijd op tafel zetten. Project binnen de opleiding **GLR Beroeps 2**, in opdracht van **GLR Food Freaks**.

📖 **Online documentatie (Docsify / GitHub Pages):** <https://loos-glr.github.io/beroeps2-project-1/>

---

## 📌 Het probleem

De helft van de Nederlanders heeft minstens 1× per week geen zin om te koken (KRO-NCRV / FSIN). Bij Generatie Z zit er een gat tussen **intentie** ("ik wil best koken") en **gedrag** ("ik bestel toch maar"). Oorzaken: tijdgebrek en mentale druk, weinig kookvaardigheden, een krap budget en keuzestress op receptensites. Het gevolg is een ongezond eetpatroon van dure kant-en-klaarmaaltijden en bezorging.

## 💡 De oplossing

**Stop de Ontkoking** (concept: **Smaakmaatje**) is een overzichtelijk kookplatform dat koken **snel, visueel en leuk** maakt in plaats van te moraliseren over gezondheid. Kernfuncties:

- Recepten per **categorie** (ontbijt, lunch, diner, voorgerecht, hoofdgerecht, nagerecht)
- **Zoeken** op ingrediënt én type gerecht
- **Receptpagina's** met foto, ingrediënten en stap-voor-stap bereiding
- Eigen recepten **toevoegen, aanpassen en verwijderen** (CRUD)
- **Registreren & inloggen**, plus een **admin-module** voor beheer
- **Delen** naar socials en recepten van andere gebruikers bekijken

## 🎯 Doelgroep

Generatie Z (geboren ~1997–2012), met focus op **16–27 jaar**: studenten en jongvolwassenen die op kamers wonen, weinig tijd, een beperkt budget en een druk sociaal leven hebben. Daarnaast een kleinere doelgroep van **beheerders (admins)**.

---

## 🧭 Design Thinking aanpak

Het project volgt de vijf Design Thinking-fasen. De volledige uitwerking staat in [`docs/`](./docs):

| Fase | Document | Kerninhoud |
| :--- | :--- | :--- |
| 1. Empathize | [docs/01-empathize.md](./docs/01-empathize.md) | Doelgroeponderzoek, persona "Sanne, 21 jaar", Empathy Map, pains & gains |
| 2. Define | [docs/02-define.md](./docs/02-define.md) | Probleemstelling (Point of View), Programma van Eisen (MoSCoW), naam-/URL-voorstellen |
| 3. Ideate | [docs/03-ideate.md](./docs/03-ideate.md) | Concepten, conceptkeuze (Smaakmaatje) en de user flow |
| 4. Prototype | [docs/04-prototype.md](./docs/04-prototype.md) | Wireframes, visual design / style guide, ERD en datadictionary |
| 5. Test | [docs/05-test.md](./docs/05-test.md) | Testopzet, resultaten en iteratie |

## ✅ Programma van Eisen (MoSCoW, kern)

**Must Haves:** mobile first / responsive · homepage · overzichtspagina · detailpagina · invoerpagina (CRUD) · zoekfunctie op ingrediënt & type · categorieën · registreren & inloggen · admin-module · echte database · recepten van andere gebruikers bekijken.

**Should Haves:** social media-integratie · favorieten/bewaren · beoordelingen/likes · filteren & sorteren · tips & ervaringen delen.

**Could Haves:** video-recepten · automatisch boodschappenlijstje · weekmenu/maaltijdplanner · weergave moeilijkheidsgraad.

**Won't Haves (buiten scope):** supermarkt-API's · betaalfunctie/webshop · native app · meertaligheid.

---

## 🛠️ Tech stack

| Laag | Technologie |
| :--- | :--- |
| Frontend | HTML5, CSS3 (Flexbox/Grid), Vanilla JavaScript — mobile first |
| Backend & data | PHP + MySQL (database-koppeling via PDO) |
| Hosting | FTP-upload (eis uit de opdracht) |
| Documentatie | Docsify (Markdown as code) |
| Versiebeheer | Git / GitHub |

## 📁 Projectstructuur

```
beroeps2-project-1/
├── README.md            # Dit bestand — projectoverzicht
├── TEAM.md              # Team Charter: ambitie, rollen & afspraken
├── .gitignore
├── .github/
│   └── PULL_REQUEST_TEMPLATE.md
└── docs/                # Docsify-documentatiesite (source of truth)
    ├── index.html       # Docsify-configuratie
    ├── _sidebar.md      # Navigatie
    ├── README.md        # Documentatie-homepage / projectplan
    ├── 01-empathize.md  # Fase 1
    ├── 02-define.md     # Fase 2
    ├── 03-ideate.md     # Fase 3
    ├── 04-prototype.md  # Fase 4
    ├── 05-test.md       # Fase 5
    └── assets/          # Afbeeldingen (Empathy Map, User Flow, ERD, …)
```

## 🚀 Aan de slag

### Documentatie lokaal bekijken

De docs zijn een statische Docsify-site die de Markdown-bestanden in `docs/` direct rendert. Start eenvoudig een lokale webserver in de projectmap:

```bash
# Met Python 3
python3 -m http.server 3000

# of met Node.js (npx)
npx serve
```

Open daarna <http://localhost:3000/docs/> in je browser.

### Applicatie draaien (in ontwikkeling)

De applicatiecode wordt in een latere fase toegevoegd. Zodra de PHP/MySQL-implementatie er is, draai je die met een lokale webserver met PHP-ondersteuning (bijv. de ingebouwde server van PHP of XAMPP/MAMP). De vereiste databasestructuur staat in de [datadictionary](./docs/04-prototype.md#datadictionary).

---

## 👥 Team & samenwerking

We werken in een klein team waarin iedereen code schrijft én ontwerpt. De rollen (Scrum Master, Lead Design, Lead Git/Dev), onze ambitie en de onderlinge afspraken staan in [TEAM.md](./TEAM.md).

### Git & GitHub afspraken

- **Branching:** niemand commit rechtstreeks naar `main`; we werken via feature-branches.
- **Pull Requests:** een PR wordt pas gemerged na minimaal **1 approval** (code review) van een ander teamlid.
- **PR's:** gebruik de [PR-template](./.github/PULL_REQUEST_TEMPLATE.md) en koppel je PR aan een issue.

## 🗺️ Status & roadmap

- [x] Design Thinking-fasen 1–4 uitgewerkt (Empathize, Define, Ideate, Prototype)
- [ ] Testfase (fase 5) uitwerken en documenteren
- [ ] Frontend bouwen (HTML/CSS/JS, mobile first)
- [ ] Backend + database (PHP/MySQL, PDO) koppelen
- [ ] Accounts, CRUD en admin-module implementeren
- [ ] Deployen via FTP

---

## 🔗 Links

- **Documentatie (GitHub Pages):** <https://loos-glr.github.io/beroeps2-project-1/>
- **Repository:** <https://github.com/loos-glr/beroeps2-project-1>
- **Team Charter:** [TEAM.md](./TEAM.md)