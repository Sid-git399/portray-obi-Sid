<?php require __DIR__ . '/../layout/header.php'; ?>

<section class="hero-obi">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1>Consultez et telechargez vos etats en quelques clics</h1>
                <p class="lead mt-3">
                    Le portail Reporting connecte votre navigateur a Oracle BI Publisher pour generer
                    vos rapports bancaires en Excel, PDF ou Word &mdash; sans jamais avoir besoin
                    de naviguer dans l'interface Oracle BI.
                </p>
                <a href="/login" class="btn btn-hero mt-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Acceder au portail
                </a>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <i class="bi bi-bar-chart-line-fill" style="font-size: 12rem; color: rgba(255,255,255,.15);"></i>
            </div>
        </div>
    </div>
</section>

<section class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: var(--obi-navy);">Pourquoi ce portail ?</h2>
        <p class="text-muted">Une couche simple au-dessus d'Oracle BI, pensee pour les utilisateurs metier.</p>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-signpost-2-fill"></i></div>
                <h5 class="fw-bold">Acces simplifie</h5>
                <p class="text-muted mb-0">
                    Plus besoin de connaitre les Subject Areas ni l'interface OBI :
                    un catalogue clair de rapports pretes a l'emploi.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-sliders"></i></div>
                <h5 class="fw-bold">Filtres intuitifs</h5>
                <p class="text-muted mb-0">
                    Choisissez une plage de dates ou un critere simple ; le portail
                    construit lui-meme la requete envoyee a Oracle BI.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-download"></i></div>
                <h5 class="fw-bold">Export en un clic</h5>
                <p class="text-muted mb-0">
                    Excel, PDF ou Word : le fichier est genere par Oracle BI Publisher
                    et livre directement dans votre navigateur.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="container my-5 py-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: var(--obi-navy);">Comment ca marche ?</h2>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="step-card text-center">
                <div class="step-number mx-auto">1</div>
                <h5 class="fw-bold">Se connecter</h5>
                <p class="text-muted mb-0">Avec votre compte portail, independant d'Oracle BI.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="step-card text-center">
                <div class="step-number mx-auto">2</div>
                <h5 class="fw-bold">Choisir un rapport</h5>
                <p class="text-muted mb-0">Parcourez le catalogue, filtrez par categorie ou mot-cle.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="step-card text-center">
                <div class="step-number mx-auto">3</div>
                <h5 class="fw-bold">Telecharger</h5>
                <p class="text-muted mb-0">Choisissez le format et recuperez votre fichier genere par OBI.</p>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>
