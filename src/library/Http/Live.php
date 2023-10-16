<?php

declare(strict_types=1);

namespace App\Psrphp\Stat\Http;

use App\Psrphp\Admin\Http\Common;
use PDO;
use PsrPHP\Database\Db;
use PsrPHP\Request\Request;
use PsrPHP\Template\Template;

class Live extends Common
{
    public function get(
        Db $db,
        Request $request,
        Template $template
    ) {
        $res = [];
        $minutes = $request->get('minutes', 30);
        $res['minutes'] = $minutes;

        $tmp = [];
        foreach ($db->query('SELECT LEFT(<datetime>, 16) as minute, COUNT(DISTINCT <ip>) AS ip, COUNT(DISTINCT <guid>) AS uv, COUNT(*) AS pv FROM <psrphp_stat_log> WHERE date >= :startdate AND datetime BETWEEN :startdatetime AND :enddatetime GROUP BY <minute> ORDER BY pv DESC', [
            ':startdate' => date('Y-m-d', time() - $minutes * 60),
            ':startdatetime' => date('Y-m-d H:i:00', time() - $minutes * 60),
            ':enddatetime' => date('Y-m-d H:i:59'),
        ])->fetchAll(PDO::FETCH_ASSOC) as $vo) {
            $tmp[$vo['minute']] = $vo;
        }
        $lives = [];
        for ($i = 1; $i <= $minutes; $i++) {
            $minute = date('Y-m-d H:i', time() - ($minutes - $i) * 60);
            $lives[$i] = $tmp[$minute] ?? ['ip' => 0, 'uv' => 0, 'pv' => 0, 'minute' => $minute];
        }
        $res['lives'] = $lives;

        $res['max'] = 1;
        foreach ($lives as $vo) {
            $res['max'] = max($res['max'], $vo['ip'], $vo['uv'], $vo['pv']);
        }

        $guidcount = $request->get('guidcount', 50);
        $res['guidcount'] = $guidcount;

        $guids = $db->query('SELECT DISTINCT <guid> FROM <psrphp_stat_log> ORDER BY id DESC LIMIT 50')->fetchAll(PDO::FETCH_ASSOC);

        foreach ($guids as &$vo) {
            $logs = $db->select('psrphp_stat_log', '*', [
                'guid' => $vo['guid'],
                'ORDER' => [
                    'id' => 'DESC',
                ],
                'LIMIT' => 50,
            ]);
            $vo = $logs[0];
            $vo['logs'] = $logs;
        }
        $res['guids'] = $guids;
        return $template->renderFromFile('live@psrphp/stat', $res);
    }
}
