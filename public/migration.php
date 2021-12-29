<?php

$db = new PDO('mysql:host=localhost;dbname=cms', 'root', '1234w');
$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$table = "body";
try {
     $sql ="CREATE TABLE IF NOT EXISTS $table(
     `id` INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
     `parent_id` int( 11 ) NOT NULL DEFAULT 0, 
     `name` varchar( 100 ) NOT NULL,
     `title` varchar( 100 ) NOT NULL,
     `description` text DEFAULT NULL,
     `slug` varchar( 100 ) NOT NULL,
     `status` tinyint( 1 ) NOT NULL DEFAULT 0,
     `more` tinyint( 1 ) NOT NULL DEFAULT 0,
     `more_link` varchar( 50 )  DEFAULT NULL,
     `more_label` varchar( 50 )  DEFAULT 'read more',
     `align_center` tinyint( 1 ) NOT NULL DEFAULT 0,
     `bg_pattern` tinyint( 1 ) NOT NULL DEFAULT 0,
     `layout` tinyint( 1 ) NOT NULL DEFAULT 1,
     `inheritance` tinyint( 1 ) NOT NULL DEFAULT 0,
     `photo1` varchar( 50 )  DEFAULT NULL,
     `photo2` varchar( 50 ) DEFAULT NULL,
     `photo3` varchar( 50 ) DEFAULT NULL,
     `photo4` varchar( 50 ) DEFAULT NULL,
     `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP);" ;
     $db->exec($sql);
     print("Created table $table.<br>");

} catch(PDOException $e) {
    echo $e->getMessage();
}


$table = "menu";
try {
     $sql ="CREATE TABLE IF NOT EXISTS $table(
     `id` INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
     `parent_id` INT( 11 ) NOT NULL DEFAULT 0, 
     `title` varchar( 100 ) NOT NULL,
     `slug` varchar( 100 ) NOT NULL,
     `status` tinyint( 1 ) NOT NULL DEFAULT 0,
     `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP);" ;
     $db->exec($sql);
     print("Created table $table.<br>");

} catch(PDOException $e) {
    echo $e->getMessage();
}


$table = "users";
try {
     $sql ="CREATE TABLE IF NOT EXISTS $table(
     `id` INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
     `username` varchar( 50 ) NOT NULL,
     `email` varchar( 50 ) NOT NULL,
     `type` varchar( 50 ) NOT NULL DEFAULT 'user',
     `password` varchar( 255 ) NOT NULL,
     `verified` tinyint( 1 ) NOT NULL DEFAULT 0,
     `token` varchar( 255 )  DEFAULT NULL,
     `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP);" ;
     $db->exec($sql);
     print("Created table $table.<br>");

} catch(PDOException $e) {
    echo $e->getMessage();
}


$table = "banner";
try {
     $sql ="CREATE TABLE IF NOT EXISTS $table(
     `id` INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
     `parent_id` int( 11 ) NOT NULL DEFAULT 0, 
     `name` varchar( 100 ) NOT NULL,
     `title` varchar( 100 ) NOT NULL,
     `description` text DEFAULT NULL,
     `status` tinyint( 1 ) NOT NULL DEFAULT 0,
     `layout` tinyint( 1 ) NOT NULL DEFAULT 1,
     `photo1` varchar( 50 )  DEFAULT NULL,
     `more_link` varchar( 50 )  DEFAULT NULL,
     `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP);" ;
     $db->exec($sql);
     print("Created table $table.<br>");

} catch(PDOException $e) {
    echo $e->getMessage();
}


