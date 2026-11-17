<div class="page-head">
    <div>
        <h1>Actualités</h1>
        <p>Rédigez, publiez et classez les articles par type de cancer</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary" type="button" data-action="notify" data-message="Nouvel article créé"><svg class="ic"><use href="#i-plus"/></svg>Nouvel article</button>
    </div>
</div>

<div class="grid cols-4">
    <article class="card stat"><div class="stat-icon i-green"><svg class="ic"><use href="#i-check"/></svg></div><div><small>Publiés</small><strong>36</strong></div></article>
    <article class="card stat"><div class="stat-icon i-amber"><svg class="ic"><use href="#i-note"/></svg></div><div><small>Brouillons</small><strong>3</strong></div></article>
    <article class="card stat"><div class="stat-icon i-blue"><svg class="ic"><use href="#i-pulse"/></svg></div><div><small>Lectures ce mois</small><strong>8 420</strong></div></article>
    <article class="card stat"><div class="stat-icon i-pink"><svg class="ic"><use href="#i-heart"/></svg></div><div><small>Catégories</small><strong>6</strong></div></article>
</div>

<div class="grid main-side mt">
    <section class="card">
        <div class="card-head">
            <div class="chips" data-filter-group="#news-table" role="group" aria-label="Filtrer les articles">
                <button class="chip is-active" type="button" data-filter="all">Tous</button>
                <button class="chip" type="button" data-filter="publie">Publiés</button>
                <button class="chip" type="button" data-filter="brouillon">Brouillons</button>
            </div>
        </div>
        <div class="table-wrap">
            <table id="news-table">
                <thead>
                    <tr><th>Article</th><th>Catégorie</th><th>Statut</th><th>Date</th><th class="td-end">Actions</th></tr>
                </thead>
                <tbody>
                    <tr data-row data-name="Dépistage : l'âge de la première mammographie" data-role="publie" data-status="publie">
                        <td><strong>Dépistage : l'âge de la première mammographie en débat</strong></td>
                        <td><span class="badge b-pink">Sein</span></td>
                        <td><span class="badge b-green">Publié</span></td>
                        <td>12 oct. 2026</td>
                        <td><div class="row-actions"><button class="btn btn-sm btn-soft" type="button" data-action="notify" data-message="Article dépublié">Dépublier</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer l'article"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                    </tr>
                    <tr data-row data-name="Immunothérapie : nouveaux résultats" data-role="publie" data-status="publie">
                        <td><strong>Immunothérapie : de nouveaux résultats chez les patients avancés</strong></td>
                        <td><span class="badge b-blue">Poumon</span></td>
                        <td><span class="badge b-green">Publié</span></td>
                        <td>05 oct. 2026</td>
                        <td><div class="row-actions"><button class="btn btn-sm btn-soft" type="button" data-action="notify" data-message="Article dépublié">Dépublier</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer l'article"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                    </tr>
                    <tr data-row data-name="Test sanguin du dépistage précoce" data-role="publie" data-status="publie">
                        <td><strong>Test sanguin : dépistage précoce à l'étude</strong></td>
                        <td><span class="badge b-violet">Colorectal</span></td>
                        <td><span class="badge b-green">Publié</span></td>
                        <td>28 sept. 2026</td>
                        <td><div class="row-actions"><button class="btn btn-sm btn-soft" type="button" data-action="notify" data-message="Article dépublié">Dépublier</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer l'article"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                    </tr>
                    <tr data-row data-name="Thérapies ciblées chez l'enfant" data-role="brouillon" data-status="brouillon">
                        <td><strong>Thérapies ciblées : meilleure survie chez l'enfant</strong></td>
                        <td><span class="badge b-amber">Leucémie</span></td>
                        <td><span class="badge b-gray">Brouillon</span></td>
                        <td>—</td>
                        <td><div class="row-actions"><button class="btn btn-sm btn-ok" type="button" data-action="notify" data-message="Article publié">Publier</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer l'article"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                    </tr>
                    <tr data-row data-name="Protection solaire" data-role="brouillon" data-status="brouillon">
                        <td><strong>Protection solaire : les gestes qui réduisent le risque de mélanome</strong></td>
                        <td><span class="badge b-green">Peau</span></td>
                        <td><span class="badge b-gray">Brouillon</span></td>
                        <td>—</td>
                        <td><div class="row-actions"><button class="btn btn-sm btn-ok" type="button" data-action="notify" data-message="Article publié">Publier</button><button class="btn btn-sm btn-danger" type="button" data-action="delete" aria-label="Supprimer l'article"><svg class="ic"><use href="#i-trash"/></svg></button></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="card">
        <div class="card-head"><h2>Rédiger un article</h2></div>
        <form class="form" action="#" method="post">
            <div class="field"><label for="a-title">Titre</label><input id="a-title" type="text" placeholder="Titre de l'article"></div>
            <div class="field">
                <label for="a-cat">Catégorie</label>
                <select id="a-cat">
                    <option>Sein</option><option>Poumon</option><option>Leucémie</option><option>Colorectal</option><option>Prostate</option><option>Peau</option>
                </select>
            </div>
            <div class="field"><label for="a-excerpt">Résumé</label><input id="a-excerpt" type="text" placeholder="Une phrase pour la page d'accueil"></div>
            <div class="field"><label for="a-body">Contenu</label><textarea id="a-body" placeholder="Rédigez l'article…"></textarea></div>
            <div class="row-actions" style="justify-content:flex-start">
                <button class="btn btn-ghost" type="button" data-action="notify" data-message="Brouillon enregistré">Enregistrer le brouillon</button>
                <button class="btn btn-primary" type="button" data-action="notify" data-message="Article publié">Publier</button>
            </div>
        </form>
    </section>
</div>
