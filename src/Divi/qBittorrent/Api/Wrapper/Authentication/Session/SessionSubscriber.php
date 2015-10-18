<?php

/*
 * This file is part of the "qBittorrent API Wrapper" package.
 *
 * https://github.com/Divi/qbittorrent-api-wrapper
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Divi\qBittorrent\Api\Wrapper\Authentication\Session;

use Guzzle\Common\Event;
use Guzzle\Http\Message\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Sylvain Lorinet <sylvain.lorinet@gmail.com>
 */
class SessionSubscriber implements EventSubscriberInterface
{
    /**
     * @var Session
     */
    protected $session;


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'request.before_send' => ['onRequestBeforeSend', 255]
        ];
    }

    /**
     * @param Event $event
     */
    public function onRequestBeforeSend(Event $event)
    {
        /** @var Request $request */
        $request = $event['request'];

        if (null == $this->session) {
            return;
        }

        $request->addCookie('SID', $this->session->getSid());
    }

    /**
     * @param Session $session
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    /**
     *
     */
    public function invalidate()
    {
        $this->session = null;
    }
}
