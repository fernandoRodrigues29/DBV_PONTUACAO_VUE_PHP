<?php

// Caminho do arquivo .db
$dbFile = __DIR__ . '/desbravadores.db';

try {
    // Conexão com SQLite
    $pdo = new PDO("sqlite:$dbFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query para buscar nomes das tabelas
//     $sql = "CREATE TABLE cantinho (
// 	id integer primary key AUTOINCREMENT,
// 	id_dbv integer,
// 	id_unit integer,
// 	presenca char(1),
// 	uniforme char(1),
// 	atividades char(1),
// 	hino char(1) 
// )";

    $sql = "INSERT INTO cantinho 
    (id_dbv, id_unit, presenca, uniforme, atividades, hino)
    VALUES 
    (1, 2, 'S', 'N', 'S', 'S');";

    $stmt = $pdo->exec($sql);
    echo "Tabela inserida com sucesso!";
    

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}