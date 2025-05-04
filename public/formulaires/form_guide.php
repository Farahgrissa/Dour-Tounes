<?php
session_start(); // Démarrer la session pour stocker les messages

require_once '../../config.php';
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Affiche les erreurs SQL

$errors = [];
$confirmation = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $civilite = $_POST['civilite'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $langue_maternelle = $_POST['langue_maternelle'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $region = $_POST['region'] ?? '';
    $langue1 = $_POST['langue1'] ?? '';
    $langue2 = $_POST['langue2'] ?? '';
    $presentation = $_POST['presentation'] ?? '';
    $message = $_POST['message'] ?? '';

    // Validation des mots de passe
    if (strlen($password) < 8) {
        $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
    }
    if ($password !== $password_confirm) {
        $errors['password_confirm'] = "Les mots de passe ne correspondent pas.";
    }

    // Vérification si l'email existe déjà
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM guides WHERE email = ?");
    $stmt->execute([$email]);
    $emailExists = $stmt->fetchColumn();

    if ($emailExists) {
        $errors['email'] = "Cet email est déjà utilisé.";
    }

    // Validation du numéro de téléphone (format international)
    if (empty($telephone)) {
        $errors['telephone'] = "Le téléphone est requis.";
    } elseif (!preg_match("/^\+(216|33|1)[0-9]{8,10}$/", $telephone)) {
        $errors['telephone'] = "Le numéro de téléphone doit être au format international (ex : +216XXXXXXXX).";
    }

    // Traitement si aucune erreur
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Gestion des fichiers
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $photo = $_FILES['photo'] ?? null;
        $cartePro = $_FILES['carte_pro'] ?? null;

        $photoRelPath = $carteRelPath = "";

        // Upload de la photo
        if ($photo && $photo['error'] === 0) {
            $photoRelPath = $targetDir . basename($photo["name"]);
            move_uploaded_file($photo["tmp_name"], $photoRelPath);
        } else {
            $errors['photo'] = "Erreur lors de l'upload de la photo.";
        }

        // Upload de la carte professionnelle
        if ($cartePro && $cartePro['error'] === 0) {
            $carteRelPath = $targetDir . basename($cartePro["name"]);
            move_uploaded_file($cartePro["tmp_name"], $carteRelPath);
        } else {
            $errors['carte_pro'] = "Erreur lors de l'upload de la carte professionnelle.";
        }

        // Remplacer les valeurs vides par NULL
        $telephone = empty($telephone) ? NULL : $telephone;
        $region = empty($region) ? NULL : $region;
        $langue2 = empty($langue2) ? NULL : $langue2;
        $message = empty($message) ? NULL : $message;

        // Insertion en base de données si tout est OK
        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO guides 
                (civilite, prenom, nom, email, password, langue_maternelle, telephone, region, langue1, langue2, presentation, photo, carte_pro, message) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->execute([
                    $civilite, $prenom, $nom, $email, $hashedPassword,
                    $langue_maternelle, $telephone, $region,
                    $langue1, $langue2, $presentation, $photoRelPath, $carteRelPath, $message
                ]);

                // Message de confirmation
                $_SESSION['confirmation'] = "Inscription réussie ! Merci pour votre confiance.";
                header("Location: ../connexion.php");
                exit;
            } catch (PDOException $e) {
                $_SESSION['errors'][] = "Erreur SQL : " . $e->getMessage();
            }
        }
    }


}

?>

