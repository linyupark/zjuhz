<?php 
require_once 'Zend/Search/Lucene/Analysis/Analyzer.php'; 
require_once 'Zend/Search/Lucene/Analysis/Analyzer/Common.php'; 
class CNLuceneAnalyzer extends Zend_Search_Lucene_Analysis_Analyzer_Common { 
    
    private $_position; 
    
    //private $_cnStopWords = array('的','是','了'); 
    
    public function setCnStopWords($cnStopWords){ 
        $this->_cnStopWords = $cnStopWords; 
    } 
    
    /**
     * 提前清除特殊符号
     */
    public function cleanup($input)
    {
    	$searchCN = array( "：", "）", "（", "．", "。", "，", "！", "；", "“", "”", "‘", "’", "［", "］", "、", "—", "　", "《", "》", "－", "…", "【", "】");
		$searchEN = array(",","/","\\",".", ";", ":", "\"", "!", "~", "`", "^", "(", ")", "?", "-", "\t", "\n", "'", "<", ">", "\r", "\r\n", "$", "&", "%", "#", "@", "+", "=", "{", "}", "[", "]");
        $input = str_replace($searchCN,'',$input);
        $input = str_replace($searchEN,'',$input);
        return $input; 
    }
  
    /** 
     * Reset token stream 
     */ 
    public function reset() 
    { 
        $this->_position = 0; 
/*
//拆分的分割符
$searchCN = array( "：", "）", "（", "．", "。", "，", "！", "；", "“", "”", "‘", "’", "［", "］", "、", "—", "　", "《", "》", "－", "…", "【", "】");
$searchEN = array(",","/","\\",".", ";", ":", "\"", "!", "~", "`", "^", "(", ")", "?", "-", "\t", "\n", "'", "<", ">", "\r", "\r\n", "$", "&", "%", "#", "@", "+", "=", "{", "}", "[", "]");
        $this->_input = str_replace($searchCN,'',$this->_input); 
        $this->_input = str_replace($searchEN,'',$this->_input); 
        //$this->_input = str_replace($this->_cnStopWords,' ',$this->_input); */
    }
  
    /** 
     * Tokenization stream API 
     * Get next token 
     * Returns null at the end of stream 
     * 
     * @return Zend_Search_Lucene_Analysis_Token|null 
     */ 
    public function nextToken() 
    { 
        if ($this->_input === null) { 
            return null; 
        } 
        $len = strlen($this->_input); 
        while ($this->_position < $len) { 
            while ($this->_position < $len && 
                    $this->_input[$this->_position]==' ' ) { 
                $this->_position++; 
            } 
            $termStartPosition = $this->_position;  
            $temp_char = $this->_input[$this->_position]; 
            $isCnWord = false; 
            // 特殊字符
            if(ord($temp_char)>127){  
                $i = 0;       
                while ($this->_position < $len && ord( $this->_input[$this->_position] )>127) { 
                    $this->_position = $this->_position + 3; 
                    $i ++; 
                    if($i==4){ 
                        $isCnWord = true; 
                        break; 
                    } 
                } 
                if($i==1){ continue; } 
                // 非特殊
            }else{ 
                while ($this->_position < $len && ctype_alpha( $this->_input[$this->_position] )) { 
                    $this->_position++; 
                } 
            } 
            if ($this->_position == $termStartPosition) { 
                $this->_position++; 
                continue; 
            } 
  
            $token = new Zend_Search_Lucene_Analysis_Token( 
                                      substr($this->_input, 
                                             $termStartPosition, 
                                             $this->_position - $termStartPosition), 
                                      $termStartPosition, 
                                      $this->_position); 
            $token = $this->normalize($token); 
            if ($token !== null) { 
                return $token; 
            } 
        } 
        return null; 
    }
    
} 
?>