from gpiozero import LED, Button
import adafruit_dht
import board
import time
import requests
import json
import signal
import threading
from flask import Flask, request, Response, jsonify
import _thread

id_site = 0
timeout = 300
active = 0

app = Flask(__name__)

@app.route('/state', methods=['POST'])
def state():
    global id_site, timeout, active
    request_data = request.get_json(force = True)  # getting data from client
    id_site = int(request_data["id_site"])
    timeout = int(request_data["timeout"])
    active = int(request_data["state"])
    if active:
        led.on()
        print("Started.")
        signal.alarm(1)
    else:
        led.off()
        print("Stopped.")
        signal.alarm(0)
    response = Response("State updated", 201, mimetype='application/json')
    return response

DEBUG = False
PORT = 8000
HOST = '192.168.1.20'

def flaskThread():
    app.run(debug=DEBUG, host=HOST, port=PORT, use_reloader=False)

if __name__ == "__main__":
    _thread.start_new_thread(flaskThread, ())

led = LED(5)
button = Button(3)
dhtDevice = adafruit_dht.DHT11(board.D14)

def read_one():
    try:
        temperature_c = float(dhtDevice.temperature)
        humidity = float(dhtDevice.humidity)
        headers = {}
        payload = {"id_site": id_site,"temp_indoor": temperature_c,"humid_indoor": humidity}
        r = requests.post("http://matt.zapto.org/domenico/php/p0/api/event/create.php", headers=headers, data=json.dumps(payload))
        print(r.text + " " + json.dumps(payload))
    except RuntimeError as error:
        # Errors happen fairly often, DHT's are hard to read, just keep going
        print(error.args[0])
    except Exception as error:
        dhtDevice.exit()
        raise error

def signal_handler(signum, frame):
    raise Exception("Timed Out")

signal.signal(signal.SIGALRM, signal_handler)

def get_state():
    global active, timeout
    headers = {}
    r = requests.get("http://matt.zapto.org/domenico/php/p0/api/event/get_state.php", headers=headers)
    timeout = int(json.loads(r.text)["timeout"])
    active = int(json.loads(r.text)["state"])

def set_state(state):
    global id_site, timeout
    headers = {}
    payload = {"id_site": id_site,"timeout": timeout,"state": state}
    r = requests.post("http://matt.zapto.org/domenico/php/p0/api/event/set_state.php", headers=headers, data=json.dumps(payload))
    print(r.text + " " + json.dumps(payload))

get_state()

if active:
    led.on()
    print("Started.")
else:
    led.off()
    print("Stopped.")

def toggle():
    global active, led, timeout
    if active:
        active = 0
        led.off()
        print("Stopped.")
        signal.alarm(0)
        set_state(0)
    else:
        active = 1
        led.on()
        print("Started.")
        signal.alarm(0)
        signal.alarm(int(timeout))
        set_state(1)

button.when_pressed = toggle

while True:
    if active:
        led.on()
        signal.alarm(int(timeout))
    else:
        led.off()
        signal.alarm(0)
    
    try:
        while active:
            if not(active):
                led.off()
                signal.alarm(0)
    except Exception:
        read_one()
    time.sleep(1)
