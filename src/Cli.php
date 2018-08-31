<?php

declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 31.08.18
 * Time: 15:14.
 */

namespace SEOCLI;

use League\CLImate\CLImate;
use SEOCLI\Traits\SingletonInstance;

class Cli extends CLImate
{
    use SingletonInstance;
}
