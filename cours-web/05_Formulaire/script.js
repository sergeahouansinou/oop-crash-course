/**
 * ============================================================
 * SCRIPT DE VALIDATION COTE CLIENT
 * ============================================================
 *
 * Ce fichier JavaScript gere la validation en temps reel
 * du formulaire de contact AVANT l'envoi au serveur.
 *
 * Fonctionnalites :
 * - Validation de chaque champ en temps reel (evenement "input")
 * - Validation globale a la soumission du formulaire
 * - Affichage/masquage des messages d'erreur
 * - Indicateur de force du mot de passe
 * - Compteur de caracteres pour le message
 * - Prevention de l'envoi si des erreurs existent
 *
 * ============================================================
 */


// ============================================================
// ATTENDRE QUE LE DOM SOIT COMPLETEMENT CHARGE
// Cela garantit que tous les elements HTML sont accessibles
// ============================================================
document.addEventListener('DOMContentLoaded', function () {

    // ============================================================
    // RECUPERATION DES ELEMENTS DU FORMULAIRE
    // On stocke les references vers les elements dans des constantes
    // pour eviter de les rechercher a chaque validation
    // ============================================================

    /** @type {HTMLFormElement} - Le formulaire principal */
    const formulaire = document.getElementById('formulaire-contact');

    /** @type {HTMLInputElement} - Champ nom */
    const champNom = document.getElementById('nom');

    /** @type {HTMLInputElement} - Champ prenom */
    const champPrenom = document.getElementById('prenom');

    /** @type {HTMLInputElement} - Champ email */
    const champEmail = document.getElementById('email');

    /** @type {HTMLInputElement} - Champ telephone */
    const champTelephone = document.getElementById('telephone');

    /** @type {HTMLInputElement} - Champ age */
    const champAge = document.getElementById('age');

    /** @type {HTMLInputElement} - Champ mot de passe */
    const champMotDePasse = document.getElementById('mot-de-passe');

    /** @type {HTMLInputElement} - Champ confirmation mot de passe */
    const champConfirmationMdp = document.getElementById('confirmation-mdp');

    /** @type {HTMLTextAreaElement} - Champ message */
    const champMessage = document.getElementById('message');

    /** @type {HTMLInputElement} - Case conditions generales */
    const champConditions = document.getElementById('conditions');

    /** @type {HTMLDivElement} - Conteneur du message de succes */
    const messageSucces = document.getElementById('message-succes');

    // --- Elements de l'indicateur de force du mot de passe ---
    const conteneurForce = document.getElementById('force-mdp');
    const barreForce = document.getElementById('barre-force');
    const texteForce = document.getElementById('texte-force');

    // --- Compteur de caracteres du message ---
    const compteurMessage = document.getElementById('compteur-message');


    // ============================================================
    // EXPRESSIONS REGULIERES (REGEX)
    // Utilisees pour valider le format des donnees
    // ============================================================

    /**
     * Regex pour les noms et prenoms :
     * - Lettres (majuscules et minuscules)
     * - Lettres accentuees francaises (e, e, a, u, etc.)
     * - Traits d'union et espaces (noms composes)
     * - Minimum 2 caracteres
     */
    const REGEX_NOM = /^[a-zA-ZÀ-ÿ\s\-']{2,50}$/;

    /**
     * Regex pour l'adresse email :
     * - Partie locale : lettres, chiffres, points, tirets, underscores
     * - Un arobase (@)
     * - Domaine : lettres, chiffres, points, tirets
     * - Extension : 2 a 10 lettres
     */
    const REGEX_EMAIL = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,10}$/;

    /**
     * Regex pour le telephone francais :
     * - Commence par 0
     * - Suivi d'un chiffre de 1 a 9
     * - Puis 8 chiffres supplementaires
     * - Total : 10 chiffres
     */
    const REGEX_TELEPHONE = /^0[1-9][0-9]{8}$/;

    /**
     * Regex pour le mot de passe (conditions individuelles) :
     * Chaque condition est testee separement pour un retour precis
     */
    const REGEX_MAJUSCULE = /[A-Z]/;
    const REGEX_MINUSCULE = /[a-z]/;
    const REGEX_CHIFFRE = /[0-9]/;
    const REGEX_SPECIAL = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?`~]/;


    // ============================================================
    // FONCTIONS UTILITAIRES
    // ============================================================

    /**
     * Affiche un message d'erreur pour un champ donne
     * et ajoute la classe CSS "erreur" au champ.
     *
     * @param {HTMLElement} champ - Le champ de saisie concerne
     * @param {string} idErreur - L'id du div d'erreur
     * @param {string} message - Le message d'erreur a afficher
     */
    function afficherErreur(champ, idErreur, message) {
        const divErreur = document.getElementById(idErreur);

        // Afficher le message d'erreur
        divErreur.textContent = message;

        // Ajouter la classe "erreur" au champ (bordure rouge)
        champ.classList.add('erreur');

        // Retirer la classe "valide" si elle etait presente
        champ.classList.remove('valide');
    }

    /**
     * Efface le message d'erreur pour un champ donne
     * et ajoute la classe CSS "valide" au champ.
     *
     * @param {HTMLElement} champ - Le champ de saisie concerne
     * @param {string} idErreur - L'id du div d'erreur
     */
    function effacerErreur(champ, idErreur) {
        const divErreur = document.getElementById(idErreur);

        // Vider le message d'erreur
        divErreur.textContent = '';

        // Retirer la classe "erreur"
        champ.classList.remove('erreur');

        // Ajouter la classe "valide" (bordure verte)
        champ.classList.add('valide');
    }

    /**
     * Reinitialise un champ (ni erreur, ni valide).
     * Utilise quand le champ est vide et n'a pas encore ete touche.
     *
     * @param {HTMLElement} champ - Le champ a reinitialiser
     * @param {string} idErreur - L'id du div d'erreur
     */
    function reinitialiserChamp(champ, idErreur) {
        const divErreur = document.getElementById(idErreur);
        divErreur.textContent = '';
        champ.classList.remove('erreur', 'valide');
    }


    // ============================================================
    // FONCTIONS DE VALIDATION INDIVIDUELLES
    // Chaque fonction valide un champ specifique et retourne
    // true (valide) ou false (invalide)
    // ============================================================

    /**
     * Valide le champ "Nom"
     * Regles : minimum 2 caracteres, lettres/accents/tirets uniquement
     *
     * @returns {boolean} - true si le nom est valide
     */
    function validerNom() {
        const valeur = champNom.value.trim();

        // Si le champ est vide
        if (valeur === '') {
            afficherErreur(champNom, 'erreur-nom', 'Le nom est obligatoire.');
            return false;
        }

        // Si le format ne correspond pas a la regex
        if (!REGEX_NOM.test(valeur)) {
            afficherErreur(
                champNom,
                'erreur-nom',
                'Le nom doit contenir au moins 2 caracteres (lettres, accents, tirets uniquement).'
            );
            return false;
        }

        // Tout est valide
        effacerErreur(champNom, 'erreur-nom');
        return true;
    }

    /**
     * Valide le champ "Prenom"
     * Memes regles que le nom
     *
     * @returns {boolean} - true si le prenom est valide
     */
    function validerPrenom() {
        const valeur = champPrenom.value.trim();

        if (valeur === '') {
            afficherErreur(champPrenom, 'erreur-prenom', 'Le prenom est obligatoire.');
            return false;
        }

        if (!REGEX_NOM.test(valeur)) {
            afficherErreur(
                champPrenom,
                'erreur-prenom',
                'Le prenom doit contenir au moins 2 caracteres (lettres, accents, tirets uniquement).'
            );
            return false;
        }

        effacerErreur(champPrenom, 'erreur-prenom');
        return true;
    }

    /**
     * Valide le champ "Email"
     * Verifie le format avec une expression reguliere
     *
     * @returns {boolean} - true si l'email est valide
     */
    function validerEmail() {
        const valeur = champEmail.value.trim();

        if (valeur === '') {
            afficherErreur(champEmail, 'erreur-email', 'L\'adresse e-mail est obligatoire.');
            return false;
        }

        if (!REGEX_EMAIL.test(valeur)) {
            afficherErreur(
                champEmail,
                'erreur-email',
                'Veuillez entrer une adresse e-mail valide (ex: nom@domaine.fr).'
            );
            return false;
        }

        effacerErreur(champEmail, 'erreur-email');
        return true;
    }

    /**
     * Valide le champ "Telephone"
     * Format francais : 10 chiffres commencant par 0
     *
     * @returns {boolean} - true si le telephone est valide
     */
    function validerTelephone() {
        const valeur = champTelephone.value.trim();

        if (valeur === '') {
            afficherErreur(champTelephone, 'erreur-telephone', 'Le numero de telephone est obligatoire.');
            return false;
        }

        if (!REGEX_TELEPHONE.test(valeur)) {
            afficherErreur(
                champTelephone,
                'erreur-telephone',
                'Le telephone doit contenir 10 chiffres et commencer par 0 (ex: 0612345678).'
            );
            return false;
        }

        effacerErreur(champTelephone, 'erreur-telephone');
        return true;
    }

    /**
     * Valide le champ "Age"
     * L'age doit etre un nombre entre 13 et 120
     *
     * @returns {boolean} - true si l'age est valide
     */
    function validerAge() {
        const valeur = champAge.value.trim();

        if (valeur === '') {
            afficherErreur(champAge, 'erreur-age', 'L\'age est obligatoire.');
            return false;
        }

        // Convertir en nombre entier
        const age = parseInt(valeur, 10);

        // Verifier que c'est bien un nombre
        if (isNaN(age)) {
            afficherErreur(champAge, 'erreur-age', 'Veuillez entrer un nombre valide.');
            return false;
        }

        // Verifier les bornes
        if (age < 13) {
            afficherErreur(champAge, 'erreur-age', 'Vous devez avoir au moins 13 ans.');
            return false;
        }

        if (age > 120) {
            afficherErreur(champAge, 'erreur-age', 'L\'age ne peut pas depasser 120 ans.');
            return false;
        }

        effacerErreur(champAge, 'erreur-age');
        return true;
    }

    /**
     * Valide le champ "Mot de passe"
     * Regles :
     * - Minimum 8 caracteres
     * - Au moins 1 majuscule
     * - Au moins 1 minuscule
     * - Au moins 1 chiffre
     * - Au moins 1 caractere special
     *
     * @returns {boolean} - true si le mot de passe est valide
     */
    function validerMotDePasse() {
        const valeur = champMotDePasse.value;

        // Si le champ est vide, masquer l'indicateur de force
        if (valeur === '') {
            afficherErreur(champMotDePasse, 'erreur-mot-de-passe', 'Le mot de passe est obligatoire.');
            conteneurForce.classList.remove('visible');
            return false;
        }

        // Afficher l'indicateur de force
        conteneurForce.classList.add('visible');

        // Mettre a jour l'indicateur de force
        mettreAJourForceMotDePasse(valeur);

        // Liste des erreurs specifiques
        const erreurs = [];

        if (valeur.length < 8) {
            erreurs.push('au moins 8 caracteres');
        }
        if (!REGEX_MAJUSCULE.test(valeur)) {
            erreurs.push('au moins 1 lettre majuscule');
        }
        if (!REGEX_MINUSCULE.test(valeur)) {
            erreurs.push('au moins 1 lettre minuscule');
        }
        if (!REGEX_CHIFFRE.test(valeur)) {
            erreurs.push('au moins 1 chiffre');
        }
        if (!REGEX_SPECIAL.test(valeur)) {
            erreurs.push('au moins 1 caractere special (!@#$%...)');
        }

        // S'il y a des erreurs, les afficher
        if (erreurs.length > 0) {
            afficherErreur(
                champMotDePasse,
                'erreur-mot-de-passe',
                'Le mot de passe doit contenir : ' + erreurs.join(', ') + '.'
            );
            return false;
        }

        effacerErreur(champMotDePasse, 'erreur-mot-de-passe');
        return true;
    }

    /**
     * Met a jour l'indicateur visuel de force du mot de passe.
     * Calcule un score base sur la longueur et la variete des caracteres.
     *
     * @param {string} motDePasse - Le mot de passe a evaluer
     */
    function mettreAJourForceMotDePasse(motDePasse) {
        let score = 0;

        // +1 point pour chaque critere rempli
        if (motDePasse.length >= 8) score++;
        if (motDePasse.length >= 12) score++;
        if (REGEX_MAJUSCULE.test(motDePasse)) score++;
        if (REGEX_MINUSCULE.test(motDePasse)) score++;
        if (REGEX_CHIFFRE.test(motDePasse)) score++;
        if (REGEX_SPECIAL.test(motDePasse)) score++;

        // Retirer toutes les classes de niveau
        barreForce.className = 'barre-force';
        texteForce.className = 'texte-force';

        // Appliquer le niveau en fonction du score
        if (score <= 2) {
            // Faible : 0 a 2 criteres
            barreForce.classList.add('faible');
            texteForce.classList.add('faible');
            texteForce.textContent = 'Faible';
        } else if (score <= 3) {
            // Moyen : 3 criteres
            barreForce.classList.add('moyen');
            texteForce.classList.add('moyen');
            texteForce.textContent = 'Moyen';
        } else if (score <= 4) {
            // Fort : 4 criteres
            barreForce.classList.add('fort');
            texteForce.classList.add('fort');
            texteForce.textContent = 'Fort';
        } else {
            // Tres fort : 5-6 criteres
            barreForce.classList.add('tres-fort');
            texteForce.classList.add('tres-fort');
            texteForce.textContent = 'Tres fort';
        }
    }

    /**
     * Valide la confirmation du mot de passe.
     * Doit etre identique au mot de passe original.
     *
     * @returns {boolean} - true si la confirmation correspond
     */
    function validerConfirmationMdp() {
        const valeur = champConfirmationMdp.value;
        const motDePasse = champMotDePasse.value;

        if (valeur === '') {
            afficherErreur(
                champConfirmationMdp,
                'erreur-confirmation-mdp',
                'Veuillez confirmer votre mot de passe.'
            );
            return false;
        }

        // Comparer les deux mots de passe
        if (valeur !== motDePasse) {
            afficherErreur(
                champConfirmationMdp,
                'erreur-confirmation-mdp',
                'Les mots de passe ne correspondent pas.'
            );
            return false;
        }

        effacerErreur(champConfirmationMdp, 'erreur-confirmation-mdp');
        return true;
    }

    /**
     * Valide le genre (boutons radio).
     * Au moins un bouton radio doit etre selectionne.
     *
     * @returns {boolean} - true si un genre est selectionne
     */
    function validerGenre() {
        // Verifier si au moins un bouton radio "genre" est coche
        const genreSelectionne = document.querySelector('input[name="genre"]:checked');

        if (!genreSelectionne) {
            document.getElementById('erreur-genre').textContent = 'Veuillez selectionner un genre.';
            return false;
        }

        document.getElementById('erreur-genre').textContent = '';
        return true;
    }

    /**
     * Valide le champ "Message" (textarea).
     * Le message doit contenir au minimum 10 caracteres.
     *
     * @returns {boolean} - true si le message est valide
     */
    function validerMessage() {
        const valeur = champMessage.value.trim();

        if (valeur === '') {
            afficherErreur(champMessage, 'erreur-message', 'Le message est obligatoire.');
            return false;
        }

        if (valeur.length < 10) {
            afficherErreur(
                champMessage,
                'erreur-message',
                'Le message doit contenir au moins 10 caracteres (' + valeur.length + '/10).'
            );
            return false;
        }

        effacerErreur(champMessage, 'erreur-message');
        return true;
    }

    /**
     * Valide que les conditions generales sont acceptees.
     * La case doit etre cochee.
     *
     * @returns {boolean} - true si les conditions sont acceptees
     */
    function validerConditions() {
        if (!champConditions.checked) {
            document.getElementById('erreur-conditions').textContent =
                'Vous devez accepter les conditions generales d\'utilisation.';
            return false;
        }

        document.getElementById('erreur-conditions').textContent = '';
        return true;
    }


    // ============================================================
    // VALIDATION GLOBALE
    // Execute toutes les validations et retourne le resultat
    // ============================================================

    /**
     * Lance toutes les validations en une seule fois.
     * Utilisee lors de la soumission du formulaire.
     *
     * @returns {boolean} - true si TOUS les champs sont valides
     */
    function validerTout() {
        // On execute TOUTES les validations (pas de court-circuit)
        // pour afficher toutes les erreurs d'un coup
        const resultats = [
            validerNom(),
            validerPrenom(),
            validerEmail(),
            validerTelephone(),
            validerAge(),
            validerMotDePasse(),
            validerConfirmationMdp(),
            validerGenre(),
            validerMessage(),
            validerConditions()
        ];

        // Le formulaire est valide seulement si TOUTES les validations passent
        return resultats.every(function (resultat) {
            return resultat === true;
        });
    }


    // ============================================================
    // ECOUTEURS D'EVENEMENTS - VALIDATION EN TEMPS REEL
    // On attache un ecouteur "input" a chaque champ pour valider
    // en temps reel pendant que l'utilisateur tape
    // ============================================================

    // --- Nom : validation a chaque frappe ---
    champNom.addEventListener('input', function () {
        // Si le champ est vide, reinitialiser (pas d'erreur)
        if (this.value.trim() === '') {
            reinitialiserChamp(this, 'erreur-nom');
            return;
        }
        validerNom();
    });

    // --- Prenom : validation a chaque frappe ---
    champPrenom.addEventListener('input', function () {
        if (this.value.trim() === '') {
            reinitialiserChamp(this, 'erreur-prenom');
            return;
        }
        validerPrenom();
    });

    // --- Email : validation a chaque frappe ---
    champEmail.addEventListener('input', function () {
        if (this.value.trim() === '') {
            reinitialiserChamp(this, 'erreur-email');
            return;
        }
        validerEmail();
    });

    // --- Telephone : validation a chaque frappe ---
    champTelephone.addEventListener('input', function () {
        if (this.value.trim() === '') {
            reinitialiserChamp(this, 'erreur-telephone');
            return;
        }
        validerTelephone();
    });

    // --- Age : validation a chaque frappe ---
    champAge.addEventListener('input', function () {
        if (this.value.trim() === '') {
            reinitialiserChamp(this, 'erreur-age');
            return;
        }
        validerAge();
    });

    // --- Mot de passe : validation a chaque frappe ---
    champMotDePasse.addEventListener('input', function () {
        if (this.value === '') {
            reinitialiserChamp(this, 'erreur-mot-de-passe');
            conteneurForce.classList.remove('visible');
            return;
        }
        validerMotDePasse();

        // Si la confirmation est deja remplie, la revalider aussi
        if (champConfirmationMdp.value !== '') {
            validerConfirmationMdp();
        }
    });

    // --- Confirmation mot de passe : validation a chaque frappe ---
    champConfirmationMdp.addEventListener('input', function () {
        if (this.value === '') {
            reinitialiserChamp(this, 'erreur-confirmation-mdp');
            return;
        }
        validerConfirmationMdp();
    });

    // --- Genre : validation au changement de selection ---
    const boutonsGenre = document.querySelectorAll('input[name="genre"]');
    boutonsGenre.forEach(function (bouton) {
        bouton.addEventListener('change', validerGenre);
    });

    // --- Message : validation a chaque frappe + compteur ---
    champMessage.addEventListener('input', function () {
        // Mettre a jour le compteur de caracteres
        const longueur = this.value.length;
        compteurMessage.textContent = longueur + ' / 1000 caracteres';

        if (this.value.trim() === '') {
            reinitialiserChamp(this, 'erreur-message');
            return;
        }
        validerMessage();
    });

    // --- Conditions : validation au changement ---
    champConditions.addEventListener('change', validerConditions);


    // ============================================================
    // SOUMISSION DU FORMULAIRE
    // Intercepte l'envoi pour valider tous les champs
    // ============================================================
    formulaire.addEventListener('submit', function (evenement) {
        // Masquer le message de succes precedent s'il y en avait un
        messageSucces.classList.remove('visible');
        messageSucces.textContent = '';

        // Lancer la validation globale
        const estValide = validerTout();

        if (!estValide) {
            // Empecher l'envoi du formulaire si des erreurs existent
            evenement.preventDefault();

            // Faire defiler vers la premiere erreur visible
            const premiereErreur = document.querySelector('.error-message:not(:empty)');
            if (premiereErreur) {
                premiereErreur.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            return;
        }

        // ============================================================
        // OPTION : Afficher un message de succes cote client
        // (Decommenter les lignes suivantes pour tester sans serveur)
        // ============================================================
        // evenement.preventDefault();
        // messageSucces.textContent = 'Formulaire valide avec succes ! Envoi en cours...';
        // messageSucces.classList.add('visible');
        // messageSucces.scrollIntoView({ behavior: 'smooth', block: 'center' });

        // Si tout est valide, le formulaire est soumis normalement vers traitement.php
    });


    // ============================================================
    // REINITIALISATION DU FORMULAIRE
    // Nettoyer tous les messages d'erreur et classes CSS
    // quand l'utilisateur clique sur "Reinitialiser"
    // ============================================================
    formulaire.addEventListener('reset', function () {
        // Petite temporisation pour que le reset natif se fasse d'abord
        setTimeout(function () {
            // Supprimer tous les messages d'erreur
            const tousMessagesErreur = document.querySelectorAll('.error-message');
            tousMessagesErreur.forEach(function (div) {
                div.textContent = '';
            });

            // Retirer les classes "erreur" et "valide" de tous les champs
            const tousLesChamps = formulaire.querySelectorAll('input, textarea');
            tousLesChamps.forEach(function (champ) {
                champ.classList.remove('erreur', 'valide');
            });

            // Masquer l'indicateur de force du mot de passe
            conteneurForce.classList.remove('visible');

            // Reinitialiser le compteur de caracteres
            compteurMessage.textContent = '0 / 1000 caracteres';

            // Masquer le message de succes
            messageSucces.classList.remove('visible');
            messageSucces.textContent = '';
        }, 10);
    });


    // ============================================================
    // FIN DU SCRIPT
    // Le formulaire est maintenant interactif avec validation
    // en temps reel et verification a la soumission
    // ============================================================

}); // Fin de DOMContentLoaded
