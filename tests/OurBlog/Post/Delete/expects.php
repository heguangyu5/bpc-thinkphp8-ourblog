<?php

return array(
    'post' => array(
        array(
            'id'          => 2,
            'category_id' => 5,
            'title'       => '推一下我的得意作品：简历解析工具BobParser',
            'is_external' => 0,
            'content'     => '简历解析是任何与简历有关的软件系统的必备工具。技术上也相对比较成熟。简单搜索一下就能找到数家服务商，有几家直接提供在线试用，可以方便地看到解析效果。

知乎上也有技术人员分享[技术实现思路](https://zhuanlan.zhihu.com/p/51018046)，还有人分享了[研发成本](https://www.zhihu.com/question/266383983/answer/346051885)。这些实现思路大多都涉及[机器学习和NLP](https://www.zhihu.com/question/27134755)，并宣称是“智能”简历解析。

BobParser是从云招OurATS招聘管理系统中独立出来的一个工具，是OurATS实现[简历查重](https://link.zhihu.com/?target=https%3A//bob-finder.com)，人才云图，人才库透视的基础。

从解析效果看，BobParser显现出了一定的“智能”，但实际上BobParser的实现思路并不是近几年大热的人工智能，这一点是和其他同类产品的本质区别。当然BobParser也不是基于简历模版进行解析的，基于模版的解析思路无法应对格式多样的个人简历，并且为了适应模版变化所需要投入的人力和时间是巨大的。

网上能搜索到的简历解析服务商可以分为两类：一类提供在线试用，一类不提供；从另一个维度看，一类突出强调智能，一类不强调智能。但除了BobParser,其他简历解析工具看起来是直接做了个工具，然后销售给客户，而BobParser是从ATS产品中独立出来的，它的主要服务目标是云招OurATS招聘管理系统，而云招OurATS又服务于千余家客户，因此BobParser的实战应用经验是有保障的。

最后，给出BobParser的试用地址：https://bob-parser.com',
            'user_id'     => 2,
            'create_date' => '2020-07-16 16:10:00',
            'update_date' => '2020-07-16 16:10:00',
        )
    ),
    'tag' => array(
        array('id' => '1', 'name' => 'Wireshark'),
        array('id' => '2', 'name' => 'OurATS'),
        array('id' => '3', 'name' => 'https'),
        array('id' => '4', 'name' => 'Moka'),
        array('id' => '5', 'name' => 'chromium')
    )
);
