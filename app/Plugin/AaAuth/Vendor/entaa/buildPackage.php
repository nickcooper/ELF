<?php

require_once 'PEAR/PackageFileManager.php';
$fm = new PEAR_PackageFileManager();
$options = array(
                'packagefile' => 'package.xml',
                'state' => 'alpha',
                'version' => '0.2.0',
                'notes' => 'Development snapshot, not for production use.',
                'filelistgenerator' => 'file',
                'baseinstalldir' => 'ITE/AAClient',
                'package' => 'ITE_AAClient',
                'summary' => 'ITE PHP5 Enterprise Authentiation and Authorization Client',
                'description' => 'This is the PHP5 impelementation of the A&A client.  It allows
                    applications to use the A&A Service.',
                'doctype' => 'http://pear.php.net/dtd/package-1.0',
                'packagedirectory' => 'C:\Program Files\Apache Group\Apache2\htdocs\Projects\DAS-ITE\Services\Enterprise_AA_Client_PHP5',
                'license' => 'PHP License',
                'changelogoldtonew' => true,
                'roles' =>
                  array(
                      'php' => 'php',
                      'txt' => 'doc',
                      '*' => 'data',
                       ),
                'dir_roles' =>
                  array(
                      'sql' => 'data',
                      'examples' => 'doc',
                      'tests' => 'test',
                       )
                );
$e = $fm->setOptions($options);
if (PEAR::isError($e)) {
    echo $e->getMessage();
    die();
}
$fm->addMaintainer('tbibbs', 'lead', 'Tony Bibbs', 'tony.bibbs@iowa.gov');

$e = $fm->writePackageFile();
if (PEAR::isError($e)) {
    echo $e->getMessage();
    die();
}

?>
