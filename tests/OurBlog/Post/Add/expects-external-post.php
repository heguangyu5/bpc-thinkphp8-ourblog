<?php

return array(
    'post' => array(
        array(
            'id'          => 1,
            'category_id' => 1,
            'title'       => '实战 Wireshark https 抓包，抓住 Moka 蹭 OurATS 的小尾巴',
            'is_external' => 1,
            'content'     => 'https://zhuanlan.zhihu.com/p/157500548',
            'user_id'     => 1
        )
    ),
    'tag' => array(
        array('id' => '1', 'name' => 'Wireshark'),
        array('id' => '2', 'name' => 'OurATS'),
        array('id' => '3', 'name' => 'https'),
        array('id' => '4', 'name' => 'Moka'),
        array('id' => '5', 'name' => 'chromium')
    ),
    'post_tag' => array(
        array('id' => 1, 'post_id' => 1, 'tag_id' => 1),
        array('id' => 2, 'post_id' => 1, 'tag_id' => 3),
        array('id' => 3, 'post_id' => 1, 'tag_id' => 4),
        array('id' => 4, 'post_id' => 1, 'tag_id' => 2),
        array('id' => 5, 'post_id' => 1, 'tag_id' => 5)
    )
);
