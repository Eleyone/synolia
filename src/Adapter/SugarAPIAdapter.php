<?php
/**
 * Created by PhpStorm.
 * User: eleyo
 * Date: 27/11/2019
 * Time: 09:04
 */

namespace App\Adapter;

use Symfony\Component\HttpClient\HttpClient;

/**
 * Class SugarAPIAdapter
 * @package App\Adapter
 */
class SugarAPIAdapter
{
    /** @var string $token */
    protected $token;

    /** @var string $base_url */
    private $base_url = 'https://sg-dev-practice.demo.sugarcrm.eu/rest/v11_6';

    /**
     * @param string $user
     * @param string $password
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function auth(string $user = "jane", string $password = '$JAvp@3*fyDohgGV')
    {
        $client = HttpClient::create([
            'headers' => [
                'Content-Type' => "application/json",
                'Cache-Control' => 'no-cache'
            ]
        ]);
        $response = $client->request('POST', $this->base_url . '/oauth2/token', [
            'json' => [
                "grant_type" => "password",
                "client_id" => "sugar",
                "client_secret" => "",
                "username" => $user,
                "password" => $password,
                "platform" => "base"
            ]
        ]);

        $content = $response->toArray();

        if ($response->getStatusCode() == 200) {
            $this->token = $content['access_token'];
        } else {
            throw new \Exception("Auth error : {$content['error_message']}");
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function serchContact(): array
    {

        if (empty($this->token)) {
            $this->auth();
        }

        $client = HttpClient::create([
            'headers' => [
                'Content-Type' => "application/json",
                'oauth-token' => $this->token
            ]
        ]);
        $response = $client->request('POST', $this->base_url . '/Contacts/filter', [
            'json' => [
                "filter" => [
                    [
                        '$or' => [
                            [
                                "first_name" => [
                                    '$contains' => "a",
                                ]
                            ],
                            [
                                "first_name" => [
                                    '$contains' => "b",
                                ]
                            ],
                            [
                                "last_name" => [
                                    '$contains' => "a",
                                ]
                            ],
                            [
                                "last_name" => [
                                    '$contains' => "b",
                                ]
                            ]
                        ],
                    ]
                ],
                "fields" => [
                    "id", "first_name", "last_name", "email", "primary_address_street", "primary_address_street_2", "primary_address_street_3", "primary_address_city", "primary_address_state", "primary_address_postalcode", "primary_address_country"
                ],
            ]
        ]);

        $content = $response->toArray();

        switch ($response->getStatusCode()) {
            case 200 :
                return $content;
            case 404 :
                return [];
            default :
                throw new \Exception("Unexpected error : {$content['error_message']}");
        }
    }

    /**
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getContact(string $id): array
    {

        if (empty($this->token)) {
            $this->auth();
        }

        $client = HttpClient::create([
            'headers' => [
                'Content-Type' => "application/json",
                'oauth-token' => $this->token
            ]
        ]);
        $response = $client->request('GET', $this->base_url . '/Contacts/' . $id, [
            'json' => [
            ]
        ]);

        $content = $response->toArray();

        switch ($response->getStatusCode()) {
            case 200 :
                return $content;
            case 404 :
                return [];
            default :
                throw new \Exception("Unexpected error : {$content['error_message']}");
        }
    }

    /**
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getContactTickets(string $contactId): array
    {

        if (empty($this->token)) {
            $this->auth();
        }

        $client = HttpClient::create();
        $response = $client->request('POST', $this->base_url . '/Tickets', [
            'json' => [
                'filter_id' => $contactId
            ]
        ]);

        $content = $response->toArray();

        switch ($response->getStatusCode()) {
            case 200 :
                return $content;
            case 404 :
                return [];
            default :
                throw new \Exception("Unexpected error : {$content['error_message']}");
        }
    }

    /**
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function createContactTickets(string $contactId, array $ticketData = []): array
    {

        if (empty($this->token)) {
            $this->auth();
        }

        $client = HttpClient::create([
            'headers' => [
                'Content-Type' => "application/json",
                'oauth-token' => $this->token
            ]
        ]);
        $response = $client->request('POST', $this->base_url . '/Contacts', [
            'filter_id' => $id
        ]);

        $content = $response->toArray();

        switch ($response->getStatusCode()) {
            case 200 :
                return $content;
            case 404 :
                return [];
            default :
                throw new \Exception("Unexpected error : {$content['error_message']}");
        }
    }

    /**
     * @return string
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function help(): string
    {
        $client = HttpClient::create();

        $response = $client->request('GET', $this->base_url . 'help');

        return $response->getContent();
    }
}