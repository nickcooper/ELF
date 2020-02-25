<?php
    echo sprintf(
        '"%s","","","%s","%s"',
        $data['Firm']['label'],
        $data['License']['license_number'],
        $data['LicenseType']['label']
    );
?>
