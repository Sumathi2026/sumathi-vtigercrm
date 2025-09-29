<?php
class Settings_Google_Connector_Google {
    
    public static function getInstance() {
        return new self();
    }

    public function authorizeUri() {
        return 'https://accounts.google.com/o/oauth2/auth';
    }

    public function tokenUri() {
        return 'https://oauth2.googleapis.com/token';
    }

    public function userInfoUri() {
        return 'https://www.googleapis.com/oauth2/v1/userinfo';
    }

    public function getScopes() {
        return [
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
            'https://mail.google.com/'
        ];
    }

    public function getServiceName() {
        return 'Google';
    }
}
