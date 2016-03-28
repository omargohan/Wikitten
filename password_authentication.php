<?php

class PasswordAuthentication {

  const passwordPath = __DIR__ . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'passwd';

  static protected function isAuthenticationEnabled() {
    return ENABLE_PASSWORD_AUTHENTICATION;
  }

  static public function hasPasswordBeenSet() {
    return is_readable(self::passwordPath) && !empty(file_get_contents(self::passwordPath));
  }

  static protected function setPassword($password) {
    if(empty($password))
      return false;

    file_put_contents(self::passwordPath, sha1($password));

    return true;
  }

  static protected function checkPassword($password) {
    $correctPassword = file_get_contents(self::passwordPath);

    if(sha1($password) === $correctPassword) {
      $_SESSION['isAuthenticated'] = true;
      return true;
    }
    else {
      $_SESSION['isAuthenticated'] = false;
      return false;
    }
  }

  static public function authenticate($password) {
    if(!ENABLE_PASSWORD_AUTHENTICATION)
      return true;

    if(PasswordAuthentication::hasPasswordBeenSet())
      return PasswordAuthentication::checkPassword($password);
    else
      return PasswordAuthentication::setPassword($password);
  }

  static protected function isAuthenticated() {
    return isset($_SESSION['isAuthenticated']) && $_SESSION['isAuthenticated'];
  }

  static public function isAuthenticationRequired() {
    return self::isAuthenticationEnabled() && !self::isAuthenticated();
  }
}

?>
