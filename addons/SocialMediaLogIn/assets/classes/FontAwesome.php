<?php

namespace MGModule\SocialMediaLogIn\assets\classes;
use MGModule\SocialMediaLogIn as main;

class FontAwesome {
    
    protected $icon;

    public function getIconName($provider) {

        if($provider == 'Azure') {
            return strtolower($this->icon ="fa2 fa-".'windows');
        }

        if($provider == 'Microsoft') {
            return strtolower($this->icon ="fa2 fa-".'windows');
        }
        
        if($provider == 'VKontakte')
        {
            return strtolower($this->icon ="fa2 fa-".'vk');
        }
        
        return strtolower($this->icon ="fa2 fa-".$provider);
    }

}

