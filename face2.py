import cv2
import os
import time

# Load the pre-trained face detection model
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')

# Get the path of the video file from the user
video_file_path = input("Enter the path of the video file: ")

# Load the video file
video_capture = cv2.VideoCapture(video_file_path)

# Define the directory containing the image samples
samples_directory = "Img_samples"

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
        if max_val > 0.8:  # Adjust this threshold as needed
            detected_names.append(sample_name)
    return detected_names

# Start time
start_time = time.time()

# Time limit in seconds
time_limit = 60  # Adjust as needed

# Create directory for saving detected faces
detected_faces_dir = "Detected_Faces"
os.makedirs(detected_faces_dir, exist_ok=True)

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

    # Save detected faces
    for i, (x, y, w, h) in enumerate(faces):
        # Save detected face region
        face_filename = os.path.join(detected_faces_dir, f"face_{i}.jpg")
        cv2.imwrite(face_filename, frame[y:y+h, x:x+w])

    # Break the loop if time limit exceeded
    if time.time() - start_time > time_limit:
        break

    # Break the loop when 'q' is pressed
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

# Release the video capture object and close all windows
video_capture.release()
cv2.destroyAllWindows()
