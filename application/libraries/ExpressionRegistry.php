<?php 
    /**
     * This class enables registration of EM expressions.
     * These expressions will be converted to javascript and stored in a hash table.
     * This allows for dynamic expressions to be executed.
     */
    class ExpressionRegistry 
    {
        protected $expressions = array();
        
        protected $em = null;

        public function __construct() 
        {
            $this->em = new ExpressionManager();
            
        }
        /**
         * This function most compile an EM expression to valid javascript.
         * It must not check variable names.
         */
        public function compile($expression)
        {
            return "function() { return '$expression'; }";
        }

        /**
         * Registers an expression.
         * @param type $expression
         */
        public function register($expression)
        {
            $key = md5($expression);
            if (isset($this->expressions[$key]))
            {
                $javascript = $this->compile($expression);
            }
            $this->expressions[$key] = $javascript;
        }
        
        
        
    }


?>