<!-- Redirection vers la page d'inscription ou affichage des erreurs -->
<?php
if (isset($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
    unset($_SESSION['errors']); // Supprime les erreurs après affichage
}

if (isset($_SESSION['confirmation'])) {
    echo "<p style='color:green;'>".$_SESSION['confirmation']."</p>";
    unset($_SESSION['confirmation']); // Supprime le message de confirmation après affichage
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Guide Touristique</title>
    <link rel="stylesheet" href="../CSS/formulaireguide.css">
</head>
<body>

    
    <header>
        <div class="logo">
            <img src="../images/logos/logo.png" alt="Logo Tourisme">
        </div>
        <nav>
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="#">Panoramas</a></li>
                <li><a href="#">Activités</a></li>
                <li><a href="#">Contact</a></li>
                
            </ul>
        </nav>
        <div class="guide">
            <a href="../connexion.php">connexion</a>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1><span>Inscription guide touristique</span></h1>
            <p>Rejoignez GuideYourTrip</p>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
    

    <section class="inscription">
        <div>
            <h2>Attirez plus de voyageurs et développez votre activité !</h2>
            <p class="tarif">💰 <strong>Paiement unique :</strong> seulement <span class="highlight">29 €</span> pour une inscription à vie !</p>
            <p class="details">Aucun abonnement. Aucun frais caché.</p>

            <h3>Pourquoi rejoindre Dour Tounes ?</h3>
            <ol>
                <li><strong>Visibilité mondiale :</strong> Présentez vos services à une large audience.</li>
                <li> <strong>Gagnez en notoriété :</strong> Mettez en avant vos compétences et expériences.</li>
            </ol>
        </div>
    </section>
    </section>
    <br>
    <br><br><br><br> <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <div class="container">
    <h2>Les champs marqués d'une <span style="color: red;">*</span> sont obligatoires</h2>

    <?php if (isset($confirmation)) : ?>
        <p style="color: green; font-weight: bold;"><?= htmlspecialchars($confirmation) ?></p>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="civilite" class="required">Civilité</label>
            <input type="radio" name="civilite" value="Mr." required> Mr.
            <input type="radio" name="civilite" value="Mme" required> Mme
        </div>

        <div class="form-group">
            <label for="prenom" class="required">Prénom</label>
            <input type="text" name="prenom" required>
        </div>

        <div class="form-group">
            <label for="nom" class="required">Nom</label>
            <input type="text" name="nom" required>
        </div>

        <div class="form-group">
            <label for="email" class="required">Email de contact pour les touristes</label>
            <input type="email" name="email" required>
        </div>


        <div class="form-group">
            <label for="langue_maternelle" class="required">Langue maternelle</label>
            <input type="text" name="langue_maternelle" required>
        </div>

        <div class="form-group">
            <label for="telephone">N° téléphone de contact</label>
            <input type="tel" name="telephone" placeholder="Saisir indicatif du pays">
        </div>

        <div class="form-group">
            <label for="region">Région ou ville de vos visites</label>
            <input type="text" name="region">
        </div>

        <div class="form-group">
            <label for="langue" class="required">Langues de visite</label>
            <input type="text" name="langue1" required placeholder="Saisir au moins une langue de visite">
            <input type="text" name="langue2" placeholder="Langue #2">
        </div>

        <div class="form-file">
            <label for="presentation" class="required">Présentation</label>
            <textarea name="presentation" rows="2" minlength="10" placeholder="Écrire quelques lignes de présentation qui apparaîtront sur votre page dédiée" required></textarea>
        </div>
        <br><br>

        <div class="form-file">
            <label for="photo" class="required">Envoyez-nous une photo du guide</label>
            <input type="file" name="photo" required>
        </div>
        <br><br>

        <div class="form-file">
            <label for="carte_pro" class="required">Envoyez votre carte professionnelle de guide</label>
            <input type="file" name="carte_pro" id="carte_pro" accept="image/*,application/pdf" required>
        </div>
        <br><br>

        <div class="form-file">
            <label for="message">Message à notre attention</label>
            <textarea id="message" name="message" placeholder="Vous pouvez saisir ici un message à notre attention." rows="3"></textarea>
        </div>
        <br><br>

        <div class="form-group">
            <label for="password" class="required">Créer un mot de passe</label>
            <input type="password" name="password" required minlength="8">
            <?php if (isset($errors['password'])): ?>
                <span class="error"><?= htmlspecialchars($errors['password']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password_confirm" class="required">Confirmer le mot de passe</label>
            <input type="password" name="password_confirm" required>
            <?php if (isset($errors['password_confirm'])): ?>
                <span class="error"><?= htmlspecialchars($errors['password_confirm']) ?></span>
            <?php endif; ?>
        </div>

        <button type="submit">Soumettre</button>
    </form>
</div>

<footer>
    <div class="footer-container">
        <div class="footer-section about">
            <h2>Dour Tounes</h2>
            <p>Rejoignez notre plateforme et attirez plus de voyageurs en quelques minutes. Partagez votre passion et faites découvrir la Tunisie.</p>
        </div>

        <div class="footer-section links">
            <h3>Liens utiles</h3>
            <ul>
                <li><a href="/dourtounes/index.php">Accueil</a></li>
                <li><a href="#">À propos</a></li>
                <li><a href="#">Nos guides</a></li>
                <li><a href="#">Avis</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>

        <div class="footer-section contact">
            <h3>Contact</h3>
            <p>Email : <a href="mailto:contact@dour_tounes.tn">contact@dour_tounes.tn</a></p>
            <p>Téléphone : +33 1 23 45 67 89</p>
            <p>Adresse : 123, Rue des Voyages, Tunis, Tunis</p>
        </div>

        <div class="footer-section social">
            <h3>Suivez-nous</h3>
            <div class="social-icons">
                <a href="#"><img src="../images/logos/fb.webp" alt="Facebook"></a>
                <a href="#"><img src="../images/logos/mail.png" alt="email"></a>
                <a href="#"><img src="../images/logos/insta.jpeg" alt="Instagram"></a>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 Dour Tounes | Tous droits réservés.</p>
    </div>
</footer>

    <script>
       // Classe principale de validation du formulaire
class FormValidator {
    constructor(formSelector) {
        // Sélectionne le formulaire par son sélecteur (peut être une classe ou un ID)
        this.form = document.querySelector(formSelector);
        // Stocke les erreurs dans un objet pour le suivi
        this.errors = {};
        
        // Si le formulaire existe, initialiser la validation
        if (this.form) {
            this.init();
        } else {
            console.error(`Formulaire "${formSelector}" non trouvé`);
        }
    }

    // Initialise toutes les validations et écouteurs d'événements
    init() {
        this.setupEventListeners();
        this.addCustomValidation();
        this.setupFileValidation();
    }

    // Configure les écouteurs d'événements de base
    setupEventListeners() {
        // Intercepte la soumission du formulaire
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        
        // Pour chaque champ du formulaire
        this.form.querySelectorAll('input, select, textarea').forEach(input => {
            // Efface les erreurs lors de la saisie
            input.addEventListener('input', () => this.clearError(input));
            
            // Validation spécifique pour les cases à cocher et boutons radio
            if (input.type === 'checkbox' || input.type === 'radio') {
                input.addEventListener('change', () => {
                    if (input.type === 'radio') {
                        this.validateRadio(input.name);
                    } else {
                        this.validateCheckbox(input);
                    }
                });
            }
        });
    }

    // Ajoute des validations personnalisées pour certains champs
    addCustomValidation() {
        // Validation du téléphone
        const phoneInput = this.form.querySelector('[name="telephone"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', (e) => {
                this.formatPhoneNumber(e.target);
                this.validatePhoneNumber(e.target);
            });
        }

        // Validation de l'email en temps réel
        const emailInput = this.form.querySelector('[name="email"]');
        if (emailInput) {
            emailInput.addEventListener('input', () => this.validateEmail(emailInput));
        }

        // Validation de la présentation (longueur minimale)
        const presentationInput = this.form.querySelector('[name="presentation"]');
        if (presentationInput) {
            presentationInput.addEventListener('input', () => this.validateMinLength(presentationInput, 10));
        }
    }

    // Configure la validation des fichiers
    setupFileValidation() {
        const photoInput = this.form.querySelector('[name="photo"]');
        const carteProInput = this.form.querySelector('#carte_pro');
        
        if (photoInput) {
            photoInput.addEventListener('change', () => this.validateFileInput(photoInput, ['image/jpeg', 'image/png', 'image/jpg']));
        }
        
        if (carteProInput) {
            carteProInput.addEventListener('change', () => this.validateFileInput(carteProInput, ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf']));
        }
    }

    // Valide un champ de type fichier
    validateFileInput(input, allowedTypes) {
        this.clearError(input);
        
        if (input.files.length === 0 && input.required) {
            this.showError(input, 'Veuillez sélectionner un fichier');
            return false;
        }
        
        if (input.files.length > 0) {
            const file = input.files[0];
            
            // Vérifie le type de fichier
            if (allowedTypes && !allowedTypes.includes(file.type)) {
                this.showError(input, 'Format de fichier non supporté');
                return false;
            }
            
            // Vérifie la taille du fichier (max 5MB)
            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                this.showError(input, 'Le fichier est trop volumineux (max 5MB)');
                return false;
            }
        }
        
        return true;
    }

    // Valide le format de l'email
    validateEmail(input) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(input.value)) {
            this.showError(input, 'Veuillez entrer une adresse email valide');
            return false;
        }
        return true;
    }

    // Valide la longueur minimale d'un champ texte
    validateMinLength(input, minLength) {
        if (input.value.length < minLength) {
            this.showError(input, `Minimum ${minLength} caractères requis (${input.value.length} actuellement)`);
            return false;
        }
        return true;
    }

    document.getElementById("myForm").addEventListener("submit", function(e) {
    e.preventDefault();  // Empêche la soumission immédiate pour traitement

    let phoneInput = document.getElementById("telephone").value;

    // Supprimer tous les caractères non numériques sauf le +
    let formattedPhone = phoneInput.replace(/\D/g, '');

    // Ajouter l'indicatif +216 si le numéro est pour la Tunisie
    if (formattedPhone.length === 8) {
        formattedPhone = '+216' + formattedPhone; // Si c'est un numéro local à 8 chiffres
    }

    // Vérifier que le numéro de téléphone a bien un format international valide
    if (!/^\+216\d{8}$/.test(formattedPhone)) {
        alert("Le numéro de téléphone n'est pas valide.");
        return;
    }

    // Remplacer la valeur du champ téléphone avec le numéro formaté
    document.getElementById("telephone").value = formattedPhone;

    // Soumettre le formulaire après validation
    this.submit();
});
    // Valide une case à cocher obligatoire
    validateCheckbox(input) {
        if (input.required && !input.checked) {
            this.showError(input, 'Ce champ est obligatoire');
            return false;
        }
        return true;
    }

    // Valide un groupe de boutons radio
    validateRadio(name) {
        const radioGroup = this.form.querySelectorAll(`[name="${name}"]`);
        if (radioGroup.length === 0) return true;
        
        const isChecked = Array.from(radioGroup).some(radio => radio.checked);
        if (!isChecked && radioGroup[0].required) {
            this.showError(radioGroup[0], 'Veuillez sélectionner une option');
            return false;
        }
        
        this.clearError(radioGroup[0]);
        return true;
    }

    // Affiche un message d'erreur sous le champ concerné
    showError(input, message) {
        // Supprime d'abord toute erreur existante
        this.clearError(input);
        
        // Trouve le parent correct pour positionner l'erreur
        let container = input.closest('.form-group') || input.closest('.form-file') || input.parentNode;
        
        // Crée un élément div pour le message d'erreur
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.style.color = '#dc3545';
        errorDiv.style.fontSize = '14px';
        errorDiv.style.marginTop = '5px';
        errorDiv.style.display = 'flex';
        errorDiv.style.alignItems = 'center';
        errorDiv.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" style="margin-right: 5px;">
                <path fill="#dc3545" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
            ${message}
        `;
        
        // Ajoute l'erreur au conteneur
        container.appendChild(errorDiv);
        
        // Ajoute une classe d'erreur au champ et style la bordure
        input.classList.add('error');
        input.style.borderColor = '#dc3545';
        
        // Enregistre l'erreur dans notre objet de suivi
        this.errors[input.name || input.id] = true;
    }

    // Supprime le message d'erreur d'un champ
    clearError(input) {
        // Trouve le parent correct
        let container = input.closest('.form-group') || input.closest('.form-file') || input.parentNode;
        
        // Cherche le message d'erreur à supprimer
        const errorDiv = container.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.remove();
            input.classList.remove('error');
            input.style.borderColor = '';
            delete this.errors[input.name || input.id];
        }
    }

    // Gère la soumission du formulaire
    async handleSubmit(e) {
        e.preventDefault();
        
        // Validation finale de tous les champs
        const isValid = this.validateAllFields();
        if (!isValid) return;

        // Préparation pour l'envoi AJAX
        const formData = new FormData(this.form);
        const submitBtn = this.form.querySelector('button[type="submit"]');
        
        try {
            // Désactive le bouton et affiche un spinner
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <div class="spinner" style="display: inline-block; width: 20px; height: 20px; border: 3px solid rgba(255,255,255,.3); border-radius: 50%; border-top-color: #fff; animation: spin 1s ease-in-out infinite;"></div>
                Envoi en cours...
            `;
            
            // Ajoute un style pour l'animation du spinner
            const style = document.createElement('style');
            style.textContent = `
                @keyframes spin {
                    to { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(style);

            // Simulation d'appel API (à remplacer par un vrai appel fetch)
            await new Promise(resolve => setTimeout(resolve, 2000));
            
            // Affiche le message de succès et réinitialise le formulaire
            this.showSuccessMessage();
            this.form.reset();
        } catch (error) {
            // Affiche une erreur en cas d'échec
            this.showGlobalError(`Erreur d'envoi : ${error.message}`);
        } finally {
            // Rétablit le bouton
            submitBtn.disabled = false;
            submitBtn.textContent = 'Soumettre';
        }
    }

    // Affiche une erreur globale (non liée à un champ spécifique)
    showGlobalError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message global-error';
        errorDiv.style.backgroundColor = '#f8d7da';
        errorDiv.style.color = '#721c24';
        errorDiv.style.padding = '10px';
        errorDiv.style.borderRadius = '5px';
        errorDiv.style.marginBottom = '20px';
        errorDiv.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" style="margin-right: 5px;">
                <path fill="#721c24" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
            ${message}
        `;
        
        // Supprime d'abord toute erreur globale existante
        const existingError = this.form.querySelector('.global-error');
        if (existingError) existingError.remove();
        
        this.form.prepend(errorDiv);
        setTimeout(() => errorDiv.remove(), 5000);
    }

    // Valide tous les champs du formulaire
    validateAllFields() {
        let isValid = true;
        
        // Validation des champs requis
        this.form.querySelectorAll('[required]').forEach(input => {
            if (input.type === 'file') {
                if (!this.validateFileInput(input, null)) {
                    isValid = false;
                }
            }
            else if (input.type === 'radio') {
                // Les boutons radio sont validés en groupe
                if (!this.validateRadio(input.name)) {
                    isValid = false;
                }
            }
            else if (input.type === 'checkbox' && !input.checked) {
                this.showError(input, 'Ce champ est obligatoire');
                isValid = false;
            }
            else if (input.tagName === 'SELECT' && input.value === '') {
                this.showError(input, 'Veuillez sélectionner une option');
                isValid = false;
            }
            else if ((input.type !== 'checkbox' && input.type !== 'radio' && input.type !== 'file') && 
                     input.value.trim() === '') {
                this.showError(input, 'Ce champ est obligatoire');
                isValid = false;
            }
        });

        // Validation spécifique des champs
        const emailInput = this.form.querySelector('[name="email"]');
        if (emailInput && emailInput.value) {
            isValid = this.validateEmail(emailInput) && isValid;
        }

        const phoneInput = this.form.querySelector('[name="telephone"]');
        if (phoneInput && phoneInput.value) {
            isValid = this.validatePhoneNumber(phoneInput) && isValid;
        }

        const presentationInput = this.form.querySelector('[name="presentation"]');
        if (presentationInput && presentationInput.required) {
            isValid = this.validateMinLength(presentationInput, 10) && isValid;
        }

        return isValid && Object.keys(this.errors).length === 0;
    }

    // Affiche un message de succès après soumission
    showSuccessMessage() {
        const successDiv = document.createElement('div');
        successDiv.className = 'success-message';
        successDiv.style.backgroundColor = '#d4edda';
        successDiv.style.color = '#155724';
        successDiv.style.padding = '15px';
        successDiv.style.borderRadius = '5px';
        successDiv.style.marginBottom = '20px';
        successDiv.style.display = 'flex';
        successDiv.style.alignItems = 'center';
        successDiv.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="margin-right: 10px;">
                <path fill="#155724" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
            <div>
                <h3 style="margin: 0 0 5px 0;">Inscription réussie !</h3>
                <p style="margin: 0;">Nous avons bien reçu vos informations.</p>
            </div>
        `;
        
        // Supprime d'abord tout message de succès existant
        const existingSuccess = this.form.querySelector('.success-message');
        if (existingSuccess) existingSuccess.remove();
        
        this.form.prepend(successDiv);
        
        // Scroll vers le haut pour voir le message
        window.scrollTo({ top: this.form.offsetTop - 100, behavior: 'smooth' });
        
        // Supprime le message après 5 secondes
        setTimeout(() => successDiv.remove(), 5000);
    }
}

// Initialisation du validateur une fois le DOM chargé
document.addEventListener('DOMContentLoaded', function() {
    const formValidator = new FormValidator('form');
});
    </script>
</body>
</html>