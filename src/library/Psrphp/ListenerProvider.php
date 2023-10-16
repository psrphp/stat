<?php

declare(strict_types=1);

namespace App\Psrphp\Stat\Psrphp;

use App\Psrphp\Stat\Http\Index;
use App\Psrphp\Stat\Http\Live;
use App\Psrphp\Stat\Lib\XdbSearcher;
use App\Psrphp\Admin\Model\MenuProvider;
use App\Psrphp\Web\Http\Common;
use PsrPHP\Database\Db;
use PsrPHP\Framework\Framework;
use PsrPHP\Request\Request;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Http\Message\ServerRequestInterface;
use PsrPHP\Session\Session;

class ListenerProvider implements ListenerProviderInterface
{
    public function getListenersForEvent(object $event): iterable
    {
        if (is_a($event, Common::class)) {
            yield function () use ($event) {
                Framework::execute(function (
                    Db $db,
                    Session $session,
                    Request $request,
                    XdbSearcher $xdbSearcher,
                    ServerRequestInterface $serverRequest
                ) {
                    $data = [
                        'url' => $serverRequest->getUri()->__toString(),
                        'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
                        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                        'referer' => $_SERVER['HTTP_REFERER'] ?? '',
                        'date' => date('Y-m-d'),
                        'datetime' => date('Y-m-d H:i:s'),
                    ];

                    $from = parse_url($data['referer'], PHP_URL_HOST) ?: '';
                    if ($from != $_SERVER['HTTP_HOST']) {
                        $session->set('from', $from);
                        $session->set('first', !$request->has('cookie.psrphp_stat_guid'));
                    }
                    $data['from'] = $session->get('from', $from);
                    $data['first'] = $session->get('first', false);

                    if ($request->has('cookie.psrphp_stat_guid')) {
                        $data['guid'] = $request->cookie('psrphp_stat_guid');
                    } else {
                        $data['guid'] = $this->create_guid();
                        setcookie('psrphp_stat_guid', $data['guid'], time() + 99 * 365 * 24 * 3600);
                    }

                    $agent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
                    if (strpos($agent, 'windows nt')) {
                        $data['type'] = 'pc';
                    } elseif (strpos($agent, 'macintosh')) {
                        $data['type'] = 'pc';
                    } elseif (strpos($agent, 'iphone')) {
                        $data['type'] = 'ios';
                    } elseif (strpos($agent, 'ipad')) {
                        $data['type'] = 'ios';
                    } elseif (strpos($agent, 'android')) {
                        $data['type'] = 'android';
                    } else {
                        $data['type'] = 'other';
                    }

                    if ($res = $xdbSearcher->search($_SERVER['REMOTE_ADDR'])) {
                        list($data['country'], $tmp, $data['province'], $data['city'], $data['ISP']) = explode('|', $res);
                    }

                    $db->insert('psrphp_stat_log', $data);
                }, [
                    Common::class => $event,
                ]);
            };
        }

        if (is_a($event, MenuProvider::class)) {
            yield function () use ($event) {
                Framework::execute(function (
                    MenuProvider $provider
                ) {
                    $provider->add('访问统计', Index::class);
                    $provider->add('实时在线', Live::class);
                }, [
                    MenuProvider::class => $event,
                ]);
            };
        }
    }

    private function create_guid(): string
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }
}
