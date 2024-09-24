<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/19 01:09
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Router;

use Freewind\Core\Result;
use Freewind\Extend\IExtend;
use Typecho\Db\Exception;
use Typecho\Widget\Request;

abstract class IExtendRouter
{
    protected $table;

    protected $ordered;

    protected $fieldList;


    abstract function page();

    abstract function lstCache(): array;

    abstract function verification($item);

    abstract function condation(Request $request): array;

    function order(Request $request): void
    {
        $params = $request->get(IExtend::ORDER_PARAM);
        $ods = explode(";", $params);
        foreach ($ods as $line) {
            if ($line) {
                $info = explode("----", $line);
                try {
                    IExtend::order($this->table, $info, $this->ordered, $this->lstCache());
                } catch (Exception $e) {
                    Result::error("保存失败", $e->getMessage());
                    break;
                }
            }
        }
        Result::success("保存成功");
    }

    function delete(Request $request): void
    {
        if ($request->isPost()) {
            $id = $request->get('id');
            if (!$id) {
                Result::error("待删除数据不能为空");
            }
            try {
                IExtend::delete($this->table, $id, $this->lstCache());
                Result::success("删除成功");
            } catch (Exception $e) {
                Result::error("删除失败", $e->getMessage());
            }
        }
        Result::error("不被请允许的请求方式");
    }

    function save(Request $request): void
    {
        $item = [];
        foreach ($this->fieldList as $field) {
            if ($request->get($field)) {
                $item[$field] = $request->get($field);
            }
        }
        $this->verification($item);
        try {
            IExtend::save($item, $this->table, $this->lstCache());
        } catch (Exception $e) {
            Result::error("保存失败");
        }
        Result::success("保存成功");
    }

    function search(Request $request): void
    {
        $pageNum = $request->get('pageNum', 1);
        $pageSize = $request->get('pageSize', 1);
        $cond = $this->condation($request);
        try {
            Result::success("查询成功", [
                'count' => IExtend::count($this->table, $cond),
                'rows' => IExtend::search($this->table, $pageNum, $pageSize, $cond, $this->ordered)
            ]);
        } catch (Exception $e) {
            Result::error("查询失败");
        }
    }

}