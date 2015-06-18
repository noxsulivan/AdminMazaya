<?php
/**

    Classe para integração com o Itaú Shopline

    Descrição: Gera os dados necessários, criptografados, para a transação.
    Autor: Caio Cesar Ariede - http://caioariede.com/
    Data: 18/9/2007
    Última Atualização: 14/12/2008

    1. Antes de iniciar o desenvolvimento, pegue os dados necessários junto
    ao Itaú Shopline, e consulte o manual do mesmo.

    2. Pague-me uma cerveja:
       http://caioariede.com/2008/integracao-itau-shopline-php

    3. The BSD License:

    Copyright (c) 2008, Caio Ariede
    All rights reserved.

    Redistribution and use in source and binary forms, with or without modification,
    are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list
      of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this
      list of conditions and the following disclaimer in the documentation and/or other
      materials provided with the distribution.
    * Neither the name of the Caio Ariede nor the names of its contributors may be
      used to endorse or promote products derived from this software without specific
      prior written permission.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
    ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
    WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
    IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
    INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
    NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
    PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
    WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
    ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
    POSSIBILITY OF SUCH DAMAGE.

**/

    //error_reporting(E_ALL);

    class Itaucripto
    {

        public $codEmp;
        public $pedido;
        public $valor;
        public $observacao;
        public $nomeSacado;
        public $codigoInscricao;
        public $numeroInscricao;
        public $enderecoSacado;
        public $bairroSacado;
        public $cepSacado;
        public $cidadeSacado;
        public $estadoSacado;
        public $dataVencimento;
        public $urlRetorna;
        public $obsAd1;
        public $obsAd2;
        public $obsAd3;

        private $CHAVE_ITAU        = 'SEGUNDA12345ITAU';
        //private $CHAVE_ITAU        = 'VIAGEM20COTA0709';
        private $TAM_COD_EMP    = 26;
        private $TAM_CHAVE        = 16;

        private $sbox;
        private $key;

        private function Algoritmo($s, $s1)
        {
            $k = 0;
            $l = 0;
            $r = '';

            $this->Inicializa($s1);

            for($j = 1; $j <= strlen($s); $j++)
            {
                $k = ($k + 1) % 256;

                $l = ($l + $this->sbox[$k]) % 256;

                $i = $this->sbox[$k];

                $this->sbox[$k] = $this->sbox[$l];
                $this->sbox[$l] = $i;

                $i1 = $this->sbox[($this->sbox[$k] + $this->sbox[$l]) % 256];
                $j1 = ord(substr($s, $j - 1, 1)) ^ $i1;
                $r .= chr($j1);

            }

            return $r;
        }

        private function Inicializa($s)
        {

            $len = strlen($s);

            for ($j = 0; $j <= 255; $j++)
            {
                $this->key[$j] = ord(substr($s, $j % $len, 1));
                $this->sbox[$j] = $j;
            }

            $l = 0;

            for ($k = 0; $k <= 255; $k++)
            {
                $l = ($l + $this->sbox[$k] + $this->key[$k]) % 256;

                $i = $this->sbox[$k];

                $this->sbox[$k] = $this->sbox[$l];
                $this->sbox[$l] = $i;
            }

        }

        private function Converte($s)
        {
            for($i = 0, $r = chr(rand(65, 90)); $i < strlen($s); $i++)
            {
                $r .= ord(strval(substr($s, $i, 1))) . chr(rand(65, 90));
            }
            return $r;
        }

        private function Desconverte($s)
        {
            return join(array_map('chr', split('[A-Z]', substr(substr($s, 1), 0, strlen($s) - 2))));
        }

        private function Corta($str, $n)
        {
            return str_pad(substr($str, 0, $n), $n, ' ', STR_PAD_RIGHT);
        }

        public function geraDados(
            $codEmp,
            $pedido,
            $valor,
            $obs,
            $chave,
            $nomeSacado,
            $codigoInscricao, 
            $numeroInscricao,
            $enderecoSacado,
            $bairroSacado,
            $cepSacado,
            $cidadeSacado,
            $estadoSacado,
            $dataVencimento,
            $urlRetorna,
            $obsAd1,
            $obsAd2,
            $obsAd3)
        {

            if (strlen($codEmp) != $this->TAM_COD_EMP)
            {
                throw new Exception('Tamanho do código da empresa diferente de 26 posições');
            }

            if (strlen($chave) != $this->TAM_CHAVE)
            {
                throw new Exception('Tamanho da chave diferente de 16 posições');
            }

            if ((int) $pedido == 0 || (int) $pedido > 99999999)
            {
                throw new Exception('Número do pedido inválido');
            }

            if (!preg_match('!^\d{1,8}\,\d{2}$!', $valor))
            {
                throw new Exception('Valor da compra inválido. Formato [000000]00,00');
            }

            if ((int) $codigoInscricao < 1 || (int) $codigoInscricao > 2)
            {
                throw new Exception('Código de inscrição inválido');
            }

            if (($numeroInscricao = preg_replace('!\D!', '', $numeroInscricao)) && strlen($numeroInscricao) > 14)
            {
                throw new Exception('Número de inscrição inválido');
            }

//            if (($cepSacado = preg_replace('!\D!', '', $cepSacado)) && strlen($cepSacado) < 8)
//            {
//                throw new Exception('CEP inválido');
//            }
//
//            if (strlen($estadoSacado) < 2 || strlen($estadoSacado) > 2)
//            {
//                throw new Exception('O estado deve ser uma sigla de no máximo 2 caracteres (UF). Informado|'.$estadoSacado.'|');
//            }

            if (!preg_match('!^(3[0-1]|0[1-9]|[1-2][0-9])(0[1-9]|1[0-2])2[0-9]{3}$!', $dataVencimento))
            {
                throw new Exception('Data de vencimento inválida. Informado|'.$dataVencimento.'|');
            }

//            if (!preg_match('!^https?://!', $urlRetorna))
//            {
//                throw new Exception('URL de retorno inválida');
//            }

            if (strlen($obsAd1) > 60)
            {
                throw new Exception('Observação opcional #1 inválida');
            }

            if (strlen($obsAd2) > 60)
            {
                throw new Exception('Observação opcional #2 inválida');
            }

            if (strlen($obsAd3) > 60)
            {
                throw new Exception('Observação opcional #3 inválida');
            }

            $codEmp                = strtoupper($codEmp);

            $valor                = substr($valor, 0, strlen($valor) - 3) . substr($valor, -2);
            $valor                = str_pad($valor, 10, '0', STR_PAD_LEFT);

            $pedido                = str_pad($pedido, 8, '0', STR_PAD_LEFT);

            $obs                = $this->Corta($obs, 40);
            $nomeSacado            = $this->Corta($nomeSacado, 30);
            $codigoInscricao    = $this->Corta($codigoInscricao, 2);
            $numeroInscricao    = $this->Corta($numeroInscricao, 14);
            $enderecoSacado        = $this->Corta($enderecoSacado, 40);
            $bairroSacado        = $this->Corta($bairroSacado, 15);
            $cepSacado            = $this->Corta($cepSacado, 8);
            $cidadeSacado        = $this->Corta($cidadeSacado, 15);
            $estadoSacado        = $this->Corta($estadoSacado, 2);
            $dataVencimento        = $this->Corta($dataVencimento, 29);
            $urlRetorna            = $this->Corta($urlRetorna, 60);
            $obsAd1                = $this->Corta($obsAd1, 60);
            $obsAd2                = $this->Corta($obsAd2, 60);
            $obsAd3                = $this->Corta($obsAd3, 60);

            $chave = strtoupper($chave);

            $DC = $pedido . $valor . $obs . $nomeSacado . $codigoInscricao . $numeroInscricao . $enderecoSacado . $bairroSacado . $cepSacado . $cidadeSacado . $estadoSacado . $dataVencimento . $urlRetorna . $obsAd1 . $obsAd2 . $obsAd3;

            $DC = $this->Algoritmo($DC, $chave);
            $DC = $this->Algoritmo($codEmp . $DC, $this->CHAVE_ITAU);

            return $this->Converte($DC);

        }

        //dados = cripto.geraConsulta(codigoEmpresa, pedido, formato, chave);
		function geraConsulta($s, $s1, $s2, $s3)
        {
            if(strlen($s) != $this->TAM_COD_EMP)
                return "Erro: tamanho do codigo da empresa diferente de 26 posições.";
            if(strlen($s3) != $this->TAM_CHAVE)
                return "Erro: tamanho da chave da chave diferente de 16 posições.";
            if(strlen($s1) < 1 || strlen($s1) > 8)
                return "Erro: número do pedido inválido.";
            if(is_numeric($s1))
                $s1 = str_pad($s1, 8, '0', STR_PAD_LEFT);
            else
                return "Erro: numero do pedido não é numérico.";
            if($s2 == "0" && $s2 == "1")
            {
                return "Erro: formato inválido.";
            } else
            {
                $s4 = $this->Algoritmo($s1 . $s2, $s3);
                $s5 = $this->Algoritmo($s . $s4, $this->CHAVE_ITAU);
                return $this->Converte($s5);
            }
        }
		
        function decripto($DC, $chave)
        {
            $s = $this->Desconverte($DC);
            $s = $this->Algoritmo($s, $this->CHAVE_ITAU);

            $this->codEmp            = substr($s, 0, 26);

            $s = $this->Algoritmo(substr($s, 26), $chave);

            $this->pedido            = substr($s, 0, 8);
            $this->valor            = substr($s, 8, 10);
            $this->observacao        = substr($s, 18, 40);
            $this->nomeSacado        = substr($s, 58, 30);
            $this->codigoInscricao    = substr($s, 88, 2);
            $this->numeroInscricao    = substr($s, 90, 14);
            $this->enderecoSacado    = substr($s, 104, 40);
            $this->bairroSacado        = substr($s, 144, 15);
            $this->cepSacado        = substr($s, 159, 8);
            $this->cidadeSacado        = substr($s, 167, 15);
            $this->estadoSacado        = substr($s, 182, 2);
            $this->dataVencimento    = substr($s, 184, 29);
            $this->urlRetorna        = substr($s, 213, 60);
            $this->obsAd1            = substr($s, 273, 60);
            $this->obsAd2            = substr($s, 333, 60);
            $this->obsAd3            = substr($s, 393, 60);

            $this->valor = number_format((int) $this->valor / 100, 2, ',', '.');
        }

    }
?>