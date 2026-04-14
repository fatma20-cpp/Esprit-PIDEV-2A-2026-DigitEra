-- =====================================================
-- VÉRIFIER ET CORRIGER LA TABLE RECLAMATION
-- =====================================================

-- 1. D'abord, vérifier la structure de la table
DESCRIBE reclamation;

-- 2. Si la colonne 'message' n'existe pas ou n'est pas TEXT, exécutez:
ALTER TABLE reclamation MODIFY COLUMN message TEXT;

-- 3. Vérifier les données (voir les messages sauvegardés):
SELECT id_reclamation, nom, prenom, message FROM reclamation LIMIT 10;

-- 4. Si certaines lignes ont NULL dans le message et vous voulez les voir en détail:
SELECT id_reclamation, id_client, nom, prenom, email, sujet, type_probleme, message, date_creation 
FROM reclamation 
WHERE id_client = '76511045' 
ORDER BY id_reclamation DESC;

-- =====================================================
-- À EXÉCUTER DANS PHPMYADMIN:
-- =====================================================
-- 1. Allez à http://localhost/phpmyadmin
-- 2. Sélectionnez la base "service_client"
-- 3. Onglet "SQL"
-- 4. Copier-coller les commandes ci-dessus
-- 5. Cliquer "Exécuter"
-- =====================================================
