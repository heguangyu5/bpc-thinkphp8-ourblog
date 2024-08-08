<?php

return array(
    'post' => array(
        array(
            'id'          => 1,
            'category_id' => 1,
            'title'       => '实战 Wireshark https 抓包，抓住 Moka 蹭 OurATS 的小尾巴',
            'is_external' => 0,
            'content'     => 'Wireshark 号称“纷争终结器 ”，作为开发者，掌握 Wireshark 的基本使用可以快速解决很多技术问题，直接减少很多无意义的“推诿”、“扯皮”。

使用 Wireshark 抓取未加密的网络流量是极其容易的，但随着大家安全意识的提高，加密流量越来越多了。

如何抓取加密的网络流量呢？本文通过一个实际问题向读者展示如何使用 Wireshark 抓取百度搜索的 https 网络数据包。

## 问题起源

商家在百度上打关键词广告是再正常不过的事情了。

本人参与开发的招聘管理系统 OurATS 也在百度上打了广告，但是应该没有购买 OurATS 这个关键词，因为我们公司只做这一个产品，所以宣传介绍、域名网址全都带有 OurATS 字样，任何一个正常的搜索引擎在搜索“OurATS”时应该都不会搞错搜索结果。

但是总会有人动歪心思，比如 OurATS 的竞品 Moka，这家公司号称融资好几个亿，员工几百人，比我们公司大多了。但他们可能觉得自己产品还不够好吧，在百度上给自家的产品 Moka 购买了“OurATS”关键词，鉴于“百度无下限”，当然购买成功了。于是就出现了下图这令人恶心的一幕：

![](https://pic4.zhimg.com/80/v2-baa538c3a1943a206fb661aa382c772f_720w.jpg)

前3条是广告，第一条红色字“ourats”就是 Moka 打的广告。

第4条是正经的 http://ourats.com 官网。

但在这个什么都能 PS 的年代，你拿一个截图，说服不了人的。对方撤了广告，然后说你“故意抹黑”，还能倒打一耙。

本人前几天也遇到过一次，但那次没能抓个现行，随后广告就不见了。谁知今天就又遇上了，这次，拿出 Wireshark 抓包做个留念，也顺便给还没抓过 https 包的读者做个实战演示。

> 为什么 Wireshark 抓包能说服人？
伪造 http 网络包很容易，伪造 https 包可就难了去了。
要想伪造本案例中的网络流量，那得拿到 baidu.com 的 ssl 证书私钥才可以。这恐怕还没人能做到吧。

## 实战第一步：配置 Wireshark

本文展示的是最简单、最通用的一种抓取 https 流量的办法，通过 (Pre)-Master-Secret log 。

注意 Wireshark 版本，本文使用是的 Version 3.2.4 (Git v3.2.4 packaged as 3.2.4-1~ubuntu18.04.0+wiresharkdevstable1) ，通过 https://launchpad.net/~wireshark-dev/+archive/ubuntu/stable 安装的。

1. 打开 Wireshark
2. Edit - Preferences
3. Protocols - TLS
4. 填写 (Pre)-Master-Secret log filename，这里定为 /tmp/a.log
5. 选中网卡接口，开始监控网络数据包

![](https://www.ourats.com/baidu-moka-ourats/wireshark-config.gif)


## 实战第二步：抓取网络数据包

1. 打开终端，通过终端启动 chromium 浏览器。

	SSLKEYLOGFILE=/tmp/a.log chromium-browser https://www.baidu.com

	注意 chromium-browser 前加了一个临时的环境变量 SSLKEYLOGFILE=/tmp/a.log ，这里的SSLKEYLOGFILE 指向的文件要和第一步中在 Wireshark 里配置的一样。

2. 百度搜索关键词“ourats”，得到搜索结果。

3. 退出 chromium，退出终端，停止 Wireshark 抓包。

![](https://www.ourats.com/baidu-moka-ourats/wireshark-capture.gif)


## 实战第三步：从数据包中找到搜索结果页

1. 由于第二步里实际上访问了两个页面，这两个页面发出了好几个 http 请求，我们先确认下要找的页面是哪个。Statistics - HTTP - Requests，在弹出窗口中可以找到包含ourats的搜索请求 /s?ie=utf-8&newi=1&mod=11&isbd=1&isid=B39A66BAF7268142&wd=ourats&....
2. 浏览 Wireshark 抓取到数据包，可以看到完整的 https 请求过程，并且也能看到 http 请求的内容了。向下找到 /s?ie=utf-8&newi=1&mod=11&isbd=1... ，右击 Follow - HTTP Stream，现在我们能看到 http 请求和返回内容了。
3. 在弹出窗口的右下角选择过滤出百度服务器 61.135.169.125:443 发送给我们的数据，并将编码设为 UTF-8，然后另存为 /tmp/a.html 。
4. 浏览器打开 /tmp/a.html，可以看到前几个请求返回的并不是搜索结果页面，但后边有一个是的，并且排到第一个的就是 Moka 蹭 OurATS 的关键词广告。

![](https://www.ourats.com/baidu-moka-ourats/wireshark-find.gif)

## 小结

本文展示的是通过浏览器抓取 https 包的办法，后边有时间了再写一篇通过 LD_PRELOAD wrapper libssl.so 来实现抓取任意使用 OpenSSL 实现加密逻辑的网络程序数据包的文章。',
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
