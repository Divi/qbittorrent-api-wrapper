<?php

/*
 * This file is part of the "qBittorrent API Wrapper" package.
 *
 * https://github.com/Divi/qbittorrent-api-wrapper
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Divi\qBittorrent\Api\Wrapper\Client;

use Divi\qBittorrent\Api\Wrapper\Authentication\Response\Exception\BadCredentialsException;
use Divi\qBittorrent\Api\Wrapper\Authentication\Session\Session;
use Divi\qBittorrent\Api\Wrapper\Authentication\Session\SessionSubscriber;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

/**
 * @author Sylvain Lorinet <sylvain.lorinet@gmail.com>
 */
class qBittorrentClient
{
    /**
     * @var bool
     */
    protected $isLogged = false;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $config;


    /**
     * @param Client $client
     * @param array  $config
     */
    public function __construct(Client $client, array $config = [])
    {
        $this->config = array_merge($this->getDefaults(), $config);

        $this->initClient($client);
    }

    /**
     * @param Client $client
     */
    protected function initClient(Client $client)
    {
        $client->setBaseUrl(sprintf('http://%s:%d', $this->config['host'], $this->config['port']));
        $client->addSubscriber(new SessionSubscriber());
        $client->setDescription($this->createDescription());

        $this->client = $client;
    }

    /**
     * @return ServiceDescription
     */
    protected function createDescription()
    {
        return ServiceDescription::factory($this->config['description.path']);
    }

    /**
     * @return array
     */
    protected function getDefaults()
    {
        return [
            'host'             => '127.0.0.1',
            'port'             => 8080,
            'username'         => 'admin',
            'password'         => 'adminadmin',
            'description.path' => __DIR__ . '/../../../../../../api/api.json'
        ];
    }

    /**
     * @return Session
     *
     * @throws BadCredentialsException
     */
    public function login()
    {
        $response = $this->client->getCommand('Login', [
            'username' => $this->config['username'],
            'password' => $this->config['password']
        ])->execute();

        $this->isLogged = true;

        return $response;
    }

    /**
     * @param string $commandName
     * @param array  $args
     *
     * @return \Guzzle\Service\Command\CommandInterface|null
     *
     * @throws BadCredentialsException
     */
    public function getCommand($commandName, array $args = [])
    {
        if (!$this->isLogged) {
            $this->login();
            $this->isLogged = true;
        }

        return $this->client->getCommand($commandName, $args);
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
