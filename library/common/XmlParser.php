<?php
class XmlParser
{
    public static function Parse($strxml)
    {
        $data = array();
        $xml = simplexml_load_string($strxml);
        $xml = (array)$xml;
        foreach($xml as $key => $value)
        {      
            $data[$key] = self::ParseElement($value);
        }
        
        //var_dump($data);
        return $data;
        
    }
    
    public static function ParseElement($ele)
    {
        if("object" != gettype($ele))
        {
            return $ele;
        }
        $ele = (array)$ele;
        $data = array();
        foreach($ele as $key => $value)
        {      
            $data[$key] = self::ParseElement($value);
        }
        
        return $data;
    }
}
