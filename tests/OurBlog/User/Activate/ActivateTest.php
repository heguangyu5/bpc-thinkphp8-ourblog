<?php

class OurBlog_User_ActivateTest extends OurBlog_DatabaseTestCase
{
    static $classGroups = 'activate';

    public function getDataSet()
    {
        return $this->createArrayDataSet(
            include __DIR__ . '/fixtures.php'
        );
    }

    public function testActivate()
    {
        OurBlog_User::activate(array(
            'id'    => 2,
            'token' => 'abcd1234abcd1234abcd1234abcd1234'
        ));

        $expectedDataSet = $this->createArrayDataSet(include __DIR__ . '/expects.php');

        $dataSet = $this->getConnection()->createDataSet(array('user'));
        $filterDataSet = new PHPUnit_DbUnit_DataSet_FilterDataSet($dataSet);
        $filterDataSet->setExcludeColumnsForTable('user', array('password', 'update_date'));

        $this->assertDataSetsEqual($expectedDataSet, $filterDataSet);
    }
}
