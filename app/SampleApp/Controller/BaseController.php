<?php

namespace SampleApp\Controller;
use Library\Pagination;
use Silex\Application;

class BaseController
{
    public function __construct(Application $app)
    {

    }

    public function pagination($number_row, $path)
    {
        $links = new Pagination(PERPAGE, 'page');
        $links->set_total($number_row);

        return array(
            'page_links' => $links->page_links($path),
            'page_limit' => $links->get_limit()
        );
    }
}