<?php

class InhabitantsController extends AppController
{
	public $uses = array(	"Penduduk",
							"JenisKelamin", "Agama",
							"Dusun"
						);
	public $helpers = array( "Html", "Form", "Time", "Javascript", "Ajax" );
	
	var $layout = 'default';
	
	public function index()
	{
		$this->pageTitle = 'Daftar Dusun';
		$this->layout = 'simple';
		
		$username = $this->Session->read('user');
		if ($username){
		$this->set( "user", $username );
		} else {
		$this->set( "user", null );
		}
		
		$daftarDusun = $this->Dusun->findAll( null, "DISTINCT `Dusun`.`id`, `Dusun`.`nama`", "`Dusun`.`nama` ASC");
		$this->set( "daftarDusun", $daftarDusun );
	
		$daftarDaerah = $this->Penduduk->findAll( null, "DISTINCT `Penduduk`.`dusun_id`, `Penduduk`.`rt`, `Penduduk`.`rw`, `Dusun`.`id`, `Dusun`.`nama`", "`Dusun`.`nama` ASC" );
		$this->set( "daftarDaerah", $daftarDaerah );
		
		$jumlahPenduduk = $this->Penduduk->findCount();
		$this->set( "jumlahPenduduk", $jumlahPenduduk);
		
		// Untuk Tampilan
		$this->set( "baris", "4" );
		$this->set( "counter", "0" );
		
		$this->render();
	}
	
	public function dusun( $dusun )
	{
		$namaDusun = $this->Dusun->findAll( "`Dusun`.`id` = `$dusun`", "DISTINCT `Dusun`.`nama`");
		$this->set( "namaDusun", $namaDusun['0']['Dusun']['nama']);
	
		$daftarDaerah = $this->Penduduk->findAll( "`Penduduk`.`dusun_id` = `$dusun` ", "DISTINCT `Penduduk`.`dusun_id`, `Penduduk`.`rt`, `Penduduk`.`rw`, `Dusun`.`id`, `Dusun`.`nama`", "`Dusun`.`nama` ASC" );
		$this->set( "daftarDaerah", $daftarDaerah );
		
		$this->pageTitle = 'Dusun '.$namaDusun['0']['Dusun']['nama'];
		
		$this->render();
	}
	
	public function stats()
	{	
		$this->pageTitle = 'Statistik';
		$this->layout = 'plotr';
		
		// Jenis Kelamin
		
		$syarat = "`Penduduk`.`jenis_kelamin_id` = `1`";
		$this->set( "pria", $this->Penduduk->findCount($syarat) );
		
		$syarat = "`Penduduk`.`jenis_kelamin_id` = `2`";
		$this->set( "wanita", $this->Penduduk->findCount($syarat) );
		
		// Agama
		
		$syarat = "`Penduduk`.`agama_id` = `1`";
		$this->set( "islam", $this->Penduduk->findCount($syarat) );
		
		$syarat = "`Penduduk`.`agama_id` = `2`";
		$this->set( "kristen", $this->Penduduk->findCount($syarat) );
		
		$syarat = "`Penduduk`.`agama_id` = `3`";
		$this->set( "katolik", $this->Penduduk->findCount($syarat) );
		
		$syarat = "`Penduduk`.`agama_id` = `4`";
		$this->set( "hindu", $this->Penduduk->findCount($syarat) );
		
		$syarat = "`Penduduk`.`agama_id` = `5`";
		$this->set( "budha", $this->Penduduk->findCount($syarat) );
		
		$syarat = "`Penduduk`.`agama_id` = `6`";
		$this->set( "lainnya", $this->Penduduk->findCount($syarat) );
		
		// Pendidikan
		
		$syarat = "`Penduduk`.`pendidikan` LIKE 'SD'";
		$temp = $this->Penduduk->findCount($syarat);
		$this->set( "SD", $temp );
		
		$syarat = "`Penduduk`.`pendidikan` LIKE 'SLTP'";
		$temp = $this->Penduduk->findCount($syarat);
		$this->set( "SMP", $temp );
		
		$syarat = "`Penduduk`.`pendidikan` LIKE 'SLTA'";
		$temp = $this->Penduduk->findCount($syarat);
		$this->set( "SMA", $temp );
		
		$syarat = "`Penduduk`.`pendidikan` LIKE 'D3'";
		$temp = $this->Penduduk->findCount($syarat);
		$this->set( "D3", $temp );
		
		$syarat = "`Penduduk`.`pendidikan` LIKE 'S1'";
		$temp = $this->Penduduk->findCount($syarat);
		$this->set( "S1", $temp );
		
		$syarat = "`Penduduk`.`pendidikan` LIKE 'SD Belum Tamat' OR `Penduduk`.`pendidikan` LIKE 'Belum Tamat SD'";
		$temp = $this->Penduduk->findCount($syarat);
		$this->set( "_SD", $temp );
		
		$syarat = "`Penduduk`.`pendidikan` LIKE 'Belum Sekolah' OR `Penduduk`.`pendidikan` LIKE 'Belum'";
		$temp = $this->Penduduk->findCount($syarat);
		$this->set( "belum", $temp );
		
		$syarat = "`Penduduk`.`pendidikan` LIKE 'Tidak Sekolah'";
		$temp = $this->Penduduk->findCount($syarat);
		$this->set( "non", $temp );
		
		// Pekerjaan
		
		$syarat = "`Penduduk`.`pekerjaan` LIKE 'PNS'";
		$temp = $this->Penduduk->findCount($syarat);
		$this->set("PNS", $temp);		
		
		$this->set("swasta",$this->Penduduk->findCount("`Penduduk`.`pekerjaan` LIKE 'Pegawai Swasta'"));
		$this->set("wiraswasta", $this->Penduduk->findCount("`Penduduk`.`pekerjaan` LIKE 'Wiraswasta'"));
		$this->set("buruh", $this->Penduduk->findCount("`Penduduk`.`pekerjaan` LIKE 'Buruh'"));
		$this->set("petani", $this->Penduduk->findCount("`Penduduk`.`pekerjaan` LIKE 'Petani'"));
		$this->set("pedagang", $this->Penduduk->findCount("`Penduduk`.`pekerjaan` LIKE 'Pedagang'"));
		$this->set("pelajar", $this->Penduduk->findCount("`Penduduk`.`pekerjaan` LIKE 'Pelajar'"));
		$this->set("ibu_rt", $this->Penduduk->findCount("`Penduduk`.`pekerjaan` LIKE 'Ibu Rumah Tangga'"));
		$this->set("menganggur", $this->Penduduk->findCount("`Penduduk`.`pekerjaan` LIKE 'Menganggur'"));
		
		$this->render();	
	}
	
