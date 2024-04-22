import os
import sys

sys.path.insert(0, os.path.dirname(__file__))

def main(environ, start_response):
    err = []
    start_response('200 OK', [('Content-Type', 'text/plain')])
    message = ''
    try:
        from selenium import webdriver
        driver = webdriver.Chrome()
        driver.get("http://selenium.dev")
        message += "started successfully"
        driver.quit()
    except Exception as e:
        err.append(str(e))
        
    err = '\n'.join(err)
    response = '\n'.join([err, message])
    return [response.encode()]
