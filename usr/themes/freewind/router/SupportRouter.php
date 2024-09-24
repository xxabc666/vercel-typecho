<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/14 11:49
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Router;

use Freewind\Core\Result;
use Freewind\Core\CookieHelper;
use Typecho\Db;
use Typecho\Db\Exception;
use Typecho\Widget\Request;

class SupportRouter implements IRouter
{

    /**
     * @throws Exception
     */
    function action(Request $request)
    {
        $cid = $request->get('cid');
        if (!$cid) {
            Result::error('文章不能为空');
        }
        $db = Db::get();
        $row = $db->fetchRow($db->select('support')->from('table.contents')->where('cid = ?', $cid));
        $support = CookieHelper::get('extend_contents_support');
        if (empty($support)) {
            $support = array();
        } else {
            $support = explode(',', $support);
        }
        if (!in_array($cid, $support)) {
            $db->query($db->update('table.contents')->rows(array('support' => (int)$row['support'] + 1))->where('cid = ?', $cid));
            $support[] = $cid;
            $support = implode(',', $support);
            CookieHelper::set('extend_contents_support', $support, 3600 * 12); // 每篇文章每人12小时可以点赞一次
            Result::success('点赞成功', $row['support'] + 1);
        } else {
            Result::error('该文章您已点过赞啦');
        }

    }
}