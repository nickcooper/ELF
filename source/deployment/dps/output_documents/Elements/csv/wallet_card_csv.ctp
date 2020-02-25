<?php
    echo sprintf(
        '"%s","%s","%s","%s","%s"',
        $data['Account']['last_name'],
        $data['Account']['first_name'],
        $data['Account']['middle_initial'],
        $data['License']['license_number'],
        $data['LicenseType']['label']
    );
?>
