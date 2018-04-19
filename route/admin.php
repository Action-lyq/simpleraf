<?php
Route::domain('admin', function() {
    Route::rule('/', 'admin/index/index');
});
