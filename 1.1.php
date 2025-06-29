<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Hàm kiểm tra kết nối ADB
function check_adb_connection() {
    try {
        $result = shell_exec("adb devices 2>&1");
        $devices = array();
        $lines = explode("\n", $result);
        foreach ($lines as $line) {
            if (strpos($line, "\tdevice") !== false) {
                $parts = explode("\t", $line);
                $devices[] = $parts[0];
            }
        }
        if (count($devices) > 0) {
            echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;32m✈ \033[1;32mThiết bị đã được kết nối qua ADB.\033[0m\n";
            return array(true, $devices[0]);
        } else {
            echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mKhông có thiết bị nào được kết nối qua ADB.\033[0m\n";
            return array(false, null);
        }
    } catch (Exception $e) {
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;31m✈ \033[1;31mKhông thể chạy lệnh ADB. Vui lòng kiểm tra lại cài đặt ADB.\033[0m\n";
        return array(false, null);
    }
}

// Hàm lưu thông tin thiết bị
function save_device_info($device_id) {
    file_put_contents("device_info.txt", $device_id);
    echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32m✅ Đã lưu thông tin thiết bị.\033[0m\n";
}

// Hàm đọc thông tin thiết bị
function load_device_info() {
    if (file_exists("device_info.txt")) {
        $device_id = file_get_contents("device_info.txt");
        $device_id = trim($device_id);
        echo "\033[1;97m════════════════════════════════════════════════\n";
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;33mĐã tải thông tin kết nối từ thiết bị.\033[0m\n";
        return $device_id;
    } else {
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mKhông tìm thấy file thông tin thiết bị.\033[0m\n";
        return null;
    }
}

// Hàm lưu tọa độ vào file
function save_coordinates($follow_x, $follow_y, $back_x, $back_y, $like_x, $like_y) {
    $content = "follow_x=$follow_x\nfollow_y=$follow_y\nback_x=$back_x\nback_y=$back_y\nlike_x=$like_x\nlike_y=$like_y\n";
    file_put_contents("coordinates.txt", $content);
    echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;33m✅ Đã lưu tọa độ vào thiết bị.\033[0m\n";
}

// Hàm đọc tọa độ từ file
function load_coordinates() {
    if (file_exists("coordinates.txt")) {
        $coordinates = array();
        $lines = file("coordinates.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($key, $value) = explode("=", $line);
            $coordinates[$key] = intval($value);
        }
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mĐã tải tọa độ từ thiết bị.\033[0m\n";
        return $coordinates;
    } else {
        echo "\033[1;97m════════════════════════════════════════════════\n";
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mKhông tìm thấy file tọa độ.\033[0m\n";
        return null;
    }
}

// Hàm kết nối thiết bị Android 11
function connect_android_11() {
    while (true) {
        try {
            echo "\033[1;36mNhập IP của thiết bị (ví dụ: 192.168.100.3): ";
            $ip = trim(fgets(STDIN));
            echo "\033[1;36mNhập cổng khi bật gỡ lỗi không dây (ví dụ: 43487): ";
            $debug_port = trim(fgets(STDIN));
            echo "\033[1;36mNhập cổng khi ghép nối thiết bị (ví dụ: 40833): ";
            $pair_port = trim(fgets(STDIN));
            echo "\033[1;36mNhập mã ghép nối Wi-Fi: ";
            $wifi_code = trim(fgets(STDIN));

            shell_exec("adb pair $ip:$pair_port $wifi_code");
            shell_exec("adb connect $ip:$debug_port");

            list($is_connected, $device_id) = check_adb_connection();
            if ($is_connected) {
                save_device_info($device_id);
                echo "\033[1;32mThiết bị đã kết nối thành công qua ADB!\033[0m\n";
                return true;
            } else {
                echo "\033[1;31mKhông thể kết nối thiết bị. Vui lòng kiểm tra lại thông tin.\033[0m\n";
            }
        } catch (Exception $e) {
            echo "\033[1;31mĐã xảy ra lỗi: " . $e->getMessage() . "\033[0m\n";
        }
    }
}

// Hàm kết nối thiết bị Android 10
function connect_android_10() {
    while (true) {
        try {
            echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;36mNhập IP của thiết bị (ví dụ: 192.168.100.3): ";
            $ip = trim(fgets(STDIN));
            echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;36mNhập cổng khi bật gỡ lỗi không dây (ví dụ: 5555): ";
            $debug_port = trim(fgets(STDIN));

            shell_exec("adb connect $ip:$debug_port");

            list($is_connected, $device_id) = check_adb_connection();
            if ($is_connected) {
                save_device_info($device_id);
                echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mThiết bị đã kết nối thành công qua ADB!\033[0m\n";
                return true;
            } else {
                echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31m❌ Không thể kết nối thiết bị. Vui lòng kiểm tra lại IP và cổng.\033[0m\n";
            }
        } catch (Exception $e) {
            echo "\033[1;31mĐã xảy ra lỗi: " . $e->getMessage() . "\033[0m\n";
        }
    }
}

// Hàm để thực hiện thao tác chạm trên màn hình
function tap_screen($x, $y) {
    shell_exec("adb shell input tap " . intval($x) . " " . intval($y));
}

function bes4($url) {
    try {
        $response = file_get_contents($url, false, stream_context_create([
            'http' => ['timeout' => 5]
        ]));
        
        if ($response !== false) {
            $doc = new DOMDocument();
            @$doc->loadHTML($response);
            $xpath = new DOMXPath($doc);
            
            $version_tag = $xpath->query("//span[@id='version_keyADB']")->item(0);
            $maintenance_tag = $xpath->query("//span[@id='maintenance_keyADB']")->item(0);
            
            $version = $version_tag ? trim($version_tag->nodeValue) : null;
            $maintenance = $maintenance_tag ? trim($maintenance_tag->nodeValue) : null;
            
            return array($version, $maintenance);
        }
    } catch (Exception $e) {
        return array(null, null);
    }
    return array(null, null);
}

function checkver() {
    $url = 'https://checkserver.hotrommo.com/';
    list($version, $maintenance) = bes4($url);
    if ($maintenance == 'on') {
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mTool đang được bảo trì. Vui lòng thử lại sau. \nHoặc vào nhóm Tele: \033[1;33mhttps://t.me/+77MuosyD-yk4MGY1\n";
        exit();
    }
    return $version;
}

$current_version = checkver();
if ($current_version) {
    echo "Phiên bản hiện tại: $current_version\n";
} else {
    echo "Không thể lấy thông tin phiên bản hoặc tool đang được bảo trì.\n";
    exit();
}
system('clear');
// Hàm hiển thị banner

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


banner();
echo "\033[1;97m[\033[1;91m❣\033[1;97m]\033[1;97m Địa chỉ Ip\033[1;32m  : \033[1;32m☞\033[1;31m♔ \033[1;32m83.86.8888\033[1;31m♔ \033[1;97m☜\n";
echo "\033[1;97m════════════════════════════════════════════════\n";
// In menu lựa chọn
echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập \033[1;31m1 \033[1;33mđể vào \033[1;34mTool Tiktok\033[1;33m\n"; 
echo "\033[1;31m\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mNhập 2 Để Xóa Authorization Hiện Tại'\n";

// Vòng lặp để chọn lựa chọn (Xử lý cả trường hợp chọn sai)
while (true) {
    try {
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập Lựa Chọn (1 hoặc 2): ";
        $choose = trim(fgets(STDIN));
        $choose = intval($choose);
        if ($choose != 1 && $choose != 2) {
            echo "\033[1;31m\n❌ Lựa chọn không hợp lệ! Hãy nhập lại.\n";
            continue;
        }
        break;
    } catch (Exception $e) {
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mSai định dạng! Vui lòng nhập số.\n";
    }
}

// Xóa Authorization nếu chọn 2
if ($choose == 2) {
    $file = "Authorization.txt";
    if (file_exists($file)) {
        if (unlink($file)) {
            echo "\033[1;32m[✔] Đã xóa $file!\n";
        } else {
            echo "\033[1;31m[✖] Không thể xóa $file!\n";
        }
    } else {
        echo "\033[1;33m[!] File $file không tồn tại!\n";
    }
    echo "\033[1;33m👉 Vui lòng nhập lại thông tin!\n";
}

// Kiểm tra và tạo file nếu chưa có
$file = "Authorization.txt";
if (!file_exists($file)) {
    if (file_put_contents($file, "") === false) {
        echo "\033[1;31m[✖] Không thể tạo file $file!\n";
        exit(1);
    }
}

// Đọc thông tin từ file
$author = "";
if (file_exists($file)) {
    $author = file_get_contents($file);
    if ($author === false) {
        echo "\033[1;31m[✖] Không thể đọc file $file!\n";
        exit(1);
    }
    $author = trim($author);
}

// Yêu cầu nhập lại nếu Authorization trống
while (empty($author)) {
    echo "\033[1;97m════════════════════════════════════════════════\n";
    echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập Authorization: ";
    $author = trim(fgets(STDIN));

    // Ghi vào file
    if (file_put_contents($file, $author) === false) {
        echo "\033[1;31m[✖] Không thể ghi vào file $file!\n";
        exit(1);
    }
}

// Chạy tool
$headers = [
    'Accept-Language' => 'vi,en-US;q=0.9,en;q=0.8',
    'Referer' => 'https://app.golike.net/',
    'Sec-Ch-Ua' => '"Not A(Brand";v="99", "Google Chrome";v="121", "Chromium";v="121"',
    'Sec-Ch-Ua-Mobile' => '?0',
    'Sec-Ch-Ua-Platform' => "Windows",
    'Sec-Fetch-Dest' => 'empty',
    'Sec-Fetch-Mode' => 'cors',
    'Sec-Fetch-Site' => 'same-site',
    'T' => 'VFZSak1FMTZZM3BOZWtFd1RtYzlQUT09',
    'User-Agent' => 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36',
    "Authorization" => $author,
    'Content-Type' => 'application/json;charset=utf-8'
];

echo "\033[1;97m════════════════════════════════════════════════\n";
echo "\033[1;32m🚀 Đăng nhập thành công! Đang vào Tool Tiktok...\n";
sleep(1);

// Hàm chọn tài khoản TikTok
function chonacc() {
    global $headers;
    $json_data = array();
    $response = file_get_contents('https://gateway.golike.net/api/tiktok-account', false, stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => buildHeaders($headers),
            'content' => json_encode($json_data)
        ]
    ]));
    return json_decode($response, true);
}

// Hàm nhận nhiệm vụ
function nhannv($account_id) {
    global $headers;
    $params = array(
        'account_id' => $account_id,
        'data' => 'null'
    );
    $json_data = array();
    $url = 'https://gateway.golike.net/api/advertising/publishers/tiktok/jobs?' . http_build_query($params);
    $response = file_get_contents($url, false, stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => buildHeaders($headers),
            'content' => json_encode($json_data)
        ]
    ]));
    return json_decode($response, true);
}

// Hàm hoàn thành nhiệm vụ
// Ẩn tất cả lỗi và cảnh báo PHP
error_reporting(0);
ini_set('display_errors', 0);

function hoanthanh($ads_id, $account_id) {
    global $headers;
    
    $json_data = array(
        'ads_id' => $ads_id,
        'account_id' => $account_id,
        'async' => true,
        'data' => null
    );

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => buildHeaders($headers),
            'content' => json_encode($json_data),
            'ignore_errors' => true // Không hiển thị lỗi của file_get_contents
        ]
    ]);

    $response = @file_get_contents('https://gateway.golike.net/api/advertising/publishers/tiktok/complete-jobs', false, $context);

    if ($response === false) {
        return ['error' => 'Không thể kết nối đến server!'];
    }

    // Lấy mã HTTP từ phản hồi
    $http_code = 0;
    if (isset($http_response_header) && preg_match('/HTTP\/\d\.\d\s(\d+)/', $http_response_header[0], $matches)) {
        $http_code = (int)$matches[1];
    }

    // Trả về lỗi nếu mã HTTP không phải 200
    if ($http_code !== 200) {
        return ['error' => "Lỗi HTTP $http_code"];
    }

    return json_decode($response, true);
}

// Hàm báo lỗi
function baoloi($ads_id, $object_id, $account_id, $loai) {
    global $headers;
    
    $json_data1 = array(
        'description' => 'Báo cáo hoàn thành thất bại',
        'users_advertising_id' => $ads_id,
        'type' => 'ads',
        'provider' => 'tiktok',
        'fb_id' => $account_id,
        'error_type' => 6
    );
    $response1 = file_get_contents('https://gateway.golike.net/api/report/send', false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => buildHeaders($headers),
            'content' => json_encode($json_data1)
        ]
    ]));
    
    $json_data = array(
        'ads_id' => $ads_id,
        'object_id' => $object_id,
        'account_id' => $account_id,
        'type' => $loai
    );
    $response = file_get_contents('https://gateway.golike.net/api/advertising/publishers/tiktok/skip-jobs', false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => buildHeaders($headers),
            'content' => json_encode($json_data)
        ]
    ]));
    return json_decode($response, true);
}

// Hàm hỗ trợ xây dựng headers
function buildHeaders($headers) {
    $headerString = "";
    foreach ($headers as $key => $value) {
        $headerString .= "$key: $value\r\n";
    }
    return $headerString;
}

// Lấy danh sách tài khoản TikTok
$chontktiktok = chonacc();

// Hiển thị danh sách tài khoản
function dsacc() {
    global $chontktiktok;
    while (true) {
        try {
            if ($chontktiktok["status"] != 200) {
                echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mAuthorization hoặc T sai hãy nhập lại!!!\n";
                echo "\033[1;97m════════════════════════════════════════════════\n";
                exit();
            }
            banner();
            echo "\033[1;97m[\033[1;91m❣\033[1;97m]\033[1;97m Địa chỉ Ip\033[1;32m  : \033[1;32m☞\033[1;31m♔ \033[1;32m83.86.8888\033[1;31m♔ \033[1;97m☜\n";
            echo "\033[1;97m════════════════════════════════════════════════\n";
            echo "\033[1;97m[\033[1;91m❣\033[1;97m]\033[1;33m Danh sách acc Tik Tok : \n";
            echo "\033[1;97m════════════════════════════════════════════════\n";
            for ($i = 0; $i < count($chontktiktok["data"]); $i++) {
                echo "\033[1;36m[".($i + 1)."] \033[1;36m✈ \033[1;97mID\033[1;32m㊪ :\033[1;93m ".$chontktiktok["data"][$i]["unique_username"]." \033[1;97m|\033[1;31m㊪ :\033[1;32m Hoạt Động\n";
            }
            echo "\033[1;97m════════════════════════════════════════════════\n";
            break;
        } catch (Exception $e) {
            echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32m".json_encode($chontktiktok)."\n";
            sleep(10);
        }
    }
}

// Hiển thị danh sách tài khoản
dsacc();

// Chọn tài khoản TikTok
$d = 0;
while (true) {
    echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập \033[1;31mID Acc Tiktok \033[1;32mlàm việc: ";
    $idacc = trim(fgets(STDIN));
    for ($i = 0; $i < count($chontktiktok["data"]); $i++) {
        if ($chontktiktok["data"][$i]["unique_username"] == $idacc) {
            $d = 1;
            $account_id = $chontktiktok["data"][$i]["id"];
            break;
        }
    }
    if ($d == 0) {
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mAcc này chưa được thêm vào golike or id sai\n";
        continue;
    }
    break;
}

// Nhập thời gian làm job
while (true) {
    try {
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập thời gian làm job : ";
        $delay = intval(trim(fgets(STDIN)));
        break;
    } catch (Exception $e) {
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mSai định dạng!!!\n";
    }
}

// Nhận tiền lần 2 nếu lần 1 fail
while (true) {
    try {
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhận tiền lần 2 nếu lần 1 fail? (y/n): ";
        $lannhan = trim(fgets(STDIN));
        if ($lannhan != "y" && $lannhan != "n") {
            echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mNhập sai hãy nhập lại!!!\n";
            continue;
        }
        break;
    } catch (Exception $e) {
        // Bỏ qua
    }
}

// Nhập số job fail để đổi acc TikTok
while (true) {
    try {
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mSố job fail để đổi acc TikTok (nhập 1 nếu k muốn dừng) : ";
        $doiacc = intval(trim(fgets(STDIN)));
        break;
    } catch (Exception $e) {
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mNhập vào 1 số!!!\n";
    }
}

// Hỏi xem người dùng có muốn sử dụng ADB không
while (true) {
    try {
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mBạn có muốn sử dụng Auto Like + Follow? (y/n): ";
        $auto_follow = strtolower(trim(fgets(STDIN)));
        
        if ($auto_follow != "y" && $auto_follow != "n") {
            echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mNhập sai hãy nhập lại!!!\n";
            continue;
        }
        
        if ($auto_follow == "y") {
            // Kiểm tra xem đã có thông tin thiết bị được lưu chưa
            $device_id = load_device_info();

            // Nếu không có thông tin thiết bị, yêu cầu kết nối ADB
            if (!$device_id) {
                echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mThiết bị chưa được kết nối qua ADB. Vui lòng thêm thiết bị.\033[0m\n";
                while (true) {
                    try {
                        echo "\033[1;97m════════════════════════════════════════════════\n";
                        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;33mNhập 1 Để kết nối thiết bị Android 10 .\033[0m\n";
                        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;33mNhập 2 Để kết nối thiết bị Android 11 .\033[0m\n";
                        echo "\033[1;97m════════════════════════════════════════════════\n";
                        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mVui lòng chọn: ";
                        $choose_HDH = trim(fgets(STDIN));
                        
                        if ($choose_HDH != "1" && $choose_HDH != "2") {
                            echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mNhập sai hãy nhập lại!!!\n";
                            continue;
                        }

                        if ($choose_HDH == "1") {
                            if (connect_android_10()) {
                                break;
                            }
                        } else {
                            if (connect_android_11()) {
                                break;
                            }
                        }
                    } catch (Exception $e) {
                        echo "\033[1;31mĐã xảy ra lỗi: " . $e->getMessage() . "\033[0m\n";
                    }
                }
            }

            // Kiểm tra và đọc tọa độ từ file (nếu có)
            $coordinates = load_coordinates();

            // Nếu không có file tọa độ, yêu cầu người dùng nhập
            if (!$coordinates) {
                while (true) {
                    try {
                        echo "\033[1;97m════════════════════════════════════════════════\n";
                        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập tọa độ X nút follow TikTok: ";
                        $follow_x = intval(trim(fgets(STDIN)));
                        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập tọa độ Y nút follow TikTok: ";
                        $follow_y = intval(trim(fgets(STDIN)));
                        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập tọa độ X1 nút Back TikTok: ";
                        $back_x = intval(trim(fgets(STDIN)));
                        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập tọa độ Y1 nút Back TikTok: ";
                        $back_y = intval(trim(fgets(STDIN)));
                        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập tọa độ X2 nút Like TikTok: ";
                        $like_x = intval(trim(fgets(STDIN)));
                        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập tọa độ Y2 nút Like TikTok: ";
                        $like_y = intval(trim(fgets(STDIN)));
                        echo "\033[1;97m════════════════════════════════════════════════\n";
                        // Lưu tọa độ vào file
                        save_coordinates($follow_x, $follow_y, $back_x, $back_y, $like_x, $like_y);
                        break;
                    } catch (Exception $e) {
                        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mNhập vào một số hợp lệ!!!\n";
                    }
                }
            } else {
                // Hỏi người dùng có muốn sử dụng tọa độ đã lưu không
                while (true) {
                    echo "\033[1;97m════════════════════════════════════════════════\n";
                    echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mBạn có muốn sử dụng Tọa Độ Đã Lưu? (y/n): ";
                    $choose = strtolower(trim(fgets(STDIN)));
                    
                    if ($choose != "y" && $choose != "n") {
                        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mNhập sai hãy nhập lại!!!\n";
                        continue;
                    }
                    
                    if ($choose == "y") {
                        // Sử dụng tọa độ đã lưu
                        $follow_x = $coordinates["follow_x"];
                        $follow_y = $coordinates["follow_y"];
                        $back_x = $coordinates["back_x"];
                        $back_y = $coordinates["back_y"];
                        $like_x = $coordinates["like_x"];
                        $like_y = $coordinates["like_y"];

                        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mSử dụng tọa độ đã lưu: Follow ($follow_x, $follow_y), Like ($like_x, $like_y)\033[0m\n";
                        break;
                    } else {
                        // Xóa tọa độ đã lưu và yêu cầu nhập tọa độ mới
                        if (file_exists("coordinates.txt")) {
                            unlink("coordinates.txt");
                            echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mĐã xóa tọa độ đã lưu.\033[0m\n";
                        }
                        
                        // Nhập tọa độ mới
                        while (true) {
                            try {
                                echo "\033[1;97m════════════════════════════════════════════════\n";
                                echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập tọa độ X nút follow TikTok: ";
                                $follow_x = intval(trim(fgets(STDIN)));
                                echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập tọa độ Y nút follow TikTok: ";
                                $follow_y = intval(trim(fgets(STDIN)));
                                echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập tọa độ X1 nút Back TikTok: ";
                                $back_x = intval(trim(fgets(STDIN)));
                                echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập tọa độ Y1 nút Back TikTok: ";
                                $back_y = intval(trim(fgets(STDIN)));
                                echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập tọa độ X2 nút Like TikTok: ";
                                $like_x = intval(trim(fgets(STDIN)));
                                echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập tọa độ Y2 nút Like TikTok: ";
                                $like_y = intval(trim(fgets(STDIN)));
                                echo "\033[1;97m════════════════════════════════════════════════\n";
                                // Lưu tọa độ vào file
                                save_coordinates($follow_x, $follow_y, $back_x, $back_y, $like_x, $like_y);
                                break;
                            } catch (Exception $e) {
                                echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mNhập vào một số hợp lệ!!!\n";
                            }
                        }
                        break;
                    }
                }
            }
        } else {
            echo "\033[1;33mBỏ qua kết nối ADB.\033[0m\n";
        }
        
        // Tiếp tục các bước tiếp theo của code
        break;
    } catch (Exception $e) {
        echo "\033[1;31mĐã xảy ra lỗi: " . $e->getMessage() . "\033[0m\n";
    }
}

// Chọn chế độ làm việc
while (true) {
    try {
        echo "\033[1;97m════════════════════════════════════════════════\n";
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập 1 : \033[1;33mChỉ nhận nhiệm vụ Follow\n";
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập 2 : \033[1;33mChỉ nhận nhiệm vụ like\n";
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;32mNhập 12 : \033[1;33mKết hợp cả Like và Follow\n";
        echo "\033[1;97m════════════════════════════════════════════════\n";
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;34mChọn lựa chọn: ";
        $chedo = intval(trim(fgets(STDIN)));
        
        if ($chedo == 1 || $chedo == 2 || $chedo == 12) {
            break;
        } else {
            echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mChỉ được nhập 1, 2 hoặc 12!\n";
        }
    } catch (Exception $e) {
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mNhập vào 1 số!!!\n";
    }
}

// Xác định loại nhiệm vụ
$lam = array();
if ($chedo == 1) {
    $lam = array("follow");
} elseif ($chedo == 2) {
    $lam = array("like");
} elseif ($chedo == 12) {
    $lam = array("follow", "like");
}

// Bắt đầu làm nhiệm vụ
$dem = 0;
$tong = 0;
$checkdoiacc = 0;
$checkdoiacc1 = 0;
$dsaccloi = array();
$accloi = "";
banner();
echo "\033[1;97m[\033[1;91m❣\033[1;97m]\033[1;97m Địa chỉ Ip\033[1;32m  : \033[1;32m☞\033[1;31m♔ \033[1;32m83.86.8888\033[1;31m♔ \033[1;97m☜\n";
echo "\033[1;97m════════════════════════════════════════════════\n";
echo "\033[1;36m|STT\033[1;97m| \033[1;33mThời gian ┊ \033[1;32mStatus | \033[1;31mType Job | \033[1;32mID Acc | \033[1;32mXu |\033[1;33m Tổng\n";
echo "\033[1;97m════════════════════════════════════════════════\n";

while (true) {
    if ($checkdoiacc == $doiacc) {
        dsacc();
        $idacc = readline("\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mJob fail đã đạt giới hạn nên nhập id acc khác để đổi: ");
        sleep(2);
        banner();
        echo "\033[1;97m[\033[1;91m❣\033[1;97m]\033[1;97m Địa chỉ Ip\033[1;32m  : \033[1;32m☞\033[1;31m♔ \033[1;32m83.86.8888\033[1;31m♔ \033[1;97m☜\n";
        echo "\033[1;97m════════════════════════════════════════════════\n";
        $d = 0;
        for ($i = 0; $i < count($chontktiktok["data"]); $i++) {
            if ($chontktiktok["data"][$i]["unique_username"] == $idacc) {
                $d = 1;
                $account_id = $chontktiktok["data"][$i]["id"];
                break;
            }
        }
        if ($d == 0) {
            echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;31mAcc chưa thêm vào Golike hoặc ID không đúng!\n";
            continue;
        }
        $checkdoiacc = 0;
    }

    echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;35mĐang Tìm Nhiệm vụ:>        \r";
    while (true) {
        try {
            $nhanjob = nhannv($account_id);
            break;
        } catch (Exception $e) {
            // pass
        }
    }

    // Kiểm tra job trùng (so sánh với job trước đó)
    static $previous_job = null;
    if ($previous_job !== null && 
        $previous_job["data"]["link"] === $nhanjob["data"]["link"] && 
        $previous_job["data"]["type"] === $nhanjob["data"]["type"]) {
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mJob trùng với job trước đó - Bỏ qua!        \r";
        sleep(2);
        try {
            baoloi($nhanjob["data"]["id"], $nhanjob["data"]["object_id"], $account_id, $nhanjob["data"]["type"]);
        } catch (Exception $e) {
            // pass
        }
        continue;
    }
    $previous_job = $nhanjob;

    if (isset($nhanjob["status"]) && $nhanjob["status"] == 200) {
        $ads_id = $nhanjob["data"]["id"];
        $link = $nhanjob["data"]["link"];
        $object_id = $nhanjob["data"]["object_id"];
        $loai = $nhanjob["data"]["type"];

        if (!isset($nhanjob["data"]["link"]) || empty($nhanjob["data"]["link"])) {
            static $notified = false; // Biến static giữ giá trị giữa các lần lặp
            
            if (!$notified) {
                echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mJob die - Không có link!        \r";
                sleep(2);
                $notified = true; // Đánh dấu đã thông báo
            }
            
            try {
                baoloi($nhanjob["data"]["id"], $nhanjob["data"]["object_id"], $account_id, $nhanjob["data"]["type"]);
            } catch (Exception $e) {
                // pass
            }
            continue;
        }
        if (!in_array($nhanjob["data"]["type"], $lam)) {
            try {
                baoloi($ads_id, $object_id, $account_id, $nhanjob["data"]["type"]);
                echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mĐã bỏ qua job {$loai}!        \r";
                sleep(1);
                continue;
            } catch (Exception $e) {
                // pass
            }
        }

        if ($loai == "follow") {
            $loainv = "follow";
        } elseif ($loai == "like") {
            $loainv = " like ";
        }

        // Mở link và tự động ấn nút tương ứng
                // Thử mở link và kiểm tra có thành công không
        exec("termux-open-url $link", $output, $return_var);
        if ($return_var !== 0) {
            echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;35mKhông thể mở link - Job die!        \r";
            try {
                baoloi($ads_id, $object_id, $account_id, $nhanjob["data"]["type"]);
            } catch (Exception $e) {
                // pass
            }
            continue;
        }
        sleep(3);  // Đợi 3 giây để TikTok load

        // Kiểm tra loại nhiệm vụ và thực hiện thao tác chạm tương ứng (nếu chọn Y)
        if ($auto_follow == "y") {
            if ($loai == "follow") {
                tap_screen($follow_x, $follow_y);  // Chạm vào nút follow
                sleep(2);  // Sau 2s trở về
                tap_screen($back_x, $back_y);
            } elseif ($loai == "like") {
                tap_screen($like_x, $like_y);  // Chạm vào nút like
            }
        }

        for ($remaining_time = $delay; $remaining_time >= 0; $remaining_time--) {
            $colors = array(
            "\033[1;37mT\033[1;36mH\033[1;35mI\033[1;32mE\033[1;31mU \033[1;34mH\033[1;33mO\033[1;36mA\033[1;36mN\033[1;37mG \033[1;35m- \033[1;36mTool\033[1;36m Vip \033[1;31m\033[1;32m",
            "\033[1;34mT\033[1;31mH\033[1;37mI\033[1;36mE\033[1;32mU \033[1;35mH\033[1;37mO\033[1;33mA\033[1;32mN\033[1;34mG \033[1;37m- \033[1;31mTool\033[1;34m Vip \033[1;31m\033[1;32m",
            "\033[1;31mT\033[1;37mH\033[1;36mI\033[1;33mE\033[1;35mU \033[1;32mH\033[1;34mO\033[1;35mA\033[1;37mN\033[1;31mG \033[1;36m- \033[1;37mTool\033[1;33m Vip \033[1;31m\033[1;32m",
            "\033[1;32mT\033[1;33mH\033[1;34mI\033[1;35mE\033[1;36mU \033[1;37mH\033[1;36mO\033[1;31mA\033[1;34mN\033[1;32mG \033[1;34m- \033[1;33mTool\033[1;31m Vip \033[1;31m\033[1;32m",
            "\033[1;37mT\033[1;34mH\033[1;35mI\033[1;36mE\033[1;32mU \033[1;33mH\033[1;31mO\033[1;37mA\033[1;34mN\033[1;37mG \033[1;35m- \033[1;34mTool\033[1;37m Vip \033[1;31m\033[1;32m",
            "\033[1;34mT\033[1;33mH\033[1;37mI\033[1;35mE\033[1;31mU \033[1;36mH\033[1;36mO\033[1;32mA\033[1;37mN\033[1;34mG \033[1;37m- \033[1;33mTool\033[1;36m Vip \033[1;31m\033[1;32m",
            "\033[1;36mT\033[1;35mH\033[1;31mI\033[1;34mE\033[1;37mU \033[1;35mH\033[1;32mO\033[1;36mA\033[1;33mN\033[1;36mG \033[1;31m- \033[1;35mTool\033[1;33m Vip \033[1;31m\033[1;32m",
            );
            foreach ($colors as $color) {
                echo "\r{$color}|{$remaining_time}| \033[1;31m";
                usleep(120000);
            }
        }

        echo "\r                          \r";
        echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;35mĐang Nhận Tiền Lần 1:>        \r";
        while (true) {
            try {
                $nhantien = hoanthanh($ads_id, $account_id);
                break;
            } catch (Exception $e) {
                // pass
            }
        }

        if ($lannhan == "y") {
            $checklan = 1;
        } else {
            $checklan = 2;
        }

        $ok = 0;
        while ($checklan <= 2) {
            if (isset($nhantien["status"]) && $nhantien["status"] == 200) {
                $ok = 1;
                $dem++;
                $tien = $nhantien["data"]["prices"];
                $tong += $tien;
                $local_time = getdate();
                $hour = $local_time["hours"];
                $minute = $local_time["minutes"];
                $second = $local_time["seconds"];
                $h = $hour;
                $m = $minute;
                $s = $second;
                if ($hour < 10) {
                    $h = "0" . $hour;
                }
                if ($minute < 10) {
                    $m = "0" . $minute;
                }
                if ($second < 10) {
                    $s = "0" . $second;
                }
                echo "                                                    \r";
                $chuoi = ("\033[1;31m| \033[1;36m{$dem}\033[1;31m\033[1;97m | " .
                         "\033[1;33m{$h}:{$m}:{$s}\033[1;31m\033[1;97m | " .
                         "\033[1;32msuccess\033[1;31m\033[1;97m | " .
                         "\033[1;31m{$nhantien['data']['type']}\033[1;31m\033[1;32m\033[1;32m\033[1;97m |" .
                         "\033[1;32m Ẩn ID\033[1;97m |\033[1;97m \033[1;32m+{$tien} \033[1;97m| " .
                         "\033[1;33m{$tong}");
                echo $chuoi . "\n";
                $checkdoiacc = 0;
                break;
            } else {
                $checklan++;
                if ($checklan == 3) {
                    break;
                }
                echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;35mĐang Nhận Tiền Lần 2:>        \r";
                $nhantien = hoanthanh($ads_id, $account_id);
            }
        }

        if ($ok != 1) {
            while (true) {
                try {
                    baoloi($ads_id, $object_id, $account_id, $nhanjob["data"]["type"]);
                    echo "                                              \r";
                    echo "\033[1;97m[\033[1;91m❣\033[1;97m] \033[1;36m✈ \033[1;31mĐã bỏ qua job:>        \r";
                    sleep(1);
                    $checkdoiacc++;
                    break;
                } catch (Exception $e) {
                    $qua = 0;
                    // pass
                }
            }
        }
    } else {
        sleep(10);
    }
}

?>

