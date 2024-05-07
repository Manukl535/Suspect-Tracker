from flask import Flask, render_template, request, redirect, url_for
import mysql.connector

app = Flask(__name__)

# Connect to  MySQL database
db = mysql.connector.connect(
    host="localhost",
    user="root",
    passwd="",
    database="suspect_tracker"
)

@app.route('/')
def login():
    return render_template('login.html')

@app.route('/authenticate', methods=['POST'])
def authenticate():
    cursor = db.cursor()

    # Retrieve username and password from the form
    username = request.form['admin_id']
    password = request.form['password']

    # Check if the credentials are valid
    query = "SELECT * FROM admin WHERE admin_id = %s AND password = %s"
    cursor.execute(query, (username, password))
    user = cursor.fetchone()

    if user:
        # If user exists, redirect to index.html
        return redirect(url_for('index'))
    else:
        # If user doesn't exist or credentials are incorrect, redirect back to login with an error message
        return redirect(url_for('login', error="Invalid username or password"))

@app.route('/index')
def index():
    return render_template('index.html')

if __name__ == '__main__':
    app.run(debug=True)
