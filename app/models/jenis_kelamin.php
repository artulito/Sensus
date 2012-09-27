<?php

class JenisKelamin extends AppModel
{
	public $useTable = "jenis_kelamin";
	public $displayField = "nama";
	public $hasOne = array( "Penduduk" );
}

?>
