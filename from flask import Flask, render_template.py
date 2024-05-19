from flask import Flask, render_template, request, redirect, url_for

app = Flask(__name__)

@app.route('/')
def home():
    return render_template('home.html')

@app.route('/settings')
def settings():
    return render_template('settings.html')

@app.route('/settings/quit', methods=['POST'])
def quit_settings():
    return redirect(url_for('home'))

@app.route('/settings/back', methods=['POST'])
def back_settings():
    return redirect(url_for('home'))

if _name_ == '_main_':
    app.run(debug=True)