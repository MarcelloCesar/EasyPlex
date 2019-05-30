<?php

require_once('knapsackitem.php');
class KnapsackProblem {
    
    private $itemTable;
    private $_bestPath;
    private $_bestValue;
    private $_bestWeight;

    public function __construct()
    {
        $this->_itemTable = array();   
        $this->_bestPath  = null;
        $this->_bestValue = null;     
    }

    public function addItem(KnapsackItem $item)
    {
        $this->_itemTable[] = $item;
    }

    public function solve($maxWeight)
    {
        $result =  $this->_avaliate(0, $maxWeight);
        
        $answer = array();
        foreach($result[1] as $solution){
            $answer[] = $this->getItemAt($solution)->getName();
        }
        $this->_bestPath   = implode(", ", $answer);
        $this->_bestValue  = $result[0];
        $this->_bestWeight = $result[2];
    }

    public function getItemAt($position)
    {
        return $this->_itemTable[$position];
    }

    public function getBestPath()
    {
        return $this->_bestPath;
    }

    public function getBestValue()
    {
        return $this->_bestValue;
    }

    public function getBestWeight()
    {
        return $this->_bestWeight;
    }

    public function _avaliate($item_index_i_want_to_take, $max_weight_i_can_still_take)
    {
        if($max_weight_i_can_still_take === 0 
           || $item_index_i_want_to_take >= count($this->_itemTable)
        ) {
            return array(0, array(), 0);
        
        } else  if (
            $this->_itemTable[$item_index_i_want_to_take]->getWeight() > $max_weight_i_can_still_take
        ) {
            return $this->_avaliate($item_index_i_want_to_take + 1, $max_weight_i_can_still_take);

        } else {
            $path1 = $this->_avaliate($item_index_i_want_to_take + 1, $max_weight_i_can_still_take);

            $path2 = $this->_avaliate(
                $item_index_i_want_to_take + 1, 
                $max_weight_i_can_still_take - $this->_itemTable[$item_index_i_want_to_take]->getWeight()
            );

            $path2[0] += $this->_itemTable[$item_index_i_want_to_take]->getValue();
            array_unshift($path2[1], $item_index_i_want_to_take);

            $path2[2] += $this->_itemTable[$item_index_i_want_to_take]->getWeight();

            if($path1[0] > $path2[0]){
                return $path1;
            } else {
                return $path2;
            }
        }
    }
}