<div class="page-head">
    <div>
        <h1>Tableau de bord</h1>
        <p>Vue d'ensemble de l'activité de la plateforme VIE — mardi 17 novembre 2026</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-ghost" type="button" data-action="notify" data-message="Export du rapport en préparation">Exporter le rapport</button>
        <a class="btn btn-primary" href="index.php?page=users"><svg class="ic"><use href="#i-plus"/></svg>Nouveau compte</a>
    </div>
</div>

<div class="grid cols-4">
    <article class="card stat">
        <div class="stat-icon i-pink"><svg class="ic"><use href="#i-users"/></svg></div>
        <div><small>Comptes</small><strong>1 248</strong><span class="delta up">+64 ce mois</span></div>
    </article>
    <article class="card stat">
        <div class="stat-icon i-violet"><svg class="ic"><use href="#i-stetho"/></svg></div>
        <div><small>Médecins à valider</small><strong>7</strong><span class="delta flat">2 depuis hier</span></div>
    </article>
    <article class="card stat">
        <div class="stat-icon i-blue"><svg class="ic"><use href="#i-calendar"/></svg></div>
        <div><small>Rendez-vous</small><strong>342</strong><span class="delta up">+12 % sur 30 jours</span></div>
    </article>
    <article class="card stat">
        <div class="stat-icon i-green"><svg class="ic"><use href="#i-news"/></svg></div>
        <div><small>Actualités publiées</small><strong>36</strong><span class="delta flat">3 brouillons</span></div>
    </article>
</div>

