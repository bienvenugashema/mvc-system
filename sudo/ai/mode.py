import sys
from deep_translator import GoogleTranslator

def classify_message(msg):
    msg = msg.lower()
    if "network" in msg or "internet" in msg:
        return "Hari ikibazo ku muyoboro w'itumanaho. Tuzagikemura vuba."
    elif "bill" in msg or "payment" in msg or "facture" in msg:
        return "Ikibazo cy'inguzanyo cyoherejwe mu ishami rishinzwe imari."
    elif "complain" in msg or "reclamation" in msg:
        return "Ushobora gutanga ikirego unyuze kuri dashboard yawe."
    else:
        return "Tuzakomeza gusuzuma ikibazo cyawe. Turagushimira ku makuru utanze."

try:
    user_input = sys.argv[1]
    translated = GoogleTranslator(source='auto', target='rw').translate(user_input)
    response = classify_message(translated)
    print(response)
except Exception as e:
    print("Habayeho ikibazo: ", e)
