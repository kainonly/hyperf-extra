<?php
declare (strict_types=1);

namespace Hyperf\Extra\Redis\Library;

interface UserLibrary
{
    public function get(string $username): array;
}