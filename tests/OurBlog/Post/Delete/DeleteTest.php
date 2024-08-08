<?php

class OurBlog_Post_DeleteTest extends OurBlog_DatabaseTestCase
{
    static $classGroups = 'post';

    public function getDataSet()
    {
        return $this->createArrayDataSet(
            include __DIR__ . '/../Edit/fixtures.php'
        );
    }

    public function testCannotDeleteOthersPost()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('id not exists or not your post');

        $post = new OurBlog_Post(1);
        $post->delete(array('id' => 2));
    }

    public function testDelete()
    {
        $post = new OurBlog_Post(1);
        $post->delete(array('id' => 1));

        $expectedDataSet = $this->createArrayDataSet(include __DIR__ . '/expects.php');

        $dataSet = $this->getConnection()->createDataSet(array('post', 'tag'));

        $this->assertDataSetsEqual($expectedDataSet, $dataSet);
        $this->assertTableEmpty('post_tag');
    }
}
