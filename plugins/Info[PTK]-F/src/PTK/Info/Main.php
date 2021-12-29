<?php

namespace PTK\Info;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener{
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info(TextFormat::GREEN . "[Info] §cĐã hoạt động!");
	}
	
	public function onDisable(){
		$this->getLogger()->info(TextFormat::RED . "[Info] Đã dừng!");
	}
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		switch($command->getName()){
			case "helps":
			   $sender->sendMessage("§r§a-=§e|§c♦§e| §aHướng Dẫn Cách Chơi§e |§c♦§e|§a=-");
			   $sender->sendMessage("§a∘ §bĐể chơi AcidIsland  bạn hãy sử dụng lệnh /is help");
			   $sender->sendMessage("§a∘ §dĐể đến các khu tiện ích của server hãy sử dụng lệnh /khu <Tên Khu> và /listkhu để xem các khu");
			   $sender->sendMessage("§a∘ §aChúc bạn chơi vui vẻ");
			   $sender->sendMessage("§o§9▃§b▃§a▃§e▃§6▃§c▃§4▃§2▃§d▃§f▃§7▃§8▃§5▃§1▃§0▃");
			   return true;
			case "luat":
			   $sender->sendMessage("§r§a-=§e|§c♦§e| §dQuy Định - Luật Chơi - §c1§f0§d2§ePE§e |§c♦§e|§a=-");
			   $sender->sendMessage("§c♦ §aKhông sử dụng các phần mềm cheat Hack...");
			   $sender->sendMessage("§c♦ §aKhông nói tục chửi thề hay gây War...");
			   $sender->sendMessage("§c♦ §aCác trường hợp khác tuỳ mức độ và hành vi. Chúng tôi sẽ xử lí sau.");
			   $sender->sendMessage("§c♦ §aKhông quảng cáo, Spam Chat trên khung chat server.");
			   $sender->sendMessage("§c♦ §aKhông tự ý giao dịch để tránh bị lừa dảo trong server.");
			   $sender->sendMessage("§o§9▃§b▃§a▃§e▃§6▃§c▃§4▃§2▃§d▃§f▃§7▃§8▃§5▃§1▃§0▃");
			   return true;			
			case "lag":
			   $sender->sendMessage("§r§a-=§e|§c♦§e| §dCách Giảm Lagg§e |§c♦§e|§a=-");
			   $sender->sendMessage("§eCách 1:§b Vào Settings->Video-> Tắt View Bobbing, Fancy Graphics, Beautiful Skies");
			   $sender->sendMessage("§eCách 2:§b Vào Settings->Video-> Bật Show Advanced...phần Render Distance kéo xuống 4 Chunks");
			  // $sender->sendMessage("§eCách 3:§b ");
			   $sender->sendMessage("§dNếu bạn còn cách nào hay hơn hãy chia sẻ ngay bằng cách §f/idea <tên> <note>");
			   $sender->sendMessage("§o§9▃§b▃§a▃§e▃§6▃§c▃§4▃§2▃§d▃§f▃§7▃§8▃§5▃§1▃§0▃");
			   return true;
			case "info":
			   $sender->sendMessage("§r§a-=§e|§c♦§e| §dGiới Thiệu Thông Tin - §c1§f0§d2§ePE§e |§c♦§e|§a=-");
			   $sender->sendMessage("§7∘ §bChào mừng đến với server §c1§f0§d2§ePE");
			   $sender->sendMessage("§7∘ §aMinecraft là game sáng tạo trong thế giới ảo, xây dựng trở thành vua của thế giới");
			   $sender->sendMessage("§7∘ §bChắc hẳn ở Việt Nam rất nhiều máy chủ hay mạnh và tối ưu hơn §c1§f0§d2§ePE");
			   $sender->sendMessage("§7∘ §a§c1§f0§d2§ePE thành lập ngày §b22/6/2017 §avà cũng là ngày mở cửa chính thức");
			   $sender->sendMessage("§7∘ §a§c1§f0§d2§ePE cũng là một máy chủ mới nhưng nhiều kinh nghiệm");
			   $sender->sendMessage("§7∘ §a§c1§f0§d2§ePE có các chế độ chơi đa dạng đáp ứng nhu cầu của các bạn");
			   $sender->sendMessage("§7∘ §eHệ thống cũng đã việt hóa phần nào để tiện cho người chơi");
			   $sender->sendMessage("§7∘ §6Survival – Sinh Tồn");
               $sender->sendMessage("§7∘ §6MiniGames – Trò chơi giải trí[Sắp Mở]");
			   $sender->sendMessage("§7∘ §6Skyblock – AcidIsland – Đảo trên trời");
			   $sender->sendMessage("§7∘ §6Op Prison – Cuộc chiến nhà tù[Sắp Mở]");
			   $sender->sendMessage("§7∘ §bMột phần nhỏ các chế độ chơi của §c1§f0§d2§ePE");
			   $sender->sendMessage("§7∘ §a§c1§f0§d2§ePE cần sự ủng hộ của các bạn, đó là động lực giúp §c1§f0§d2§ePE phát triển hơn");
			   $sender->sendMessage("§7∘ §aChúc các bạn có 1 ngày chơi vui vẻ!");
			   $sender->sendMessage("§o§9▃§b▃§a▃§e▃§6▃§c▃§4▃§2▃§d▃§f▃§7▃§8▃§5▃§1▃§0▃");
			   return true;
			case "thongbao":
			   $sender->sendMessage("§r§a-=§e|§c♦§e| §dThông Báo - Tuyển Dụng§e |§c♦§e|§a=-");
			   $sender->sendMessage("§d► Helper Team");
			   $sender->sendMessage("§r- Yêu cầu các bạn bắt buộc phải am hiểu về Minecraft và server ");
			   $sender->sendMessage("§r- Thời gian onl tổi thiểu 3-5 giờ/1 ngày");
			   $sender->sendMessage("§r- Nhiệt tình trả lời và trợ giúp các new member");
			   $sender->sendMessage("§1► Police Team");
			   $sender->sendMessage("§r- Yêu cầu các bạn phải có kĩ năng phân biệt và phát hiện hack cheat");
			   $sender->sendMessage("§r- Thời gian online tối thiểu 3-5 giờ/1 ngày");
			   $sender->sendMessage("§r- Có sức nhẫn nhịn chịu đựng áp lực trước trẻ em, biết cư xử đúng mực");
			   $sender->sendMessage("§r- Không lạm quyền bừa bãi, xử phạt theo tư cách cá nhân");
			   $sender->sendMessage("§o§9▃§b▃§a▃§e▃§6▃§c▃§4▃§2▃§d▃§f▃§7▃§8▃§5▃§1▃§0▃");
			   return true;
			case "giapoint":
			   $sender->sendMessage("§r§a-=§e|§c♦§e| §dBảng Giá Mua Point§e |§c♦§e|§a=-");
			   $sender->sendMessage("§b►§aThẻ 10.000₫ = 10 Points.");
			   $sender->sendMessage("§b►§aThẻ 20.000₫ = 20 Points.");
			   $sender->sendMessage("§b►§aThẻ 50.000₫ = 50 Points.");
			   $sender->sendMessage("§b►§aThẻ 100.000₫ = 100 Points.");
			   $sender->sendMessage("§b►§aThẻ 200.000₫ = 200 Points.");
			   $sender->sendMessage("§b►§aThẻ 500.000₫ = 500 Points.");
			   $sender->sendMessage("§b►§aXin hãy sử dụng lệnh /vitien để mua.");
			   $sender->sendMessage("§o§9▃§b▃§a▃§e▃§6▃§c▃§4▃§2▃§d▃§f▃§7▃§8▃§5▃§1▃§0▃");
			   return true;
			case "muavip":
			   $sender->sendMessage("§r§a-=§e|§c♦§e| §dBảng Giá Mua Vip§e |§c♦§e|§a=-");
			   $sender->sendMessage("§b►§a30 Points = Vip1.");
			   $sender->sendMessage("§b►§a70 Points = Vip2.");
			   $sender->sendMessage("§b►§a140 Points = Vip3.");
			   $sender->sendMessage("§b►§a200 Points = Vip4.");
			   $sender->sendMessage("§b►§a250 Points = Vip5.");
			   $sender->sendMessage("§b►§a310 Points = Vip6.");
			   $sender->sendMessage("§b►§aXin hãy sử dụng lệnh /vitien để mua.");
			   $sender->sendMessage("§o§9▃§b▃§a▃§e▃§6▃§c▃§4▃§2▃§d▃§f▃§7▃§8▃§5▃§1▃§0▃");
			   return true;
			case "giadoixu":
			   $sender->sendMessage("§r§a-=§e|§c♦§e| §dBảng Giá Mua Vip§e |§c♦§e|§a=-");
			   $sender->sendMessage("§b►§a1 Point = 450 Xu");
			   $sender->sendMessage("§b►§aXin hãy sử dụng lệnh /vitien để đổi.");
			   $sender->sendMessage("§o§9▃§b▃§a▃§e▃§6▃§c▃§4▃§2▃§d▃§f▃§7▃§8▃§5▃§1▃§0▃");
			   return true;
			case "allsv":
			   $sender->sendMessage("§r§a-=§e|§c♦§e| §eCác Server đang hoạt động §e |§c♦§e|§a=-");
		$sender->sendMessage("§f> §bLobby: Pe.Mine102.net:19132");
		$sender->sendMessage("§f> §bAcidIsland: Pe.Mine102.net:19100");
		$sender->sendMessage("§f> §bSkyBlock: Pe.Mine102.net:19101");
		$sender->sendMessage("§f> §bSurvival: Pe.Mine102.net:19102");
		$sender->sendMessage("§o§9▃§b▃§a▃§e▃§6▃§c▃§4▃§2▃§d▃§f▃§7▃§8▃§5▃§1▃§0▃");
		return true;
			default:
			   return false;
		}
	}
}