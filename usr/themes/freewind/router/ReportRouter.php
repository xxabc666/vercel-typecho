<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/14 12:26
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Router;

use DateInterval;
use DatePeriod;
use DateTime;
use Freewind\Core\Result;
use Freewind\Core\Article;
use Typecho\Db\Exception;
use Typecho\Widget\Request;

class ReportRouter implements IRouter
{

    /**
     * @throws Exception
     * @throws \Exception
     */
    function action(Request $request)
    {
        ob_clean();
        $dateArray = self::createDateArray();
        Result::success('', [
            'article' => Article::article_count($dateArray),
            'category' => Article::metas_count(),
            'tag' => Article::metas_count('tag'),
        ]);
    }

    /**
     * @throws \Exception
     */
    private static function createDateArray(): array
    {
        $start = new DateTime('first day of ' . 5 . ' month ago');
        $end = new DateTime();
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);
        $dateArray = [];
        foreach ($period as $dt) {
            $dateArray[] = $dt->format("Y-m");
        }
        return $dateArray;
    }

}