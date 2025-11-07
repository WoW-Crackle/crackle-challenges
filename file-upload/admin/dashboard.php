<?php
session_start();

// 로그인 체크
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// 로그아웃 처리
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$admin_username = $_SESSION['admin_username'] ?? 'admin';

// Level 2 FLAG 파일에서 읽기
$flag2 = '';
$flag_file = '../flag2.txt';
if (file_exists($flag_file)) {
    $flag2 = trim(file_get_contents($flag_file));
}

// 통계 데이터 (실제로는 DB에서 가져옴)
$total_applications = 0;
$today_applications = 0;
$pending_reviews = 0;

// uploads 폴더의 파일 수 계산
$uploads_dir = '../uploads';
if (is_dir($uploads_dir)) {
    $files = scandir($uploads_dir);
    $total_applications = count($files) - 2; // . 과 .. 제외
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 대시보드 - TechCorp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }
        .navbar .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 600;
        }
        .navbar .nav-link:hover {
            color: white !important;
        }
        .container-custom {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .welcome-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .welcome-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 10px;
        }
        .welcome-subtitle {
            color: #666;
            font-size: 1rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .stat-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .flag-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            color: white;
            margin-bottom: 30px;
        }
        .flag-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .flag-value {
            background: rgba(255,255,255,0.2);
            padding: 15px 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: 1px;
            word-break: break-all;
        }
        .admin-badge {
            background: rgba(255,255,255,0.2);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        .quick-actions {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .quick-actions h5 {
            font-weight: 700;
            margin-bottom: 20px;
            color: #1a1a1a;
        }
        .action-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            text-decoration: none;
            color: #1a1a1a;
            font-weight: 600;
            margin-bottom: 10px;
            transition: all 0.2s;
        }
        .action-btn:hover {
            background: #e9ecef;
            color: #1a1a1a;
            transform: translateX(5px);
        }
        .logout-btn {
            color: #dc3545;
        }
        .logout-btn:hover {
            background: #fff5f5;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <!-- 네비게이션 -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">🔒 TechCorp Admin</a>
            <div class="ms-auto">
                <span class="navbar-text me-3">
                    <span class="admin-badge">ADMIN</span> <?php echo htmlspecialchars($admin_username); ?>
                </span>
                <a href="?logout=1" class="nav-link d-inline">로그아웃</a>
            </div>
        </div>
    </nav>

    <!-- 메인 컨텐츠 -->
    <div class="container-custom">
        <!-- 환영 메시지 -->
        <div class="welcome-section">
            <div class="welcome-title">👋 환영합니다, <?php echo htmlspecialchars($admin_username); ?>님!</div>
            <div class="welcome-subtitle">TechCorp 채용 관리 시스템에 로그인하셨습니다.</div>
        </div>

        <!-- Level 2 FLAG -->
        <?php if ($flag2): ?>
        <div class="flag-section">
            <div class="flag-title">
                🏆 Level 2 Complete!
            </div>
            <div class="flag-value"><?php echo htmlspecialchars($flag2); ?></div>
            <div style="margin-top: 15px; opacity: 0.9; font-size: 0.9rem;">
                축하합니다! 관리자 패널에 성공적으로 접근하였습니다.
            </div>
        </div>
        <?php endif; ?>

        <!-- 통계 카드 -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-value"><?php echo $total_applications; ?></div>
                <div class="stat-label">총 지원서</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-value"><?php echo $today_applications; ?></div>
                <div class="stat-label">오늘 접수</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-value"><?php echo $pending_reviews; ?></div>
                <div class="stat-label">검토 대기</div>
            </div>
        </div>

        <!-- 빠른 작업 -->
        <div class="quick-actions">
            <h5>🚀 빠른 작업</h5>
            <a href="../applications" class="action-btn">📊 지원 현황 보기</a>
            <a href="../uploads" class="action-btn">📁 업로드된 파일 보기</a>
            <a href="?logout=1" class="action-btn logout-btn">🚪 로그아웃</a>
        </div>
    </div>

    <footer class="text-center py-4 text-muted" style="margin-top: 40px;">
        <small>© 2024 TechCorp Admin Panel. All rights reserved.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>