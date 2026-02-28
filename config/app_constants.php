<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Constants
    |--------------------------------------------------------------------------
    |
    | Các giá trị constant được sử dụng trong ứng dụng
    |
    */

    // Parent IDs cho bài viết
    'post_parent_ids' => [1, 2, 3, 4, 5, 9],

    // Parent IDs cho tuyển dụng
    'job_parent_ids' => [6, 7, 8],

    // Số lượng bài viết mặc định
    'default_post_limit' => 4,
    'default_latest_posts_limit' => 3,
    'default_documents_limit' => 8,

    // Pagination
    'posts_per_page' => 12,
    'jobs_per_page' => 9,
    'documents_per_page' => 12,

    // File upload
    'cv_max_size' => 10240, // KB (10MB)
    'cv_allowed_mimes' => ['pdf', 'doc', 'docx'],
];

