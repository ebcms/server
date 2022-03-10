<?php

declare(strict_types=1);

namespace App\Ebcms\Server\Model;

use Composer\InstalledVersions;
use DigPHP\Framework\Config;
use DigPHP\Framework\Framework;
use DigPHP\Router\Router;
use Exception;
use Throwable;

class Server
{
    private $api;

    public function __construct(Config $config)
    {
        $this->api = $config->get('api.host@ebcms/server', 'https://www.ebcms.com/plugin/server/api');
    }

    public function query(string $path, array $param = []): array
    {
        try {
            $url = $this->api . $path . '?' . http_build_query($this->getCommonParam());
            $res = (array)json_decode($this->post($url, $param), true);

            if (!isset($res['status'])) {
                return [
                    'status' => 0,
                    'message' => '接口错误！',
                ];
            }
        } catch (Throwable $th) {
            return [
                'status' => 0,
                'message' => '接口错误：' . $th->getMessage(),
            ];
        }
        return $res;
    }

    private function getCommonParam(): array
    {
        $package = InstalledVersions::getRootPackage();
        $res = [];
        $res['project'] = $package['name'];
        $res['version'] = $package['pretty_version'];
        $res['site'] = Framework::execute(function (
            Router $router
        ): string {
            return $router->build('/');
        });
        return $res;
    }

    private function get(string $url, $timeout = 5, $ssl_verify = false)
    {
        $options = [
            'http' => [
                'method' => 'GET',
                'timeout' => $timeout,
            ],
        ];
        if ($ssl_verify === false) {
            $options['ssl'] = [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ];
        }
        $response = file_get_contents($url, false, stream_context_create($options));
        if (false === $response) {
            throw new Exception('post(' . $url . ') failure!');
        }
        return $response;
    }

    private function post(string $url, array $data = [], $timeout = 5, $ssl_verify = false)
    {
        $content = http_build_query($data);
        $options = [
            'http' => [
                'method' => 'POST',
                'timeout' => $timeout,
                'header' => "Accept: application/json\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: " . mb_strlen($content),
                'content' => $content,
            ],
        ];
        if ($ssl_verify === false) {
            $options['ssl'] = [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ];
        }
        $response = file_get_contents($url, false, stream_context_create($options));
        if (false === $response) {
            throw new Exception('post(' . $url . ') failure!');
        }
        return $response;
    }
}