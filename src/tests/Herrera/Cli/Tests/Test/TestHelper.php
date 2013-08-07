<?php

namespace Herrera\Cli\Tests\Test;

use Symfony\Component\Console\Helper\Helper;

class TestHelper extends Helper
{
    public function getName()
    {
        return 'test';
    }
}
