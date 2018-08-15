<?php
abstract class Product
{
    public $title;
    public $price;
    private $render;
    abstract public function get();
}
abstract class Render
{
    abstract public function get($obj);
}
class Phone extends Product
{
    public $title;
    public $price;
    private $render;
    function __construct($t, $p, $r)
    {
        $this ->title = $t;
        $this ->price = $p;
        $this ->render = $r;
    }
    public function get()
    {
        return $this->render->get($this);
    }
}

class DiscontPriceRender extends Render
{
    protected $txt;
    public function get($obj)
    {
        $this->txt ="<div class =\"product\"> \n";
        foreach($obj as $k =>$v)
        {
            if ($k === 'price') {
                $v = $v - 15*($v/100);
            }
            if ($k == 'title') {
                $v.=" discont 15%";
            }
            $this->txt .="\t <div class=\"$k\">$v</div>\n  ";
        }
        $this->txt .="</div>\n";
        return $this->txt;
    }
}
class ClearPriceRender extends Render
{
    protected $txt;
    public function get($obj)
    {
        $this->txt ="<div class =\"product\"> \n";
        foreach($obj as $k =>$v)
        {
            $this->txt .="\t <div class=\"$k\">$v</div>\n  ";
        }
        $this->txt .="</div>\n";
        return $this->txt;
    }
}

$phone = new Phone("Phone", 550, new DiscontPriceRender);
echo $phone->get();
