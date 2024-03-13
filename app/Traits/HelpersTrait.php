<?php

namespace App\Traits;


trait HelpersTrait
{
    /**
     * @param $rowHtml
     * @return array
     * מקבל שורה בטבלה TR
     * מחזיר כל הערכים במערך
     */
    function rowHtmlToArray($rowHtml){
        $rowHtml = trim(preg_replace('/\s\s+/', ' ', $rowHtml));

        $rowHtml = str_replace(array('<tr>','</tr>'), "", $rowHtml);

        $rowHtmlArr = array();
        while($rowHtml!=""){
            $pos1 = strpos($rowHtml, '<td>');
            $pos2 = strpos($rowHtml, '</td>');
            if($pos1===false or $pos2===false){
                break;
            }
            $val = substr($rowHtml,$pos1+4,$pos2-4);
            if(substr($val,-1)=='<'){
                $val = substr($val,0,-1);
            }
            $rowHtmlArr[]=$val;
            $rowHtml =  substr($rowHtml,$pos2+6);
        }
        return $rowHtmlArr;
    }
}
