# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a French-language web development teaching course ("cours-web") covering HTML, CSS, JavaScript, PHP OOP, and form handling. All code, comments, and documentation are written in French. It runs on MAMP (local Apache/PHP stack).

## Architecture

The course is organized as sequential modules in `cours-web/`:

- **01_HTML/** - HTML fundamentals with a sample `index.html` and lesson markdown
- **02_CSS/** - CSS fundamentals with `style.css` and lesson markdown
- **03_JavaScript/** - JavaScript fundamentals with `script.js` and lesson markdown
- **04_POO_PHP/** - PHP 8+ OOP concepts across 4 progressive files (`01_classes.php` through `04_polymorphisme.php`) plus lesson markdown
- **05_Formulaire/** - Complete contact form with client-side validation (`script.js`), styling (`style.css`), and server-side OOP validation (`traitement.php`)

Each module has a `LEARN_*.md` file containing the full lesson content.

## Running Code

PHP OOP examples (module 04) run from the command line:
```
php cours-web/04_POO_PHP/01_classes.php
```

The form module (05) requires a web server (MAMP) — access via `http://localhost:8888/oop-crash-course/cours-web/05_Formulaire/`.

## Conventions

- All code uses French naming: classes (`Validateur`, `Sanitizer`), methods (`validerNom`, `nettoyerChaine`), variables (`$erreurs`, `$donnees`)
- PHP files use `declare(strict_types=1)` and PHP 8+ features (constructor promotion, typed properties, union types)
- Comments and documentation are in French throughout
- The form module uses dual validation: client-side JS + server-side PHP OOP (`Sanitizer` and `Validateur` classes in `traitement.php`)
