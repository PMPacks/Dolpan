<?php

/*
 *
 * ______ _____ _   __      _   ___           ______ _ *   
 * | ___ \_   _| | / /     | | / (_)          | ___ \ | *  
 * | |_/ / | | | |/ /______| |/ / _  ___ _ __ | |_/ / |__   __ _ _ __ ___  
 * |  __/  | | |    \______|    \| |/ _ \ '_ \|  __/| '_ \ / _` | '_ ` _ \ 
 * | |     | | | |\  \     | |\  \ |  __/ | | | |   | | | | (_| | | | | | |
 * \_|     \_/ \_| \_/     \_| \_/_|\___|_| |_\_|   |_| |_|\__,_|_| |_| |_|
 *
 * ___  ____            __  _____  _____ ______ _____ 
 * |  \/  (_)          /  ||  _  |/ __  \| ___ \  ___|
 * | .  . |_ _ __   ___`| || |/' |`' / /'| |_/ / |__  
 * | |\/| | | '_ \ / _ \| ||  /| |  / /  |  __/|  __| 
 * | |  | | | | | |  __/| |\ |_/ /./ /___| |   | |___ 
 * \_|  |_/_|_| |_|\___\___/\___/ \_____/\_|   \____/ *  
 *
 * Chương trình này là phần mềm miễn phí: bạn có thể phân phối lại nó và / hoặc sửa đổi
 * theo điều khoản của Giấy phép Công cộng nhỏ hơn GNU như được xuất bản bởi
 * Free Software Foundation, phiên bản 3 của Giấy phép, hoặc  * (Tùy chọn) bất kỳ phiên bản sau nào.
 *
 * @author Dolpan
 * @link https://fb.com/DolpanDev
 *
 *
*/

namespace pocketmine;

use pocketmine\event\TranslationContainer;
use pocketmine\utils\TextFormat;

/**
 * Handles the achievement list and a bit more
 */
abstract class Achievement {
	/**
	 * @var array[]
	 */
	public static $list = [
		/*"openInventory" => array(
			"name" => "Mở Rưởng Đồ",
			"requires" => [],
		),*/
		"mineWood" => [
			"name" => "Kiếm Lấy Gỗ",
			"requires" => [ //"openInventory",
			],
		],
		"buildWorkBench" => [
			"name" => "Bắt Đầu Chế Tạo",
			"requires" => [
				"mineWood",
			],
		],
		"buildPickaxe" => [
			"name" => "Đén Giờ Đào Mỏ!",
			"requires" => [
				"buildWorkBench",
			],
		],
		"buildFurnace" => [
			"name" => "Chủ Đề Nóng Bỏng",
			"requires" => [
				"buildPickaxe",
			],
		],
		"acquireIron" => [
			"name" => "Kiếm Lấy Sắt",
			"requires" => [
				"buildFurnace",
			],
		],
		"buildHoe" => [
			"name" => "Đến Giờ Trồng Trọi!",
			"requires" => [
				"buildWorkBench",
			],
		],
		"makeBread" => [
			"name" => "Nướng Bánh",
			"requires" => [
				"buildHoe",
			],
		],
		"bakeCake" => [
			"name" => "Những Lời Nói Dối",
			"requires" => [
				"buildHoe",
			],
		],
		"buildBetterPickaxe" => [
			"name" => "Nâng Cấp Đồ Nghề",
			"requires" => [
				"buildPickaxe",
			],
		],
		"buildSword" => [
			"name" => "Đến Giờ Tấn Công!",
			"requires" => [
				"buildWorkBench",
			],
		],
		"diamonds" => [
			"name" => "KIM CƯƠNG!",
			"requires" => [
				"acquireIron",
			],
		],

	];


	/**
	 * @param Player $player
	 * @param        $achievementId
	 *
	 * @return bool
	 */
	public static function broadcast(Player $player, $achievementId){
		if(isset(Achievement::$list[$achievementId])){
			$translation = new TranslationContainer("chat.type.achievement", [$player->getDisplayName(), TextFormat::GREEN . Achievement::$list[$achievementId]["name"]]);
			if(Server::getInstance()->getConfigString("announce-player-achievements", true) === true){
				Server::getInstance()->broadcastMessage($translation);
			}else{
				$player->sendMessage($translation);
			}

			return true;
		}

		return false;
	}

	/**
	 * @param       $achievementId
	 * @param       $achievementName
	 * @param array $requires
	 *
	 * @return bool
	 */
	public static function add($achievementId, $achievementName, array $requires = []){
		if(!isset(Achievement::$list[$achievementId])){
			Achievement::$list[$achievementId] = [
				"name" => $achievementName,
				"requires" => $requires,
			];

			return true;
		}

		return false;
	}


}
