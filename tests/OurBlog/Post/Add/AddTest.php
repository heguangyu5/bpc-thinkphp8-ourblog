<?php

class OurBlog_Post_AddTest extends OurBlog_DatabaseTestCase
{
    static $classGroups = 'post';

    protected $data;
    protected static $post;

    public function getDataSet()
    {
        $this->data = include __DIR__ . '/data.php';
        if (!self::$post) {
            self::$post = new OurBlog_Post(1);
        }

        return $this->createArrayDataSet(array(
            'post'     => array(),
            'post_tag' => array(),
            'tag'      => array(
                array('id' => '1', 'name' => 'Wireshark'),
                array('id' => '2', 'name' => 'OurATS'),
                array('id' => '3', 'name' => 'https')
            )
        ));
    }

    public function testCategoryIdKeyIsRequired()
    {
        unset($this->data['categoryId']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('missing required key categoryId');

        self::$post->add($this->data);
    }

    public function testTitleKeyIsRequired()
    {
        unset($this->data['title']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('missing required key title');

        self::$post->add($this->data);
    }

    public function testContentKeyIsRequired()
    {
        unset($this->data['content']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('missing required key content');

        self::$post->add($this->data);
    }

    public function testCategoryIdIsRequried()
    {
        $this->data['categoryId'] = '';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('categoryId required');

        self::$post->add($this->data);
    }

    public function testTitleIsRequried()
    {
        $this->data['title'] = '';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('title required');

        self::$post->add($this->data);
    }

    public function dataProviderTestInvalidCategoryId()
    {
        return array(
            array('Linux'),
            array('0'),
            array('-1')
        );
    }

    public function testInvalidCategoryId($categoryId)
    {
        $this->data['categoryId'] = $categoryId;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid categoryId');

        self::$post->add($this->data);
    }

    public function testTitleMaxLength()
    {
        $this->data['title'] .= str_repeat(
            'A',
            501 - mb_strlen($this->data['title'], 'UTF-8')
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('title too long, maxlength is 500');

        self::$post->add($this->data);
    }

    public function testContentMaxLength()
    {
        $this->data['content'] = str_pad($this->data['content'], 64001, 'A');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('content too long, maxlength is 64000 bytes');

        self::$post->add($this->data);
    }

    public function testTagsMaxLength()
    {
        $this->data['tags'] = str_repeat('123456789,', 40) . ',';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('tags too long');

        self::$post->add($this->data);
    }

    public function testTagsLimit()
    {
        $this->data['tags'] = 'tag1,tag2,tag3,tag4,tag5,tag6,tag7,tag8,tag9,tag10,tag11';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('too many tags');

        self::$post->add($this->data);
    }

    public function testOneTagMaxLength()
    {
        $this->data['tags'] = str_pad('tag', 31, 'A');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('tag too long');

        self::$post->add($this->data);
    }

    public function testAddWithOnlyRequiredInfo()
    {
        $this->data['content'] = '';
        $this->data['tags']    = '';
        self::$post->add($this->data);

        $expectedDataSet = $this->createArrayDataSet(include __DIR__ . '/expects-only-required-info.php');

        $dataSet = $this->getConnection()->createDataSet(array('post', 'tag'));
        $filterDataSet = new PHPUnit_DbUnit_DataSet_FilterDataSet($dataSet);
        $filterDataSet->setExcludeColumnsForTable('post', array('create_date', 'update_date'));

        $this->assertDataSetsEqual($expectedDataSet, $filterDataSet);
        $this->assertTableEmpty('post_tag');
    }

    public function testAddWithAllNewTags()
    {
        $this->data['tags'] = 'Moka,chromium';
        self::$post->add($this->data);

        $expectedDataSet = $this->createArrayDataSet(include __DIR__ . '/expects-all-new-tags.php');

        $dataSet = $this->getConnection()->createDataSet(array('post', 'tag', 'post_tag'));
        $filterDataSet = new PHPUnit_DbUnit_DataSet_FilterDataSet($dataSet);
        $filterDataSet->setExcludeColumnsForTable('post', array('create_date', 'update_date'));

        $this->assertDataSetsEqual($expectedDataSet, $filterDataSet);
    }

    public function testAddWithAllExistsTags()
    {
        $this->data['tags'] = 'Wireshark,https,OurATS';
        self::$post->add($this->data);

        $expectedDataSet = $this->createArrayDataSet(include __DIR__ . '/expects-all-exists-tags.php');

        $dataSet = $this->getConnection()->createDataSet(array('post', 'tag', 'post_tag'));
        $filterDataSet = new PHPUnit_DbUnit_DataSet_FilterDataSet($dataSet);
        $filterDataSet->setExcludeColumnsForTable('post', array('create_date', 'update_date'));

        $this->assertDataSetsEqual($expectedDataSet, $filterDataSet);
    }

    public function testAddWithSomeExistsSomeNewTags()
    {
        self::$post->add($this->data);

        $expectedDataSet = $this->createArrayDataSet(include __DIR__ . '/expects-some-exists-some-new-tags.php');

        $dataSet = $this->getConnection()->createDataSet(array('post', 'tag', 'post_tag'));
        $filterDataSet = new PHPUnit_DbUnit_DataSet_FilterDataSet($dataSet);
        $filterDataSet->setExcludeColumnsForTable('post', array('create_date', 'update_date'));

        $this->assertDataSetsEqual($expectedDataSet, $filterDataSet);
    }

    public function testAddExternalPost()
    {
        $this->data['external'] = '1';
        $this->data['content']  = 'https://zhuanlan.zhihu.com/p/157500548';

        self::$post->add($this->data);

        $expectedDataSet = $this->createArrayDataSet(include __DIR__ . '/expects-external-post.php');

        $dataSet = $this->getConnection()->createDataSet(array('post', 'tag', 'post_tag'));
        $filterDataSet = new PHPUnit_DbUnit_DataSet_FilterDataSet($dataSet);
        $filterDataSet->setExcludeColumnsForTable('post', array('create_date', 'update_date'));

        $this->assertDataSetsEqual($expectedDataSet, $filterDataSet);
    }
}
