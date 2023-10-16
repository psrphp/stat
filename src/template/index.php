{include common/header@psrphp/admin}
<h1>访问统计</h1>

<form method="GET">
    <select name="type" onchange="this.form.submit()">
        {if 'date' == $type}
        <option value="date" selected>按日</option>
        {else}
        <option value="date">按日</option>
        {/if}
        {if 'month' == $type}
        <option value="month" selected>按月</option>
        {else}
        <option value="month">按月</option>
        {/if}
    </select>
    {if $type=='date'}
    <input type="date" name="date" value="{$date}" onchange="this.form.submit()">
    {else}
    <input type="month" name="month" value="{$month}" onchange="this.form.submit()">
    {/if}
</form>

<div style="margin-top: 30px;border: 1px solid gray;padding: 20px 20px 30px 20px;">
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
        {foreach $datas as $key => $vo}
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
            <div style="position: absolute;bottom: -20px;width: 100%;text-align: center;">{$key}</div>
        </div>
        {/foreach}
    </div>
</div>

<div style="display: flex;flex-wrap: wrap;gap: 20px;margin-top: 20px;">
    <div>
        <fieldset>
            <legend>分时数据</legend>
            <table>
                <tr>
                    <th>时间</th>
                    <th>IP数</th>
                    <th>UV数</th>
                    <th>PV数</th>
                    <th>百度蜘蛛</th>
                </tr>
                {foreach $datas as $vo}
                <tr>
                    <td>{$vo.title}</td>
                    <td>{$vo.ip}</td>
                    <td>{$vo.uv}</td>
                    <td>{$vo.pv}</td>
                    <td>{$vo.baiduspider}</td>
                </tr>
                {/foreach}
            </table>
        </fieldset>
    </div>
    <div>
        <fieldset>
            <legend>受访页面</legend>
            <table>
                <tr>
                    <th>页面</th>
                    <th>ip</th>
                    <th>uv</th>
                    <th>pv</th>
                </tr>
                {foreach $urls as $vo}
                <tr>
                    <td>{$vo.url}</td>
                    <td>{$vo.ip}</td>
                    <td>{$vo.uv}</td>
                    <td>{$vo.pv}</td>
                </tr>
                {/foreach}
            </table>
        </fieldset>
    </div>
    <div>
        <fieldset>
            <legend>IP分析</legend>
            <table>
                <tr>
                    <th>ip</th>
                    <th>uv</th>
                    <th>pv</th>
                </tr>
                {foreach $ips as $vo}
                <tr>
                    <td>{$vo.ip}</td>
                    <td>{$vo.uv}</td>
                    <td>{$vo.pv}</td>
                </tr>
                {/foreach}
            </table>
        </fieldset>
    </div>
    <div>
        <fieldset>
            <legend>渠道贡献</legend>
            <table>
                <tr>
                    <th>渠道</th>
                    <th>ip</th>
                    <th>uv</th>
                    <th>pv</th>
                </tr>
                {foreach $froms as $vo}
                <tr>
                    <td>{if strlen($vo['from'])}{$vo['from']}{else}直接访问{/if}</td>
                    <td>{$vo.ip}</td>
                    <td>{$vo.uv}</td>
                    <td>{$vo.pv}</td>
                </tr>
                {/foreach}
            </table>
        </fieldset>
    </div>
    <div>
        <fieldset>
            <legend>终端分析</legend>
            <table>
                <tr>
                    <th>终端类型</th>
                    <th>ip</th>
                    <th>uv</th>
                    <th>pv</th>
                </tr>
                {foreach $types as $vo}
                <tr>
                    <td>{$vo.type}</td>
                    <td>{$vo.ip}</td>
                    <td>{$vo.uv}</td>
                    <td>{$vo.pv}</td>
                </tr>
                {/foreach}
            </table>
        </fieldset>
    </div>
    <div>
        <fieldset>
            <legend>新老访客</legend>
            <table>
                <tr>
                    <th>类型</th>
                    <th>ip</th>
                    <th>uv</th>
                    <th>pv</th>
                </tr>
                {foreach $firsts as $vo}
                <tr>
                    <td>{if $vo['first']}新访客{else}老访客{/if}</td>
                    <td>{$vo.ip}</td>
                    <td>{$vo.uv}</td>
                    <td>{$vo.pv}</td>
                </tr>
                {/foreach}
            </table>
        </fieldset>
    </div>
    <div>
        <fieldset>
            <legend>按国家分析</legend>
            <table>
                <tr>
                    <th>国家</th>
                    <th>ip</th>
                    <th>uv</th>
                    <th>pv</th>
                </tr>
                {foreach $countrys as $vo}
                <tr>
                    <td>{$vo.country}</td>
                    <td>{$vo.ip}</td>
                    <td>{$vo.uv}</td>
                    <td>{$vo.pv}</td>
                </tr>
                {/foreach}
            </table>
        </fieldset>
    </div>
    <div>
        <fieldset>
            <legend>按省份分析</legend>
            <table>
                <tr>
                    <th>国家</th>
                    <th>省份</th>
                    <th>ip</th>
                    <th>uv</th>
                    <th>pv</th>
                </tr>
                {foreach $provinces as $vo}
                <tr>
                    <td>{$vo.country}</td>
                    <td>{$vo.province}</td>
                    <td>{$vo.ip}</td>
                    <td>{$vo.uv}</td>
                    <td>{$vo.pv}</td>
                </tr>
                {/foreach}
            </table>
        </fieldset>
    </div>
    <div>
        <fieldset>
            <legend>按城市分析</legend>
            <table>
                <tr>
                    <th>国家</th>
                    <th>省份</th>
                    <th>城市</th>
                    <th>ip</th>
                    <th>uv</th>
                    <th>pv</th>
                </tr>
                {foreach $citys as $vo}
                <tr>
                    <td>{$vo.country}</td>
                    <td>{$vo.province}</td>
                    <td>{$vo.city}</td>
                    <td>{$vo.ip}</td>
                    <td>{$vo.uv}</td>
                    <td>{$vo.pv}</td>
                </tr>
                {/foreach}
            </table>
        </fieldset>
    </div>
    <div>
        <fieldset>
            <legend>服务提供商</legend>
            <table>
                <tr>
                    <th>提供商</th>
                    <th>ip</th>
                    <th>uv</th>
                    <th>pv</th>
                </tr>
                {foreach $ISPs as $vo}
                <tr>
                    <td>{$vo.ISP}</td>
                    <td>{$vo.ip}</td>
                    <td>{$vo.uv}</td>
                    <td>{$vo.pv}</td>
                </tr>
                {/foreach}
            </table>
        </fieldset>
    </div>
</div>
{include common/footer@psrphp/admin}