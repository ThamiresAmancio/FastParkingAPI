<?php
session_start();

use App\Core\Controller;

class Clientes extends Controller{

    public function index(){

         $clienteModel = $this->model("Cliente");

         $cliente = $clienteModel->listarTodas();

         echo json_encode($cliente, JSON_UNESCAPED_UNICODE);
    }

    public function find($idRegistroCliente){

        $clienteModel = $this->model("Cliente");

        $cliente = $clienteModel->buscarPorId($idRegistroCliente);

        if($cliente){
            echo json_encode($cliente, JSON_UNESCAPED_UNICODE);
        }else{
            http_response_code(404);
            echo json_encode(["erro" => "Cliente não encontrada"]);
        }
    }


    public function store(){

        $json = file_get_contents("php://input");
        $novoCliente = json_decode($json);

        $clienteModel = $this->model("Cliente");

        $clienteModel->nome = $novoCliente->nome;
        $clienteModel->placa = $novoCliente->placa;
        // $clienteModel->dataHoraEntrada = $novoCliente->dataHoraEntrada;
        
        $precoModel = $this->model("Preco");
        $ultimoPreco = $precoModel->pegarUltimoId();
        $clienteModel->idPreco = $ultimoPreco->id;

        $clienteModel = $clienteModel->inserir();
        if($clienteModel){
            http_response_code(201);
            echo json_encode($clienteModel, JSON_UNESCAPED_UNICODE);
        }else{
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao cadastrar cliente"]);
        }
    }


    public function update ($id) {

        $json = file_get_contents("php://input");
        $atualizarCliente = json_decode($json);

        $clienteModel = $this->model("Cliente");
        $clienteModel = $clienteModel->buscarPorId($id);
        
        if(!$clienteModel){
            http_response_code(404);
            echo json_encode(["erro" => "Cliente não encontrada"]);
            exit;
        }

        $clienteModel->nome = $atualizarCliente->nome;
        $clienteModel->placa = $atualizarCliente->placa;

        if($clienteModel->editar()){
            http_response_code(204);
            echo json_encode(["sucess" => "Sucesso ao editar cliente"]);
        }else{
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao editar cliente"]);
        }
    }


    public function delete($id){

        $clienteModel = $this->model("Cliente");
        $clienteModel = $clienteModel->buscarPorId($id);


        if (!$clienteModel) {
            http_response_code(404);
            echo json_encode(["erro" => "Cliente não encontrado"]);
            exit();
        }
        
        $clienteModel->id = $id;
        $clienteModel = $this->calculoPrecos($clienteModel);
      
        if($clienteModel->saida()){
            http_response_code(204);
        }else{
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao excluir categoria"]);
        }
    }


    private function calculoPrecos($clienteModel) {

        $dataEntrada = DateTime::createFromFormat("Y-m-d H:i:s", $clienteModel->dataHoraEntrada);
        $dataSaida = new DateTime();
        $intervalo = $dataSaida->diff($dataEntrada);
        $horas = 0;

        if($intervalo->d > 0){

            $horas = $horas + $intervalo->d * 24;
        }
        $horas = $horas + $intervalo->h;

        if($intervalo->i > 10){
            $horas +=1;
        }
        $precoModel = $precoModel = $this->model("Preco");
        $precoModel->buscarPorId($clienteModel->idPreco);

        $clienteModel->valorPagar = $precoModel->primeirasHoras;
        $horas--;
        if($horas > 0){
            $clienteModel->valorPagar += $precoModel->demaisHoras * $horas;
        }
        $clienteModel->dataHoraSaida = $dataSaida->format("Y-m-d H:i:s");

        return $clienteModel;        
    }
}
