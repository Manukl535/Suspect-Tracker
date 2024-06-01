# Developed by Manu and Srisha

import sys
import cv2
import os
import time

# Load the pre-trained face detection model
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')

# Get the path of the video file from the command-line argument
video_file_path = sys.argv[1]

# Load the video file
video_capture = cv2.VideoCapture(video_file_path)

# Define the directory containing the image samples
samples_directory = "Suspects"

# Get the list of sample image files
sample_files = os.listdir(samples_directory)

# Function to find sample name in the detected face region
def find_sample_names(face_region):
    detected_names = []
    for sample_file in sample_files:
        sample_name = os.path.splitext(sample_file)[0]
        sample_img = cv2.imread(os.path.join(samples_directory, sample_file), cv2.IMREAD_GRAYSCALE)
        result = cv2.matchTemplate(face_region, sample_img, cv2.TM_CCOEFF_NORMED)
        _, max_val, _, max_loc = cv2.minMaxLoc(result)
        if max_val > 0.8:  
            detected_names.append(sample_name)
    return detected_names

# Set to keep track of printed names
printed_names = set()

# Start time
start_time = time.time()

# Time limit in seconds
time_limit = 60  

while True:
    # Check if time limit exceeded
    if time.time() - start_time > time_limit:
        break
    
    # Read each frame of the video
    ret, frame = video_capture.read()

    if not ret:
        break

    # Convert the frame to grayscale for face detection
    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)

    # Detect faces in the frame
    faces = face_cascade.detectMultiScale(gray, scaleFactor=1.1, minNeighbors=5, minSize=(30, 30))

    # Draw rectangles around the faces and find sample names
    for (x, y, w, h) in faces:
        cv2.rectangle(frame, (x, y), (x+w, y+h), (0, 255, 0), 2)
        face_region = gray[y:y+h, x:x+w]
        detected_names = find_sample_names(face_region)
        for name in detected_names:
            if name not in printed_names:  # Check if name has already been printed
                print("Face Detected:", name)
                printed_names.add(name) 

    # Display the resulting frame
    cv2.imshow('Video', frame)

    # Break the loop if time limit exceeded
    if time.time() - start_time > time_limit:
        break

    # Break the loop when 'q' is pressed
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

# Release the video capture object and close all windows
video_capture.release()
cv2.destroyAllWindows()

# Create or open the "Detected_faces.txt" file in write mode
with open("Detected_faces.txt", "w") as file:
    # Write the detected names to the file
    for name in printed_names:
        file.write(name + "\n")

# Print a message indicating that the names have been saved to the file
print("Detected names have been saved to 'Detected_faces.txt' file.")

