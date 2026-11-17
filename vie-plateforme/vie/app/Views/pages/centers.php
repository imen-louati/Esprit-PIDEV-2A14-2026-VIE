<div class="page-head">
    <div>
        <h1>Centres de soins</h1>
        <p>Centres spécialisés en oncologie, services proposés et équipements disponibles</p>
    </div>
    <div class="page-actions">
        <div class="chips" role="group" aria-label="Filtrer par ville">
            <button class="chip is-active" type="button">Toutes les villes</button>
            <button class="chip" type="button">Tunis</button>
            <button class="chip" type="button">Ariana</button>
            <button class="chip" type="button">Sousse</button>
        </div>
    </div>
</div>

<div class="grid main-side">
    <div class="stack">
        <section class="card">
            <div class="card-head">
                <div><h2>Carte des centres</h2><p>4 centres référencés en Tunisie</p></div>
                <span class="badge b-blue">Mise à jour aujourd'hui</span>
            </div>
            <div class="map-box tall" role="img" aria-label="Carte schématique des centres de soins à Tunis, Ariana et Sousse">
                <div class="pin" style="left:46%;top:28%"><svg class="ic"><use href="#i-map"/></svg><span>Salah Azaiez</span></div>
                <div class="pin" style="left:54%;top:16%"><svg class="ic"><use href="#i-map"/></svg><span>Abderrahmen Mami</span></div>
                <div class="pin blue" style="left:38%;top:40%"><svg class="ic"><use href="#i-map"/></svg><span>El Manar</span></div>
                <div class="pin" style="left:70%;top:82%"><svg class="ic"><use href="#i-map"/></svg><span>Farhat Hached</span></div>
                <div class="map-legend">Tunis · Ariana · Sousse</div>
            </div>
        </section>

        <div class="grid cols-2">
            <article class="card center-card">
                <div class="center-top">
                    <div class="center-ic i-pink"><svg class="ic"><use href="#i-map"/></svg></div>
                    <div><h3>Institut Salah Azaiez</h3><small>Tunis · Bab Saadoun</small></div>
                </div>
                <div class="tags"><span class="tag">Oncologie médicale</span><span class="tag">Radiothérapie</span><span class="tag">Chirurgie</span></div>
                <dl class="kv">
                    <div><dt>Équipements</dt><dd>IRM, scanner, TEP-scan</dd></div>
                    <div><dt>Téléphone</dt><dd>+216 71 000 001</dd></div>
                </dl>
                <div class="row-actions" style="justify-content:flex-start"><button class="btn btn-sm btn-soft" type="button" data-action="notify" data-message="Modification du centre bientôt disponible">Modifier</button><button class="btn btn-sm btn-ghost" type="button" data-action="notify" data-message="Fiche du centre ouverte">Voir la fiche</button></div>
            </article>
            <article class="card center-card">
                <div class="center-top">
                    <div class="center-ic i-blue"><svg class="ic"><use href="#i-map"/></svg></div>
                    <div><h3>Hôpital Abderrahmen Mami</h3><small>Ariana · Pavillon Ibn Khaldoun</small></div>
                </div>
                <div class="tags"><span class="tag">Pneumologie</span><span class="tag">Oncologie thoracique</span></div>
                <dl class="kv">
                    <div><dt>Équipements</dt><dd>Scanner, bronchoscopie</dd></div>
                    <div><dt>Téléphone</dt><dd>+216 71 000 002</dd></div>
                </dl>
                <div class="row-actions" style="justify-content:flex-start"><button class="btn btn-sm btn-soft" type="button" data-action="notify" data-message="Modification du centre bientôt disponible">Modifier</button><button class="btn btn-sm btn-ghost" type="button" data-action="notify" data-message="Fiche du centre ouverte">Voir la fiche</button></div>
            </article>
            <article class="card center-card">
                <div class="center-top">
                    <div class="center-ic i-violet"><svg class="ic"><use href="#i-map"/></svg></div>
                    <div><h3>CHU Farhat Hached</h3><small>Sousse · Avenue Ibn El Jazzar</small></div>
                </div>
                <div class="tags"><span class="tag">Hématologie</span><span class="tag">Oncologie pédiatrique</span></div>
                <dl class="kv">
                    <div><dt>Équipements</dt><dd>Chambres stériles, cytométrie</dd></div>
                    <div><dt>Téléphone</dt><dd>+216 73 000 003</dd></div>
                </dl>
                <div class="row-actions" style="justify-content:flex-start"><button class="btn btn-sm btn-soft" type="button" data-action="notify" data-message="Modification du centre bientôt disponible">Modifier</button><button class="btn btn-sm btn-ghost" type="button" data-action="notify" data-message="Fiche du centre ouverte">Voir la fiche</button></div>
            </article>
            <article class="card center-card">
                <div class="center-top">
                    <div class="center-ic i-green"><svg class="ic"><use href="#i-map"/></svg></div>
                    <div><h3>Clinique El Manar</h3><small>Tunis · Rue Ibn Sina</small></div>
                </div>
                <div class="tags"><span class="tag">Sénologie</span><span class="tag">Dépistage</span><span class="tag">Soins de support</span></div>
                <dl class="kv">
                    <div><dt>Équipements</dt><dd>Mammographie 3D, IRM mammaire</dd></div>
                    <div><dt>Téléphone</dt><dd>+216 71 000 004</dd></div>
                </dl>
                <div class="row-actions" style="justify-content:flex-start"><button class="btn btn-sm btn-soft" type="button" data-action="notify" data-message="Modification du centre bientôt disponible">Modifier</button><button class="btn btn-sm btn-ghost" type="button" data-action="notify" data-message="Fiche du centre ouverte">Voir la fiche</button></div>
            </article>
        </div>
    </div>

    <section class="card">
        <div class="card-head"><h2>Ajouter un centre</h2></div>
        <form class="form" action="#" method="post">
            <div class="field"><label for="c-name">Nom du centre</label><input id="c-name" type="text" placeholder="Institut régional d'oncologie"></div>
            <div class="form-row">
                <div class="field"><label for="c-city">Ville</label><input id="c-city" type="text" placeholder="Sfax"></div>
                <div class="field"><label for="c-phone">Téléphone</label><input id="c-phone" type="tel" placeholder="+216 74 000 000"></div>
            </div>
            <div class="field"><label for="c-address">Adresse</label><input id="c-address" type="text" placeholder="Rue, quartier"></div>
            <div class="form-row">
                <div class="field"><label for="c-lat">Latitude</label><input id="c-lat" type="text" placeholder="34.7406"></div>
                <div class="field"><label for="c-lng">Longitude</label><input id="c-lng" type="text" placeholder="10.7603"></div>
            </div>
            <div class="field"><label for="c-services">Services médicaux</label><textarea id="c-services" placeholder="Oncologie médicale, radiothérapie…"></textarea></div>
            <div class="field"><label for="c-equipment">Équipements</label><input id="c-equipment" type="text" placeholder="IRM, scanner, TEP-scan"></div>
            <button class="btn btn-primary btn-block" type="button" data-action="notify" data-message="Centre enregistré">Enregistrer le centre</button>
        </form>
    </section>
</div>
