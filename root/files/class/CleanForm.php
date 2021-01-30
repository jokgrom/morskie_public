<?php


class CleanForm
{
    protected function clean($value) {
        $value=trim($value);
        $value=stripcslashes($value);
        $value=strip_tags($value);
        return $value;
    }
    static function number($value){
        $value=trim($value);
        $value=stripcslashes($value);
        $value=strip_tags($value);

        $value=preg_replace('/[^0-9]/','', $value);
        $value=substr($value, 0, 6);
        $value=(int)($value);
        if($value<1){
            return $value=0;
        }else{
            return $value;
        }
    }
}