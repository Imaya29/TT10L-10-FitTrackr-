from flask import Flask, render_template, request, jsonify

app = Flask(_FitTrackr_)

@app.route('/')
def home():
    return render_template('home.html')

@app.route('/settings', methods=['GET'])
def settings():
    return render_template('settings.html')

@app.route('/quit', methods=['GET'])
def quit():
    return "Quitting the application..."

@app.route('/back', methods=['GET'])
def back():
    return render_template('home.html')

@app.route('/workouts', methods=['GET'])
def workouts():
    return render_template('workouts.html')

if _name_ == '_main_':
    app.run(debug=True)