	public function search()
	{
		$this->pageTitle = 'Pencarian';
		
		$this->render();
	}
	
	public function view( $uniqueId )
	{
		$dataUnik = $this->Penduduk->decodeUniqueId( $uniqueId );
        //debug( $dataUnik );exit;
		
		$syarat  = "`Penduduk`.`nama` = '{$dataUnik['Penduduk']['nama']}'";
		$syarat .= " AND `Penduduk`.`dusun_id` = {$dataUnik['Penduduk']['dusun_id']}";
		$syarat .= " AND `Penduduk`.`rt` = {$dataUnik['Penduduk']['rt']}";
		$syarat .= " AND `Penduduk`.`rw` = {$dataUnik['Penduduk']['rw']}";
		$syarat .= " AND `Penduduk`.`tanggal_lahir` = '{$dataUnik['Penduduk']['tanggal_lahir']}'";
		
		$dataKepalaKeluarga = $this->Penduduk->find($syarat);
		$daftarAnggotaKeluarga = $this->Penduduk->findAll( "`Penduduk`.`nomor_kepala_keluarga` = '{$this->Penduduk->getUniqueId($dataKepalaKeluarga)}'", null, "`tanggal_lahir` ASC" );
		
		$this->set( "dataKepalaKeluarga", $dataKepalaKeluarga );
		$this->set( "daftarAnggotaKeluarga", $daftarAnggotaKeluarga );
		
		$this->pageTitle = $dataUnik['Penduduk']['nama'];
		$this->layout = 'simple';
		
		$this->render();
	}
	
