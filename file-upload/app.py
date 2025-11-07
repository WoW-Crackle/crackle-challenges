from flask import Flask, render_template, request, redirect, url_for, send_from_directory, flash, session
import os
from werkzeug.utils import secure_filename
import secrets
from datetime import datetime

app = Flask(__name__)
app.secret_key = secrets.token_hex(16)

# 기본 설정
UPLOAD_FOLDER = 'uploads'
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER
app.config['MAX_CONTENT_LENGTH'] = 5 * 1024 * 1024

# 필요한 폴더 생성
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

# 제출된 지원서 목록
applications = []

# 메인 페이지 
@app.route('/')
def index():
    return render_template('index.html')

# 지원서 제출 페이지 
@app.route('/apply', methods=['GET', 'POST'])
def apply():
    if request.method == 'POST':
        name = request.form.get('name', '').strip()
        email = request.form.get('email', '').strip()

        if not name or not email:
            flash('이름과 이메일을 입력해주세요.', 'danger')
            return redirect(request.url)
        
        if 'resume' not in request.files:
            flash('이력서를 첨부해주세요.', 'danger')
            return redirect(request.url)
        
        file = request.files['resume']
        
        if file.filename == '':
            flash('파일을 선택해주세요.', 'danger')
            return redirect(request.url)
        
        if file:
            filename = file.filename
            timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
            
            # 타임스탬프 추가하여 파일명 생성
            if '.' in filename:
                name_part = filename.rsplit('.', 1)[0]
                ext_part = filename.rsplit('.', 1)[1]
                final_filename = f"{name_part}_{timestamp}.{ext_part}"
            else:
                final_filename = f"{filename}_{timestamp}"
            
            # 파일 저장
            filepath = os.path.join(app.config['UPLOAD_FOLDER'], final_filename)
            file.save(filepath)
            
            # 지원 정보 저장
            applications.append({
                'id': len(applications) + 1,
                'name': name,
                'email': email,
                'filename': final_filename,
                'time': datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            })
            
            flash(f'{name}님, 지원서가 접수되었습니다!', 'success')
            flash(f'업로드된 파일: {final_filename}', 'info')
            return redirect(url_for('apply'))
        else:
            flash('파일 업로드에 실패했습니다.', 'danger')
            return redirect(request.url)
    
    return render_template('apply.html')

# 업로드된 파일에 접근 
@app.route('/uploads/<path:filename>')
def uploaded_file(filename):
    return send_from_directory(app.config['UPLOAD_FOLDER'], filename)

# 제출된 지원서 목록 
@app.route('/applications')
def applications_list():
    return render_template('applications.html', applications=applications)

if __name__ == '__main__':    
    app.run(debug=True, host='0.0.0.0', port=5000)
