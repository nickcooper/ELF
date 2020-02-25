<?
    foreach($headers as &$header)
    {
        $header = "\"".$header."\"";
    }
    echo implode(",",$headers)."\n";

    foreach($rows as $row)
    {
        foreach ($row as &$value)
        {
            if(is_array($value) && isset($value['label']))
            {
                $value = "\"".$value['label']."\"";
            }
            else
            {
                $value = "\"".$value."\"";
            }
        }
        echo implode(",",$row)."\n";
    }
?>