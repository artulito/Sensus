<?php

class Agama extends AppModel
{                                  
	public $useTable = "agama";
	public $displayField = "nama";
	public $hasOne = array( "Penduduk" );
}

?>
