<?php
class PagesControllerTest extends ControllerTestCase
{
    public function testIndex()
    {
        $result = $this->testAction('/pages/pages/index');
        debug($result);
    }
}