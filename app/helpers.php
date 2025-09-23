<?php

if (!function_exists('mb_strimwidth')) {
    /**
     * Fallback function for mb_strimwidth when mbstring extension is not available
     * 
     * @param string $str
     * @param int $start
     * @param int $width
     * @param string $trimmarker
     * @param string $encoding
     * @return string
     */
    function mb_strimwidth($str, $start, $width, $trimmarker = '', $encoding = 'UTF-8')
    {
        // Simple fallback implementation
        $str = substr($str, $start);
        if (strlen($str) <= $width) {
            return $str;
        }
        return substr($str, 0, $width - strlen($trimmarker)) . $trimmarker;
    }
}

if (!function_exists('formatarDataExtenso')) {
    /**
     * Formatar data por extenso em português
     * 
     * @param mixed $data
     * @return string
     */
    function formatarDataExtenso($data)
    {
        $meses = [
            1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril',
            5 => 'maio', 6 => 'junho', 7 => 'julho', 8 => 'agosto',
            9 => 'setembro', 10 => 'outubro', 11 => 'novembro', 12 => 'dezembro'
        ];
        
        $dataObj = \Carbon\Carbon::parse($data);
        $dia = $dataObj->day;
        $mes = $meses[$dataObj->month];
        $ano = $dataObj->year;
        
        // Assumindo Marília como cidade padrão, pode ser configurável
        return "Marília, {$mes}, {$dia} de {$ano}";
    }
}