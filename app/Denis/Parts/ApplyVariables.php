<?php


namespace App\Denis\Parts;


trait ApplyVariables
{
    public function formatVariables($string, $variables)
    {
        $out = [];
        foreach ($variables as $key=>$val){
            $out["{{$key}}"]=$val;
        }
        return str_replace(array_keys($out), array_values($out), $string);
    }
}