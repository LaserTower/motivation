<?php


namespace App\Denis\Parts;


trait ApplyVariables
{
    public function formatVariables($string, $variables)
    {
        array_walk($variables, function (&$value) {
            return "\{$value\}";
        });
        return str_replace(array_keys($variables), array_values($variables), $string);
    }
}