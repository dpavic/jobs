<?php

namespace Dpavic\JobsBundle\Utils;

class Jobs
{

    static public function slugify($text)
    {
        //replace all non letters or digits with - 
        $text = preg_replace('/\W+/', '-', $text);

        //trim and lovercase
        $text = strtolower(trim($text, '-'));

        return $text;
    }

}
