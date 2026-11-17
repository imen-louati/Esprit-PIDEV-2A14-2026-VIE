<div class="page-head">
    <div>
        <h1>Utilisateurs</h1>
        <p>Tous les comptes de la plateforme, par rôle et par statut</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-ghost" type="button" data-action="notify" data-message="Export CSV en préparation">Exporter</button>
        <button class="btn btn-primary" type="button" data-action="notify" data-message="Formulaire de création bientôt disponible"><svg class="ic"><use href="#i-plus"/></svg>Ajouter un utilisateur</button>
    </div>
</div>

<div class="grid cols-4">
    <article class="card stat"><div class="stat-icon i-pink"><svg class="ic"><use href="#i-heart"/></svg></div><div><small>Patients</small><strong>474</strong></div></article>
    <article class="card stat"><div class="stat-icon i-blue"><svg class="ic"><use href="#i-users"/></svg></div><div><small>Proches</small><strong>399</strong></div></article>
    <article class="card stat"><div class="stat-icon i-violet"><svg class="ic"><use href="#i-stetho"/></svg></div><div><small>Médecins</small><strong>225</strong></div></article>
    <article class="card stat"><div class="stat-icon i-amber"><svg class="ic"><use href="#i-clock"/></svg></div><div><small>En attente</small><strong>9</strong></div></article>
</div>

