<?php
$secretKey = 'JfRP2GDaWHeXdCeZsrpeFjWpssjSSraNAZQ9rZK29E4dEKfahAr9UKsWe1DHRzPM';

// Kiểm tra Secret header
$headers = getallheaders();
if (!isset($headers['Secret']) || $headers['Secret'] !== $secretKey) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid secret key']);
    exit;
}

// Kiểm tra file
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

// Lấy tên từ query hoặc tạo tên ngẫu nhiên
$name = isset($_GET['name']) ? preg_replace('/[^a-zA-Z0-9_\-]/', '_', $_GET['name']) : uniqid();
$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION) ?: 'png';
$filename = $name . '_' . time() . '.' . $ext;

// Đường dẫn lưu file (ngay cùng thư mục với file PHP)
$filepath = __DIR__ . '/' . $filename;

// Lưu file
if (!move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save file']);
    exit;
}

// Trả về tên file
echo json_encode([
    'success' => true,
    'filename' => $filename
]);
