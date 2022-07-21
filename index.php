<?php
    require('src/service/ProcessarXML.php');
    $xmlDoc = new DOMDocument();
    $processarXML = new ProcessarXML();
?>

<html lang="PT-BR">
    <head>
        <title> Teste - José Fernando </title>
        <link rel="stylesheet" href="src/public/assets/style.css"></head>
    <body>

        <?php if ($_SERVER['REQUEST_METHOD'] == 'GET') { ?>

            <div class="upload bg-f-upload" align="center">
                <h1>Escolha um arquivo .XML <br> para realizar a importação</h1>
                <form class="margin-top-4" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" enctype="multipart/form-data">
                    <input class="margin-right-1" id="doc" type="file" name="doc" required/>
                    <input type="submit" value="Importar"/>
                </form>
            </div>

        <?php } else {

            if($processarXML->verificaExtensao($_FILES['doc']['name']) == "xml"){

                if (isset($_FILES['doc']) && ($_FILES['doc']['error'] == UPLOAD_ERR_OK)) {

                    $xml = simplexml_load_file($_FILES['doc']['tmp_name']);
                    $dadosXML = $processarXML->lerXML($xml); ?>

                        <table id="customers" align="center">
                            <tr>
                                <th>Descrição</th>
                                <th>Dados Da Nota</th>
                            </tr>
                            <tr>
                                <td>Número da NF</td>
                                <td><?=$dadosXML['numeroNF'];?></td>
                            </tr>
                            <tr>
                                <td>Data de Emissão</td>
                                <td><?=$dadosXML['dataEmissaoNF'];?></td>
                            </tr>
                            <tr>
                                <td>CPF do Destinatário</td>
                                <td><?=$dadosXML['destinatario']->CPF;?></td>
                            </tr>
                            <tr>
                                <td>CNPJ do Destinatário</td>
                                <td><?=$dadosXML['destinatario']->CNPJ;?></td>
                            </tr>
                            <tr>
                                <td>Nome do Destinatário</td>
                                <td><?=$dadosXML['destinatario']->xNome;?></td>
                            </tr>
                            <tr>
                                <td>Lograduro do Destinatário</td>
                                <td><?=$dadosXML['enderecoEmitente']->xLgr;?></td>
                            </tr>
                            <tr>
                                <td>Nro. de Endereço do Destinatário</td>
                                <td><?=$dadosXML['enderecoEmitente']->nro;?></td>
                            </tr>
                            <tr>
                                <td>Bairro do Destinatário</td>
                                <td><?=$dadosXML['enderecoEmitente']->xBairro;?></td>
                            </tr>
                            <tr>
                                <td>Código de Municipio do Destinatário</td>
                                <td><?=$dadosXML['enderecoEmitente']->cMun;?></td>
                            </tr>
                            <tr>
                                <td>Municipio do Destinatário</td>
                                <td><?=$dadosXML['enderecoEmitente']->xMun;?></td>
                            </tr>
                            <tr>
                                <td>UF do Destinatário</td>
                                <td><?=$dadosXML['enderecoEmitente']->UF;?></td>
                            </tr>
                            <tr>
                                <td>CEP do Destinatário</td>
                                <td><?=$dadosXML['enderecoEmitente']->CEP;?></td>
                            </tr>
                            <tr>
                                <td>Código do País do Destinatário</td>
                                <td><?=$dadosXML['enderecoEmitente']->cPais;?></td>
                            </tr>
                            <tr>
                                <td>IE do Destinatário</td>
                                <td><?=$dadosXML['destinatario']->IE;?></td>
                            </tr>
                            <tr>
                                <td>IE do Destinatário</td>
                                <td><?=$dadosXML['destinatario']->email;?></td>
                            </tr>
                            <tr>
                                <td>IE do Destinatário</td>
                                <td><?=$dadosXML['destinatario']->indIEDest;?></td>
                            </tr>
                            <tr>
                                <td>Valor da NF</td>
                                <td><?=$dadosXML['valorNF'];?></td>
                            </tr>
                        </table>
                <?php

            if($dadosXML['emitente']->CNPJ == "09066241000884") {

                $destinatarioCPF  = isset($dadosXML['destinatario']->CPF) ? $dadosXML['destinatario']->CPF : "Não Informado";
                $destinatarioCNPJ = isset($dadosXML['destinatario']->CNPJ) ? $dadosXML['destinatario']->CNPJ : "Não Informado"; ?>

                <div align="center">
                <?php $processarXML->cadastrarNF(
                            $dadosXML["numeroNF"], $dadosXML["dataEmissaoNF"], $dadosXML['valorNF'],
                            $dadosXML["destinatario"], $dadosXML["enderecoEmitente"]
                        );
            } else {
                echo $processarXML->retornaMsg(
                    "<div align='center'>
                            <p class='msg f-color-erro'>
                                Você não pode importar essa NFe!
                            </p>
                        </div>"
                    );
                }
            }
        } else {
            echo $processarXML->retornaMsg(
                "<div align='center'>
                        <p class='msg f-color-erro'>
                            Este arquivo não é XML!
                        </p>
                     </div>"
                );
            }
        } ?>
        </div>
    </body>
</html>