$table = "box";
try {
     $sql ="CREATE TABLE IF NOT EXISTS $table(
     `id` INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
     `parent_id` int( 11 ) NOT NULL DEFAULT 0, 
     `name` varchar( 100 ) NOT NULL,
     `description` text DEFAULT NULL,
     `status` tinyint( 1 ) NOT NULL DEFAULT 0,
     `photo1` varchar( 50 )  DEFAULT NULL,
     `bg_photo` tinyint( 1 ) NOT NULL DEFAULT 0,
     `more_link` varchar( 50 )  DEFAULT NULL,
     `label_link` varchar( 50 )  DEFAULT 'learn more',
     `color_bg` varchar( 10 )  DEFAULT '#000000',
     `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP);" ;
     $db->exec($sql);
     print("Created table $table.<br>");

} catch(PDOException $e) {
    echo $e->getMessage();
}


//insert menu

try {
    $st = $db->prepare("
        INSERT INTO menu(
            id, 
            parent_id, 
            title, 
            slug, 
            status, 
            created_at
        )VALUES(?,?,?,?,?,?)");

    $st->execute([1, 0, 'Gabinet', 'gabinet', 1, '2021-07-11 20:23:44']);
    $st->execute([2, 0, 'Usługi', 'uslugi', 1, '2021-07-11 20:23:51']);
    $st->execute([3, 0, 'Cennik', 'cennik', 1, '2021-07-11 20:24:09']);
    $st->execute([4, 0, 'Kontakt', 'kontakt', 1, '2021-07-11 20:24:16']);
    $st->execute([5, 1, 'O nas', 'gabinet/o-nas', 1, '2021-07-11 20:24:31']);
    $st->execute([6, 1, 'Personel', 'gabinet/personel', 1, '2021-07-11 20:24:54']);
    $st->execute([7, 2, 'Protetyka', 'uslugi/protetyka', 1, '2021-07-11 20:25:15']);
    $st->execute([8, 2, 'Implanty', 'uslugi/implanty', 1, '2021-07-11 20:25:25']);
    $st->execute([9, 2, 'Ortodonta', 'uslugi/ortodonta', 1, '2021-07-11 20:25:40']);
    $st->execute([10, 2, 'Wybielanie', 'uslugi/wybielanie', 1, '2021-07-11 20:25:50']);
    $st->execute([11, 0, 'Galeria', 'galeria', 1, '2021-12-04 15:40:30']);
 
    
    print("Insert menu success.<br>");

} catch(PDOException $e) {
    echo $e->getMessage();
}


//insert body

try {
    $st = $db->prepare(" 
        INSERT INTO body(
            id, 
            parent_id, 
            name, 
            title, 
            description, 
            slug, 
            status, 
            more, 
            photo1, 
            photo2, 
            photo3, 
            created_at
        )VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");

    $st->execute([
        1, 5, 'O nas', 'Nasza misja', 'ukujemy i uświadamiamy naszym pacjentom znaczenie codziennej profilaktyki. Tworzymy doświadczony zesp&oacute;ł obejmujący&nbsp;opieką stomatologiczną pacjent&oacute;w&nbsp;w każdym wieku.&nbsp; Gabinet dentystyczny obsługują najlepsi lekarze dentyści, specjalizujący się w kluczowych dziedzinach stomatologii. Pragniemy, aby efektem naszego leczenia był piękny uśmiech, zdrowe i mocne zęby o naturalnym wyglądzie.<br />\r\n<br />\r\nSzczeg&oacute;lne miejsce w naszym gabinecie dentystycznym zajmują dzieci.&nbsp; Najmłodszych pacjent&oacute;w traktujemy wyjątkowo i otaczamy szczeg&oacute;lną opieką. Do każdego pacjenta podchodzimy indywidualnie, traktując go z najwyższą uwagą i troską.&nbsp; Gwarantujemy Państwu intymność, komfort i wygodę podczas leczenia w gabinecie dentystycznym.<br />\r\n<br />\r\nNaszą misją jest leczenie pacjent&oacute;w, kt&oacute;rego efektem jest piękny i zdrowy uśmiech. Każdemu pacjentowi zapewniamy profesjonalne leczenie, gwarantując najwyższe standardy i rezultaty. Każdy lekarz dentysta w naszym gabinecie, przykłada dużą wagę do edukacji stomatologicznej pacjent&oacute;w w szczeg&oacute;lności tych najmłodszych.', 'o-nas', 1, 0, 'YG4gyAKmC5.jpg', NULL, NULL, '2021-07-11 20:33:14'
    ]);

    print("Insert body success.<br>");

} catch(PDOException $e) {
    echo $e->getMessage();
}


//insert users

try {
    $st = $db->prepare(" 
        INSERT INTO users(
            id, 
            username, 
            email, 
            type, 
            password, 
            verified, 
            token, 
            created_at
        )VALUES(?,?,?,?,?,?,?,?)");

    $st->execute([
        1, 'admin', 'kontakt@wojciech-kondraciuk.pl', 'admin', '$2y$10$BHFmQPA1U.pkoFZuaydCgu2DJYA0WPldlvtLmNhAYsH.y7GCqZzce', 1, '773198ec4caea652505347d4b2de433f272c2dda493413fb9598c21dda29af06239d6cac415a8dc910fef76b52def21a2c0b', '2021-09-23 20:20:05'
    ]);

    print("Insert users success.<br>");

} catch(PDOException $e) {
    echo $e->getMessage();
}


//insert box

try {
    $st = $db->prepare(" 
        INSERT INTO box(
            id, 
            parent_id, 
            name, 
            description, 
            status, 
            photo1, 
            more_link, 
            label_link,
            color, 
            created_at
        )VALUES(?,?,?,?,?,?,?,?,?,?)");

    $st->execute([
        1, 5, 'O nas', 'Nasza misja', 'txt', 'o-nas', 1, 0, 'YG4gyAKmC5.jpg', NULL, NULL, '2021-07-11 20:33:14'
    ]);

    print("Insert box success.<br>");

} catch(PDOException $e) {
    echo $e->getMessage();
}