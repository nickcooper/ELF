<?php

class CourseCatalogTest extends CakeTestCase
{
    /**
     * Fixtures
     */
    public $fixtures = array(
        'plugin.logging.audit',
        'plugin.logging.audit_delta',

        'plugin.continuing_education.course_catalog',
        'plugin.ContinuingEducation.course_catalogs_license_type',
        'plugin.licenses.license_type',
    );

    public $dropTables = true;

    public function setUp()
    {
        parent::setUp();

        $this->CourseCatalog = ClassRegistry::init('ContinuingEducation.CourseCatalog');
    }

    public function testAdd()
    {
        // get the fixture data
        $course_catalog = $this->CourseCatalog->find('first');

        // test save with good data
        $this->assertEquals(true, $this->CourseCatalog->add($course_catalog));

        // test abbr validation - fail if not between 1 and 6 characters
        $course_catalog['CourseCatalog']['abbr'] = '1234567';

        $this->assertEquals(false, $this->CourseCatalog->add($course_catalog));
    }
}