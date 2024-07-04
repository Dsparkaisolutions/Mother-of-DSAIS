import random
import json
import torch
import nltk
from model import NeuralNet
from nltk_utils import bag_of_words, tokenize

nltk.download('punkt')

# Load intents from intents.json
with open('intents.json', 'r') as file:
    intents = json.load(file)

# Load data from data.pth
FILE = "data.pth"
data = torch.load(FILE)
input_size = data["input_size"]
hidden_size = data["hidden_size"]
output_size = data["output_size"]
all_words = data['all_words']
tags = data['tags']
model_state = data["model_state"]

# Initialize the model and load state
device = torch.device('cuda' if torch.cuda.is_available() else 'cpu')
model = NeuralNet(input_size, hidden_size, output_size).to(device)
model.load_state_dict(model_state)
model.eval()

# Function to get response from the model
def get_response(message):
    # Tokenize the input message
    sentence = tokenize(message)
    X = bag_of_words(sentence, all_words)
    X = X.reshape(1, X.shape[0])
    X = torch.from_numpy(X).to(device)

    # Get the model's prediction
    output = model(X)
    _, predicted = torch.max(output, dim=1)
    tag = tags[predicted.item()]

    # Check if prediction probability is above 0.6 and return response
    probs = torch.softmax(output, dim=1)
    prob = probs[0][predicted.item()]

    if prob.item() > 0.6:
        for intent in intents['intents']:
            if tag == intent["tag"]:
                return random.choice(intent['responses'])

    return "I do not understand..."

# Example usage in console
if __name__ == "__main__":
    print("Let's chat! (type 'quit' to exit)")
    while True:
        sentence = input("You: ")
        if sentence == "quit":
            break

        resp = get_response(sentence)
        print(resp)
