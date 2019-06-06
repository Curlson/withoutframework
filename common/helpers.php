<?php 

function dd($value)
{
    if(is_array($value)){
        echo '<pre>';
        print_r($value);
        echo '<pre>';
    }else{
        echo $value ;
        echo "<br/>";
    }
}



?>
