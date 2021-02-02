<?php

namespace App\Controller;

interface RouteCatalog
{
    public const ADMIN_DASHBOARD = 'blog_admin_dashboard';

    public const ADMIN_CATEGORY_INDEX  = 'blog_admin_category_index';
    public const ADMIN_CATEGORY_TREE   = 'blog_admin_category_tree';
    public const ADMIN_CATEGORY_SHOW   = 'blog_admin_category_show';
    public const ADMIN_CATEGORY_NEW    = 'blog_admin_category_new';
    public const ADMIN_CATEGORY_EDIT   = 'blog_admin_category_edit';
    public const ADMIN_CATEGORY_DELETE = 'blog_admin_category_delete';

    public const ADMIN_POST_INDEX    = 'blog_admin_post_index';
    public const ADMIN_POST_SHOW     = 'blog_admin_post_show';
    public const ADMIN_POST_NEW      = 'blog_admin_post_new';
    public const ADMIN_POST_EDIT     = 'blog_admin_post_edit';
    public const ADMIN_POST_DELETE   = 'blog_admin_post_delete';
    public const ADMIN_POST_WORKFLOW = 'blog_admin_post_workflow';
}
