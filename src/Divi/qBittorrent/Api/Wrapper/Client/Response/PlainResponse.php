<?php

/*
 * This file is part of the "qBittorrent API Wrapper" package.
 *
 * https://github.com/Divi/qbittorrent-api-wrapper
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Divi\qBittorrent\Api\Wrapper\Client\Response;

use Guzzle\Service\Command\OperationCommand;
use Guzzle\Service\Command\ResponseClassInterface;

/**
 * @author Sylvain Lorinet <sylvain.lorinet@gmail.com>
 */
class PlainResponse implements ResponseClassInterface
{
    /**
     * @param OperationCommand $command
     *
     * @return string
     */
    public static function fromCommand(OperationCommand $command)
    {
        return $command->getResponse()->getBody(true);
    }
}
