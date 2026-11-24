# VIE — Plateforme de santé et bien-être inclusif

Projet universitaire ESPRIT (Technologies Web 2A, 2026-2027) — groupe **ALTHEA**.
VIE accompagne les patients atteints de cancer, leurs proches et les médecins : information, suivi et coordination.

## Périmètre de cette version

- Intégration des templates **Front-Office** (`includes/` + `require_once`) et **Back-Office** (MVC : `app/Controllers`, `app/Views/pages`, `layout.php`, `partials/`, `$currentPage`).
- **Module Gestion des utilisateurs / métiers** avec **une seule table métier : `users`** (plus `password_resets` pour la réinitialisation du mot de passe). Aucune donnée n'est pré-remplie : les comptes sont créés depuis l'application.

## Fonctionnalités

| Domaine | Détail |
|---|---|
| Inscription | Patient, proche, médecin (jamais admin en inscription publique). Champs affichés et **validés côté serveur** selon le rôle. |
| Premier compte | Si la table `users` est vide, le premier compte créé devient **administrateur** (amorçage). Créez-le tout de suite après l'installation. |
| Connexion | E-mail + mot de passe, `password_verify`, statut `active` exigé, `session_regenerate_id`, limitation des tentatives (5 / 15 min). |
| Mot de passe oublié | Jeton aléatoire `random_bytes(32)`, **seul le hash SHA-256 est stocké**, expiration 30 min, usage unique, message neutre. |
| CRUD utilisateurs (admin) | Liste filtrable + pagination, fiche, création, modification, suppression, validation des médecins, blocage / déblocage. |
| Rôles et autorisations | `admin`, `medecin`, `patient`, `proche`. Chaque contrôleur déclare `$allowedRoles` ; le rôle est relu en base à chaque requête. |
| Métiers | Code de partage patient (`CC-XXXX-00`), liaison proche ↔ patient, autorisations par le patient (rendez-vous / médicaments / documents, date de fin, révocation), validation des médecins par l'admin. |
| Profil | Modification des informations, changement de mot de passe, suppression du compte. |
| Garde-fous | Impossible de supprimer / bloquer / rétrograder soi-même ou le dernier admin actif. |

### Statuts

`active` (actif) · `inactive` (médecin en attente de validation) · `blocked` (bloqué).

## Sécurité (workshop)

- Requêtes **PDO préparées** partout (`ATTR_EMULATE_PREPARES = false`), aucun placeholder réutilisé.
- Mots de passe : `password_hash` / `password_verify` / `password_needs_rehash`.
- **CSRF** : jeton de session sur tous les formulaires POST (déconnexion incluse).
- Sessions : `use_strict_mode`, cookie `HttpOnly` + `SameSite=Lax` (+ `Secure` en HTTPS), expiration après 30 min d'inactivité, régénération de l'identifiant à la connexion.
- En-têtes : `Content-Security-Policy`, `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Cache-Control: no-store`.
- Sortie échappée avec `e()` (`htmlspecialchars`). Aucun message d'erreur SQL affiché.
- Validation des saisies : PHP (`app/Core/Validator.php`) **et** JavaScript (`assets/js/forms.js`).

## Installation (XAMPP / WAMP)

1. PHP **8.0+**, MySQL / MariaDB.
2. Copier le dossier dans `htdocs` (XAMPP) ou `www` (WAMP) : `http://localhost/vie/`.
3. Ouvrir phpMyAdmin → onglet **SQL** → coller le contenu de `database/database.sql` (crée la base `projetV` et les tables, sans données).
4. Adapter si besoin `config.php` (hôte, utilisateur, mot de passe MySQL).
5. Ouvrir `http://localhost/vie/index.php?page=register` et créer le premier compte (administrateur).
6. Se connecter : `http://localhost/vie/index.php?page=login`.

Sans XAMPP : `php -S localhost:8000` depuis le dossier du projet, puis `http://localhost:8000/home.php`.

### E-mails de réinitialisation

Par défaut (`'mail_mode' => 'log'`), le message est écrit dans `storage/mails.log` (dossier protégé par `.htaccess`) car un poste de développement n'a pas de serveur SMTP. Copiez le lien de ce fichier pour tester. En production, passez à `'mail_mode' => 'mail'`, configurez le serveur d'envoi, définissez `'app_url'` et servez le site en **HTTPS**.

## Scénario de test (validation)

1. Créer le premier compte → il devient admin → se connecter (`Dashboard`).
2. Dans une fenêtre privée : s'inscrire comme **patient** → noter son code de partage.
3. S'inscrire comme **proche** avec ce code → le patient le voit dans « Mes proches autorisés » et peut modifier ou révoquer l'accès.
4. S'inscrire comme **médecin** → connexion refusée (en attente) → l'admin le valide depuis le Dashboard → connexion possible.
5. Admin → `Users` : créer, modifier, bloquer, débloquer, supprimer ; tester les filtres.
6. Tenter d'ouvrir `index.php?page=dashboard` avec un compte patient → **403 Accès refusé**.
7. « Mot de passe oublié ? » → lien dans `storage/mails.log` → nouveau mot de passe.

## Architecture

```
index.php              routeur unique (?page=...)
routes.php             page => [Contrôleur, méthode]
config.php             base de données, sessions, mail
home.php               page publique (Front-Office)
includes/              head, navbar, footer, scripts, bootstrap
app/
  Config/Database.php  connexion PDO (singleton)
  Core/                Security (session, CSRF, en-têtes), Validator, Flash, Mailer
  Models/UserModel.php entité utilisateur
  Controllers/         Controller (base), Auth, Dashboard, Users, Profile, Doctor, Patient, Proche
  Views/
    layout.php         assemble head, sidebar, header, $content, footer, scripts
    partials/          head, sidebar, header, footer, scripts
    pages/             vues Back-Office
    auth/              connexion, inscription, mot de passe oublié, réinitialisation
assets/                css, js, img
database/database.sql  schéma (sans données)
storage/               journal des e-mails (développement)
```

### Pages (`index.php?page=...`)

`home`, `login`, `register`, `forgot-password`, `reset-password`, `logout` · admin : `dashboard`, `users`, `users-show`, `users-create`, `users-edit` · médecin : `doctor` · patient : `patient` · proche : `proche` · tous : `profile`.

### Ajouter un module (équipe)

1. Créer `app/Controllers/MonModuleController.php` (`extends Controller`, définir `$page`, `$title`, `$allowedRoles`).
2. Créer la vue `app/Views/pages/mon-module.php` (HTML + boucles d'affichage uniquement).
3. Déclarer la route dans `routes.php` et le lien dans `app/Views/partials/sidebar.php`.

## Organisation Git (rappel consignes)

- Une branche par fonctionnalité : `feature/auth`, `feature/users-crud`, `feature/profile`…
- Commits courts et explicites (`feat(users): ajout de la modification`), Pull Request relue par un autre membre, fusion dans `main`.
- Suivre chaque tâche dans le **GitHub Project Board** (To do / In progress / Done) et lier les cartes aux PR.
- La validation se fait sur **une seule machine** : vérifier l'installation complète depuis ce README avant la séance.

## Limites connues

- Les modules rendez-vous, médicaments, documents, centres et actualités ne sont pas inclus : leurs zones affichent un état vide.
- Les sessions existantes d'un utilisateur ne sont pas révoquées après un changement de mot de passe par jeton (pas de table de sessions).
