# Projectplan: Stop de Ontkoking

> **Van "ik bestel toch maar even" naar "dit kan ik ook!"**

Welkom bij de projectdocumentatie van **Stop de Ontkoking** — een kookplatform voor Generatie Z, gebouwd binnen de opleiding **GLR Beroeps 2** in opdracht van **GLR Food Freaks**. In deze documentatie lopen we stap voor stap door de vijf **Design Thinking-fasen** die aan de basis liggen van ons ontwerp.

---

## 📋 Opdracht & opdrachtgever

- **Opdrachtgever:** GLR Food Freaks
- **Opdracht:** ontwerp en bouw een responsive webplatform dat jongeren verleidt om zelf te koken en recepten te delen.
- **Werkwijze:** Design Thinking (Empathize → Define → Ideate → Prototype → Test), met Git/GitHub voor versiebeheer.

## 🎯 Doelgroep

Generatie Z (geboren ~1997–2012), met de focus op **16–27 jaar**: studenten en jongvolwassenen die op kamers wonen en kampen met tijdgebrek, een beperkt budget en keuzestress. De tweede doelgroep is de **beheerder (admin)** die content en gebruikers beheert.

## ❗ Probleemstelling (kort)

Generatie Z wil wél gezond en lekker eten, maar **gemak en snelheid winnen** op het moment van de keuze. Tussen de intentie ("ik wil koken") en het gedrag ("ik bestel toch") zit een gat dat we dichten door koken **snel, overzichtelijk, visueel en leuk** te maken.

---

## 🧭 De vijf Design Thinking-fasen

Klik op een fase om de volledige uitwerking te lezen:

1. **[Empathize](./01-empathize.md)** — gebruikersonderzoek, persona "Sanne, 21 jaar", Empathy Map met pains & gains.
2. **[Define](./02-define.md)** — de probleemstelling (Point of View), het Programma van Eisen (MoSCoW) en naam-/URL-voorstellen.
3. **[Ideate](./03-ideate.md)** — concepten, de conceptkeuze (**Smaakmaatje**) en de user flow.
4. **[Prototype](./04-prototype.md)** — wireframes, visual design / style guide, ERD en datadictionary.
5. **[Test](./05-test.md)** — testopzet, resultaten en iteraties.

## 🏆 Het gekozen concept: Smaakmaatje

Uit de Ideate-fase kozen we voor **Smaakmaatje**: een overzichtelijk receptenplatform met categorieën én zoekfunctie, aangevuld met gebruikersrecepten, delen op socials en een stap-voor-stap kookmodus. Het dekt alle Must Haves en pakt het kerninzicht uit de Empathize-fase: **overzicht + snelheid + inspiratie**.

## ✅ Programma van Eisen (MoSCoW, kern)

- **Must Haves:** mobile first · homepage · overzichtspagina · detailpagina · invoerpagina (CRUD) · zoekfunctie op ingrediënt & type · categorieën · registreren & inloggen · admin-module · echte database.
- **Should Haves:** social media-integratie · favorieten · beoordelingen/likes · filteren & sorteren · tips & ervaringen delen.
- **Could Haves:** video-recepten · boodschappenlijstje · weekmenu/maaltijdplanner · weergave moeilijkheidsgraad.
- **Won't Haves:** supermarkt-API's · betaalfunctie · native app · meertaligheid.

## 🛠️ Technische architectuur (kort)

| Laag | Technologie |
| :--- | :--- |
| Frontend | HTML5, CSS3 (Flexbox/Grid), Vanilla JavaScript — mobile first |
| Backend & data | PHP + MySQL (PDO) |
| Hosting | FTP-upload |
| Documentatie | Docsify (Markdown as code) |

De volledige uitwerking — inclusief ERD en datadictionary — vind je in de fase **[Prototype](./04-prototype.md)**.

---

## 👥 Team

Rollen, ambitie en samenwerkingsafspraken staan in het [Team Charter](../TEAM.md). We werken via branches en mergen alleen na een goedgekeurde pull request (minimaal 1 review).

## 🔗 Handige links

- **Repository:** <https://github.com/loos-glr/beroeps2-project-1>
- **Team Charter:** [TEAM.md](../TEAM.md)
- **Projectoverzicht (root README):** [README.md](../README.md)