<section class="card mt">
    <div class="card-head">
        <div class="chips" data-filter-group="#all-users" role="group" aria-label="Filtrer les comptes">
            <button class="chip is-active" type="button" data-filter="all">Tous</button>
            <button class="chip" type="button" data-filter="patient">Patients</button>
            <button class="chip" type="button" data-filter="proche">Proches</button>
            <button class="chip" type="button" data-filter="medecin">Médecins</button>
            <button class="chip" type="button" data-filter="en_attente">En attente</button>
            <button class="chip" type="button" data-filter="suspendu">Suspendus</button>
        </div>
        <form class="search" action="#" role="search" style="max-width:300px;box-shadow:none;background:var(--bg)">
            <svg class="ic"><use href="#i-search"/></svg>
            <input type="search" placeholder="Nom ou e-mail" aria-label="Filtrer les utilisateurs">
        </form>
    </div>
    <div class="table-wrap">
        <table id="all-users">
            <thead>
                <tr><th>Nom</th><th>Rôle</th><th>Téléphone</th><th>Statut</th><th>Dernière connexion</th><th class="td-end">Actions</th></tr>
            </thead>
            <tbody>
                <tr data-row data-name="Administrateur VIE" data-role="admin" data-status="actif">
                    <td><div class="person"><span class="avatar av-navy">AV</span><div><strong>Administrateur VIE</strong><small>admin@vie.tn</small></div></div></td>
                    <td><span class="badge b-gray">Admin</span></td>
                    <td>+216 20 000 001</td>
                    <td><span class="badge b-green" data-status-badge>Actif</span></td>
                    <td>Aujourd'hui, 09:42</td>
                    <td><div class="row-actions"><button class="btn btn-sm btn-ghost" type="button" data-action="notify" data-message="Compte administrateur protégé">Protégé</button></div></td>
                </tr>
                <tr data-row data-name="Dr Ben Salah" data-role="medecin" data-status="actif">
                    <td><div class="person"><span class="avatar av-violet">BS</span><div><strong>Dr Ben Salah</strong><small>bensalah@vie.tn</small></div></div></td>
                    <td><span class="badge b-violet">Médecin</span></td>
                    <td>+216 20 000 002</td>
                    <td><span class="badge b-green" data-status-badge>Actif</span></td>
                    <td>Aujourd'hui, 08:50</td>
                    <td><div class="row-actions"><button class="btn btn-sm btn-soft" type="button" data-action="suspend">Suspendre</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer Dr Ben Salah"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                </tr>
                <tr data-row data-name="Dr Kallel" data-role="medecin" data-status="en_attente">
                    <td><div class="person"><span class="avatar av-violet">DK</span><div><strong>Dr Kallel</strong><small>kallel@vie.tn</small></div></div></td>
                    <td><span class="badge b-violet">Médecin</span></td>
                    <td>+216 20 000 003</td>
                    <td><span class="badge b-amber" data-status-badge>En attente</span></td>
                    <td>Jamais</td>
                    <td><div class="row-actions"><button class="btn btn-sm btn-ok" type="button" data-action="validate">Valider</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer Dr Kallel"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                </tr>
                <tr data-row data-name="Sarra Trabelsi" data-role="patient" data-status="actif">
                    <td><div class="person"><span class="avatar av-pink">ST</span><div><strong>Sarra Trabelsi</strong><small>sarra.trabelsi@vie.tn</small></div></div></td>
                    <td><span class="badge b-pink">Patient</span></td>
                    <td>+216 20 000 004</td>
                    <td><span class="badge b-green" data-status-badge>Actif</span></td>
                    <td>Aujourd'hui, 07:30</td>
                    <td><div class="row-actions"><button class="btn btn-sm btn-soft" type="button" data-action="suspend">Suspendre</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer Sarra Trabelsi"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                </tr>
                <tr data-row data-name="Karim Mansour" data-role="patient" data-status="suspendu">
                    <td><div class="person"><span class="avatar av-navy">KM</span><div><strong>Karim Mansour</strong><small>karim.mansour@vie.tn</small></div></div></td>
                    <td><span class="badge b-pink">Patient</span></td>
                    <td>+216 20 000 005</td>
                    <td><span class="badge b-red" data-status-badge>Suspendu</span></td>
                    <td>12 nov. 2026</td>
                    <td><div class="row-actions"><button class="btn btn-sm btn-soft" type="button" data-action="suspend">Réactiver</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer Karim Mansour"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                </tr>
                <tr data-row data-name="Mehdi Trabelsi" data-role="proche" data-status="actif">
                    <td><div class="person"><span class="avatar av-blue">MT</span><div><strong>Mehdi Trabelsi</strong><small>mehdi.trabelsi@vie.tn</small></div></div></td>
                    <td><span class="badge b-blue">Proche</span></td>
                    <td>+216 20 000 006</td>
                    <td><span class="badge b-green" data-status-badge>Actif</span></td>
                    <td>Hier, 19:12</td>
                    <td><div class="row-actions"><button class="btn btn-sm btn-soft" type="button" data-action="suspend">Suspendre</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer Mehdi Trabelsi"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                </tr>
                <tr data-row data-name="Amine Gharbi" data-role="patient" data-status="actif">
                    <td><div class="person"><span class="avatar av-green">AG</span><div><strong>Amine Gharbi</strong><small>amine.gharbi@vie.tn</small></div></div></td>
                    <td><span class="badge b-pink">Patient</span></td>
                    <td>+216 20 000 007</td>
                    <td><span class="badge b-green" data-status-badge>Actif</span></td>
                    <td>Hier, 10:03</td>
                    <td><div class="row-actions"><button class="btn btn-sm btn-soft" type="button" data-action="suspend">Suspendre</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer Amine Gharbi"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                </tr>
                <tr data-row data-name="Leïla Ben Youssef" data-role="proche" data-status="actif">
                    <td><div class="person"><span class="avatar av-blue">LB</span><div><strong>Leïla Ben Youssef</strong><small>leila.by@vie.tn</small></div></div></td>
                    <td><span class="badge b-blue">Proche</span></td>
                    <td>+216 20 000 008</td>
                    <td><span class="badge b-green" data-status-badge>Actif</span></td>
                    <td>14 nov. 2026</td>
                    <td><div class="row-actions"><button class="btn btn-sm btn-soft" type="button" data-action="suspend">Suspendre</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer Leïla Ben Youssef"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
