# Cours Web - Du HTML a l'API REST

Cours complet de developpement web en francais, couvrant les fondamentaux du frontend au backend. Chaque module contient une lecon detaillee, des fichiers de code commentes et 5 exercices progressifs avec solutions.

---

## Prerequis

- [MAMP](https://www.mamp.info/) installe et demarre (Apache + MySQL + PHP 8+)
- Un editeur de code (VS Code recommande)
- Un navigateur moderne (Chrome, Firefox)
- [Postman](https://www.postman.com/downloads/) pour le module 06

## Structure des modules

Les modules sont conçus pour etre suivis dans l'ordre :

| #   | Module         | Contenu                                                            | Fichier de cours      |
| --- | -------------- | ------------------------------------------------------------------ | --------------------- |
| 01  | **HTML**       | Structure, balises semantiques, formulaires, accessibilite         | `LEARN_HTML.md`       |
| 02  | **CSS**        | Selecteurs, box model, flexbox, grid, animations, responsive       | `LEARN_CSS.md`        |
| 03  | **JavaScript** | Variables, DOM, evenements, fetch, localStorage, todo list         | `LEARN_JS.md`         |
| 04  | **POO PHP**    | Classes, heritage, encapsulation, polymorphisme, interfaces        | `LEARN_POO.md`        |
| 05  | **Formulaire** | Validation client (JS) + serveur (PHP OOP), sanitization, securite | `LEARN_FORMULAIRE.md` |
| 06  | **API REST**   | CRUD PHP/MySQL, PDO, requetes preparees, JSON, test Postman        | `LEARN_API_REST.md`   |

## Comment utiliser ce cours

### Modules 01 a 03 (Frontend)

Ouvrir les fichiers `index.html` directement dans le navigateur ou via MAMP :

```
http://localhost:8888/oop-crash-course/cours-web/01_HTML/index.html
http://localhost:8888/oop-crash-course/cours-web/02_CSS/index.html
http://localhost:8888/oop-crash-course/cours-web/03_JavaScript/index.html
```

Pour le module 03, ouvrir la console du navigateur (F12) pour voir les resultats du script.

### Module 04 (PHP OOP)

Executer les fichiers PHP en ligne de commande :

```bash
php cours-web/04_POO_PHP/01_classes.php
php cours-web/04_POO_PHP/02_heritage.php
php cours-web/04_POO_PHP/03_encapsulation.php
php cours-web/04_POO_PHP/04_polymorphisme.php
```

### Module 05 (Formulaire)

Acceder via MAMP :

```
http://localhost:8888/oop-crash-course/cours-web/05_Formulaire/index.html
```

Le formulaire envoie les donnees vers `traitement.php` pour la validation serveur.

### Module 06 (API REST)

1. Executer `database.sql` dans phpMyAdmin pour creer la base de donnees
2. Tester dans le navigateur ou avec Postman :

```
http://localhost:8888/oop-crash-course/cours-web/06_API_REST/index.php?action=produits
```

## Progression pedagogique

```
HTML (structure) → CSS (style) → JavaScript (interactivite)
                                        |
                                        v
                    PHP OOP (classes) → Formulaire (validation client + serveur)
                                        |
                                        v
                                   API REST (CRUD, MySQL, JSON)
```

Chaque module s'appuie sur les precedents. Le module 06 combine PHP OOP (module 04), la validation (module 05) et fetch/JSON (module 03).
