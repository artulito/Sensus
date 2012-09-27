<?php
class UsersController extends AppController
{

public $helpers = array( "Html", "Form", "Time", "Javascript", "Ajax" );

var $layout = 'simple';

function register()
{
	// jika sudah login, redirect ke index
	$username = $this->Session->read('user');
	if ($username){
	$this->redirect('/inhabitants/');}

	$this->pageTitle = 'Pendaftaran';
	
	// set pesan error
	$this->set('username_error', 'Username harus diisi');
	$this->set('passkey_error', 'Passkey harus diisi');

	if (!empty($this->data))
	{
		// Jika validasi didalam model berhasil
		if ($this->User->validates($this->data))
		{
			// cek jika username sudah terdaftar
			if ($this->User->findByUsername($this->data['User']['username']))
			{
				$this->User->invalidate('username');
				$this->set('username_error', 'User sudah terdaftar');
			} 
			// cek jika passkey diisi dengan benar
			//elseif (md5($this->data['User']['passkey']) != '5d7dfde8b2f2c0b32a9bb9a7b07671d5')
			elseif ($this->data['User']['passkey'] != 'foo')
			{
				$this->User->invalidate('passkey');
				$this->set('passkey_error', 'Passkey salah');
			}
			// validasi selesai
			else			
			{
				// set dan simpan data
				$this->data['User']['password'] = md5($this->data['User']['password']);
				$this->data['User']['last_login'] = date("Y-m-d H:i:s");
				
				$this->User->save($this->data);				
				// Langsung login		
				$this->Session->write('user', $this->data['User']['username']);
				$this->redirect('/users/index');
			}
		// Jika validasi dalam model tidak berhasil
		} else {
			$this->validateErrors($this->User);
		}
	}
}

function status()
{
	// jika belum login, redirect ke login
	$username = $this->Session->read('user');
	if (!$username){
	$this->redirect('/users/login');}
	
	// set knownusers
	$this->set('knownusers', $this->User->findAll(null, array('id', 'username',
	'first_name', 'last_name', 'last_login'), 'id DESC'));
}

function login()
{
	// jika sudah login, redirect ke index
	$username = $this->Session->read('user');
	if ($username){
	$this->redirect('/inhabitants/');}
	
	$this->pageTitle = 'Login';
	
	$this->set('error', false);
	// jika form yg disubmit tidak kosong
	if ($this->data)
	{
	// jika username dan password cocok
	$results = $this->User->findByUsername($this->data['User']['username']);
		if ($results && $results['User']['password'] ==
		md5($this->data['User']['password']))
		{
			$this->Session->write('user', $this->data['User']['username']);
			$this->Session->write('last_login', $results['User']['last_login']);
			$results['User']['last_login'] = date("Y-m-d H:i:s");
			$this->User->save($results);
			// redirect
			$this->redirect('/users/');
		} else {
			$this->set('error', true);
		}
	}
}

function logout()
{
	// delete session dan redirect ke index
	$this->Session->delete('user');
	$this->redirect('/inhabitants/');
}

function index()
{
	// jika sudah login, redirect ke index
	$username = $this->Session->read('user');
		if ($username){
		$this->redirect('/inhabitants/');}	
	
	// Alternatif: coba tampilkan info
	$username = $this->Session->read('user');
	if ($username)
	{
		$results = $this->User->findByUsername($username);
		$this->set('User', $results['User']);
		$this->set('last_login', $this->Session->read('last_login'));
	} else {
		$this->redirect('/users/login');
	}
}

}
?>