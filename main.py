#1 Importing libraries
from fastapi import FastAPI
from Irispara import Irispara
import joblib
import numpy as np
from sklearn.preprocessing import MinMaxScaler

#2 Create the app object
app = FastAPI()
model = joblib.load("Naive_bayes_model.pkl")
scaler = joblib.load("minmax_scaler.pkl")

#3 Index route
@app.get('/')
def index():
    return {'message': 'Hello, World'}

#4 Route with a single parameter
@app.get('/{name}')
def get_name(name: str):
    return {'Welcome This is my first FastAPI deployment': f'{name}'}

#5 Prediction functionality
@app.post('/predict')
def predict_species(data: Irispara):
    input_data = np.array([[data.SepalLengthCm, data.SepalWidthCm, data.PetalLengthCm, data.PetalWidthCm]])
    scaled_data = scaler.transform(input_data)
    prediction = model.predict(scaled_data)
    species_map = {0: "Setosa", 1: "Versicolor", 2: "Virginica"}
    predicted_species = species_map.get(prediction[0], "Unknown")
    return {"Predicted Species": predicted_species}
