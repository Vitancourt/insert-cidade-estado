<?php

ini_set("display_errors",0);
ini_set("display_startup_erros",0);
error_reporting(E_ALL);

require_once("database/Database.php");

$arquivo = file_get_contents('cidades-estados.json');

$json = json_decode($arquivo);

$database = Database::conectar();

foreach($json as $registro){

    foreach($registro as $r){
        
        $sql = 
            "insert into estado
                (estado)
            values
                (:estado)
        ";

        $consulta = $database->prepare($sql);

        $estado = $r->nome;

        echo "<hr>----ESTADO ".$estado." ----<hr>";

        $consulta->bindParam(':estado', $estado, PDO::PARAM_STR);
        
        try{
        
            $consulta->execute();
            
            if($consulta->rowCount() > 0){
        
                $ultimo_id = $database->lastInsertId();
                
            }
            
        }catch(PDOException $e){
        
            echo $e->getMessage();
                
        }

        foreach($r->cidades as $cid)	{
            $sql = 
                "insert into cidade
                    (cidade, estado_id)
                values
                    (:cidade, :estado_id)
            ";

            $consulta = $database->prepare($sql);

            $cidade = $cid;
            $estado_id = $ultimo_id;

            echo $cidade."<br>";

            $consulta->bindParam(':cidade', $cidade, PDO::PARAM_STR);
            $consulta->bindParam(':estado_id', $estado_id, PDO::PARAM_INT);
            
            try{
            
                $consulta->execute();
                
            }catch(PDOException $e){
            
                echo $e->getMessage();
                    
            }    
        }


    }

}

?>
