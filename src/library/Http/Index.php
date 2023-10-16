<?php

declare(strict_types=1);

namespace App\Psrphp\Stat\Http;

use App\Psrphp\Admin\Http\Common;
use PDO;
use PsrPHP\Database\Db;
use PsrPHP\Request\Request;
use PsrPHP\Template\Template;

class Index extends Common
{
    public function get(
        Db $db,
        Request $request,
        Template $template
    ) {
        $res = [];

        if ($request->get('type', 'date') == 'date') {
            $date = $request->get('date', date('Y-m-d'));

            $res['type'] = 'date';
            $res['date'] = $date;

            $res['froms'] = $db->query('SELECT <from>, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date=:date GROUP BY <from> ORDER BY pv DESC', [
                ':date' => $date
            ])->fetchAll(PDO::FETCH_ASSOC);
            $res['types'] = $db->query('SELECT <type>, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date=:date GROUP BY <type> ORDER BY pv DESC', [
                ':date' => $date
            ])->fetchAll(PDO::FETCH_ASSOC);
            $res['countrys'] = $db->query('SELECT <country>, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date=:date GROUP BY <country> ORDER BY pv DESC LIMIT 20', [
                ':date' => $date
            ])->fetchAll(PDO::FETCH_ASSOC);
            $res['provinces'] = $db->query('SELECT <country>, <province>, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date=:date GROUP BY <country>, <province> ORDER BY pv DESC LIMIT 20', [
                ':date' => $date
            ])->fetchAll(PDO::FETCH_ASSOC);
            $res['citys'] = $db->query('SELECT <country>, <province>, <city>, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date=:date GROUP BY <country>, <province>, <city> ORDER BY pv DESC LIMIT 20', [
                ':date' => $date
            ])->fetchAll(PDO::FETCH_ASSOC);
            $res['ISPs'] = $db->query('SELECT <ISP>, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date=:date GROUP BY <ISP> ORDER BY pv DESC LIMIT 20', [
                ':date' => $date
            ])->fetchAll(PDO::FETCH_ASSOC);
            $res['firsts'] = $db->query('SELECT <first>, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date=:date GROUP BY <first> ORDER BY pv DESC LIMIT 20', [
                ':date' => $date
            ])->fetchAll(PDO::FETCH_ASSOC);
            $res['ips'] = $db->query('SELECT <ip>, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date=:date GROUP BY <ip> ORDER BY pv DESC LIMIT 20', [
                ':date' => $date
            ])->fetchAll(PDO::FETCH_ASSOC);
            $res['urls'] = $db->query('SELECT <url>, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date=:date GROUP BY <url> ORDER BY pv DESC LIMIT 20', [
                ':date' => $date
            ])->fetchAll(PDO::FETCH_ASSOC);
            $datas = [];
            for ($i = 0; $i < 24; $i++) {
                $datas[$i] = [
                    'ip' => $db->count('psrphp_stat_log', '@ip', [
                        'date' => $date,
                        'datetime[<>]' => [$date . ' ' . str_pad((string)$i, 2, '0', STR_PAD_LEFT) . ':00:00', $date . ' ' . str_pad((string)$i, 2, '0', STR_PAD_LEFT) . ':59:59'],
                    ]) ?: 0,
                    'uv' => $db->count('psrphp_stat_log', '@guid', [
                        'date' => $date,
                        'datetime[<>]' => [$date . ' ' . str_pad((string)$i, 2, '0', STR_PAD_LEFT) . ':00:00', $date . ' ' . str_pad((string)$i, 2, '0', STR_PAD_LEFT) . ':59:59'],
                    ]) ?: 0,
                    'pv' => $db->count('psrphp_stat_log', [
                        'date' => $date,
                        'datetime[<>]' => [$date . ' ' . str_pad((string)$i, 2, '0', STR_PAD_LEFT) . ':00:00', $date . ' ' . str_pad((string)$i, 2, '0', STR_PAD_LEFT) . ':59:59'],
                    ]) ?: 0,
                    'baiduspider' => $db->count('psrphp_stat_log', [
                        'date' => $date,
                        'datetime[<>]' => [$date . ' ' . str_pad((string)$i, 2, '0', STR_PAD_LEFT) . ':00:00', $date . ' ' . str_pad((string)$i, 2, '0', STR_PAD_LEFT) . ':59:59'],
                        'user_agent[~]' => '%Baiduspider%',
                    ]) ?: 0,
                    'title' => $date . ' ' . $i . '时',
                ];
            }
        } else {
            $month = $request->get('month', date('Y-m'));

            $res['type'] = 'month';
            $res['month'] = $month;

            $res['froms'] = $db->query('SELECT <from>, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date BETWEEN :date_start AND :date_end GROUP BY <from> ORDER BY pv DESC', [
                ':date_start' => $month . '-01',
                ':date_end' => $month . '-31',
            ])->fetchAll(PDO::FETCH_ASSOC);
            $res['types'] = $db->query('SELECT <type>, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date BETWEEN :date_start AND :date_end GROUP BY <type> ORDER BY pv DESC', [
                ':date_start' => $month . '-01',
                ':date_end' => $month . '-31',
            ])->fetchAll(PDO::FETCH_ASSOC);
            $res['countrys'] = $db->query('SELECT <country>, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date BETWEEN :date_start AND :date_end GROUP BY <country> ORDER BY pv DESC LIMIT 20', [
                ':date_start' => $month . '-01',
                ':date_end' => $month . '-31',
            ])->fetchAll(PDO::FETCH_ASSOC);
            $res['provinces'] = $db->query('SELECT <country>, <province>, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date BETWEEN :date_start AND :date_end GROUP BY <country>, <province> ORDER BY pv DESC LIMIT 20', [
                ':date_start' => $month . '-01',
                ':date_end' => $month . '-31',
            ])->fetchAll(PDO::FETCH_ASSOC);
            $res['citys'] = $db->query('SELECT <country>, <province>, <city>, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date BETWEEN :date_start AND :date_end GROUP BY <country>, <province>, <city> ORDER BY pv DESC LIMIT 20', [
                ':date_start' => $month . '-01',
                ':date_end' => $month . '-31',
            ])->fetchAll(PDO::FETCH_ASSOC);
            $res['ISPs'] = $db->query('SELECT <ISP>, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date BETWEEN :date_start AND :date_end GROUP BY <ISP> ORDER BY pv DESC LIMIT 20', [
                ':date_start' => $month . '-01',
                ':date_end' => $month . '-31',
            ])->fetchAll(PDO::FETCH_ASSOC);
            $res['firsts'] = $db->query('SELECT <first>, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date BETWEEN :date_start AND :date_end GROUP BY <first> ORDER BY pv DESC LIMIT 20', [
                ':date_start' => $month . '-01',
                ':date_end' => $month . '-31',
            ])->fetchAll(PDO::FETCH_ASSOC);
            $res['ips'] = $db->query('SELECT <ip>, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date BETWEEN :date_start AND :date_end GROUP BY <ip> ORDER BY pv DESC LIMIT 20', [
                ':date_start' => $month . '-01',
                ':date_end' => $month . '-31',
            ])->fetchAll(PDO::FETCH_ASSOC);
            $res['urls'] = $db->query('SELECT <url>, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date BETWEEN :date_start AND :date_end GROUP BY <url> ORDER BY pv DESC LIMIT 20', [
                ':date_start' => $month . '-01',
                ':date_end' => $month . '-31',
            ])->fetchAll(PDO::FETCH_ASSOC);

            $datas = [];
            $days =  (int)date('d', strtotime(date('Y-m-01 00:00:01', strtotime($month . '-15') + 86400 * 20)) - 100);
            for ($i = 0; $i < $days; $i++) {
                $date = date('Y-m-d', strtotime($month) + $i * 86400);
                $datas[$i + 1] = [
                    'ip' => $db->count('psrphp_stat_log', '@ip', [
                        'date' => $date,
                    ]) ?: 0,
                    'uv' => $db->count('psrphp_stat_log', '@guid', [
                        'date' => $date,
                    ]) ?: 0,
                    'pv' => $db->count('psrphp_stat_log', [
                        'date' => $date,
                    ]) ?: 0,
                    'baiduspider' => $db->count('psrphp_stat_log', [
                        'date' => $date,
                        'user_agent[~]' => 'Baiduspider',
                    ]) ?: 0,
                    'title' => $date,
                ];
            }
        }

        $max = 1;
        foreach ($datas as $value) {
            $max = max($max, $value['ip'], $value['uv'], $value['pv']);
        }
        $res['datas'] = $datas;
        $res['max'] = $max;

        return $template->renderFromFile('index@psrphp/stat', $res);
    }
}
