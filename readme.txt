Jabung Village Population Census
======================================================

Quick Installation
1. Import this sql file to a database:
	sensus-rekap.sql
	sensus-users.sql
2. Extract the 'sensus' folder to the server directory
2. Change the nesessary setting in file:
	sensus/app/config/database.php
3. Good Luck



Sensus Desa Jabung
=======================================================

LISENSI: belum dirilis
-------------------------------------------------------

Persyaratan:
1. Apache server dengan mod-rewrite aktif
2. MySQL database
3. PHP 5, (pernah dicoba di PHP 4.3.8 -> error)
4. A good browser (Use firefox :-)

-------------------------------------------------------

Instalasi:

1. Import data ke database kamu dari file yg tersedia,
   sensus-rekap.sql dan sensus-users.sql

2. Letakkan folder 'sensus' pada direktori server

3. Jangan lupa diedit konfigurasi database yg
   ada di sensus/app/config/database.php
	- Nama database
	- Username & Password
	- Nilai 'prefix' tidak perlu diubah ('sdj_')
	
4. Optional: jika mod-rewrite di apache tidak tersedia,
   ubah konfigurasi di sensus/app/config/core.php
   seperti berikut:

   	//define ('BASE_URL', env('SCRIPT_NAME'));

	uncomment menjadi:

	define ('BASE_URL', env('SCRIPT_NAME'));

5. Good Luck!

-------------------------------------------------------

Acknowledgements:

Cake PHP framework (http://www.cakephp.org)
Wordpress (http://www.wordpress.org/) --> grab some CSS
famfamfam icon (http://www.famfamfam.com/)
Prototype (http://www.prototypejs.org)
Script.aculo.us (http://script.aculo.us/)
Plotr Javascript library (http://www.solutoire.com/)

========================================================

Credits (Questions and Comments):
ath.angga@gmail.com
arif.hdyt@gmail.com