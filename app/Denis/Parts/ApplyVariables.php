<?php


namespace App\Denis\Parts;


trait ApplyVariables
{
    public function formatVariables($string, $variables)
    {
        $data = $variables['once'] ?? [];
         
        $out = [];
        foreach ($data as $key=>$val){
            if(is_array($val)){
                continue;
            }
            $out["{{$key}}"]=$val;
        }
        return str_replace(array_keys($out), array_values($out), $string);
    }
}