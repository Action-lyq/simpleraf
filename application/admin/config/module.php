<?php
use think\facade\Url;

return [
    'index' => [
        Url::build('admin/index/index')
    ],
    'article' => [
        Url::build('admin/article/index'),
        Url::build('admin/article/add'),
        Url::build('admin/article/edit')
    ],
    'contact' => [
        Url::build('admin/contact/index'),
        Url::build('admin/contact/detail'),
    ],
    'cases' => [
        Url::build('admin/cases/index'),
        Url::build('admin/cases/add'),
        Url::build('admin/cases/edit')
    ],
];
