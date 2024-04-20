import os
import sys

sys.path.insert(0, os.path.dirname(__file__))

def main(environ, start_response):
    err = ['seer', 'allah yahdik']
    start_response('200 OK', [('Content-Type', 'text/plain')])
    message = 'Starting App\n'
        
    version = 'Python v' + sys.version.split()[0] + '\n'
    err = '\n'.join(err)
    response = '\n'.join([err, message, version])
    return [response.encode()]
