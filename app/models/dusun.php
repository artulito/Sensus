<?php

class Dusun extends AppModel
{
	public $useTable = "dusun";                         
	public $displayField = "nama";
	public $hasOne = array( "Penduduk" );
}

?>
