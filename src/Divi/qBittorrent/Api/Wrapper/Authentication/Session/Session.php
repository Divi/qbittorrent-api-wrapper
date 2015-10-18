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

/**
 * @author Sylvain Lorinet <sylvain.lorinet@gmail.com>
 */
class Session
{
    /**
     * @var string
     */
    private $id;


    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSid()
    {
        return $this->id;
    }
}
