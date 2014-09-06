import MySQLdb
import json

# Download Guest List.csv from Facebook event page and copy it to a file named
# 'list.csv'. Remove the first line (column title) and the '"'s around each
# name (they cause trouble with MySQL)
filename = "list.csv"
data = open(filename, 'r')
guests = []

# Config Setup
config_file = open('config.json', 'r')
config = json.load(config_file)

for line in data:
   person = line.split(',')
   guests.append([person[0].strip(), person[1].strip()])

print("Number of guests {}\n".format(str(len(guests))))

db = MySQLdb.connect(config['db_host'], config['db_user'], config['db_password'],
 config['db_name'])
cursor = db.cursor()

for person, status in guests:
   print("Adding " + person + " status: " + status)
   cursor.execute("INSERT INTO {} (name, status)\
         VALUES (\"{}\", \"{}\")".format(db_table, person, status))

# Clean up
cursor.close()
db.commit()
db.close()
