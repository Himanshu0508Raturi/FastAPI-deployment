#!/bin/bash

# Use Render-assigned port
# executable command: chmod +x start.sh
echo "Starting FastAPI server on port ${PORT}..."
uvicorn main:app --host 0.0.0.0 --port ${PORT}
