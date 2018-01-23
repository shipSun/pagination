<?php
/**
 * Created by PhpStorm.
 * User: shipSun
 * Date: 2018/1/22
 * Time: 22:11
 */

class Pagination
{
    protected $total;
    protected $pageSize=20;
    protected $pageNo=1;
    protected $pagingNo=5;
    protected $baseUrl="";
    protected $format="";
    protected $preText="<<";
    protected $nextText=">>";
    protected $ellipsisText = "<span>...</span>";

    private $totalPageNo;
    private $prePageNo;
    private $nextPageNo;
    private $centerPageNo;
    private $pagingList;

    public function setPagingNo($pagingNo){
        $pagingNo = $this->getInt($pagingNo);
        if($pagingNo){
            $this->pagingNo = $pagingNo;
        }
    }
    public function setBaseUrl($url){
        $this->baseUrl = $url;
    }
    public function setFormat($format){
        $this->format = $format;
    }
    public function setPreText($text){
        $this->preText = $text;
    }
    public function setNextText($text){
        $this->nextText = $text;
    }
    public function setEllipsisText($text){
        $this->ellipsisText = $text;
    }
    public function page($total, $pageNo){
        $this->total = $total;
        $this->pageNo = $pageNo;

        $this->initPageFormat();
        $this->replace("{prePage}", $this->getPrePageCode());
        $this->showIndexPage();
        $this->showLeftEllipsis();
        $this->replace( "{paging}", $this->getPagingCode());
        $this->showRightEllipsis();
        $this->showLastPage();
        $this->replace("{nextPage}", $this->getNextPageCode());
        return $this->format;
    }
    protected function showLeftEllipsis(){
        $list = $this->getPagingList();
        if(count($list)==$this->getPagingNo() && $list[1] > 2){
            return $this->replace('{leftEllipsis}',$this->ellipsisText);

        }
        return $this->replace('{leftEllipsis}','');
    }
    protected function showRightEllipsis(){
        $list = $this->getPagingList();
        if(count($list) == $this->getPagingNo() && $list[$this->getPagingNo()]!=$this->getTotalPageNo()){
            return $this->replace('{rightEllipsis}',$this->ellipsisText);
        }
        return $this->replace('{rightEllipsis}','');;
    }
    protected function getPagingList()
    {
        if(!$this->pagingList){
            $list = [];
            $startPageNo = 1;
            $len = $this->getTotalPageNo();
            if($this->getTotalPageNo() > $this->getPagingNo()){
                $rightLen = $this->getPagingNo() - $this->getPageCenterNo();
                $leftLen = $this->getPageCenterNo() - 1;

                $centerPageNo = $this->getPageNo();
                if(($this->getPageNo()+$rightLen) > $this->getTotalPageNo()){
                    $centerPageNo-= $rightLen-($this->getTotalPageNo()-$this->getPageNo());
                }
                if($this->getPageNo() > $this->getPageCenterNo()){
                    $startPageNo = $centerPageNo-$leftLen;
                }

                $len = $this->getPagingNo();
            }

            for($i=1;$i<=$len;$i++){
                $list[$i] = $startPageNo++;
            }
            $this->pagingList = $list;
        }
        return $this->pagingList;
    }
    protected function showIndexPage(){
        $pagingList = $this->getPagingList();
        if($pagingList[1] > 1){
            return $this->replace( "{indexPage}", $this->getIndexPageCode());
        }
        return $this->replace( "{indexPage}", '');
    }
    protected function showLastPage(){
        $pagingList = $this->getPagingList();
        if(count($pagingList) == $this->getPagingNo() && $pagingList[$this->pagingNo] != $this->getTotalPageNo()){
            return $this->replace( "{lastPage}", $this->getLastPageCode());
        }
        return $this->replace( "{lastPage}", '');
    }
    protected function getPageCenterNo(){
        if(!$this->centerPageNo){
            $this->centerPageNo = ceil($this->getPagingNo()/2);
        }
        return $this->centerPageNo;
    }
    protected function getPagingNo(){
        $pagingNo = $this->getInt($this->pagingNo);
        if(!$pagingNo){
            $this->pagingNo = 5;
        }
        return $this->pagingNo;
    }
    protected function initPageFormat(){
        if(!$this->format){
            $this->format =  '{prePage}{indexPage}{leftEllipsis}{paging}{rightEllipsis}{lastPage}{nextPage}';
        }
        return $this->format;
    }
    protected function getPagingCode(){
        $html = '';
        foreach($this->getPagingList() as $val){
            $html.= $this->formatCode($val);
        }
        return $html;
    }
    protected function getNextPageCode(){
        return $this->formatCode($this->getNextPageNo(), $this->nextText);
    }
    protected function getNextPageNo(){
        if(!$this->nextPageNo){
            $nextPageNo = $this->getPageNo()+1;
            if($nextPageNo > $this->getTotalPageNo()){
                return $this->getTotalPageNo();
            }
            $this->nextPageNo = $nextPageNo;
        }
        return $this->nextPageNo;
    }
    protected function getPrePageCode(){
        return $this->formatCode($this->getPrePageNo(), $this->preText);
    }
    protected function getPrePageNo(){
        if(!$this->prePageNo){
            $prePageNo = $this->getPageNo()-1;
            $prePageNo = $this->getInt($prePageNo);
            if(!$prePageNo){
                $prePageNo = 1;
            }
            $this->prePageNo = $prePageNo;
        }
        return $this->prePageNo;
    }
    protected function getIndexPageCode(){
        return $this->formatCode(1);
    }
    protected function getLastPageCode(){
        return $this->formatCode($this->getTotalPageNo());
    }
    protected function getTotalPageNo(){
        if(!$this->totalPageNo){
            $this->totalPageNo = ceil($this->total/$this->getPageSize());
        }
        return $this->totalPageNo;
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
    protected function formatCode($num, $text=''){
        if(!$text){
            $text = $num;
        }
        $html = '<a ';
        if($this->getPageNo() == $num){
            $html.= 'class="current" ';
        }
        $html.= 'href="'.$this->getUrl($num).'">';
        $html.= $text;
        $html.= '</a>';
        return $html;
    }
    protected function getUrl($num){
        return $this->baseUrl.$num;
    }
    protected function replace($key, $val){
        $this->format = str_replace( $key, $val, $this->format);
        return $this->format;
    }
}