<?php

class Penduduk extends AppModel
{
	public $useTable = "penduduk";
	public $belongsTo = array( "Agama", "JenisKelamin", "Dusun" );
	
	public function getUniqueId( $dataKepalaKeluarga )
	{
		$string  = $dataKepalaKeluarga['Penduduk']['nama'] . " | ";
		$string .= $dataKepalaKeluarga['Penduduk']['dusun_id'] . " | ";
		$string .= $dataKepalaKeluarga['Penduduk']['rt'] . " | ";
        
        if( isset( $dataKepalaKeluarga['Penduduk']['tanggal_lahir'] ) ) {
		    $string .= $dataKepalaKeluarga['Penduduk']['rw'] . " | ";
		    $string .= $dataKepalaKeluarga['Penduduk']['tanggal_lahir'];
        }
        else {
            $string .= $dataKepalaKeluarga['Penduduk']['rw'];
        }
		
		return base64_encode( $string );
	}
	
	public function decodeUniqueId( $endodedString )
	{
		$decoded = base64_decode( $endodedString );
		$kepalaKeluargaInfo = explode( " | ", $decoded );
		
		$dataKepalaKeluarga['Penduduk']['nama'] = $kepalaKeluargaInfo[0];
		$dataKepalaKeluarga['Penduduk']['dusun_id'] = $kepalaKeluargaInfo[1];
		$dataKepalaKeluarga['Penduduk']['rt'] = $kepalaKeluargaInfo[2];
		$dataKepalaKeluarga['Penduduk']['rw'] = $kepalaKeluargaInfo[3];
        if( isset( $kepalaKeluargaInfo[4] ) )
            $dataKepalaKeluarga['Penduduk']['tanggal_lahir'] = $kepalaKeluargaInfo[4];
        
        return $dataKepalaKeluarga;
	}
    
    public function getAgeInfo( $birthDate )
	{
		// Membagi $sqlDate menjadi array 3 bagian
		$birthDateInfo = explode( "-", $birthDate );
		// Konversi $dateInfo ke UNIX time
		$birthDateTime = mktime( 0, 0, 0, $birthDateInfo[1], $birthDateInfo[2], $birthDateInfo[0] );
		// Mendapatkan umur dalam detik
		$age = time() - $birthDateTime;
		
		// Mendapatkan tahun
		$years  = floor( $age / ( 60 * 60 * 24 * 30 * 12 ) );
		// Mendapatkan bulan
		$months = floor( ($age - ( $years *  60 * 60 * 24 * 30 * 12 )) / ( 60 * 60 * 24 * 30 ) );
		// Mendapatkan hari
		$days   = floor( ($age - ( $years *  60 * 60 * 24 * 30 * 12 ) - ( $months * 60 * 60 * 24 * 30 )) / ( 60 * 60 * 24 ) );
		
		$ageInfo[0] = $ageInfo["years"]  = $years;
		$ageInfo[1] = $ageInfo["months"] = $months;
		$ageInfo[2] = $ageInfo["days"]   = $days;
		
		return $ageInfo;
	}
    
	// Patches :)
	
    private function getUniqueIdWithoutBirthDate( $dataKepalaKeluarga )
    {
        $string = $dataKepalaKeluarga['Penduduk']['nama'] . " | ";
		$string .= $dataKepalaKeluarga['Penduduk']['dusun_id'] . " | ";
		$string .= $dataKepalaKeluarga['Penduduk']['rt'] . " | ";
		$string .= $dataKepalaKeluarga['Penduduk']['rw'];
		
		return base64_encode( $string );
    }
    
    public function patch_for_birthdate()
    {
        $daftarKepalaKeluarga = $this->findAll( "`Penduduk`.`status_kepala_keluarga` = 'Y'" );
        
        foreach( $daftarKepalaKeluarga as $dataKepalaKeluarga ) {
            $uniqueIdWithoutBirthDate = $this->getUniqueIdWithoutBirthDate( $dataKepalaKeluarga );
            $uniqueId = $this->getUniqueId( $dataKepalaKeluarga );
            //debug( $uniqueIdWithoutBirthDate );
            $anggotaKeluargaBelumDipatch = $this->findAll( "`Penduduk`.`nomor_kepala_keluarga` = '{$uniqueIdWithoutBirthDate}'" );
            
            foreach( $anggotaKeluargaBelumDipatch as $dataAnggotaKeluarga ) {
                //debug( $dataAnggotaKeluarga['Penduduk']['nama'] );
                $this->id = $dataAnggotaKeluarga['Penduduk']['id'];
                $this->saveField( "nomor_kepala_keluarga", $uniqueId );
            }
        }
    }
    
    public function patch_for_truncated()
    {
        $daftarKepalaKeluarga = $this->findAll( "`Penduduk`.`status_kepala_keluarga` = 'Y'" );
        
        foreach( $daftarKepalaKeluarga as $dataKepalaKeluarga ) {
            $uniqueId = $this->getUniqueId( $dataKepalaKeluarga );
            $truncatedUniqueId = substr( $uniqueId, 0, 63 );
            
            $anggotaKeluargaBelumDipatch = $this->findAll( "`Penduduk`.`nomor_kepala_keluarga` = '{$truncatedUniqueId}'" );
            foreach( $anggotaKeluargaBelumDipatch as $dataAnggotaKeluarga ) {
                $this->id = $dataAnggotaKeluarga['Penduduk']['id'];
                $this->saveField( "nomor_kepala_keluarga", $uniqueId );
            }
        }
    }
    
    public function patch_for_non_integer()
    {
        $nonKepalaKeluarga = $this->findAll( "`Penduduk`.`status_kepala_keluarga` = 'N'" );
        
        foreach( $nonKepalaKeluarga as $dataPenduduk ) {
            $dataKepalaKeluarga = $this->decodeUniqueId( $dataPenduduk['Penduduk']['nomor_kepala_keluarga'] );
            
            // Membenarkan rt dan rw non-numeric
            $dataKepalaKeluarga['Penduduk']['rt'] = intval( $dataKepalaKeluarga['Penduduk']['rt'] );
            $dataKepalaKeluarga['Penduduk']['rw'] = intval( $dataKepalaKeluarga['Penduduk']['rw'] );
            //debug( $dataKepalaKeluarga );
            $uniqueId = $this->getUniqueId( $dataKepalaKeluarga );
            
            $this->id = $dataPenduduk['Penduduk']['id'];
            $this->saveField( "nomor_kepala_keluarga", $uniqueId );
        }
    }
	
	// Patch Kepala Keluarga
	public function patch_kk()
	{
		$daftarKepalaKeluarga = $this->findAll( 
		"`Penduduk`.`status_kepala_keluarga` = 'Y'",
		"`Penduduk`.`id`, `Penduduk`.`nama`, `Penduduk`.`dusun_id`, `Penduduk`.`rt`, `Penduduk`.`rw`, `Penduduk`.`tanggal_lahir` " 
		);
		
		//$this->set("a", $daftarKepalaKeluarga );
		
		foreach( $daftarKepalaKeluarga as $dataKepalaKeluarga ) {
			$uniqueId =  "0"; //$this->getUniqueId( $dataKepalaKeluarga );
			$this->id = $dataKepalaKeluarga['Penduduk']['id'];
			$this->saveField( "nomor_kepala_keluarga", $uniqueId );
		}
	}
	
}

?>