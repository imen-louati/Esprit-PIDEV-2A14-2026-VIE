<div class="notice notice-pink">
    <div class="notice-icon"><svg class="ic"><use href="#i-shield"/></svg></div>
    <div>
        <strong>Proche autorisé : Mehdi Trabelsi</strong>
        <span>Il consulte votre résumé de suivi (rendez-vous et médicaments) jusqu'au 31 décembre 2026. Les documents ne sont pas partagés.</span>
    </div>
    <button class="btn btn-sm btn-ghost" type="button" data-action="notify" data-message="Gestion des autorisations ouverte">Gérer les autorisations</button>
</div>

<div class="banner banner-patient">
    <div>
        <h1>Bonjour, Sarra</h1>
        <p>Votre traitement avance bien. Voici l'essentiel de votre semaine.</p>
        <div class="progress-row" style="max-width:420px">
            <div class="progress-meta"><span>Progression du traitement</span><span>68 %</span></div>
            <div class="progress" style="background:rgba(255,255,255,.25)"><i style="width:68%;background:#fff"></i></div>
        </div>
    </div>
    <div class="banner-stats">
        <div><small>Prochain RDV</small><b>Mar. 10:30</b></div>
        <div><small>Prises du jour</small><b>1 / 2</b></div>
    </div>
</div>

<div class="grid main-side mt">
    <div class="stack">
        <article class="next-rdv">
            <small>Prochain rendez-vous</small>
            <h2>Mardi 24 novembre · 10:30</h2>
            <p>Dr Ben Salah — Oncologie médicale · Contrôle sein</p>
            <div class="meta">
                <span><svg class="ic"><use href="#i-map"/></svg>Institut Salah Azaiez, Tunis</span>
                <span><svg class="ic"><use href="#i-clock"/></svg>Durée estimée : 30 min</span>
            </div>
            <div class="row-actions" style="justify-content:flex-start">
                <button class="btn btn-primary" type="button" data-action="notify" data-message="Ajouté à votre calendrier">Ajouter au calendrier</button>
                <button class="btn btn-ghost" type="button" style="background:rgba(255,255,255,.14);color:#fff;box-shadow:none" data-action="notify" data-message="Demande de modification envoyée">Modifier</button>
            </div>
        </article>

        <section class="card">
            <div class="card-head">
                <div><h2>Centre de soins le plus proche</h2><p>Institut Salah Azaiez · à 4 km de chez vous</p></div>
                <a class="link" href="index.php?page=patient">Voir les autres centres</a>
            </div>
            <div class="map-box" role="img" aria-label="Carte du centre de soins le plus proche">
                <div class="pin" style="left:58%;top:46%"><svg class="ic"><use href="#i-map"/></svg><span>Institut Salah Azaiez</span></div>
                <div class="pin blue" style="left:32%;top:72%"><svg class="ic"><use href="#i-map"/></svg><span>Vous</span></div>
                <div class="map-legend">Bab Saadoun · Tunis</div>
            </div>
        </section>

        <section class="card">
            <div class="card-head">
                <div><h2>Mes médicaments</h2><p>Cochez chaque prise effectuée</p></div>
                <span class="badge b-blue">3 traitements</span>
            </div>
            <div>
                <div class="med">
                    <div class="med-icon i-pink"><svg class="ic"><use href="#i-pill"/></svg></div>
                    <div class="grow"><strong>Tamoxifène · 20 mg</strong><small>1 comprimé chaque matin · 08:00</small></div>
                    <button class="check" type="button" aria-pressed="true" aria-label="Prise du matin effectuée"><svg class="ic"><use href="#i-check"/></svg></button>
                </div>
                <div class="med">
                    <div class="med-icon i-blue"><svg class="ic"><use href="#i-pill"/></svg></div>
                    <div class="grow"><strong>Vitamine D3 · 1000 UI</strong><small>1 gélule chaque soir · 20:00</small></div>
                    <button class="check" type="button" aria-pressed="false" aria-label="Prise du soir effectuée"><svg class="ic"><use href="#i-check"/></svg></button>
                </div>
                <div class="med">
                    <div class="med-icon i-violet"><svg class="ic"><use href="#i-pill"/></svg></div>
                    <div class="grow"><strong>Ondansétron · 8 mg</strong><small>En cas de nausées, 2 prises maximum par jour</small></div>
                    <span class="badge b-gray">Si besoin</span>
                </div>
            </div>
        </section>

        <section class="card">
            <div class="card-head"><h2>Mes documents</h2><button class="btn btn-sm btn-soft" type="button" data-action="notify" data-message="Sélection du fichier"><svg class="ic"><use href="#i-plus"/></svg>Ajouter</button></div>
            <div class="mini-list">
                <div class="mini"><div class="center-ic i-blue" style="width:42px;height:42px"><svg class="ic"><use href="#i-doc"/></svg></div><div class="grow"><strong>Compte rendu de consultation</strong><small>PDF · ajouté le 12 nov. 2026</small></div><button class="btn btn-sm btn-ghost" type="button" data-action="notify" data-message="Téléchargement lancé">Télécharger</button></div>
                <div class="mini"><div class="center-ic i-pink" style="width:42px;height:42px"><svg class="ic"><use href="#i-doc"/></svg></div><div class="grow"><strong>Bilan sanguin</strong><small>PDF · ajouté le 03 nov. 2026</small></div><button class="btn btn-sm btn-ghost" type="button" data-action="notify" data-message="Téléchargement lancé">Télécharger</button></div>
            </div>
        </section>
    </div>

    <div class="stack">
        <section class="card">
            <div class="card-head"><h2>Mon parcours</h2></div>
            <div class="progress"><i style="width:68%"></i></div>
            <div class="steps">
                <div class="step done"><i></i>Diagnostic</div>
                <div class="step done"><i></i>Chirurgie</div>
                <div class="step now"><i></i>Traitement</div>
                <div class="step"><i></i>Contrôle</div>
            </div>
            <dl class="kv mt">
                <div><dt>Type</dt><dd>Cancer du sein</dd></div>
                <div><dt>Stade</dt><dd>II</dd></div>
                <div><dt>Médecin référent</dt><dd>Dr Ben Salah</dd></div>
            </dl>
        </section>

        <section class="card">
            <div class="card-head"><h2>Code de partage</h2></div>
            <div class="code-box">
                <div><small>À donner à un proche</small><strong>CC-7F3K-92</strong></div>
                <button class="btn btn-sm btn-primary" type="button" data-action="copy" data-value="CC-7F3K-92"><svg class="ic"><use href="#i-copy"/></svg>Copier</button>
            </div>
            <p style="color:var(--muted);font-size:13px;margin-top:12px">Le proche saisit ce code à l'inscription. Vous choisissez ce qu'il voit et pour combien de temps.</p>
        </section>

        <section class="card">
            <div class="card-head"><h2>Actualités pour vous</h2><a class="link" href="home.php#actualites">Tout voir</a></div>
            <div class="mini-list">
                <div class="news-card">
                    <div class="news-date"><b>12</b><span>OCT</span></div>
                    <div><strong>Dépistage : l'âge de la première mammographie en débat</strong><p>Sein · 3 min de lecture</p></div>
                </div>
                <div class="news-card">
                    <div class="news-date"><b>05</b><span>OCT</span></div>
                    <div><strong>Immunothérapie : de nouveaux résultats chez les patients avancés</strong><p>Poumon · 4 min de lecture</p></div>
                </div>
            </div>
        </section>
    </div>
</div>
