<?php
/**
 * Upload plugin config settings
 *
 * @author Iowa Interactive, LLC.
 */

/**
 * Allowed mime types 
 *
 * In your settings for actsAs in your model listing one of the indeces will 
 * imply you accept the following mime types
 */
$config['Upload']['mimeTypes'] = array(
    'image' => array(
        'bmp'   => 'image/bmp',
        'gif'   => 'image/gif',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'tif'   => 'image/tiff',
        'tiff'  => 'image/tiff',
        'png'   => array('image/png', 'image/x-png'),
    ),
    'csv' => array(
        'csv'   => 'text/csv',
    ),
    'pdf' => array(
        'pdf'   => 'application/pdf',
    )
);