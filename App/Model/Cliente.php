<?php

use App\Core\Model;

class Cliente{

    public $id;
    public $nome;
    public $placa;
    public $dataHoraEntrada;
    public $dataHoraSaida;
    public $valorPagar;
    public $idPreco;


    public function listarTodas(){

        $sql = " SELECT *  FROM tblClientes";
        $stmt = Model::getConexao()->prepare($sql);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $resultado;
        }else{
            return [];
        }
    }



    public function buscarPorId($id){

        $sql = " SELECT * FROM tblClientes WHERE id = ? ";

        $stmt = Model::getConexao()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $clientes = $stmt->fetch(PDO::FETCH_OBJ);

            $this->id = $clientes->id;
            $this->nome = $clientes->nome;
            $this->placa = $clientes->placa;
            $this->dataHoraEntrada = $clientes->dataHoraEntrada;
            $this->dataHoraSaida = $clientes->dataHoraSaida;
            $this->valorPagar = $clientes->nome;
            $this->idPreco = $clientes->idPreco;

            return $this;
        }else{
            return false;
        }
    }
 
     public function inserir(){
         
         $sql = "INSERT INTO tblClientes (nome,placa,dataHoraEntrada,idPreco) VALUES (?,?,current_timestamp(),?)";

         $stmt = Model::getConexao()->prepare($sql);
         $stmt->bindValue(1, $this->nome);
         $stmt->bindValue(2, $this->placa);
         $stmt->bindValue(3, $this->idPreco);


         if($stmt->execute()){
             $this->id = Model::getConexao()->lastInsertId();
             return $this;
         }else{
             return [];
         }
     }
      public function editar(){
         $sql = "UPDATE tblClientes SET nome = ? , placa = ?  WHERE id = ? ";
         $stmt = Model::getConexao()->prepare($sql);
         $stmt->bindValue(1,$this->nome);
         $stmt->bindValue(2,$this->placa);
         $stmt->bindValue(3,$this->id);
         return $stmt->execute();
     }
     public function saida(){
        $sql = "UPDATE tblClientes SET dataHoraSaida = current_timestamp(), valorPagar = ?  WHERE id = ? ";
        $stmt = Model::getConexao()->prepare($sql);
        $stmt->bindValue(1,$this->valorPagar);
        $stmt->bindValue(2,$this->id);
        return $stmt->execute();
    }
}