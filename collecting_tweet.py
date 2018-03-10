import tweepy
import json
from pymongo import MongoClient

MONGO_HOST = 'mongodb://localhost/twitterdb'    # Name of MongoDB
keys_file = open("keys.txt")                    # Have a text file in the same folder with 4 lines of text as follows
lines = keys_file.readlines()
consumer_key = lines[0].rstrip()
consumer_secret = lines[1].rstrip()
access_token = lines[2].rstrip()
access_token_secret = lines[3].rstrip()
WORDS = ['guns', 'school shootings', 'firearms', 'Second Ammendment', 'gun control', 'gun laws', 'gun rights',
         'gun ownership']


class StreamListener(tweepy.StreamListener):

    def on_connect(self):
        # Called initially to connect to the Streaming API
        print("You are now connected to the streaming API.")

    def on_error(self, status_code):
        # On error - if an error occurs, display the error / status code
        print('An Error has occured: ' + repr(status_code))
        return False

    def on_data(self, data):
        try:
            client = MongoClient(MONGO_HOST)
            # Use twitterdb database. If it doesn't exist, it will be created.
            db = client.twitterdb

            # Decode the JSON from Twitter
            datajson = json.loads(data)

            # grab the 'created_at' data from the Tweet to use for display
            created_at = datajson['created_at']

            # print out a message to the screen that we have collected a tweet
            tweet = datajson['text'].split()
            # if datajson['retweeted'] is False:
            for word in WORDS:
                if word in tweet:
                    # print("Tweet collected at " + str(created_at))
                    print(datajson['text'])
                    db.twitter_search.insert(datajson)
                    break

        except Exception as e:
            print(e)


auth = tweepy.OAuthHandler(consumer_key, consumer_secret)
auth.set_access_token(access_token, access_token_secret)
listener = StreamListener(api=tweepy.API(wait_on_rate_limit=True))
streamer = tweepy.Stream(auth=auth, listener=listener)
print("Tracking: " + str(WORDS))
streamer.filter(locations=[-175.1, 22.4, -59.8, 72.3])
