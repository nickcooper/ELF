<?php
    // collect the license variants
    $variants = array(
        'IR' => 'N',
        'DC' => 'N',
        'SI' => 'N'
    );
    
    foreach ($data['LicenseVariant'] as $lic_variant)
    {
        if (array_key_exists($lic_variant['Variant']['abbr'], $variants))
        {
            $variants[$lic_variant['Variant']['abbr']] = 'Y';
        }
    }

    echo sprintf(
        '"%s","%s","%s","%s","%s","%s","%s","%s","%s"',
        $data['Account']['last_name'],
        $data['Account']['first_name'],
        $data['Account']['middle_initial'],
        $data['License']['license_number'],
        $data['LicenseType']['label'],
        $variants['IR'],
        $variants['DC'],
        $variants['SI'],
        'N'
    );
?>
