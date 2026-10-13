-- ============================================
-- BALLON D'OR 2025 - Base de données
-- ============================================

CREATE DATABASE IF NOT EXISTS ballondor;
USE ballondor;

-- Table des utilisateurs
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    est_admin TINYINT(1) DEFAULT 0,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table des joueurs
CREATE TABLE joueurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    club VARCHAR(100) NOT NULL,
    pays VARCHAR(100) NOT NULL
);

-- Table des avis
CREATE TABLE avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    joueur_id INT NOT NULL,
    utilisateur_id INT NOT NULL,
    note INT NOT NULL,
    commentaire TEXT NOT NULL,
    date_avis DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (joueur_id) REFERENCES joueurs(id) ON DELETE CASCADE,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Compte administrateur
-- pseudo : admin   |   mot de passe : admin123
INSERT INTO utilisateurs (pseudo, email, mot_de_passe, est_admin) VALUES
('admin', 'admin@ballondor.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- Les 18 joueurs candidats au Ballon d'Or 2025
INSERT INTO joueurs (nom, club, pays) VALUES
('Kylian Mbappe', 'Real Madrid', 'France'),
('Erling Haaland', 'Manchester City', 'Norvege'),
('Vinicius Jr', 'Real Madrid', 'Bresil'),
('Lionel Messi', 'Inter Miami', 'Argentine'),
('Lamine Yamal', 'FC Barcelone', 'Espagne'),
('Jude Bellingham', 'Real Madrid', 'Angleterre'),
('Phil Foden', 'Manchester City', 'Angleterre'),
('Pedri', 'FC Barcelone', 'Espagne'),
('Florian Wirtz', 'Bayern Munich', 'Allemagne'),
('Bukayo Saka', 'Arsenal', 'Angleterre'),
('Rodri', 'Manchester City', 'Espagne'),
('Harry Kane', 'Bayern Munich', 'Angleterre'),
('Raphinha', 'FC Barcelone', 'Bresil'),
('Antoine Griezmann', 'Atletico Madrid', 'France'),
('Dani Carvajal', 'Real Madrid', 'Espagne'),
('Ruben Dias', 'Manchester City', 'Portugal'),
('Marcus Rashford', 'FC Barcelone', 'Angleterre'),
('Cristiano Ronaldo', 'Al Nassr', 'Portugal');
