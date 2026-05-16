FROM python:3.11-alpine

WORKDIR /app
RUN echo "OK from Python" > index.html

CMD python3 -m http.server $PORT
