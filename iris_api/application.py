from fastapi import FastAPI
from Irispara import Irispara
import joblib
import numpy as np

# Load the model and scaler
model = joblib.load("iris_api/Naive_bayes_model.pkl")
scaler = joblib.load("iris_api/minmax_scaler.pkl")

# Create the FastAPI app (must be named `app`)
app = FastAPI()

@app.get("/")
def index():
    return {'message': 'Hello, World'}

@app.get("/{name}")
def get_name(name: str):
    return {'Welcome This is my first FastAPI deployment': f'{name}'}

@app.post("/predict")
def predict_species(data: Irispara):
    # Convert input to NumPy array
    input_data = np.array([[data.SepalLengthCm, data.SepalWidthCm, data.PetalLengthCm, data.PetalWidthCm]])

    # Apply MinMax Scaling
    scaled_data = scaler.transform(input_data)

    # Make prediction
    prediction = model.predict(scaled_data)

    # Map to species name
    species_map = {0: "Setosa", 1: "Versicolor", 2: "Virginica"}
    predicted_species = species_map.get(prediction[0], "Unknown")

    return {"Predicted Species": predicted_species}
