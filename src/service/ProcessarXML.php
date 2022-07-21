<?php

require('src/config/ConectaDB.php');

class ProcessarXML
{

    private $conectarDB;

    public function __construct(){
        $this->conectarDB = new ConectaDB();
    }

    public function cadastrarNF($numeroNF, $dataEmissaoNF, $valorNF, $destinatario, $enderecoEmitente){

        $sql = "INSERT INTO nf (
            nf_numero, 
            nf_data_emissao,
            nf_valor,
            destinatario_cpf,
            destinatario_cnpj,
            destinatario_nome,
            destinatario_logradouro,
            destinatario_endereco_nro,
            destinatario_bairro,
            destinatario_cod_municipio,
            destinatario_ds_municipio,
            destinatario_uf,
            destinatario_cep,
            destinatario_cod_pais,
            destinatario_ind_ie,
            destinatario_ie,
            destinatario_email
        ) 
        VALUES (
            '$numeroNF', 
            '$dataEmissaoNF', 
            '$valorNF' ,
            '$destinatario->CPF',
            '$destinatario->CNPJ',
            '$destinatario->xNome',
            '$enderecoEmitente->xLgr',
            '$enderecoEmitente->nro',
            '$enderecoEmitente->xBairro',
            '$enderecoEmitente->cMun',
            '$enderecoEmitente->xMun',
            '$enderecoEmitente->UF',
            '$enderecoEmitente->CEP',
            '$enderecoEmitente->cPais',
            '$destinatario->indIEDest',
            '$destinatario->IE',
            '$destinatario->email'
        )";

        if ($this->conectarDB->conexaoDB()->query($sql) === TRUE) {
            echo self::retornaMsg("<p class='msg f-color-sucess'>NFe cadastrado na base de dados com sucesso!</p>");

        } else {
            echo self::retornaMsg(
                "<p class='msg f-color-erro'>
                          Ocorreu um erro ao enviar para a base de dados! 
                          <br> Error: " . $sql . "
                    </p><br>" . $this->conectarDB->conexaoDB()->error
            );
        }
        $this->conectarDB->conexaoDB()->close();
    }


    public function lerXML($xml){

        $numeroNFe = isset($xml->NFe->infNFe["Id"]) ? $xml->NFe->infNFe["Id"] : $xml->infNFe["Id"];
        $dataEmissaoNFe = new DateTime(isset($xml->NFe->infNFe["Id"]) ? $xml->NFe->infNFe->ide->dhEmi : $xml->infNFe->ide->dhEmi);
        $nf_valor = isset($xml->NFe->infNFe["Id"]) ? $xml->NFe->infNFe->total->ICMSTot->vNF : $xml->infNFe->total->ICMSTot->vNF;

        $dataFormatada = $dataEmissaoNFe->format('Y/m/d');

        foreach(isset($xml->NFe->infNFe["Id"]) ? $xml->NFe->infNFe->dest : $xml->infNFe->dest as $dest) {
            $dadosNF['destinatario'] = $dest;
        }

        foreach(isset($xml->NFe->infNFe["Id"]) ? $xml->NFe->infNFe->emit : $xml->infNFe->emit as $emit) {
            $dadosNF['emitente'] = $emit;
        }

        foreach(isset($xml->NFe->infNFe["Id"]) ? $xml->NFe->infNFe->emit->enderEmit : $xml->infNFe->emit->enderEmit as $enderEmit) {
            $dadosNF['enderecoEmitente'] = $enderEmit;
        }

        $dadosNF['numeroNF'] = $numeroNFe;
        $dadosNF['dataEmissaoNF'] = $dataFormatada;
        $dadosNF['valorNF'] = $nf_valor;

        return $dadosNF;
    }

    public function verificaExtensao($nomeArquivo){
        $extensao = explode(".",$nomeArquivo);
        return end($extensao);
    }

    public function retornaMsg($msg){
        return $msg;
    }
}