<?php

// Kiểm tra xem hàm banner đã được định nghĩa chưa trước khi khai báo lại
if (!function_exists('banner')) {
function banner() {
system('clear');
$banner = "
\033[1;33m╔═════════════════════════════════════════════════╗
\033[1;33m║                                                 \033[1;33m║
\033[1;33m║  \033[1;39m████████╗██╗  ██╗                              \033[1;33m║
\033[1;33m║  \033[1;39m╚══██╔══╝██║  ██║  \033[1;32m Admin\033[1;37m : \033[1;36mThiệu Hoàng        \033[1;33m║
\033[1;33m║     \033[1;39m██║   ███████║   \033[1;32mNgày\033[1;37m : \033[1;36m" . date('d/m/Y H:i:s') . "\033[1;33m ║
\033[1;33m║     \033[1;39m██║   ██╔══██║  \033[1;32m YouTube\033[1;37m : \033[1;36m@thieuhoang75    \033[1;33m║
\033[1;33m║     \033[1;39m██║   ██║  ██║  \033[1;32m Version\033[1;37m : \033[1;36mTool Gộp Vip     \033[1;33m║
\033[1;33m║     \033[1;39m╚═╝   ╚═╝  ╚═╝                              \033[1;33m║
\033[1;33m║      \033[1;32mBox Zalo \033[1;37m: \033[1;36mhttps://zalo.me/g/ahnoav496   \033[1;33m  ║
\033[1;33m╚═════════════════════════════════════════════════╝ 
";
foreach (str_split($banner) as $X) {
    echo $X;
    usleep(1250);
}
}
}

// Cấu hình GitHub
$GITHUB_TOKEN = "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";
$USER = "thtool";
$REPO = "tool_golike";
$BRANCH = "main";

$file_map = [
    "1.1" => "Golike/TikTok_v1.php",
    "1.2" => "Golike/Instagram.php",
    "1.3" => "Golike/ig.php",
    "1.4" => "Golike/Twitter.php",
    "1.5" => "Golike/Thread.php",
    "1.6" => "Golike/Snapchat.php",
    "1.7" => "Golike/Linkedin_v1.php",
    "1.8" => "Golike/shoppe.php",
    "2.0" => "TDS/TikTok.php",
];

try {
    while (true) {
        system(php_uname('s') == 'Windows NT' ? 'cls' : 'clear');
        banner();
        echo "\033[1;37m╔══════════════════════╗\n";
        echo "\033[1;37m║  \033[1;32mTool Auto Golike    \033[1;37m║\n";
        echo "\033[1;37m╚══════════════════════╝\n";                   
        echo "\033[1;97m[\033[1;32m*\033[1;97m] \033[1;33m1.1 \033[1;97m: \033[1;34mTool Auto TikTok V1 \033[1;33m[Vip] \033[1;32m[Online]\n";            
        echo "\033[1;97m[\033[1;32m*\033[1;97m] \033[1;33m1.2 \033[1;97m: \033[1;34mTool Auto Instagram \033[1;32m[Online]\n";            
        echo "\033[1;97m[\033[1;32m*\033[1;97m] \033[1;33m1.3 \033[1;97m: \033[1;34mTool Auto Instagram V1 \033[1;33m[Vip] \033[1;32m[Online]\n";            
        echo "\033[1;97m[\033[1;32m*\033[1;97m] \033[1;33m1.4 \033[1;97m: \033[1;34mTool Auto Twitter \033[1;32m[Online]\n";            
        echo "\033[1;97m[\033[1;32m*\033[1;97m] \033[1;33m1.5 \033[1;97m: \033[1;34mTool Auto Threads \033[1;32m[Online]\n";            
        echo "\033[1;97m[\033[1;32m*\033[1;97m] \033[1;33m1.6 \033[1;97m: \033[1;34mTool Auto Snapchat \033[1;32m[Online]\n";  
        echo "\033[1;97m[\033[1;32m*\033[1;97m] \033[1;33m1.7 \033[1;97m: \033[1;34mTool Auto Linkedin \033[1;33m[Vip] \033[1;32m[Online]\n";  
        echo "\033[1;97m[\033[1;32m*\033[1;97m] \033[1;33m1.8 \033[1;97m: \033[1;34mTool Auto Shoppe \033[1;33m[Vip] \033[1;32m[Online]\n";  
        echo "\033[1;37m╔══════════════════════╗         \n";
        echo "\033[1;37m║  \033[1;32mTool TraoDoiSub.com \033[1;37m║          \n";
        echo "\033[1;37m╚══════════════════════╝           \n";
        echo "\033[1;97m[\033[1;32m*\033[1;97m] \033[1;33m2.0 \033[1;97m: \033[1;34mTool TDS TikTok \033[1;33m[Vip] \033[1;32m[Online]\n";          
        echo "\033[97m════════════════════════════════════════════════\n";
        echo "\033[1;91m┌─╼\033[1;97m[\033[1;91m<\033[1;97m/\033[1;91m>\033[1;97m]--\033[1;91m>\033[1;97m Nhập lựa chọn \033[1;97m \n\033[1;91m└─╼\033[1;91m✈ \033[1;33m : ";
        $chon = trim(fgets(STDIN));
        echo "\033[97m════════════════════════════════════════════════\n";

        if (array_key_exists($chon, $file_map)) {
            $FILE_PATH = $file_map[$chon];
            $FILE_PATH_ENCODED = rawurlencode($FILE_PATH);

            $url = "https://api.github.com/repos/{$USER}/{$REPO}/contents/{$FILE_PATH_ENCODED}?ref={$BRANCH}";
            $headers = [
                "Authorization: token {$GITHUB_TOKEN}",
                "User-Agent: GolikeApp",
                "Accept: application/vnd.github.v3+json"
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code == 200) {
                $data = json_decode($response, true);
                if (isset($data['content'])) {
                    $file_content = base64_decode($data['content']);
                    
                    // Xóa thẻ PHP nếu có
                    $file_content = preg_replace('/^\s*<\?php/', '', $file_content);
                    $file_content = preg_replace('/\?>\s*$/', '', $file_content);
                    
                    // Tạo file tạm và include để an toàn hơn eval
                    $temp_file = tempnam(sys_get_temp_dir(), 'php_');
                    file_put_contents($temp_file, "<?php\n" . $file_content);
                    include $temp_file;
                    unlink($temp_file);
                    
                    break;
                } else {
                    echo "File không có nội dung hoặc định dạng không đúng!\n";
                }
            } else {
                die("\033[1;31mMạng yếu không thể kết nối đến API, vui lòng vào lại tool.");
            }
            echo "\033[97m════════════════════════════════════════════════\n";
        } else {
            echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[31mLựa chọn không hợp lệ, vui lòng chọn lại!\n";
            sleep(2);
            echo "\033[97m════════════════════════════════════════════════\n";
        }
    }
} catch (Exception $e) {
    die("\033[1;31mMạng yếu không thể kết nối đến API, vui lòng vào lại tool.");
}
?>
