import cv2
import os
import time

# Load the pre-trained face detection model
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')

# Directory containing reference images
images_dir = 'images/'

# List all image files in the directory
image_files = os.listdir(images_dir)

# Open the webcam (the default camera)
video_capture = cv2.VideoCapture(0)

start_time = time.time()
elapsed_time = 0
match_found = False

while elapsed_time < 30:
    # Capture each frame from the webcam
    ret, frame = video_capture.read()

    # Convert the frame to grayscale for face detection
    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)

    # Detect faces in the frame
    faces = face_cascade.detectMultiScale(gray, scaleFactor=1.1, minNeighbors=5, minSize=(30, 30))

    # Loop through detected faces
    for (x, y, w, h) in faces:
        # Draw rectangles around the detected faces
        cv2.rectangle(frame, (x, y), (x+w, y+h), (255, 0, 0), 2)
        
        # Loop through each image file
        for image_file in image_files:
            # Load the reference image
            reference_image = cv2.imread(os.path.join(images_dir, image_file), cv2.IMREAD_GRAYSCALE)
            
            # Resize the reference image to match the size of the detected face
            reference_image = cv2.resize(reference_image, (w, h))
            
            # Compare the detected face with the reference image
            difference = cv2.absdiff(gray[y:y+h, x:x+w], reference_image)
            mean_difference = difference.mean()
            
            print("Mean Difference:", mean_difference)  # Debugging
            
            # Define a threshold for matching
            threshold = 20
            
            # If the mean difference is below the threshold, a match is found
            if mean_difference < threshold:
                print("Match found with image:", image_file)  # Debugging
                match_found = True
                break  # Exit the inner loop
                
        if match_found:
            break  # Exit the outer loop
    
    # Generate the live_suspect.txt file
    try:
        with open('live_suspect.txt', 'w') as file:
            if match_found:
                file.write("Match found!")
            else:
                file.write("No match found!")
        print("live_suspect.txt generated successfully.")
    except Exception as e:
        print("Error occurred while writing to live_suspect.txt:", e)
    
    if match_found:
        # End the execution if match is found
        break
    
    # Display the resulting frame
    cv2.imshow('Face Detection', frame)

    # Update elapsed time
    elapsed_time = time.time() - start_time

    # Break the loop when 'q' is pressed
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

# Release the video capture object and close all windows
video_capture.release()
cv2.destroyAllWindows()