	public function add()
	{
		$username = $this->Session->read('user');
		if (!$username){
		$this->redirect('/users/login');}	
		
		$this->pageTitle = 'Tambah Data';
		
		if( !empty( $this->data ) ) {
			$dataAlamat = $this->data['Alamat'];
			
			$this->Session->write( "dataAlamat", $dataAlamat );
			unset( $this->data['Alamat'] );
			
			// Memulai proses save
			$jumlahKegagalan = 0;
			foreach( $this->data as $dataPenduduk ) {
				if( !empty( $dataPenduduk['Penduduk']['nama'] ) &&
					!empty( $dataPenduduk['Penduduk']['dusun_id'] ) &&
                    !empty( $dataPenduduk['Penduduk']['rt'] ) &&
                    !empty( $dataPenduduk['Penduduk']['rw'] ) &&
                    !empty( $dataPenduduk['Penduduk']['tanggal_lahir'] )
					) {
						// Memasukkan data alamat
						$dataPenduduk['Penduduk']['dusun_id'] = $dataAlamat['dusun'];         
						$dataPenduduk['Penduduk']['rt'] = intval($dataAlamat['rt']);
						$dataPenduduk['Penduduk']['rw'] = intval($dataAlamat['rw']); 
						
						// Memasukkan data lainnya
						$dataPenduduk['Penduduk']['agama_id'] = $dataPenduduk['Penduduk']['agama'];
						$dataPenduduk['Penduduk']['jenis_kelamin_id'] = $dataPenduduk['Penduduk']['jenis_kelamin'];
						unset( $dataPenduduk['Penduduk']['agama'] );
						unset( $dataPenduduk['Penduduk']['jenis_kelamin'] );
						
						if( $dataPenduduk['Penduduk']['status_kepala_keluarga'] == "Y" ) {
							$uniqueId = $this->Penduduk->getUniqueId( $dataPenduduk );
							$dataPenduduk['Penduduk']['nomor_kepala_keluarga'] = 0;
						}
						elseif( $dataPenduduk['Penduduk']['status_kepala_keluarga'] == "N" ) {
							$dataPenduduk['Penduduk']['nomor_kepala_keluarga'] = $uniqueId;
						}
						
						$this->Penduduk->create();
						if( !$this->Penduduk->save( $dataPenduduk ) ) {
							$jumlahKegagalan++;
						}
				}
			}                                                    
			if( $jumlahKegagalan == 0 ) {
				$this->flash( "Berhasil save",  "/inhabitants/add" );
			}
			else {
				$this->flash( "Terdapat kegagalan", "/inhabitants/add" );
			}
		}
		else {
			if( $this->Session->check( "dataAlamat" ) ) {
				$dataAlamat = $this->Session->read( "dataAlamat" );
			}
			else {
				$dataAlamat['dusun'] = null;
				$dataAlamat['rt'] = null;
				$dataAlamat['rw'] = null;
			}
			
			$this->set( "dataAlamat", $dataAlamat );
			$this->render();
		}
	}
	
	public function edit( $uniqueId )
	{
		$username = $this->Session->read('user');
		if (!$username){
		$this->redirect('/users/login');}
		
		$this->pageTitle = 'Edit Data';
		
		if( !empty( $this->data ) ) {
			$dataAlamat = $this->data['Alamat'];
			
			$this->Session->write( "dataAlamat", $dataAlamat );
			unset( $this->data['Alamat'] );
			
			// Memulai proses save
			$jumlahKegagalan = 0;
			foreach( $this->data as $dataPenduduk ) {
				if( !empty( $dataPenduduk['Penduduk']['nama'] ) &&
					!empty( $dataPenduduk['Penduduk']['dusun_id'] ) &&
                    !empty( $dataPenduduk['Penduduk']['rt'] ) &&
                    !empty( $dataPenduduk['Penduduk']['rw'] ) &&
                    !empty( $dataPenduduk['Penduduk']['tanggal_lahir'] )
					) {
						// Memasukkan data alamat
						$dataPenduduk['Penduduk']['dusun_id'] = $dataAlamat['dusun'];         
						$dataPenduduk['Penduduk']['rt'] = $dataAlamat['rt'];
						$dataPenduduk['Penduduk']['rw'] = $dataAlamat['rw']; 
						
						// Memasukkan data lainnya
						$dataPenduduk['Penduduk']['agama_id'] = $dataPenduduk['Penduduk']['agama'];
						$dataPenduduk['Penduduk']['jenis_kelamin_id'] = $dataPenduduk['Penduduk']['jenis_kelamin'];
						unset( $dataPenduduk['Penduduk']['agama'] );
						unset( $dataPenduduk['Penduduk']['jenis_kelamin'] );
						
						if( $dataPenduduk['Penduduk']['status_kepala_keluarga'] == "Y" ) {
							$uniqueId = $this->Penduduk->getUniqueId( $dataPenduduk );
							$dataPenduduk['Penduduk']['nomor_kepala_keluarga'] = 0;
						}
						elseif( $dataPenduduk['Penduduk']['status_kepala_keluarga'] == "N" ) {
							$dataPenduduk['Penduduk']['nomor_kepala_keluarga'] = $uniqueId;
						}
						
						if( !isset( $dataPenduduk['Penduduk']['id'] ) ) {
							$this->Penduduk->create();
						}
						if( !$this->Penduduk->save( $dataPenduduk ) ) {
							$jumlahKegagalan++;    
						}
				}
			}
			
			$link = '/inhabitants/daftar/'.$dataAlamat['dusun'];
			
			if( $jumlahKegagalan == 0 ) {
				$this->flash( "Berhasil update",  $link );
			}
			else {
				$this->flash( "Terdapat kegagalan", $link );
			}
		}
		else {
			$dataUnik = $this->Penduduk->decodeUniqueId( $uniqueId );
            //debug( $dataUnik );exit;
			$syarat  = "`Penduduk`.`nama` = '{$dataUnik['Penduduk']['nama']}' AND ";
			$syarat .= "`Penduduk`.`dusun_id` = {$dataUnik['Penduduk']['dusun_id']} AND ";
			$syarat .= "`Penduduk`.`rt` = {$dataUnik['Penduduk']['rt']} AND ";
			$syarat .= "`Penduduk`.`rw` = {$dataUnik['Penduduk']['rw']} AND ";
            $syarat .= "`Penduduk`.`tanggal_lahir` = '{$dataUnik['Penduduk']['tanggal_lahir']}'";
			
			$dataKepalaKeluarga = $this->Penduduk->find($syarat);
			$dataAnggotaKeluarga = $this->Penduduk->findAll( "`Penduduk`.`nomor_kepala_keluarga` = '{$this->Penduduk->getUniqueId($dataKepalaKeluarga)}'", null, "`tanggal_lahir` ASC" );
			
			$this->set( "dataKepalaKeluarga", $dataKepalaKeluarga );
			$this->set( "dataAnggotaKeluarga", $dataAnggotaKeluarga );
			
			$this->render();
		}
	}
		
