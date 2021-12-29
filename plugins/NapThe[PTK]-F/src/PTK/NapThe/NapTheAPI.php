<?php
namespace PTK\NapThe;
use PTK\NapThe\Main;
class NapTheAPI{
    private $plugin;
    public function NapTheAPI(Main $plugin){
        $this->plugin = $plugin;
    }

    public function look($playername){
        $amount = $this->plugin->storage->look($playername);
        if ($amount<0){
            $amount = 0;
        }
        return $amount;
    }
    
    public function take($playername, $amount){
        if ($this->look($playername)>=$amount){
            $this->give($playername, intval($amount)*-1);
            return true;
        }
        return false;
    }
    
    public function give($playername, $amount){
        $total = $this->look($playername) + intval($amount);
        $this->plugin->storage->update($playername, $total);
    }
}

