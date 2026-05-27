<?php

namespace Config;

use App\Filters\AuthFilter;
use App\Filters\GuestFilter;
use App\Filters\RoleFilter;
use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => \CodeIgniter\Filters\Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        'auth'          => AuthFilter::class,
        'guest'         => GuestFilter::class,
        'role'          => RoleFilter::class,
    ];

    public array $globals = [
        'before' => [
            'invalidchars',
            'csrf' => ['except' => ['uploads/*']],
        ],
        'after' => [
            'toolbar',
            'secureheaders',
            'performance',
        ],
    ];

    public array $methods = [];
    public array $filters = [];
}
