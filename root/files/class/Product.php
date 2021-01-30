<?php
    abstract class  Product {
        public $personId;
        public $product;
        public $db;

        abstract protected function add($personId, $product);
        abstract protected function edit($personId, $product);
	}

