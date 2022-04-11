<?php
/**
 * User: Marius Mertoiu
 * Date: 10/04/2022 19:38
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Controller;

use Parsy\Service\DataParser;

class ParserController
{
    public function parse(): array
    {
        $parser = new DataParser();
        return $parser->getData();
    }
}