<?php

namespace Tests;

use BenSampo\Enum\Enum;

final class Url extends Enum
{
    const register  = '/api/register';
    const login = '/api/login';
    const details   = '/api/user';
    const posts = '/api/posts';
    const create_post = '/api/post';
}
