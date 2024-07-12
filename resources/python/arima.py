from flask import Flask, jsonify
from pymongo import MongoClient
from statsmodels.tsa.arima.model import ARIMA
import numpy as np
import logging

app = Flask(__name__)

connection_string = "mongodb+srv://putriisabella1007:irAy7S15RF7aVyfn@cluster0.7rajyce.mongodb.net/"
client = MongoClient(connection_string)

db = client.rme
collection_input = db.monthly_stocks
collection_forecast = db.forecast

# Set up logging
logging.basicConfig(level=logging.DEBUG)

@app.route('/process_forecast', methods=['GET'])
def process_forecast():
    try:
        # Read input data
        month = []
        year = []
        quantity = []
        data = collection_input.find()
        for d in data:
            month.append(d['month'])
            year.append(d['year'])
            quantity.append(d['quantity'])

        logging.debug(f"Read data: months={month}, years={year}, quantities={quantity}")
        logging.debug(f"Quantity data types: {type(quantity[0])}, {quantity}")

        # Ensure the quantity data is numeric
        quantity = list(map(float, quantity))

        # Predict future values
        model = ARIMA(quantity, order=(1, 1, 1))
        model_fit = model.fit()
        forecast = model_fit.forecast(steps=24)

        logging.debug(f"Forecast: {forecast}")

        # Delete all existing forecast data
        collection_forecast.delete_many({})

        # Create new forecast data
        last_month = month[-1]
        last_year = year[-1]
        months = []
        years = []

        month = last_month
        year = last_year
        for _ in range(24):
            month += 1

            if month > 12:
                month = 1
                year += 1

            months.append(month)
            years.append(year)

        docs = []
        for m, y, f in zip(months, years, forecast):
            doc = {"month": m, "year": y, "forecast": f}
            docs.append(doc)

        collection_forecast.insert_many(docs)
        return jsonify({"message": "Forecast process completed successfully"}), 201

    except Exception as e:
        logging.error(f"Error processing forecast: {e}")
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)
