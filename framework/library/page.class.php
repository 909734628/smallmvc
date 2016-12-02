<?php
/**
 * Created by PhpStorm.
 * User: myPersonalFile
 * Date: 2016/12/1
 * Time: 上午11:10
 */
class page
{
    private $total;
    private $pagesize;
    private $current;
    private $maxpage;

    /**
     * page constructor.
     * @param $total （总记录数 数据库查询）
     * @param $pagesize （每页显示的条数）
     * @param $current （当前页）
     */
    public function __construct($total, $pagesize, $current)
    {
        $this->total = $total;
        $this->pagesize = $pagesize;
        $this->current = max($current, 1);
        $this->maxpage = ceil($total / $pagesize);
    }

    /**
     * @return string 返回分页查询关键语句；
     */
    public function getLimit()
    {
        $lim = ($this->current - 1) * $this->pagesize;
        return $lim . ',' . $this->pagesize;
    }

    private function getUrlParams()
    {
        unset($_GET['page']);
        return http_build_query($_GET);
    }

    public function showPage()
    {
        if ($this->maxpage <= 1) {
            return '';
        }
        $url = $this->getUrlParams();
        $url = $url ? "?$url&page=" : '?page=';
        $first = "<a href='" . $url . "1'>[首页]</a>";

        $prev = ($this->current == 1) ? '[上一页]' : "<a href='" . $url . ($this->current - 1) . "'>[上一页]</a>";

        $next = ($this->current == $this->maxpage) ? '[下一页]' : "<a href='" . $url . ($this->current + 1) . "'>[下一页]</a>";

        $last = "<a href='" . $url . $this->maxpage . "'>[尾页]</a>";

        return "当前为 $this->current/$this->maxpage " . $first . $prev . $next . $last;

    }
}
