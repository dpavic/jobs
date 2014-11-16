<?php

namespace Dpavic\JobsBundle\Tests\Utils;

use Dpavic\JobsBundle\Utils\Jobs;


class JobsTest extends \PHPUnit_Framework_TestCase
{

    public function testSlugify()
    {
        $this->assertEquals('sensio', Jobs::slugify('Sensio'));
        $this->assertEquals('sensio-labs', Jobs::slugify('Sensio labs'));
    }

}

