<?php

namespace MGLara\Library\EscPrint;

use Illuminate\Support\Facades\Auth;

/*
 * Condensado: 137 Colunas
 * Normal:      80 Colunas
 * Large:       40 Colunas
 *
 */

class EscPrint
{

	protected $_conteudoSecao = array();
	protected $_comandos = array();
	protected $_linhas = 31;
	protected $_textoFinal = "";
    
	public $impressora = "impressoraMatricial";
	public $quebralaser = 3;

	function __construct($impressora = null, $linhas = null)
	{
		$this->_conteudoSecao =
			array(
				"documento" => "",
				"cabecalho" => "",
				"rodape"    => "",
			);

		if (!empty($impressora)) {
			$this->impressora = $impressora;
        } else {
			$this->impressora = Auth::user()->impressoramatricial;
        }

		if ($linhas != null)
			$this->_linhas = $linhas;

		$this->_comandos =
			array(
				"Draft"           => array(Chr(27)."x0",   ""     ), //Modo Draft
				"NLQ"             => array(Chr(27)."x1",   ""     ), //Modo NLQ
				"NLQRoman"        => array(Chr(27)."k0",   ""     ), //Fonte NLQ "Roman"
				"NLQSansSerif"    => array(Chr(27)."k1",   ""     ), //Fonte NLQ "SansSerif"
				"10cpp"           => array(Chr(27)."P",    ""     ), //Espaçamento horizontal em 10cpp
				"12cpp"           => array(Chr(27)."M",    ""     ), //Espaçamento horizontal em 12cpp
				"CondensedOn"     => array(Chr(15),        "<p style='font-size:0.58em'>"     ), //Ativa o modo condensado
				"CondensedOff"    => array(Chr(18),        "</p>"     ), //Desativa o modo condensado
				"LargeOn"         => array(Chr(27)."W1",   "<p style='font-size:2em'>"     ), //Ativa o modo expandido
				"LargeOff"        => array(Chr(27)."W0",   "</p>"     ), //Desativa o modo expandido
				"BoldOn"          => array(Chr(27)."E",   "<b>"   ), //Ativa o modo negrito
				"BoldOff"         => array(Chr(27)."F",   "</b>"  ), //Desativa o modo negrito
				"ItalicOn"        => array(Chr(27)."4",   "<i>"   ), //Ativa o modo itálico
				"ItalicOff"       => array(Chr(27)."5",   "</i>"  ), //Desativa o modo itálico
				"UnderlineOn"     => array(Chr(27)."-1",  "<u>"   ), //Ativa o modo sublinhado
				"UnderlineOff"    => array(Chr(27)."-0",  "</u>"  ), //Desativa o modo sublinhado
				"DblStrikeOn"     => array(Chr(27)."G",   "<b>"   ), //Ativa o modo de passada dupla
				"DblStrikeOff"    => array(Chr(27)."H",   "</b>"  ), //Desativa o modo de passada dupla
				"SupScriptOn"     => array(Chr(27)."S1",  ""      ), //Ativa o modo sobrescrito
				"SubScriptOn"     => array(Chr(27)."S0",  ""      ), //Ativa o modo subescrito
				"ScriptOff"       => array(Chr(27)."T",   ""      ), //Desativa os modos sobrescrito e subescrito

				//Controle de página
				"6lpp"            => array(Chr(27)."2",   ""      ), //Espaçamento vertical de 6 linhas por polegada
				"8lpp"            => array(Chr(27)."0",   ""      ), //Espaçamento vertical de 8 linhas por polegada
				"MarginLeft"      => array(Chr(27)."l",   ""      ), //Margem esquerda, onde "?"
				"MarginRight"     => array(Chr(27)."Q",   ""      ), //Margem direita, onde "?"
				"PaperSize"       => array(Chr(27)."C",   ""      ), //Tamanho da página, onde "?"
				"AutoNewPageOn"   => array(Chr(27)."N",   ""      ), //Ativa o salto sobre o picote, onde "?"
				"AutoNewPageOff"  => array(Chr(27)."O",   ""      ), //Desativa o salto sobre o picote

				//Controle da impressora
				"Reset"           => array(Chr(27)."@",   ""  ), //Inicializa a impressora (Reset)
				"LF"              => array(Chr(10),       "\n"    ), //Avança uma linha
				"FF"              => array(Chr(12),       "<hr>"  ), //Avança uma página
				"CR"              => array(Chr(13),       ""      ), //Retorno do carro

			);

	}

	/*
	 * Limpa Conteudo de uma secao
	 */
	public function limpaSecao($secao)
	{
		$this->_conteudoSecao[$secao] = "";
	}

	/*
	 * Imprime na matricial
	 */
	public function imprimir($impressora = null)
	{
		$arquivo = tempnam(sys_get_temp_dir(), "EscPrint-Lara");
		$handle = fopen($arquivo, "w");
		fwrite($handle, $this->converteEsc());
		fclose($handle);
        if ($impressora == null) {
            $impressora = $this->impressora;
        }
        $ret = exec("lpr -P {$impressora} {$arquivo}");
		unlink($arquivo);
        return $ret;
	}

