<?php
class Shortener 
{    
    const DB_NAME = '';
    const DB_HOST = 'localhost';
    const DB_USERNAME = '';
    const DB_PASSWORD = '';    
    const TABLE = 'urls';
    protected $connecton;
    protected $chars = ['a', 'A', 'b', 'B', 'c', 'C', 'd', 'D',
                        'e', 'E', 'f', 'F', 'g', 'G', 'h', 'H',
                        'i', 'I', 'g', 'G', 'k', 'K', 'l', 'L',
                        'm', 'M', 'n', 'N', 'o', 'O', 'p', 'P', 
                        'q', 'Q', 'r', 'R', 's', 'S', 't', 'T', 
                        'u', 'U', 'v', 'V', 'w', 'W', 'x', 'X', 
                        'y', 'Y', 'z', 'Z'];
    
    public function __construct() 
    {
        $this->connecton = new mysqli($this::DB_HOST, $this::DB_USERNAME, $this::DB_PASSWORD, $this::DB_NAME);
    }
    
    public function generateShortLink()
    {
        $short_url = '';
        for($i=0; $i<5; $i++){
            $n = rand(0, 54);
            $short_url .= $this->chars[$n];
        }
        $rez = $this->connecton->query('SELECT * FROM `'.$this::TABLE.'` WHERE `short_url`='.$short_url);
        if(count($rez) > 0 && !$this->connecton->errno){
            $this->generateShortLink();
        }else{
            return $short_url;
        }
    }
    
    public function checkUrl($url)
    {
        $new_link = parse_url($url);
        if($new_link){
            if(empty($new_link['scheme'])){
                return 'http://'.$url;
            }else{
                return $url;
            }            
        }else{
            return false;
        }        
    }

    public function saveToBd($url, $short_url)
    {
        $url = $this->checkUrl($url);
        if($url){
            $stmt = $this->connecton->prepare("INSERT INTO `".$this::TABLE."` SET `url`=?, `short_url`=?");
            $stmt->bind_param('ss', $url, $short_url);
            $rez = $stmt->execute();
            $stmt->close();
            return $rez;
        }else{
            return false;
        }
    }
    
    public function getShortUrl($url)
    {
        $url = $this->checkUrl($url);
        if($url){
            $stmt = $this->connecton->prepare("SELECT `id`, `short_url` FROM `".$this::TABLE."` WHERE url=?");
            $stmt->bind_param('s', $url);
            $stmt->execute();
            $short_url = $stmt->get_result()->fetch_assoc()['short_url'];
            $stmt->close();
            if(!empty($short_url)){
                return $short_url;
            }else{
                $short_url = $this->generateShortLink();
                $this->saveToBd($url, $short_url);
                return $short_url;
            }
        }else{
            return false;
        }
    }
    
    public function getOriginalUrl($short_url)
    {
        $stmt = $this->connecton->prepare("SELECT `url` FROM `".$this::TABLE."` WHERE short_url=?");
        $stmt->bind_param('s', $short_url);
        $stmt->execute();
        $url = $stmt->get_result()->fetch_assoc()['url'];
        $stmt->close();
        if(!empty($url)){
            return $url;
        }else{
            return false;
        }
    }

    public function __destruct()
    {
        $this->connecton->close();
    }
    
}
