<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/15 10:35
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description: 热门文章组件
 */

namespace Freewind\Widget;

use Freewind\Config\Constants;
use Typecho\Db;
use Typecho\Db\Exception;
use Typecho\Widget\Request as WidgetRequest;
use Typecho\Widget\Response as WidgetResponse;
use Widget\Base\Contents;

class HotWidght extends Contents
{
    public function __construct(WidgetRequest $request, WidgetResponse $response, $params = null)
    {
        parent::__construct($request, $response, $params);
        $this->parameter->setDefault(['pageSize' => $this->options->postsListSize, 'parentId' => 0, 'ignoreAuthor' => false]);
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        $select = $this->select()->from('table.contents')
            ->join('table.fields', "table.contents.cid = table.fields.cid and table.fields.name = 'postType'")
            ->where("table.contents.password IS NULL OR table.contents.password = ''")
            ->where('table.contents.status = ?', Constants::POST_STATUS_PUBLISH)
            ->where('table.contents.type = ?', 'post')
            ->where("table.fields.str_value = ?", Constants::POST_TYPE_ARTICLE)
            ->limit($this->parameter->pageSize)
            ->order('table.contents.views', Db::SORT_DESC);
        $this->db->fetchAll($select, array($this, 'push'));
    }
}