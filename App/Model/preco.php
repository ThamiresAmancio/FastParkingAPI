<?php 


use App\Core\Model;


class Preco {
    public $idPreco;
    public $primeirasHoras;
    public $demaisHoras;


    public function pegarUltimoId() {

        $sql = "SELECT * FROM tblpreco ORDER BY id DESC LIMIT 1";
        $stmt = Model::getConexao()->prepare($sql);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $resultado = $stmt->fetch(PDO::FETCH_OBJ);
            return $resultado;
        }else{
            return [];
        }
    }


    public function buscarPorId($id){

            $sql = " SELECT *FROM tblPreco WHERE id = ?";
    
            $stmt = Model::getConexao()->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
    
    
            if($stmt->rowCount() > 0){
                $precos = $stmt->fetch(PDO::FETCH_OBJ);
                $this->id = $precos->id;
                $this->primeirasHoras = $precos->primeirasHoras;
                $this->demaisHoras = $precos->demaisHoras;

    
                return $this;
            }else{
                return false;
            }
    }

    public function inserir(){
        
        $sql = "INSERT INTO tblPreco (primeirasHoras,demaisHoras) VALUES (?,?)";

        $stmt = Model::getConexao()->prepare($sql);
        $stmt->bindValue(1, $this->primeirasHoras);
        $stmt->bindValue(2, $this->demaisHoras);

        if($stmt->execute()){
            $this->id = Model::getConexao()->lastInsertId();
            return $this;
        }else{
            return false;
        }
    }


    public function editar(){

        $sql = "UPDATE tblPreco SET primeirasHoras = ? , demaisHoras = ?  WHERE id = ? ";

        $stmt = Model::getConexao()->prepare($sql);
        $stmt->bindValue(1,$this->primeirasHoras);
        $stmt->bindValue(2,$this->demaisHoras);
        $stmt->bindValue(3,$this->id);
        return $stmt->execute();

    }

}

?>