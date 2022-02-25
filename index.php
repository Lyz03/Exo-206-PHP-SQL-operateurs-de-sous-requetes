<?php

/**
 * Commencez par importer le fichier sql live.sql via PHPMyAdmin.
 * 1. Sélectionnez tous les utilisateurs.
 * 2. Sélectionnez tous les articles.
 * 3. Sélectionnez tous les utilisateurs qui parlent de poterie dans un article.
 * 4. Sélectionnez tous les utilisateurs ayant au moins écrit deux articles.
 * 5. Sélectionnez l'utilisateur Jane uniquement s'il elle a écris un article ( le résultat devrait être vide ! ).
 *
 * ( PS: Sélectionnez, mais affichez le résultat à chaque fois ! ).
 */

$db = new PDO('mysql:host=localhost;dbname=live;charset=utf8', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// part 1
$stmt = $db->prepare("SELECT * FROM user");

if ($stmt->execute()) {
    echo '<pre>';
    print_r($stmt->fetchAll());
    echo '</pre>';
}


// part 2
$stmt = $db->prepare("SELECT * FROM article");

if ($stmt->execute()) {
    echo '<pre>';
    print_r($stmt->fetchAll());
    echo '</pre>';
}

// part 3
$stmt = $db->prepare("
        SELECT username FROM user
            WHERE id = ANY (SELECT user_fk FROM article WHERE contenu LIKE '%poterie%')
");

if ($stmt->execute()) {
    echo '<pre>';
    print_r($stmt->fetchAll());
    echo '</pre>';
}

// part 4
$stmt = $db->prepare("
        SELECT username FROM user
            WHERE id = ANY (SELECT user_fk FROM article GROUP BY user_fk HAVING count(user_fk) >= 2)
");

if ($stmt->execute()) {
    echo '<pre>';
    print_r($stmt->fetchAll());
    echo '</pre>';
}

// part 5
$stmt = $db->prepare("
        SELECT username FROM user
            WHERE username LIKE 'jane%' AND id = ANY (SELECT user_fk FROM article)
");

if ($stmt->execute()) {
    echo '<pre>';
    print_r($stmt->fetchAll());
    echo '</pre>';
}