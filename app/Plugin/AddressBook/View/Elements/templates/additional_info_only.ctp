<?php
    // location label
    if(preg_match('/[a-z0-9]/', $address['label'])) :
        echo sprintf('Label: %s <br />', $address['label']);
    endif;
    
    // attention of
    if(preg_match('/[a-z0-9]/', $address['attention'])) :
        echo sprintf('Attn: %s <br />', $address['attention']);
    endif;
    
    // phone1
    if(preg_match('/[a-z0-9]/', $address['phone1'])) :
        $ext = '';
        if(preg_match('/[a-z0-9]/', $address['ext1']))
        {
            $ext = sprintf(', x%s', $address['ext1']);
        }
        
        echo sprintf('Phone 1: %s%s <br />', $address['phone1'], $ext);
    endif;
    
    // phone2
    if(preg_match('/[a-z0-9]/', $address['phone2'])) :
        $ext = '';
        if(preg_match('/[a-z0-9]/', $address['ext2']))
        {
            $ext = sprintf(', x%s', $address['ext2']);
        }
        
        echo sprintf('Phone 2: %s%s <br />', $address['phone2'], $ext);
    endif;
    
    // fax
    if(preg_match('/[a-z0-9]/', $address['fax'])) :
        echo sprintf('Fax: %s <br />', $address['fax']);
    endif;
    
    // lat and long
    if(preg_match('/[a-z0-9]/', $address['latitude']) && preg_match('/[a-z0-9]/', $address['longitude'])) :
        echo sprintf('Geo: %s, %s <br />', $address['latitude'],$address['latitiude']);
    endif;
?>