<div class="grid main-side mt">
    <div class="stack">
        <section class="card">
            <div class="card-head">
                <div>
                    <h2>Gestion des utilisateurs</h2>
                    <p>Validez les médecins, suspendez ou supprimez un compte.</p>
                </div>
                <div class="chips" data-filter-group="#users-table" role="group" aria-label="Filtrer par rôle">
                    <button class="chip is-active" type="button" data-filter="all">Tous</button>
                    <button class="chip" type="button" data-filter="patient">Patients</button>
                    <button class="chip" type="button" data-filter="proche">Proches</button>
                    <button class="chip" type="button" data-filter="medecin">Médecins</button>
                    <button class="chip" type="button" data-filter="en_attente">En attente</button>
                </div>
            </div>
            <div class="table-wrap">
                <table id="users-table">
                    <thead>
                        <tr><th>Nom</th><th>Rôle</th><th>Statut</th><th>Inscrit le</th><th class="td-end">Actions</th></tr>
                    </thead>
                    <tbody>
                        <tr data-row data-name="Sarra Trabelsi" data-role="patient" data-status="actif">
                            <td><div class="person"><span class="avatar av-pink">ST</span><div><strong>Sarra Trabelsi</strong><small>sarra.trabelsi@vie.tn</small></div></div></td>
                            <td><span class="badge b-pink">Patient</span></td>
                            <td><span class="badge b-green" data-status-badge>Actif</span></td>
                            <td>02 sept. 2026</td>
                            <td><div class="row-actions"><button class="btn btn-sm btn-soft" type="button" data-action="suspend">Suspendre</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer Sarra Trabelsi"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                        </tr>
                        <tr data-row data-name="Mehdi Trabelsi" data-role="proche" data-status="actif">
                            <td><div class="person"><span class="avatar av-blue">MT</span><div><strong>Mehdi Trabelsi</strong><small>mehdi.trabelsi@vie.tn</small></div></div></td>
                            <td><span class="badge b-blue">Proche</span></td>
                            <td><span class="badge b-green" data-status-badge>Actif</span></td>
                            <td>04 sept. 2026</td>
                            <td><div class="row-actions"><button class="btn btn-sm btn-soft" type="button" data-action="suspend">Suspendre</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer Mehdi Trabelsi"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                        </tr>
                        <tr data-row data-name="Dr Ben Salah" data-role="medecin" data-status="actif">
                            <td><div class="person"><span class="avatar av-violet">BS</span><div><strong>Dr Ben Salah</strong><small>Oncologie médicale</small></div></div></td>
                            <td><span class="badge b-violet">Médecin</span></td>
                            <td><span class="badge b-green" data-status-badge>Actif</span></td>
                            <td>28 août 2026</td>
                            <td><div class="row-actions"><button class="btn btn-sm btn-soft" type="button" data-action="suspend">Suspendre</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer Dr Ben Salah"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                        </tr>
                        <tr data-row data-name="Dr Kallel" data-role="medecin" data-status="en_attente">
                            <td><div class="person"><span class="avatar av-violet">DK</span><div><strong>Dr Kallel</strong><small>Hématologie</small></div></div></td>
                            <td><span class="badge b-violet">Médecin</span></td>
                            <td><span class="badge b-amber" data-status-badge>En attente</span></td>
                            <td>15 nov. 2026</td>
                            <td><div class="row-actions"><button class="btn btn-sm btn-ok" type="button" data-action="validate">Valider</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer Dr Kallel"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                        </tr>
                        <tr data-row data-name="Karim Mansour" data-role="patient" data-status="suspendu">
                            <td><div class="person"><span class="avatar av-navy">KM</span><div><strong>Karim Mansour</strong><small>karim.mansour@vie.tn</small></div></div></td>
                            <td><span class="badge b-pink">Patient</span></td>
                            <td><span class="badge b-red" data-status-badge>Suspendu</span></td>
                            <td>19 oct. 2026</td>
                            <td><div class="row-actions"><button class="btn btn-sm btn-soft" type="button" data-action="suspend">Réactiver</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer Karim Mansour"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                        </tr>
                        <tr data-row data-name="Amine Gharbi" data-role="patient" data-status="actif">
                            <td><div class="person"><span class="avatar av-green">AG</span><div><strong>Amine Gharbi</strong><small>amine.gharbi@vie.tn</small></div></div></td>
                            <td><span class="badge b-pink">Patient</span></td>
                            <td><span class="badge b-green" data-status-badge>Actif</span></td>
                            <td>07 nov. 2026</td>
                            <td><div class="row-actions"><button class="btn btn-sm btn-soft" type="button" data-action="suspend">Suspendre</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer Amine Gharbi"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card">
            <div class="card-head">
                <div>
                    <h2>Nouveaux comptes</h2>
                    <p>Inscriptions par mois sur les 8 derniers mois</p>
                </div>
                <div class="legend"><span><i style="background:var(--blue)"></i>Inscriptions</span><span><i style="background:var(--fuchsia)"></i>Mois en cours</span></div>
            </div>
            <div class="bars" role="img" aria-label="Histogramme des inscriptions mensuelles, en hausse jusqu'en novembre">
                <div class="bar-col"><i style="height:32%"></i><span>Avr</span></div>
                <div class="bar-col"><i style="height:41%"></i><span>Mai</span></div>
                <div class="bar-col"><i style="height:38%"></i><span>Juin</span></div>
                <div class="bar-col"><i style="height:52%"></i><span>Juil</span></div>
                <div class="bar-col"><i style="height:60%"></i><span>Août</span></div>
                <div class="bar-col"><i style="height:68%"></i><span>Sept</span></div>
                <div class="bar-col"><i style="height:82%"></i><span>Oct</span></div>
                <div class="bar-col is-peak"><i style="height:94%"></i><span>Nov</span></div>
            </div>
        </section>
    </div>

    <div class="stack">
        <section class="card">
            <div class="card-head"><h2>Répartition des comptes</h2></div>
            <div class="donut-wrap">
                <div class="donut" role="img" aria-label="Patients 38 %, proches 32 %, médecins 18 %, administrateurs 12 %">
                    <div class="donut-center"><strong>1 248</strong><small>comptes</small></div>
                </div>
                <ul class="donut-legend">
                    <li><i style="background:var(--fuchsia)"></i>Patients<b>38 %</b></li>
                    <li><i style="background:var(--blue)"></i>Proches<b>32 %</b></li>
                    <li><i style="background:var(--violet)"></i>Médecins<b>18 %</b></li>
                    <li><i style="background:var(--marine)"></i>Équipe<b>12 %</b></li>
                </ul>
            </div>
        </section>

        <section class="card">
            <div class="card-head">
                <h2>Médecins à valider</h2>
                <a class="link" href="index.php?page=users">Tout voir</a>
            </div>
            <div class="mini-list">
                <div class="mini" data-row data-name="Dr Kallel">
                    <span class="avatar av-violet">DK</span>
                    <div class="grow"><strong>Dr Kallel</strong><small>Hématologie · N° d'ordre 23456</small></div>
                    <button class="btn btn-sm btn-ok" type="button" data-action="validate">Valider</button>
                </div>
                <div class="mini" data-row data-name="Dr Hamdi">
                    <span class="avatar av-violet">DH</span>
                    <div class="grow"><strong>Dr Hamdi</strong><small>Radiothérapie · N° d'ordre 34567</small></div>
                    <button class="btn btn-sm btn-ok" type="button" data-action="validate">Valider</button>
                </div>
                <div class="mini" data-row data-name="Dr Jlassi">
                    <span class="avatar av-violet">DJ</span>
                    <div class="grow"><strong>Dr Jlassi</strong><small>Chirurgie oncologique · N° d'ordre 45678</small></div>
                    <button class="btn btn-sm btn-ok" type="button" data-action="validate">Valider</button>
                </div>
            </div>
        </section>

        <section class="card">
            <div class="card-head"><h2>Journal d'activité</h2></div>
            <ul class="timeline">
                <li><time>Aujourd'hui, 09:42</time><strong>Médecin validé</strong><p>Dr Ben Salah a rejoint la plateforme.</p></li>
                <li class="blue"><time>Aujourd'hui, 08:15</time><strong>Article publié</strong><p>Dépistage : l'âge de la première mammographie en débat.</p></li>
                <li class="violet"><time>Hier, 17:30</time><strong>Compte suspendu</strong><p>Karim Mansour — signalement en cours d'examen.</p></li>
                <li class="green"><time>Hier, 11:05</time><strong>Nouveau centre</strong><p>Clinique El Manar ajoutée à la carte.</p></li>
            </ul>
        </section>
    </div>
</div>
