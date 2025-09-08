<?php

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