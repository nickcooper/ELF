<?php
class ProgramFixture extends CakeTestFixture 
{
    public $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'label' => array('type' => 'string', 'length' => 150, 'null' => false),
        'slug' => array('type' => 'string', 'length' => 150, 'null' => false),
        'abbr' => array('type' => 'string', 'length' => 6, 'null' => false),
        'byline' => array('type' => 'string', 'length' => 255, 'null' => true),
        'email' => array('type' => 'string', 'length' => 255, 'null' => true),
        'short_descr' => array('type' => 'string', 'length' => 255, 'null' => true),
        'descr' => array('type' => 'text', 'null' => true),
        'enabled' => array('type' => 'integer', 'length' => 1, 'null' => false),
        'merchant_code' => array('type' => 'string', 'length' => 45, 'null' => true),
        'service_code' => array('type' => 'string', 'length' => 45, 'null' => true),
        'created' => 'datetime',
        'modified' => 'datetime'
    );

    public $records = array(
        array(
            'id' => 1, 
            'label' => 'Test Program #1',
            'slug' => 'test-program-1',
            'abbr' => 'PRG1',
            'byline' => 'Testing is our number one priority!',
            'email' => 'dev@iowai.org',
            'short_descr' => 'This is a test record.',
            'descr' => 'This is a test record.',
            'enabled' => 1,
            'merchant_code' => 'XQ123',
            'service_code' => 'YR0345-3', 
            'created' => '2013-03-18 10:39:23', 
            'modified' => '2013-03-18 10:41:31'
        ),
        array(
            'id' => 2, 
            'label' => 'Test Program #2',
            'slug' => 'test-program-2',
            'abbr' => 'PRG2',
            'byline' => 'Testing is our number two priority!',
            'email' => 'dev@iowai.org',
            'short_descr' => 'This is another test record.',
            'descr' => 'This is another test record.',
            'enabled' => 1,
            'merchant_code' => 'XQ890',
            'service_code' => 'YR0678-0',
            'created' => '2013-03-18 10:41:23', 
            'modified' => '2013-03-18 10:43:31'
        ),
    );
}
?>