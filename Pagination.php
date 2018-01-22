<?php
/**
 * Created by PhpStorm.
 * User: shipSun
 * Date: 2018/1/22
 * Time: 22:11
 */

class Pagination
{
    protected $total=200;
    protected $pageSize=20;
    protected $pageNo=1;
    protected $pagingNo=5;

    protected $baseUrl='';
    protected $format="";

    public function page($total, $pageNo){
        $this->total = $total;
        $this->pageNo = $pageNo;

        $html = $this->getPageFormat();
        $html = str_replace("{prePage}", $this->getPrePageCode(), $html);
        $html = $this->showIndexPage($html);
        $html = str_replace( "{paging}", $this->getPagingCode(), $html);
        $html = $this->showLastPage($html);
        $html = str_replace("{nextPage}", $this->getNextPageCode(), $html);
        return $html;
    }
    protected function getPagingList(){
        $list = [];
        $leftLen = $this->getPagingNo()-$this->getPageCenterNo();
        $rightLen = $this->getPageCenterNo()-1;
        
    }
    protected function showIndexPage($html){
        $pagingList = $this->getPagingList();
        if($pagingList[0] > 1){
            return str_replace( "{indexPage}", $this->getIndexPageCode(), $html);
        }
        return str_replace( "{indexPage}", '', $html);
    }
    protected function showLastPage($html){
        $pagingList = $this->getPagingList();
        if(count($pagingList) == $this->getPagingNo() && $pagingList[$this->pagingNo] != $this->getTotalPageNo()){
            return str_replace( "{lastPage}", $this->getLastPageCode(), $html);
        }
        return str_replace( "{lastPage}", '', $html);
    }
    protected function getPageCenterNo(){
        return ceil($this->getPagingNo()/2);
    }
    protected function getPagingNo(){
        $pagingNo = $this->getInt($this->pagingNo);
        if(!$pagingNo){
            $this->pagingNo = 5;
        }
        return $this->pagingNo;
    }
    protected function getPageFormat(){
        if(!$this->format){
            $this->format =  '{prePage}{indexPage}{paging}{lastPage}{nextPage}';
        }
        return $this->format;
    }
    protected function getPagingCode(){
        $html = '';
        for($i=1; $i<=$this->getTotalPageNo(); ++$i){
            $html.= $this->formatCode($i);
        }
        return $html;
    }
    protected function getNextPageCode(){
        return $this->formatCode($this->getNextPageNo());
    }
    protected function getNextPageNo(){
        $nextPageNo = $this->getPageNo()+1;
        if($nextPageNo > $this->getTotalPageNo()){
            return $this->getTotalPageNo();
        }
        return $nextPageNo;
    }
    protected function getPrePageCode(){
        return $this->formatCode($this->getPrePageNo());
    }
    protected function getPrePageNo(){
        $prePageNo = $this->getPageNo()-1;
        $prePageNo = $this->getInt($prePageNo);
        if(!$prePageNo){
            $prePageNo = 1;
        }
        return $prePageNo;
    }
    protected function getIndexPageCode(){
        return $this->formatCode(1);
    }
    protected function getLastPageCode(){
        return $this->formatCode($this->getTotalPageNo());
    }
    protected function getTotalPageNo(){
        return ceil($this->total/$this->getPageSize());
    }
    protected function getTotal(){
        $this->total = $this->getInt($this->total);
        if(!$this->total){
            return 0;
        }
        return $this->total;
    }
    protected function getPageSize(){
        $this->pageSize = $this->getInt($this->pageSize);
        if(!$this->pageSize){
            $this->pageSize = 20;
        }
        return $this->pageSize;
    }
    protected function getPageNo(){
        $this->pageNo = $this->getInt($this->pageNo);
        if(!$this->pageNo){
            return 1;
        }
        return $this->pageNo;
    }
    protected function getInt($num){
        $num = intval($num);
        if($num<0){
            return 0;
        }
        return $num;
    }
    protected function formatCode($num){
        $html = '<a ';
        $html.= 'class="test" ';
        $html.= 'href="'.$this->getUrl($num).'">';
        $html.= $num;
        $html.= '</a>';
        return $html;
    }
    protected function getUrl($num){
        return $this->baseUrl.$num;
    }
}