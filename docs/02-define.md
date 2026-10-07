# Fase 2: Define (Kaders & Probleemstelling)

In de Define-fase convergeren we: we bundelen alle inzichten uit de Empathize-fase tot één scherpe probleemstelling en een concreet Programma van Eisen (PvE).

## 1. De Probleemstelling (Point of View)

> **Generatie Z (16–27 jaar, met name studenten zoals 'Sanne') heeft een manier nodig om snel, makkelijk en betaalbaar een verse, gezonde maaltijd te bereiden — met de juiste inspiratie op het juiste moment — omdat tijdgebrek, mentale druk en een gebrek aan kookvaardigheden hen telkens weer naar dure, ongezonde kant-en-klaarmaaltijden en bezorgdiensten drijven.**

**Afgeleide How-Might-We vraag:** *Hoe kunnen we Generatie Z op een laagdrempelige, snelle en visueel aantrekkelijke manier verleiden om zelf te koken en recepten te delen?*

## 2. Programma van Eisen (MoSCoW)

### Must Haves
* **Responsive / Mobile First** – de doelgroep gebruikt het platform altijd en overal op de telefoon.
* **Homepage/landingspagina** – verwelkomt bezoekers, wijst de weg en stimuleert om verder te gaan.
* **Overzichtspagina** – overzicht van gerechten binnen de gekozen categorie.
* **Detailpagina** – gerecht in detail (foto, ingrediënten, bereidingswijze, tijd).
* **Invoerpagina** – gebruikers kunnen recepten en ingrediënten toevoegen, aanpassen en verwijderen (CRUD).
* **Zoekfunctie** – zoeken op **ingrediënt** én op **type gerecht** (ontbijt, lunch, diner, voorgerecht, hoofdgerecht, nagerecht).
* **Categorieën** – de zes gerechttypes.
* **Registreren & inloggen** – module voor gebruikersaccounts.
* **Admin-module** – beheerder kan alle recepten én gebruikers aanpassen/verwijderen.
* **Echte database** – gegevens worden écht opgeslagen en uitgelezen (niet gefaket).
* **Recepten van andere gebruikers bekijken** – bezoekers kunnen andermans recepten inzien.

### Should Haves
* Social media-integratie (delen naar / ophalen van TikTok & Instagram).
* Favorieten / "bewaar dit recept"-functionaliteit.
* Beoordelingen / likes op recepten.
* Filteren en sorteren (bereidingstijd, moeilijkheid, aantal ingrediënten).
* Tips & ervaringen delen met de community.

### Could Haves
* Video-recepten / korte kook-clips.
* Automatisch boodschappenlijstje genereren.
* Weekmenu / maaltijdplanner.
* Weergave moeilijkheidsgraad speciaal voor beginnende koks.

### Won't Haves (buiten scope)
* Integratie met echte supermarkt-API's (voorraad/prijzen).
* Betaalfunctie / webshop.
* Native mobiele app (we bouwen een responsive webapp).
* Meertaligheid.

## 3. Naam- en URL-voorstellen

De opdracht vraagt om **drie pakkende naam/URL-voorstellen**; de definitieve keuze maken we samen met de opdrachtgever (GLR Food Freaks).

| # | Naam | URL | Onderbouwing |
| :--- | :--- | :--- | :--- |
| 1 | **Kookloos** | kookloos.nl | Speelt in op 'ontkoken' + je hoeft geen expert te zijn om te koken. |
| 2 | **Fuelbox** | fuelbox.nl | Koken als energie/brandstof; jonge, snelle uitstraling. |
| 3 | **Smaakmaatje** | smaakmaatje.nl | Vriendelijk en laagdrempelig: "een maatje dat je helpt koken". |

## 4. User Stories (kern)

| Als… | wil ik… | zodat… | Prioriteit |
| :--- | :--- | :--- | :--- |
| Bezoeker | recepten bekijken per categorie | ik inspiratie opdoe | Must |
| Bezoeker | zoeken op ingrediënt | ik kan koken met wat ik in huis heb | Must |
| Bezoeker | recepten van anderen bekijken | ik nieuwe gerechten ontdek | Must |
| Gebruiker | me registreren / inloggen | ik mijn eigen recepten kan beheren | Must |
| Gebruiker | een recept toevoegen/wijzigen/verwijderen | ik mijn recepten actueel houd | Must |
| Gebruiker | een recept delen naar sociale media | ik mijn vrienden inspireer | Should |
| Admin | alle recepten en gebruikers beheren | ik ongewenste content kan verwijderen | Must |
