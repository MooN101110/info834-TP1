import redis
import sys
import json

# Connexion à Redis
def connect_redis():
    try:
        r = redis.Redis(host='localhost', port=6378, db=0)
        r.ping()
        return r
    except Exception as e:
        print(json.dumps({"error": str(e)}))
        sys.exit(1)


def increment_login_attempts(mail):
    r = connect_redis()
    key = f"login_attempts:{mail}"
    permanent_key = f"login_attempts:permanent:{mail}"
    r.incr(key)
    r.incr(permanent_key)
    r.expire(key, 600)  # Expiration dans 10 minutes
    print(json.dumps({"status": "incremented"}))


def get_login_attempts(mail):
    r = connect_redis()
    key = f"login_attempts:{mail}"
    attempts = r.get(key)
    attempts = int(attempts) if attempts else 0
    print(json.dumps({"attempts": attempts}))
    
def get_last_connexion():
    r = connect_redis()
    keys = r.keys("login_attempts:*")
    all_attempts = {}
    for key in keys:
        all_attempts[key.decode("utf-8")] = int(r.get(key))
    print(json.dumps({"data" :all_attempts}))
    
    
def get_permanent_login_attempts():
    r = connect_redis()
    keys = r.keys("login_attempts:permanent:*")
    all_attempts = {}
    for key in keys:
        all_attempts[key.decode("utf-8")] = int(r.get(key))
    # Tri des tentatives par ordre croissant
    all_attempts = dict(sorted(all_attempts.items(), key=lambda item: item[1], reverse=True))
    # Limiter à 3 résultats
    all_attempts = dict(list(all_attempts.items())[:3])
    print(json.dumps({"data" :all_attempts}))


if len(sys.argv) < 2:
    print(json.dumps({"error": "Aucune action spécifiée"}))
    sys.exit(1)

action = sys.argv[1]

if action == "connect":
    connect_redis()
    print(json.dumps({"status": "OK"}))

elif action == "increment" and len(sys.argv) == 3:
    increment_login_attempts(sys.argv[2])

elif action == "get" and len(sys.argv) == 3:
    get_login_attempts(sys.argv[2])
    
elif action == "last_connexion" and len(sys.argv) == 2:
    get_last_connexion()
    
elif action == "most_active" and len(sys.argv) == 2:
    get_permanent_login_attempts()

else:
    print(json.dumps({"error": "Action invalide"}))
    sys.exit(1)
