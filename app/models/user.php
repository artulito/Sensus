<?php
class User extends AppModel
{
var $name = 'User';
var $validate = array(
'username' => VALID_NOT_EMPTY,
'password' => VALID_NOT_EMPTY,
'email' => VALID_EMAIL,
'passkey' => VALID_NOT_EMPTY
);
}
?>