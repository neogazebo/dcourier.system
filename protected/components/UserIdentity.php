<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_id;
    public $_ip;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $user = User::model()->find('username=?', array($this->username));
        if ($user == NULL) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif (!$user->validatePassword($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } elseif($user->active != 1){
            $this->errorCode=self::ERROR_UNKNOWN_IDENTITY;
        }else {
            $this->errorCode = self::ERROR_NONE;
            $this->_id = $user->id;
            $this->_ip = $_SERVER['REMOTE_ADDR'];
            $this->setState('email', $user->email);
            $this->setState('ip address',  $this->_ip);
            $session = Yii::app()->session;
            $id =$session->getSessionID();
            $session->setSessionItem($id,'user_id',  $this->_id);
            $user->access =time();
            $user->update();
        }

        return!$this->errorCode;
    }
	
		public function getId()
		{
				return $this->_id;
		}

}