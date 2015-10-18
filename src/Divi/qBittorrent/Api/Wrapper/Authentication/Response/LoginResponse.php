<?php

/*
 * This file is part of the "qBittorrent API Wrapper" package.
 *
 * https://github.com/Divi/qbittorrent-api-wrapper
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Divi\qBittorrent\Api\Wrapper\Authentication\Response;

use Divi\qBittorrent\Api\Wrapper\Authentication\Response\Exception\BadCredentialsException;
use Divi\qBittorrent\Api\Wrapper\Authentication\Session\Session;
use Divi\qBittorrent\Api\Wrapper\Authentication\Session\SessionSubscriber;
use Guzzle\Service\Command\OperationCommand;
use Guzzle\Service\Command\ResponseClassInterface;

/**
 * @author Sylvain Lorinet <sylvain.lorinet@gmail.com>
 */
class LoginResponse implements ResponseClassInterface
{
    /**
     * @inheritdoc
     */
    public static function fromCommand(OperationCommand $command)
    {
        $response = $command->getResponse();

        if ($response->isSuccessful() && 'Fail.' === $response->getBody(true)) {
            throw new BadCredentialsException('Cannot login, bad credentials');
        }

        if (!$response->hasHeader('Set-Cookie')) {
            throw new \RuntimeException(
                'The cookie SID is not found'
            );
        }

        $params = $response->getHeader('Set-Cookie')->parseParams();

        if (!isset($params[0]) || !isset($params[0]['SID'])) {
            throw new \RuntimeException(
                'The cookie SID is malformed, see header details :' . $response->getRawHeaders()
            );
        }

        $session = new Session($params[0]['SID']);

        self::saveSession($command, $session);

        return $session;
    }

    /**
     * @param OperationCommand $command
     * @param Session          $session
     */
    protected static function saveSession(OperationCommand $command, Session $session)
    {
        $listeners = $command->getClient()->getEventDispatcher()->getListeners('request.before_send');

        foreach ($listeners as $listener) {
            if ($listener[0] instanceof SessionSubscriber) {
                $listener[0]->setSession($session);

                break;
            }
        }
    }
}
