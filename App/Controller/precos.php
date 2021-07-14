<?php


session_start();

use App\Core\Controller;


class Precos extends Controller {

    public function index(){

        $PrecoModel = $this->model("Preco");

        $preco = $PrecoModel->pegarUltimoId();

        if(!$preco){
            http_response_code(204);
            exit;
        }

        echo json_encode($preco, JSON_UNESCAPED_UNICODE);
    }

    public function find($id){

        $PrecoModel = $this->model("Preco");

        $preco = $PrecoModel->buscarPorId($id);

        if($preco){
            echo json_encode($preco, JSON_UNESCAPED_UNICODE);
        }else{
            http_response_code(404);
            echo json_encode(["erro" => "preço não encontrada"]);
        }
    }


    public function store(){

        $json = file_get_contents("php://input");
        $novoPreco = json_decode($json);

        $PrecoModel = $this->model("Preco");

        $PrecoModel->primeirasHoras = $novoPreco->primeirasHoras;
        $PrecoModel->demaisHoras = $novoPreco->demaisHoras;

        $PrecoModel = $PrecoModel->inserir();
        if($PrecoModel){
            http_response_code(201);
            echo json_encode($PrecoModel, JSON_UNESCAPED_UNICODE);
        }else{
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao cadastrar preços"]);
        }
    }

    public function update ($id) {

        $json = file_get_contents("php://input");
        $atualizarPreco = json_decode($json);

        $PrecoModel = $this->model("Preco");
        $PrecoModel = $PrecoModel->buscarPorId($id);
        
        if(!$PrecoModel){
            http_response_code(404);
            echo json_encode(["erro" => "Preços não encontrada"]);
            exit;
        }

        $PrecoModel->primeirasHoras = $atualizarPreco->primeirasHoras;
        $PrecoModel->demaisHoras = $atualizarPreco->demaisHoras;

        if($PrecoModel->editar()){
            http_response_code(204);
            echo json_encode(["sucess" => "Sucesso ao editar Preços"]);
        }else{
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao editar Preços"]);
        }
    }

}

?>