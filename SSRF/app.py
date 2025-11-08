from flask import Flask, request
import requests

app = Flask(__name__)

@app.route('/')
def home():
    return '''
    <h2>SSRF 워게임 테스트 페이지</h2>
    <form action="/fetch" method="get">
        <input name="url" placeholder="URL을 입력하세요">
        <input type="submit" value="요청 보내기">
    </form>
    '''

@app.route('/fetch')
def fetch():
    url = request.args.get('url')
    try:
        response = requests.get(url, timeout=3)
        return f"<h3>요청 결과:</h3><pre>{response.text}</pre>"
    except Exception as e:
        return f"<p>에러 발생: {e}</p>"

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
