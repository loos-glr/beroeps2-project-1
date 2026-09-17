# **Studentenhandleiding: Documentatie & Design Thinking in Git**

  

Welkom bij BEROEPS 2! In de echte tech-wereld levert niemand meer een Word-documentje of een losse PDF in als projectverantwoording. Documentatie leeft direct naast je code. Dit noemen we **Docs as Code**.

  

In dit project leggen we de volledige Design Thinking cyclus vast in jullie GitHub repository via **Markdown (.md)**.

  

## **1. Wat verwachten we van jullie?**

  

* **Geen Word, geen PDF's.** Jullie volledige onderzoek, van de doelgroepanalyse tot de testresultaten, staat in .md bestanden in GitHub.

* **Hybride werken:** Jullie gebruiken GitHub voor de teksten/conclusies, en Figma (of FigJam) voor het visuele werk (zoals wireframes en brainstorms).

* **Alles via Pull Requests:** Heb jij de 'Empathize' fase uitgewerkt? Dan open je een PR. Je teamgenoot moet jouw onderzoek controleren (reviewen) en goedkeuren voordat het op de main branch komt.

  

## **2. Inrichting van jullie Repository (De /docs map)**

  

Zodra jullie repo is aangemaakt, voert het team (bijv. de Lead Git) de volgende stappen uit:

  

1. Maak in de hoofdmap (root) een nieuwe map aan genaamd docs/.

2. Maak in de docs/ map een submap aan genaamd assets/ (hier komen straks jullie geëxporteerde afbeeldingen in).

3. Maak in de docs/ map de 5 Markdown bestanden aan voor jullie Design Thinking Cyclus:

* 01-empathize.md

* 02-define.md

* 03-ideate.md

* 04-prototype.md

* 05-test.md

4. Kopieer de templates uit **Sectie 4** van dit document naar de juiste bestanden en vul deze wekelijks aan.

  

## **3. Visueel Werk (Figma exporteren naar GitHub)**

  

Omdat jullie met gratis Figma-accounts werken, ben je gelimiteerd tot maximaal 3 bestanden per teamproject. Als je bestanden moet verwijderen voor een nieuw vak, breken de links in jullie GitHub repo.

  

Daarom slaan we de belangrijkste ontwerpen op als **afbeeldingen in GitHub**. Zo blijft jullie portfolio voor altijd heel!

  

### **Stappenplan: Van Figma naar GitHub Markdown**

  

**Stap 1: Exporteren uit Figma**

  

1. Selecteer in Figma het frame (bijv. je wireframe of user flow) dat je wilt exporteren.

2. Ga in het rechter menu naar onderen naar **Export**.

3. Kies voor **PNG** (kies '2x' of '3x' voor een scherpere resolutie bij veel tekst).

4. Sla de afbeelding op zonder spaties in de naam! (bijv. lofi-wireframe-home.png).

  

**Stap 2: Toevoegen aan de Repository**

  

1. Plaats de afbeelding in de map docs/assets/ en push deze naar je branch.

  

**Stap 3: De afbeelding tonen in Markdown**

  

Open het Markdown-bestand (bijv. 04-prototype.md) en gebruik de volgende code om de afbeelding op te roepen:

  

![Wireframe van de Homepage](./assets/lofi-wireframe-home.png)

  

## **4. Templates voor Design Thinking**

  

*Kopieer onderstaande indelingen naar jullie eigen bestanden.*

  

### **01-empathize.md**

  

````

# Fase 1: Empathize (Begrijp de gebruiker)

  

## 1. Doelgroeponderzoek

* **Wie is onze gebruiker?** [Beschrijf de Gen Z student]

* **Pijnpunten (Pains):**

* *[Bijv: Te weinig geld door dure bezorgdiensten]*

* **Behoeftes (Gains):**

* *[Bijv: Gezond willen eten, maar het mag max 20 minuten duren]*

  

## 2. Empathy Map (Visueel)

![Empathy Map](./assets/empathy-map-v1.png)

  

## 3. Conclusie

*Wat is het belangrijkste inzicht dat we meenemen naar de Define fase?*

  

### **02-define.md**

  

# Fase 2: Define (Kaders & Probleemstelling)

  

## 1. De Probleemstelling (Point of View)

*[Doelgroep] heeft een manier nodig om [behoefte] omdat [inzicht uit empathize fase].*

  

## 2. Programma van Eisen (MoSCoW)

**Must Haves:**

* De app moet responsive zijn (Mobile First).

* [Voeg toe...]

  

**Should Haves:**

* [Voeg toe...]

  

**Won't Haves (Buiten scope):**

* Integratie met echte supermarkt API's.

````

  

### **03-ideate.md**

  

````

# Fase 3: Ideate (Oplossingen bedenken)

  

## 1. Concepten

* **How-Might-We vraag:** [Vul in]

* **Concept 1:** [Korte beschrijving]

* **Concept 2:** [Korte beschrijving]

  

## 2. Conceptkeuze & Onderbouwing

*Welk concept gaan we bouwen en waarom sluit dit het beste aan bij de PvE uit de Define fase?*

  

## 3. User Flow (Visueel)

![User Flow Diagram](./assets/user-flow.png)

  

### **04-prototype.md**

  

# Fase 4: Prototype (Ontwerp & Techniek)

  

## 1. Wireframes (Lo-Fi)

![Wireframes](./assets/wireframes-lofi.png)

  

## 2. Visual Design (Hi-Fi)

![Visual Design](./assets/visual-design-hifi.png)

  

## 3. Technische Architectuur

* **Frontend:** [Bijv. HTML, CSS, Vanilla JS]

* **Backend/Data:** [Bijv. Lokale JSON of Externe API]

````

  

### **05-test.md**

  

# Fase 5: Test (Validatie)

  

````

## 1. Testopzet

* **Wat testen we?** [Bijv: Werkt de filterfunctie logisch?]

* **Wie zijn de testpersonen?** [Bijv: 3 studenten uit een andere groep]

  

## 2. Test Resultaten

| Testpersoon | Taak geslaagd? | Opmerking / Feedback |

| :--- | :--- | :--- |

| Persoon A | Ja | Vond de knop wat klein. |

| Persoon B | Nee | Begreep de navigatie niet direct. |

  

## 3. Iteratie

*Wat gaan we in de code/design aanpassen n.a.v. de feedback?*

* [Bijv: We maken de actieknop 15% groter.]

````