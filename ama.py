import pandas as pd
import json
import sys
import logging
import requests
from difflib import get_close_matches, SequenceMatcher

# Set up logging
logging.basicConfig(filename='ama.log', level=logging.DEBUG)

# Weather API credentials
API_KEY = '2fd7c4ec296b4c8da6b74927241807'

try:
    # Load dataset
    dataset = pd.read_csv('dataset.csv')
    logging.debug("Dataset loaded successfully.")

    # Function to get answer based on user input
    def get_answer(user_input):
        user_input_lower = user_input.lower()
        max_similarity = 0.8  # Adjust the similarity threshold as needed
        best_match = None
        
        # Check for exact match first
        for index, row in dataset.iterrows():
            utterance = row['Example Utterance'].lower()
            if user_input_lower in utterance or utterance in user_input_lower:
                return row['Answer']
        
        # If no exact match, find closest match using get_close_matches
        suggestions = get_close_matches(user_input_lower, dataset['Example Utterance'], n=1, cutoff=max_similarity)
        if suggestions:
            best_match = suggestions[0]
            answer = dataset.loc[dataset['Example Utterance'] == best_match, 'Answer'].iloc[0]
            return answer
        
        # If still no match, use SequenceMatcher to find best match
        for utterance in dataset['Example Utterance']:
            similarity = SequenceMatcher(None, user_input_lower, utterance.lower()).ratio()
            if similarity > max_similarity:
                max_similarity = similarity
                best_match = utterance
                answer = dataset.loc[dataset['Example Utterance'] == best_match, 'Answer'].iloc[0]
        
        return answer if best_match else None

    # Function to suggest close matches
    def suggest_input(user_input):
        utterances = dataset['Example Utterance'].tolist()
        suggestions = get_close_matches(user_input, utterances)
        if suggestions:
            return f"Did you mean: {', '.join(suggestions)}?"
        return "Sorry, I don't understand that question."

    # Function to fetch real-time weather information using Weather API
    def fetch_weather(location):
        url = f'http://api.weatherapi.com/v1/current.json?key={API_KEY}&q={location}&aqi=no'
        try:
            response = requests.get(url)
            response.raise_for_status()  # Raise an error for bad responses (4xx or 5xx)
            if response.status_code == 200:
                weather_data = response.json()
                temp = weather_data['current']['temp_c']
                humidity = weather_data['current']['humidity']
                wind_speed = weather_data['current']['wind_kph']
                return {
                    'temp': temp,
                    'humidity': humidity,
                    'wind_speed': wind_speed
                }
            else:
                logging.error(f"Failed to fetch weather data: {response.status_code}")
                return None
        except requests.exceptions.RequestException as e:
            logging.error(f"Request error: {str(e)}")
            return None
        except json.JSONDecodeError as e:
            logging.error(f"JSON decoding error: {str(e)}")
            return None

    # Function to handle city-specific weather requests
    def handle_city_weather_request(user_input):
        # Extract city name from user input
        tokens = user_input.lower().split()
        if len(tokens) > 1:
            location = ' '.join(tokens[1:])
            weather_data = fetch_weather(location)
            if weather_data:
                return f"The weather  {location} is Temp:{weather_data['temp']} C Humidity:{weather_data['humidity']}% Wind Speed:{weather_data['wind_speed']} Kmph"
            else:
                return f"Sorry, I couldn't fetch the weather data for {location}."
        else:
            return "Please specify a location after 'weather' to get weather information."

    # Handle user input
    if len(sys.argv) > 1:
        user_input = sys.argv[1]
        if "weather" in user_input.lower():
            response = handle_city_weather_request(user_input)
        else:
            response = get_answer(user_input)

        if not response:
            suggestion = suggest_input(user_input)
            response = suggestion

        print(json.dumps({'answer': response}))

    else:
        print(json.dumps({'answer': "Sorry, I don't understand that question."}))

except Exception as e:
    logging.error(f"Error: {str(e)}")
    print(json.dumps({'answer': "Sorry, I don't understand that question."}))
