<?php
Route::domain('admin', function() {
    Route::rule('/', 'admin/index/index');

    Route::rule('weuploads', 'admin/base/weuploads');

    Route::rule('login$', 'admin/control/login');
    Route::rule('login.do$', 'admin/control/do_login');
    Route::rule('logout.do$', 'admin/control/do_logout');

    Route::rule('article$', 'admin/article/index');
    Route::rule('article.add$', 'admin/article/add');
    Route::rule('article.add.do$', 'admin/article/do_add');
    Route::rule('article.edit$', 'admin/article/edit');
    Route::rule('article.edit.do$', 'admin/article/do_edit');

    Route::rule('cases$', 'admin/cases/index');
    Route::rule('cases.add$', 'admin/cases/add');
    Route::rule('cases.add.do$', 'admin/cases/do_add');
    Route::rule('cases.edit$', 'admin/cases/edit');
    Route::rule('cases.edit.do$', 'admin/cases/do_edit');
    Route::rule('cases.upload$', 'admin/cases/upload');
    Route::rule('cases.uploads$', 'admin/cases/uploads');

    Route::rule('contact$', 'admin/contact/index');
    Route::rule('contact.detail$', 'admin/contact/detail');

    Route::domain('www', function() {
        Route::rule('share-<id>$', 'index/share/detail');
        Route::rule('cases-<id>$', 'index/cases/detail');
    });
});
