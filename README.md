# CSRF (Cross-Site Request Forgery) Challenge

![Python](https://img.shields.io/badge/Python-3.11-blue?logo=python)
![Flask](https://img.shields.io/badge/Flask-3.x-lightgrey?logo=flask)
![Purpose](https://img.shields.io/badge/Purpose-Learning--&--Demo-yellow)

간단한 Flask 기반 CSRF 예제입니다.  
로그인(세션) 기반으로 동작하며, 상품 상세 페이지와 Q&A 게시판(글쓰기/삭제) 기능을 포함합니다.  
이 저장소는 교육/실습용이며, CSRF을 확인·실습하도록 의도적으로 일부 취약한 구현을 포함할 수 있습니다.

📁파일 구조

CSRF/
├── app.py 
├── static/
│ ├── css/
│ │ └── style.css 
│ ├── img/
│ │ ├── information.png
│ │ ├── review.png
│ │ ├── size.png
│ │ ├── tee1.png
│ │ ├── tee2.png
│ │ ├── tee3.png
│ │ ├── tee4.png
│ │ ├── tee5.png
│ │ └── tee6.png
│ └── js/
│ └── script.js 
├── templates/
│ ├── base.html
│ ├── index.html
│ ├── login.html
│ ├── product1.html
│ └── qna.html
└── README.md

🎯기능 요약

- 세션 기반 로그인 / 로그아웃(`admin` / `1234`)
- 상품 목록 및 1번 상품 상세 페이지
- 상품 상세에서 Q&A(로그인 필요) 접근
- Q&A: 글 목록, 글쓰기(post), 글 삭제(GET/POST 허용(현 코드)) — **취약점을 위해 허용된 부분 존재**
- Flash 메시지로 사용자 피드백 제공

🚀필요 사항

Python 3.7+
Flask

🛠️설치

pip install flask 

▶️실행

python app.py