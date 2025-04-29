
<?php 
require_once './config.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dour tounes</title>
    <link rel="stylesheet" href="./public/CSS/dourtounes.css" />
  </head>

  <body>
    <header>
      <div class="logo">
        <img src="./images/logos/logo1.png" alt="Logo Tourisme" />
      </div>
      <nav>
        <ul>
          <li>
            <a href="#">Accueil</a>
          </li>
          <li><a href="#panoramas">destinations</a></li>
          <li><a href="#activites">Activités</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </nav>
      <div class="guide">
        <a href="./public/connexion.php">Connexion</a>
      </div>
    </header>      


    <section class="hero">
      <div class="text-content">
        <h1>Le Portail du tourisme intérieur tunisien</h1>
        <p>
          Dour Tounes est une plateforme Tunisien qui vise à promouvoir le
          tourisme intérieur et la destination Tunisie avec ses différents
          panoramas : Terre, Mer, Pierre et Désert pour les tunisiens résidents
          en Tunisie et désireux de découvrir les richesses d’un pays qui nous
          proposera encore et toujours de nouvelles surprises...
        </p>
      </div>
      <div class="reseaux-sociaux">
        <a href="#" class="icone facebook"></a>
        <a href="#" class="icone instagram"></a>
        <a href="#" class="icone email"></a>
      </div>
    </section>

    <div class="back">
      <section class="panoramas">
        <h2>Panoramas</h2>
        <div class="destinations">
          <div class="destination">
            <img src="./public/images/monument/kairaoun1.jpg" alt="Pierre" />
            <h3>Monuments</h3>
            <a href="./destinations/monument.html">Découvrir les destinations</a>
          </div>
          <div class="destination">
            <img src="./images/desert/tozeur2.jpg" alt="Desert" />
            <h3>Desert</h3>
            <a href="./public/destinations/desert.html">Découvrir les destinations</a>
          </div>
          <div class="destination">
            <img src="./public/images/nature/zaghouan.jpg" alt="Terre" />
            <h3>Terre</h3>
            <a href="./public/destinations/nature.html"
              >Découvrir les destinations</a>
            </div>
          <div class="destination">
            <img src="./public/images/mer/dj.jpg" alt="Mer" />
            <h3>Mer</h3>
            <a href="./public/destinations/mer.html">Découvrir les destinations</a>
          </div>
        </div>
      </section>

      <section class="container">
        <!-- Bouton Contact -->
        <div class="contact-box">
          <h3>Contactez votre guide directement !</h3>
        </div>

        <!-- Contenu Principal -->
        <div class="content">
          <div class="text">
            <h3>
              <strong>Dour Tounes :</strong> des voyages authentiques, sans
              intermédiaire.
            </h3>

            <p>Trouver le guide parfait n’a jamais été aussi simple.</p>
            <br />
            <p>
              Sur <strong>Dour Tounes</strong>, vous entrez directement en
              contact avec des guides locaux passionnés, prêts à partager leurs
              connaissances et leur amour de leur région.
            </p>
            <p>
              Solo, en famille ou entre amis, découvrez une expérience unique,
              taillée sur mesure, et vivez des moments mémorables grâce à nos
              experts.
            </p>
            <br />
            <p>
              <strong
                >Rejoignez-nous dès aujourd’hui et laissez Dour Tounes vous
                ouvrir les portes du monde.</strong
              >
            </p>
          </div>

          <!-- Images animées -->
          <div class="image-section">
            <div class="image-box">
              <img src="./images/monument/jemaa.jpg" alt="196 Destinations" />
              <div class="overlay">
                196 <br />
                Destinations
              </div>
            </div>

            <div class="image-box">
              <img src="./images/mer/sidi bousaid.jpg" alt="1148 Guides touristiques" />

              <div class="overlay">
                1148 <br />
                Guides touristiques
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="cont">
        <div class="text-section">
          <h2><span>Êtes-vous un guide touristique ?</span></h2>
          <p><strong>Faites-vous connaître dès maintenant !</strong></p>
          <p>
            Rejoignez <strong>Dour Tounes</strong> et attirez plus de voyageurs
            en quelques minutes.
          </p>
          <p>
            <strong>Créez votre propre page</strong> et soyez directement
            contacté par des touristes du monde entier.
          </p>
          <ul>
            <li>
              ✔
              <strong>Mettez en avant vos services et votre expertise.</strong>
            </li>
            <li>
              ✔ <strong>Aucun intermédiaire, 100 % de vos revenus.</strong>
            </li>
            <li>
              ✔
              <strong
                >Paiement unique : seulement 29 € pour une inscription illimitée
                !</strong
              >
            </li>
          </ul>
          <a href="public/formulaires/form_guide.php"
            ><button class="cta-button">JE M'INSCRIS MAINTENANT</button></a
          >
        </div>
        <div class="image-section">
          <img src="./images/logos/Register.jpg" alt="Guide Touristique" />
        </div>
      </section>

      <section class="avis-section">
        <div class="avis-header">
          <h2>Vos avis</h2>
        </div>
        <p class="avis-description">
          Les touristes qui ont eu la joie de rencontrer nos guides ont laissé
          un avis.
        </p>

        <div class="avis-container">
          <div class="avis-card">
            <div class="user-info">
              <div class="avatar">D</div>
              <div>
                <h3>David K'ouas</h3>
                <p class="date">2023-12-30</p>
              </div>
              <img class="google-icon" src="./images/logos/google-icon.png" alt="Google" />
            </div>
            <div class="stars">⭐⭐⭐⭐⭐</div>
            <p class="avis-text">
              Super moment de visite de porto avec Ricardo, un vrai passionné de
              sa ville et des gens en général.
            </p>
            <a href="#" class="read-more">Lire la suite</a>
          </div>

          <div class="avis-card">
            <div class="user-info">
              <div class="avatar">V</div>
              <div>
                <h3>Valerie Ollivier</h3>
                <p class="date">2023-12-05</p>
              </div>
              <img class="google-icon" src="./images/logos/google-icon.png" alt="Google" />
            </div>
            <div class="stars">⭐⭐⭐⭐⭐</div>
            <p class="avis-text">
              Elle est vraiment passionnée par l'histoire de sa région et fière
              de sa ville.
            </p>
            <a href="#" class="read-more">Lire la suite</a>
          </div>

          <div class="avis-card">
            <div class="user-info">
              <div class="avatar">P</div>
              <div>
                <h3>Pablo Carrascosa</h3>
                <p class="date">2023-09-24</p>
              </div>
              <img class="google-icon" src="./images/logos/google-icon.png" alt="Google" />
            </div>
            <div class="stars">⭐⭐⭐⭐⭐</div>
            <p class="avis-text">
              I warmly recommend Martha Tours and Trips. With her team of
              drivers, you will explore Angola safely!
            </p>
            <a href="avis.html" class="read-more"
              >Lire la suite</a
            >
          </div>
        </div>

        <a href="/public/formulaires/avis.php"
          ><button class="btn-avis">LAISSEZ VOTRE AVIS</button></a
        >
      </section>

      <iframe
        width="560"
        height="315"
        src="https://www.youtube.com/embed/H__i5LKFVXg?si=EmAqaJbV-arMZrvy"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen
      ></iframe>
    </div>

    <footer>
      <div class="footer-container">
        <div class="footer-section about">
          <h2>Dour Tounes</h2>
          <p>
            Rejoignez notre plateforme et attirez plus de voyageurs en quelques
            minutes. Partagez votre passion et faites découvrir la Tunisie.
          </p>
        </div>

        <div class="footer-section links">
          <h3>Liens utiles</h3>
          <ul>
            <li><a href="#">Accueil</a></li>
            <li><a href="#">Nos guides</a></li>
            <li><a href="#">Avis</a></li>
            <li><a href="#">Contact</a></li>
          </ul>
        </div>

        <div class="footer-section contact">
          <h3>Contact</h3>
          <p>
            Email :
            <a href="mailto:contact@dourtounes.com"
              >contact@dour-tounes.com</a
            >
          </p>
          <p>Téléphone : +33 1 23 45 67 89</p>
          <p>Adresse : 123, Rue des Voyages, Tunis, Tunis</p>
        </div>

        <div class="footer-section social">
          <h3>Suivez-nous</h3>
          <div class="social-icons">
            <a href="#"><img src="./images/logos/fb.webp" alt="Facebook" /></a>
            <a href="#"><img src="./images/logos/mail.png" alt="email" /></a>
            <a href="#"><img src="./images/logos/insta.jpeg" alt="Instagram" /></a>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2025 Dour Tounes | Tous droits réservés.</p>
      </div>
    </footer>
  </body>
</html>
