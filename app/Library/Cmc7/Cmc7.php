<?php
namespace MGLara\Library\Cmc7;


/**
 * @property char $cmc7 Número do CMC7
 */
class Cmc7{

    protected $cmc7;
    protected $cmc7_numerico;
    public $banco;
    protected $digitostirar;
    protected $valido;

    public function __construct($cmc7 = null) {
         $this->cmc7 = $this->setCmc7($cmc7);
    }

    public function setCmc7 ($cmc7) {
        return preg_replace("/[^0-9]/","", $cmc7);
    }

    public function banco(){
        return (int) substr($this->cmc7, 0, 3);
    }
    public function agencia(){
        return (int) substr($this->cmc7, 3, 4);
    }
    public function contacorrente(){
        $lIgnorar = $this->DigitosaIgnorarContaCMC7();
        return substr($this->cmc7, (19 + $lIgnorar), (9 - $lIgnorar)) . "-" . $this->contacorrenteDigito();
    }
    public function contacorrenteDigito(){
        return (int) substr($this->cmc7, 28, 1);
    }
    public function numero(){
        return (int) substr($this->cmc7, 11, 6);
    }
    // Valida Cmc7
    public function valido(){

        $firstPiece = substr($this->cmc7, 0, 7);
        $secondPiece = substr($this->cmc7, 8, 10);
        $thirdPiece = substr($this->cmc7, 19, 10);

        $firstDigit = substr($this->cmc7, 7, 1);
        $secondDigit = substr($this->cmc7, 18, 1);
        $thirdDigit = substr($this->cmc7, 29, 1);

        $calcula_first = $this->calculaCmc7($firstPiece);
        $calcula_second = $this->calculaCmc7($secondPiece);
        $calcula_third = $this->calculaCmc7($thirdPiece);

        if (($calcula_second <> $firstDigit)
                || ($calcula_first <> $secondDigit)
                || ($calcula_third <> $thirdDigit)) {
            return false;
        }else{
            return true;
        }


    }
    public function calculaCmc7($str){
        $size = strlen($str)-1;
        $result = 0;
        $weight = 2;
        for ($i = $size; $i >= 0; $i--) {
            $total = substr($str, $i, 1) * $weight;
            if ($total > 9) {
                $result = $result + 1 + ($total - 10);
            } else {
                $result = $result + $total;
            }
            if ($weight == 1) {
                $weight = 2;
            } else {
                $weight = 1;
            }
        }
        $dv = 10 - $this->mod($result, 10);
        if ($dv == 10) {
            $dv = 0;
        }
        return $dv;
    }
    public function mod($dividend,$divisor){
        return round($dividend - (floor($dividend / $divisor) * $divisor));
    }

    public function DigitosaIgnorarContaCMC7(){
        $digitostirar = null;
        if($this->banco()==1){
            $digitostirar = 2; //'001 - Banco do Brasil
        }
        if($this->banco()== 33){
            $digitostirar = '4'; //'033 - Santander / Banespa
        }
        if($this->banco()==41){
            $digitostirar = 0; //041 - Banrisul Obs: Este banco utiliza todo o campo para o número da conta
        }
        if($this->banco()==104){
            $digitostirar = 0 ; //104 - CEF. Utiliza apenas 7, mas os 3 primeiros são necessários para calcular o dv
        }
        if($this->banco()==237){
            $digitostirar = 3 ; //237 - Bradesco
        }
        if($this->banco()==341){
            $digitostirar = 4; //341 - Itau
        }
        if($this->banco()==389){
            $digitostirar = 1; //389 - Mercantil
        }
        if($this->banco()==409){
            $digitostirar = 3; //409 - Unibanco
        }
        if($this->banco()==479){
            $digitostirar = 2; //479 - Bank of Boston
        }
        if($digitostirar==''){
            $digitostirar = 3;
        }
        return $digitostirar;
    }
}