	public function auto_complete( $noRecord, $fieldName )
	{
		$this->set( "data", $this->Penduduk->findAll( "`Penduduk`.`$fieldName` LIKE '%{$this->data[$noRecord]['Penduduk'][$fieldName]}%'", "DISTINCT" . $fieldName ) );
		$this->set( "field", $fieldName );
		$this->render( "auto_complete", "ajax" );
	}
    
    public function auto_list()
    {                      
        if( isset( $this->data['Penduduk'] ) ) {
            foreach( $this->data['Penduduk'] as $field => $content ) {
                if( !empty( $content ) ) {
                    if( $field == "nama" ) {
                        $syarat[] = "`Penduduk`.`nama` LIKE '%$content%'";
                    }
                    elseif( $field == "usia_awal" ) {
                        $syarat[] = "YEAR(NOW) - YEAR(`Penduduk`.`tanggal_lahir`) >= $content";
                    }
                    elseif( $field == "usia_akhir" ) {
                        $syarat[] = "YEAR(NOW) - YEAR(`Penduduk`.`tanggal_lahir`) < $content";
                    }   
                    else {
                        if( is_numeric($content) ) {
                            $syarat[] = "`Penduduk`.`$field` = $content";
                        }
                        else {
                            $syarat[] = "`Penduduk`.`$field` = '$content'";
                        }
                    }
                }
            }
            if( isset($syarat) ) {
            $daftarPenduduk = $this->Penduduk->findAll( implode( " AND ", $syarat ), null, "`Penduduk`.`id` ASC" );
            $this->set( "daftarPenduduk", $daftarPenduduk );
			} else {
			$this->set( "daftarPenduduk", null );
			}
            $this->render( "auto_list", "ajax" );
        }
    }
    
    public function decode( $uniqueId )
    {
        $dataKepalaKeluarga = $this->Penduduk->decodeUniqueId( $uniqueId );
        debug( $dataKepalaKeluarga );
        exit;
    }
	
	// Patches :)
	
	public function patch_for_birthdate()
	{
		$this->Penduduk->patch_for_birthdate();
	}
    
    public function patch_for_truncated()
	{
		$this->Penduduk->patch_for_truncated();
	}
    
    public function patch_for_non_integer()
    {
        $this->Penduduk->patch_for_non_integer();
    }
    
    public function assertion()
    {
        //$errorField[];
		
		$daftarKepalaKeluarga = $this->Penduduk->findAll( "`Penduduk`.`status_kepala_keluarga` = 'Y'" );
        foreach( $daftarKepalaKeluarga as $dataKepalaKeluarga ) {
            $daftarUniqueId[] = $this->Penduduk->getUniqueid( $dataKepalaKeluarga );
        }
        
        $nonKepalaKeluarga = $this->Penduduk->findAll( "`Penduduk`.`status_kepala_keluarga` = 'N'" );
        
        $error = 0;
        foreach( $nonKepalaKeluarga as $dataPenduduk ) {
            if( !in_array( $dataPenduduk['Penduduk']['nomor_kepala_keluarga'], $daftarUniqueId )  ) {
                //debug( $dataPenduduk['Penduduk']['id'] . " " . $dataPenduduk['Penduduk']['nama'] );
				//$errorfield[] = $dataPenduduk;
                $error++;
            }
        }
        
		$this->set( "jumlahError", $error);
        //debug( $error );exit;
    }
}

?>