{include common/header@psrphp/admin}
<h1>实时在线</h1>

<h3>最近{$minutes}分钟访问统计</h3>
<div style="border: 1px solid gray;padding: 20px 20px 30px 20px;">
    <div style="display: flex;justify-content: center;gap: 20px;">
        <div style="display: flex;gap: 5px;align-items: center;">
            <div style="width: 30px;height: 20px;background: #2196F3;"></div>
            <div>IP数</div>
        </div>
        <div style="display: flex;gap: 5px;align-items: center;">
            <div style="width: 30px;height: 20px;background: red;"></div>
            <div>UV数</div>
        </div>
        <div style="display: flex;gap: 5px;align-items: center;">
            <div style="width: 30px;height: 20px;background: blue;"></div>
            <div>PV数</div>
        </div>
    </div>
    <div style="display: flex;height: 200px;align-items: flex-end;gap: 10px;margin-top: 30px;">
        {foreach $lives as $vo}
        <div style="flex-grow: 1;position: relative;display: flex;align-items: flex-end;">
            <div style="position: relative;flex-grow: 1;">
                <div style="position: absolute;top: -20px;width: 100%;text-align: center;">{$vo.ip}</div>
                <div style="height: {:ceil($vo['ip']*200/$max+1)}px;background: #2196F3;"></div>
            </div>
            <div style="position: relative;flex-grow: 1;">
                <div style="position: absolute;top: -20px;width: 100%;text-align: center;">{$vo.uv}</div>
                <div style="height: {:ceil($vo['uv']*200/$max+1)}px;background: red;"></div>
            </div>
            <div style="position: relative;flex-grow: 1;">
                <div style="position: absolute;top: -20px;width: 100%;text-align: center;">{$vo.pv}</div>
                <div style="height: {:ceil($vo['pv']*200/$max+1)}px;background: blue;"></div>
            </div>
            <div style="position: absolute;bottom: -20px;width: 100%;text-align: center;font-size: .6em;">{:substr($vo['minute'], 11, 5)}</div>
        </div>
        {/foreach}
    </div>
</div>

<style>
    tr.nowrap th,
    tr.nowrap td {
        white-space: nowrap;
    }

    pre {
        word-break: break-word;
        white-space: pre-wrap;
    }
</style>
<h3>最近{$guidcount}个访客</h3>
<table>
    <tr class="nowrap">
        <th>时间</th>
        <th>GUID</th>
        <th>地区</th>
        <th>设备</th>
        <th>来自</th>
        <th>当前访问网址</th>
        <th>访问记录</th>
    </tr>
    {foreach $guids as $vo}
    <tr class="nowrap">
        <td>{$vo['datetime']}</td>
        <td>
            <code title="{$vo['guid']}">{:substr($vo['guid'], 0, 8)}</code>
            {if $vo['user_agent'] != str_ireplace(['bot', 'spider', 'snoopy'], '', $vo['user_agent'])}
            <span style="color: red;" title="爬虫">🕷</span>
            {/if}
        </td>
        <td>{$vo.country}-{$vo.province}-{$vo.city}</td>
        <td>{$vo.type}</td>
        <td>{$vo['from']?:'直接访问'}</td>
        <td>{$vo.url}</td>
        <td>
            <a href="javascript:void(0)" onclick="event.target.parentNode.parentNode.nextElementSibling.style.display=event.target.parentNode.parentNode.nextElementSibling.style.display=='table-row'?'none':'table-row'">{:count($vo['logs'])}</a>
        </td>
    </tr>
    <tr style="display: none;">
        <td colspan="7">
            {foreach $vo['logs'] as $log}
            <details>
                <summary><span style="color: gray;">[{$log.datetime}]</span>&nbsp;&nbsp;{$log.url}</summary>
                <pre>{php}print_r($log);{/php}</pre>
            </details>
            {/foreach}
        </td>
    </tr>
    {/foreach}
</table>
{include common/footer@psrphp/admin}