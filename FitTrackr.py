<!DOCTYPE html>
<html>
  <head>
    <title>FitTrackr</title>
    <link rel="stylesheet" href="{{ url_for('static', filename='css/main.css') }}">
  </head>
  <body>
    <div class="taskbar">
      <button class="settings-button" onclick="showSettings()">Settings</button>
    </div>
    <div class="content">
      <!-- Main content goes here -->
    </div>
    <script src="{{ url_for('static', filename='js/main.js') }}"></script>
  </body>
</html>