<h1 align="center">
  <a href="xxx/">
  <img src="xxx.log" width="250"/></a>
  <br>
  Lin-CMS-Laravel8
</h1>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-%3E%3D7.3.0-blue.svg" alt="php version" data-canonical-src="https://img.shields.io/badge/PHP-%3E%3D7.3.0-blue.svg" style="max-width:100%;"></a>
  <a href="https://img.shields.io/badge/laravel-8.*-blue" rel="nofollow"><img src="https://img.shields.io/badge/laravel-8.*-blue" alt="Laravel version" data-canonical-src="https://img.shields.io/badge/laravel-8.*-blue" style="max-width:100%;"></a>
  <img src="https://img.shields.io/badge/license-license--2.0-lightgrey.svg" alt="LISENCE" data-canonical-src="https://img.shields.io/badge/license-license--2.0-lightgrey.svg" style="max-width:100%;"></a>
</p>

# 简介

## 预防针

* 本项目非官方团队出品，仅出于学习、研究目的丰富下官方项目的语言支持

[comment]: <> (* 本项目采取后跟进官方团队功能的形式，即官方团队出什么功能，这边就跟进开发什么功能，开发者不必担心前端适配问题。)

[comment]: <> (* 在上一点的基础上，我们会尝试加入一些自己的想法并实现。)

[comment]: <> (* 局限于本人水平，有些地方还需重构，已经纳入了计划中，当然也会有我没考虑到的，希望有更多人参与进来一起完善，毕竟PHP作为世界上最好的语言不能缺席。)

[comment]: <> (## 专栏教程)

[comment]: <> (* [《Lin CMS PHP&Vue教程》]&#40;https://course.7yue.pro/lin/lin-cms-php/&#41;专栏教程连载更新中，通过实战开源前后端分离CMS——Lin CMS全家桶（lin-cms-vue & lin-cms-tp5）为一个前端应用实现内容管理系统。一套教程入门上手vue、ThinkPHP两大框架，自用、工作、私单一次打通。)

[comment]: <> (* 读者反馈：[《Lin CMS PHP&Vue教程》读者反馈贴]&#40;https://github.com/ChenJinchuang/lin-cms-tp5/issues/47&#41;)

[comment]: <> (## 线上文档地址&#40;完善中&#41;)

[comment]: <> ([http://chenjinchuang.gitee.io/lin-cms-book/]&#40;http://chenjinchuang.gitee.io/lin-cms-book/&#41;)

[comment]: <> (## 线上 Demo)

[comment]: <> (可直接参考官方团队的线上Demo：[http://face.cms.7yue.pro/]&#40;http://face.cms.7yue.pro/&#41;，用户名:super，密码：123456)

## 什么是 Lin CMS？

> Lin-CMS 是林间有风团队经过大量项目实践所提炼出的一套**内容管理系统框架**。Lin-CMS 可以有效的帮助开发者提高 CMS 的开发效率。

本项目是基于Laravel 8的 Lin CMS 后端实现。

官方团队产品了解请访问[TaleLin](https://github.com/TaleLin)

## Lin CMS 的特点

Lin CMS 的构筑思想是有其自身特点的。下面我们阐述一些 Lin 的主要特点。

**Lin CMS 是一个前后端分离的 CMS 解决方案**

这意味着，Lin 既提供后台的支撑，也有一套对应的前端系统，当然双端分离的好处不仅仅在于此，我们会在后续提供NodeJS和PHP版本的 Lin。如果你心仪 Lin，却又因为技术栈的原因无法即可使用，没关系，我们会在后续提供更多的语言版本。为什么 Lin 要选择前后端分离的单页面架构呢？

首先，传统的网站开发更多的是采用服务端渲染的方式，需用使用一种模板语言在服务端完成页面渲染：比如 JinJa2、Jade 等。 服务端渲染的好处在于可以比较好的支持 SEO，但作为内部使用的 CMS 管理系统，SEO 并不重要。

但一个不可忽视的事实是，服务器渲染的页面到底是由前端开发者来完成，还是由服务器开发者来完成？其实都不太合适。现在已经没有多少前端开发者是了解这些服务端模板语言的，而服务器开发者本身是不太擅长开发页面的。那还是分开吧，前端用最熟悉的 Vue 写 JS 和 CSS，而服务器只关注自己的 API 即可。

其次，单页面应用程序的体验本身就要好于传统网站。

更多关于Lin CMS的介绍请访问[Lin CMS线上文档](http://doc.cms.7yue.pro/)

**框架本身已内置了 CMS 常用的功能**

Lin 已经内置了 CMS 中最为常见的需求：用户管理、权限管理、日志系统等。开发者只需要集中精力开发自己的 CMS 业务即可

[comment]: <> (## Lin CMS TP5 的特点)

[comment]: <> (在当前项目的版本`&#40;0.0.1&#41;`中，特点更多来自于`ThinkPHP 5.1`框架本身带来的特点。通过充分利用框架的特性，实现高效的后端使用、开发，也就是说，只要你熟悉`ThinkPHP`框架，那么对于理解使用和二次开发本项目是没有难度的，即便对于框架的某些功能存在疑问也完全可以通过ThinkPHP官方的开发手册找到答案。当然我们更欢迎你通过[Issues]&#40;https://github.com/ChenJinchuang/lin-cms-tp5/issues&#41;来向我们提问:&#41;)

[comment]: <> (在下一个版本中`&#40;>0.0.1&#41;`,我们会在框架的基础上融入一些自己的东西来增强或者优化框架的使用、开发体验。)

## 所需基础

由于 Lin 采用的是前后端分离的架构，所以你至少需要熟悉 PHP 和 Vue。

Lin 的服务端框架是基于 Laravel8 的，所以如果你比较熟悉Laravel的开发模式，那将可以更好的使用本项目。但如果你并不熟悉Laravel，我们认为也没有太大的关系，因为框架本身已经提供了一套完整的开发机制，你只需要在框架下用 PHP 来编写自己的业务代码即可。照葫芦画瓢应该就是这种感觉。

但前端不同，前端还是需要开发者比较熟悉 Vue 的。但我想以 Vue 在国内的普及程度，绝大多数的开发者是没有问题的。这也正是我们选择 Vue 作为前端框架的原因。如果你喜欢 React Or Angular，那么加入我们，为 Lin 开发一个对应的版本吧。

# 快速开始

## Server 端必备环境

* 安装MySQL（version： 5.7+）

* 安装PHP环境(version： 7.3+)

## 获取工程项目

```bash
git clone https://github.com/calmzo/lin-cms-laravel8.git
```

> 执行完毕后会生成lin-cms-laravel8目录

## 安装依赖包

执行命令前请确保你已经安装了composer工具

```bash
# 进入项目根目录
cd lin-cms-laravel8
# 先执行以下命令，全局替换composer源，解决墙的问题
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
# 接着执行以下命令安装依赖包
composer install
```

## 数据库配置

MySQL 中新建一个数据库，名为` lin-cms `数据库,编辑env文件，配置数据库：

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
  //省略后面一堆的配置项
```
**请务必根据自己的实际情况修改此配置项**

## 导入数据
项目已初始化表数据  主要为root用户和用户组
1、执行 `migrate Artisan` 命令，执行数据库迁移：
```bash
php artisan migrate
```

2、运行 Seeders
```bash
#运行 DatabaseSeeder 类
php artisan db:seed

#指定一个特定的 seeder 类
php artisan db:seed --class=UserSeeder
```

