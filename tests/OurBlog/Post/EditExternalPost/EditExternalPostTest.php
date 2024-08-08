<?php

class OurBlog_Post_EditExternalPostTest extends OurBlog_DatabaseTestCase
{
    static $classGroups = 'post';

    public function getDataSet()
    {
        return $this->createArrayDataSet(
            include __DIR__ . '/fixtures.php'
        );
    }

    public function testEditAttachTags()
    {
        $post = new OurBlog_Post(1);
        $post->edit(array(
            'id'         => 1,
            'categoryId' => 1,
            'title'      => '实战 Wireshark https 抓包 2, 再抓 Moka 蹭 OurATS 的小尾巴',
            'is_external' => 0,
            'content'    => '原文请看 https://zhuanlan.zhihu.com/p/157698929',
            'tags'       => ''
        ));

        $expectedDataSet = $this->createArrayDataSet(include __DIR__ . '/expects.php');

        $dataSet = $this->getConnection()->createDataSet(array('post'));
        $filterDataSet = new PHPUnit_DbUnit_DataSet_FilterDataSet($dataSet);
        $filterDataSet->setExcludeColumnsForTable('post', array('update_date'));

        $this->assertDataSetsEqual($expectedDataSet, $filterDataSet);
        $this->assertTableEmpty('tag', 'post_tag');
    }
}
