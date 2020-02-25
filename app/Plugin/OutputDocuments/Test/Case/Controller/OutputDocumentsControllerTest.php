<?php
class OutputDocumentsControllerTest extends ControllerTestCase
{
    public function testIndex()
    {
        $result = $this->testAction('/output_documents/output_documents/index');
        debug($result);
    }
}