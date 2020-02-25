<?php

class SearchableControllerTest extends ControllerTestCase
{
	public $components = array(
        'Paginator',
        'ForeignObject' => array('validate' => false),
    );

    public function testIndex()
    {
        $result = $this->testAction('/searchable/searchable/index');
        debug($result);
    }
}