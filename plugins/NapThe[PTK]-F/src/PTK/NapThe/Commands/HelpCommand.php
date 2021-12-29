<?php
namespace PTK\NapThe\Commands;

use PTK\NapThe\Main;
use pocketmine\command\CommandSender;

class HelpCommand{
    public function execute(Main $plugin, CommandSender $sender, $label, array $args){
        $sender->sendMessage($plugin->prefix."§a/vitien napthe <vina | mobi | viettel | vtc | gate> <Số Seri> <Mã Thẻ> §eđể nạp thẻ");
        $sender->sendMessage($plugin->prefix."§a/vitien kttk §eđể kiểm tra số points trong tài khoản");
        $sender->sendMessage($plugin->prefix."§a/vitien muarank <1 | 2 | 3 | 4 | 5 | 6> §eđể nâng rank tương ứng");
        $sender->sendMessage($plugin->prefix."§a/vitien doixu <số xu> §eđể đổi từ points sang §eGold");
        return true;
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

