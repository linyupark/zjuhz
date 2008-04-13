<?php 
require_once 'Zend/Search/Lucene/Analysis/Analyzer.php'; 
require_once 'Zend/Search/Lucene/Analysis/Analyzer/Common.php'; 
class CNLuceneAnalyzer extends Zend_Search_Lucene_Analysis_Analyzer_Common { 
    
    private $_position; 
  
    /** 
     * Reset token stream 
     */ 
    public function reset() 
    { 
        $this->_position = 0;
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
    	// 如果没有输入的内容
    	if($this->_input === null)
    		return null;
    	// 字符串总长度
    	$len = strlen($this->_input);
    	// 字符长度范围内做..
    	while ($this->_position < $len)
    	{
    		if($this->_input[$this->_position] == ' ')
    		{
    			$this->_position++; //如果是分词点就向后
    			continue;
    		}
    		
    		else // 非分词就记录当前位置
    		{
    			$termStartPosition = $this->_position;
    			while ($this->_position < $len && $this->_input[$this->_position] != ' ')
    			{
    				$this->_position++;
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
    	}
    	return null; 
    /*
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
                    if($i==2){ 
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
        return null;*/
    }
    
} 
?>