	/*
	 * Converte as <TAGS> em Comandos ESC ou <HTML>
	 */
	public function converte($tipo = 0)
	{
		$texto = $this->_textoFinal;
		foreach ($this->_comandos as $key => $value)
		{
			$texto = str_replace("<" . $key . ">", $value[$tipo], $texto);
		}
		return $texto;
	}

	/*
	 * Alias para converte()
	 */
	public function converteHtml()
	{
		ob_start();
		?>
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR" lang="pt-BR">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta name="language" content="pt-BR" />
				<title>MGLara - Relatório Matricial</title>
			</head>
			<style>
				@page
				{
					size: auto;   /* auto is the initial value */
					//margin: 10mm;  /* this affects the margin in the printer settings */
					margin-top: 4mm;
					margin-bottom: 4mm;
				}
				hr:nth-of-type(<?php echo $this->quebralaser; ?>n)
				{
					page-break-after: always;
				}
				hr:last-child
				{
					page-break-after: avoid;
				}
			</style>
			<body>
				<pre><?php echo $this->converte(1); ?></pre>
			</body>
		</html>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	/*
	 * Alias para converte()
	 */
	public function converteEsc()
	{
		return $this->converte(0);
	}

	/*
	 * Adiciona Texto e depois quebra de linha
	 */
	public function adicionaLinha($texto = "", $secao = "documento", $pad_length = null, $pad_type = STR_PAD_RIGHT, $pad_string = " ")
	{
		$this->adicionaTexto($texto, $secao, $pad_length, $pad_type, $pad_string);
		$this->adicionaTexto("\n", $secao);
	}

	/*
	 * Adiciona Texto a Secao
	 */
	public function adicionaTexto($texto, $secao = "documento", $pad_length = null, $pad_type = STR_PAD_RIGHT, $pad_string = " ")
	{
		//inicializa secão caso necessario
		if (empty($this->_conteudoSecao[$secao]))
			$this->_conteudoSecao[$secao] = "";

		//faz o PAD
		if ($pad_length != null)
		{
			//se a string for maior que o tamanho corta
			if (strlen($texto) >= $pad_length)
				if ($pad_type == STR_PAD_LEFT)
					$texto = substr($texto, strlen($texto)-$pad_length,$pad_length);
				else
					$texto = substr($texto, 0, $pad_length);
			else
				$texto = str_pad ($texto, $pad_length, $pad_string, $pad_type);
		}

		//concatena
		$this->_conteudoSecao[$secao] .= $texto;
	}

	/*
	 * monta os cabecalhos e rodapes
	 */
	public function prepara()
	{

		// pega codigo do comando LF
		$lf = "\n";

		// conta linhas de cabecalho e Rodape
		if (empty($this->_conteudoSecao["cabecalho"]))
		{
			$linhasCabecalho = 0;
		}
		else
		{
			// se ultimo caracter do cabecalho nao for quebra de linha, adiciona
			if (substr($this->_conteudoSecao["cabecalho"], -1) <> $lf)
				$this->_conteudoSecao["cabecalho"] .= $lf;
			$linhasCabecalho = substr_count($this->_conteudoSecao["cabecalho"], $lf);
		}

		if (empty($this->_conteudoSecao["rodape"]))
		{
			$linhasRodape = 0;
		}
		else
		{
			//remove quebra de linha se houver no final do rodape
			$this->_conteudoSecao["rodape"] = trim($this->_conteudoSecao["rodape"], $lf);
			$linhasRodape = substr_count($this->_conteudoSecao["rodape"], $lf);
			$linhasRodape++;
		}

		$textoLinhas = explode($lf, $this->_conteudoSecao["documento"]);

		$linha = 1;
		$textoFinal = "";
		$linhasDocumento = $this->_linhas - $linhasCabecalho - $linhasRodape;
		foreach ($textoLinhas as $textoLinha)
		{
			//adiciona cabecalho na primeira linha
			if ($linha == 1)
				$textoFinal .= $this->_conteudoSecao["cabecalho"];

			// concatena linha
			$textoFinal .= $textoLinha . $lf;
			$linha++;

			// se estourou adiciona rodape
			if ($linha >= $linhasDocumento)
			{
				$textoFinal .= $this->_conteudoSecao["rodape"];
				$textoFinal .= "<FF>";
				$linha = 1;
			}
		}

		for ($linha = $linha - 1; $linha < $linhasDocumento; $linha++)
			$textoFinal .= $lf;

		//adiciona rodape ultima pagina
		$textoFinal .= $this->_conteudoSecao["rodape"];
		$textoFinal .= "<FF>";

		$this->_textoFinal = $textoFinal;
	}

}
