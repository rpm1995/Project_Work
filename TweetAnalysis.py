import json
import mysql.connector as mariadb

from geopy.geocoders import Nominatim
from textblob import TextBlob


def push_json_to_mysql(mariadb_connection, cursor):
    query = "INSERT INTO TweetData(TweetID, Tweet, State) VALUES(%s, %s, %s);"
    geolocator = Nominatim()

    with open('tweets.json') as f:
        for line in f:
            try:
                data = json.loads(line)

                full_text = None
                if 'extended_tweet' in data:
                    full_text = data['extended_tweet']['full_text']
                else:
                    full_text = data['text']

                bounding_box = data['place']['bounding_box']['coordinates'][0]
                tweet_id = data['id']
                latitude = (bounding_box[0][1] + bounding_box[1][1] + bounding_box[2][1] + bounding_box[3][1]) / 4
                longitude = (bounding_box[0][0] + bounding_box[1][0] + bounding_box[2][0] + bounding_box[3][0]) / 4
                location = geolocator.reverse(str(latitude) + ", " + str(longitude)).raw
                state = location['address']['state']

                try:
                    args = (tweet_id, full_text, state)
                    cursor.execute(query, args)

                    mariadb_connection.commit()
                except mariadb.DataError:
                    print(full_text)
                    print(state)
                    print(tweet_id)
            except (KeyError, ValueError):
                print(line)


def calculate_sentiment(cursor):
    cursor.execute("SELECT * FROM TweetData")
    rows = cursor.fetchall()

    for row in rows:
        tweet_id = row[0]
        tweet = row[1].decode('utf-8')
        tweet_sentiment = TextBlob(tweet).sentiment

        print(row[1])
        print(tweet_sentiment)


if __name__ == '__main__':
    mariadb_connection = mariadb.connect(host='localhost', port='3307', user='root', password='root123',
                                         database='TweetAnalysis', charset='utf8mb4')
    cursor = mariadb_connection.cursor()

    # push_json_to_mysql(mariadb_connection, cursor)
    calculate_sentiment(cursor)

    cursor.close()
    mariadb_connection.close()
