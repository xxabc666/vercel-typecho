<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-28 21:36
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
namespace Freewind\Core;

class FreewindUtils
{
    public static function linkColor(): string
    {
        $colors = ['#23B7E4', '#23B7E4', '#26C14C', '#111111', '#F9D632', '#7266BA', '#F0504F', '#23B7E4'];
        return $colors[array_rand($colors)];
    }

    public static function getRandstr($length)
    {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $randStr = str_shuffle($str);//打乱字符串
        $rands = substr($randStr, 0, $length);//substr(string,start,length);返回字符串的一部分
        return $rands;
    }

    public static function printCalendar(): string
    {
        $mdays = date("t");    //当月总天数
        $datenow = date("j");  //当日日期
        $monthnow = date("n"); //当月月份
        $yearnow = date("Y");  //当年年份
//计算当月第一天是星期几
        $wk1st = date("w", mktime(0, 0, 0, $monthnow, 1, $yearnow));
        $trnum = ceil(($mdays + $wk1st) / 7); //计算表格行数
//以下是表格字串
        $tabstr = "<table class='tc-calendar'><tr class='tc-week'><td>日</td><td>一</td><td>二</td><td>三</td><td>四</td><td>五</td><td>六</td></tr>";
        for ($i = 0; $i < $trnum; $i++) {
            $tabstr .= "<tr class=even>";
            for ($k = 0; $k < 7; $k++) { //每行七个单元格
                $tabidx = $i * 7 + $k; //取得单元格自身序号
                //若单元格序号小于当月第一天的星期数($wk1st)或大于(月总数+$wk1st)
                //只填写空格，反之，写入日期
                ($tabidx < $wk1st or $tabidx > $mdays + $wk1st - 1) ? $dayecho = "&nbsp" : $dayecho = $tabidx - $wk1st + 1;
                //突出标明今日日期
                // $dayecho="<span style=\"background-color:red;color:#fff;\">$dayecho</span>";
                if ($dayecho == $datenow) {
                    $todaybg = " class=current";
                } else {
                    $todaybg = "";
                }
                $tabstr .= "<td" . $todaybg . ">$dayecho</td>";
            }
            $tabstr .= "</tr>";
        }
        $tabstr .= "</table>";
        return $tabstr;
    }
}