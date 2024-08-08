<?php

class OurBlog_BaseDbTables_InitTest extends OurBlog_DatabaseTestCase
{
    static $classGroups = 'BaseDbTablesInit';

    public function getDataSet()
    {
        return $this->createArrayDataSet(array(
            'category' => include __DIR__ . '/tables/category.php',
            'user'     => include __DIR__ . '/tables/user.php'
        ));
    }

    public function testNothing()
    {
        $this->assertTrue(true);
    }
}
