<?php


class SimplexParser{

    private $_parseString;
    private $_parseStringVector;
    private $_parseStringPointer;

    public function __construct($parseString)
    {
        $this->_parseString = $parseString;
        $this->_parseStringVector  = null;
    }    

    public function parse($parseString = null)
    {
        if(empty($parseString) && empty($this->_parseString)){
            throw new Exception ("Cannot parse undefined string");
        } else if (!empty($parseString)){
            $this->_parseString = $parseString;
        }

        $this->_parse();
    }

    private function _parse()
    {
        $this->_parseStringVector = 
        $this->_parse_getFunctionOption();        
    }

    private function _parse_getFuncionOption()
    {
        $this->_parseStringPointer = 0;
        $char = $this->_parseString[$this->_parseStringPointer];
    }
}
