<?php
$pageTitle = 'VIE — Votre santé. Votre parcours. Notre mission.';
$currentPage = 'home';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once 'includes/head.php'; ?>
</head>
<body>
    <div class="page-bg" aria-hidden="true"></div>
    <?php require_once 'includes/navbar.php'; ?>

    <main>
        <section class="hero">
            <div class="container hero-grid">
                <div class="hero-copy">
                    <h1>Votre santé. Votre parcours. <em>Notre mission.</em></h1>
                    <p class="hero-lead">VIE réunit information médicale fiable, suivi personnel et coordination entre patients, proches et médecins, pour traverser chaque étape du parcours en oncologie avec plus de clarté.</p>
                    <div class="hero-actions">
                        <a class="btn btn-primary" href="login.php">Accéder à mon espace</a>
                        <a class="btn btn-ghost" href="#espaces">Découvrir les 3 espaces</a>
                    </div>
                    <div class="hero-trust">
                        <div><strong>3 espaces</strong><span>Patient, proche et médecin</span></div>
                        <div><strong>Partage choisi</strong><span>Le patient décide ce qui est visible</span></div>
                        <div><strong>Sans diagnostic</strong><span>Un assistant qui informe et oriente</span></div>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="hero-frame">
                        <img src="assets/img/hero-vie.svg" alt="Illustration d'un électrocardiogramme et d'une croix médicale sur fond bleu nuit">
                    </div>
                    <figure class="float-video glass">
                        <video autoplay muted loop playsinline poster="assets/img/video-poster.svg" aria-label="Présentation vidéo de la plateforme VIE">
                            <source src="assets/video/vie-presentation.mp4" type="video/mp4">
                        </video>
                        <figcaption>VIE en 60 secondes</figcaption>
                    </figure>
                    <div class="float-chip chip-follow glass">
                        <small>Mon suivi</small>
                        <strong>Traitement en cours : 68 %</strong>
                        <div class="bar"><i style="width:68%"></i></div>
                    </div>
                    <div class="float-chip chip-next glass">
                        <small>Prochain rendez-vous</small>
                        <strong>Mardi 10 h 30 — Dr Ben Salah</strong>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="categories">
            <div class="container">
                <div class="section-head">
                    <h2>Comprendre chaque type de cancer</h2>
                    <p>Une page par type de cancer : symptômes, traitements, recherches et centres de soin à proximité.</p>
                </div>
                <div class="cards-3">
                    <article class="cat-card cat-sein glass">
                        <div class="cat-art">
                            <svg viewBox="0 0 64 64" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M32 6c-8 0-14 6-14 13 0 8 8 13 14 21 6-8 14-13 14-21 0-7-6-13-14-13z"/>
                                <path d="M24 38 14 58M40 38l10 20"/>
                                <path d="M32 40 24 58M32 40l8 18" stroke-opacity=".6"/>
                            </svg>
                        </div>
                        <div class="cat-body">
                            <h3>Sein</h3>
                            <p>Dépistage, imagerie, chirurgie et traitements complémentaires, expliqués simplement.</p>
                            <a class="link-arrow" href="login.php">Lire le dossier
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>
                        </div>
                    </article>
                    <article class="cat-card cat-poumon glass">
                        <div class="cat-art">
                            <svg viewBox="0 0 64 64" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M32 8v22M32 30c-4 2-6 4-8 4M32 30c4 2 6 4 8 4"/>
                                <path d="M24 16c-8 2-14 14-14 28 0 6 4 8 9 7 5-1 7-4 7-9V24c0-4-1-8-2-8z"/>
                                <path d="M40 16c8 2 14 14 14 28 0 6-4 8-9 7-5-1-7-4-7-9V24c0-4 1-8 2-8z"/>
                            </svg>
                        </div>
                        <div class="cat-body">
                            <h3>Poumon</h3>
                            <p>Prévention, sevrage, diagnostic et prise en charge thoracique, étape par étape.</p>
                            <a class="link-arrow" href="login.php">Lire le dossier
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>
                        </div>
                    </article>
                    <article class="cat-card cat-leucemie glass">
                        <div class="cat-art">
                            <svg viewBox="0 0 64 64" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M32 6C24 18 14 27 14 39a18 18 0 0 0 36 0C50 27 40 18 32 6z"/>
                                <path d="M24 40a8 8 0 0 0 8 8" stroke-opacity=".6"/>
                            </svg>
                        </div>
                        <div class="cat-body">
                            <h3>Leucémie</h3>
                            <p>Comprendre les analyses, les traitements ciblés et l'accompagnement des familles.</p>
                            <a class="link-arrow" href="login.php">Lire le dossier
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="section" id="actualites">
            <div class="container split">
                <div>
                    <div class="section-head">
                        <h2>Actualités médicales</h2>
                        <p>Des informations vérifiées, publiées par l'équipe VIE et classées par type de cancer.</p>
                    </div>
                    <div class="news-list">
                        <article class="news-item glass">
                            <div class="date-badge"><b>12</b><span>OCT</span></div>
                            <div>
                                <span class="tag">Sein</span>
                                <h3>Dépistage : l'âge de la première mammographie en débat</h3>
                                <p>Les recommandations évoluent selon les profils de risque. Ce qu'il faut savoir avant d'en parler à son médecin.</p>
                            </div>
                        </article>
                        <article class="news-item glass">
                            <div class="date-badge blue"><b>05</b><span>OCT</span></div>
                            <div>
                                <span class="tag">Poumon</span>
                                <h3>Immunothérapie : de nouveaux résultats chez les patients avancés</h3>
                                <p>Un point clair sur les études récentes et sur ce qu'elles changent pour le suivi des patients.</p>
                            </div>
                        </article>
                    </div>
                </div>

                <div id="evenements">
                    <div class="section-head">
                        <h2>Événements</h2>
                        <p>Sensibilisation, dépistage et rencontres avec les professionnels de santé.</p>
                    </div>
                    <div class="event-stack">
                        <article class="event glass">
                            <div class="event-icon">
                                <svg viewBox="0 0 64 64" fill="none" stroke="#ED1F9E" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M32 8c-7 0-12 5-12 11 0 7 7 11 12 18 5-7 12-11 12-18 0-6-5-11-12-11z"/>
                                    <path d="M23 40 16 56M41 40l7 16"/>
                                </svg>
                            </div>
                            <div>
                                <h3>Octobre Rose</h3>
                                <p>Campagne annuelle de sensibilisation au dépistage du cancer du sein.</p>
                                <div class="meta">
                                    <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="5" width="18" height="16" rx="3"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>Chaque mois d'octobre</span>
                                    <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 21s7-6.2 7-12a7 7 0 0 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="9" r="2.5"/></svg>Centres partenaires</span>
                                </div>
                            </div>
                        </article>
                        <article class="event glass">
                            <div class="event-icon">
                                <svg viewBox="0 0 64 64" fill="none" stroke="#2C92E5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="10" y="14" width="44" height="30" rx="5"/>
                                    <path d="M22 54h20M32 44v10"/>
                                    <path d="M20 30h8l4-8 5 14 3-6h4"/>
                                </svg>
                            </div>
                            <div>
                                <h3>Conférence : vivre avec un cancer</h3>
                                <p>Oncologues, psychologues et associations répondent aux questions des patients et des proches.</p>
                                <div class="meta">
                                    <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="5" width="18" height="16" rx="3"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>28 novembre, 10 h</span>
                                    <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="6" width="18" height="12" rx="3"/><path d="m10 9 5 3-5 3z"/></svg>En ligne et sur place</span>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="espaces">
            <div class="container">
                <div class="section-head">
                    <h2>Trois espaces autour du patient</h2>
                    <p>Chacun accède à ce dont il a besoin, avec des droits définis par le patient lui-même.</p>
                </div>
                <div class="spaces">
                    <article class="space space-patient glass">
                        <div class="space-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#ED1F9E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
                        </div>
                        <h3>Espace patient</h3>
                        <p>Un suivi personnel clair, de la première consultation aux contrôles.</p>
                        <ul>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>Rendez-vous et téléconsultation</li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>Médicaments, documents et progression</li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>Code de partage pour un proche</li>
                        </ul>
                        <a class="link-arrow" href="login.php">Me connecter
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </article>
                    <article class="space space-proche glass">
                        <div class="space-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#2C92E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><circle cx="17" cy="9" r="2.5"/><path d="M2.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6M16 14.2c3 .2 5.5 2 5.5 5.8"/></svg>
                        </div>
                        <h3>Espace proche (aidant)</h3>
                        <p>Rester informé, sans rien demander deux fois, avec l'accord du patient.</p>
                        <ul>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>Résumé du suivi partagé</li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>Prochains rendez-vous à accompagner</li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>Accès limité dans le temps</li>
                        </ul>
                        <a class="link-arrow" href="login.php">Entrer avec mon code
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </article>
                    <article class="space space-medecin glass">
                        <div class="space-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#a99bff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3v6a4 4 0 0 0 8 0V3"/><path d="M10 13v2a5 5 0 0 0 10 0v-1"/><circle cx="20" cy="12" r="2"/></svg>
                        </div>
                        <h3>Espace médecin</h3>
                        <p>Un outil de travail sobre pour organiser consultations et dossiers autorisés.</p>
                        <ul>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>Agenda et disponibilités</li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>Dossiers des patients autorisés</li>
                            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>Notes de suivi et comptes rendus</li>
                        </ul>
                        <a class="link-arrow" href="login.php">Accès professionnel
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </article>
                </div>
            </div>
        </section>

        <section class="container">
            <div class="cta">
                <h2>Mieux informé. Toujours accompagné.</h2>
                <p>Créez votre espace en quelques minutes et gardez votre parcours de soins au même endroit.</p>
                <a class="btn btn-primary" href="login.php">Commencer</a>
            </div>
        </section>
    </main>

    <?php require_once 'includes/footer.php'; ?>
    <?php require_once 'includes/scripts.php'; ?>
</body>
</html>
