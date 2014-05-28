import MySQLdb

# Download Guest List.csv from Facebook event page and copy it to a file named
# 'list.csv'. Remove the first line (column title) and the '"'s around each
# name (they cause trouble with MySQL)
filename = "list.csv"
data = open(filename, 'r');
guests = [];

db_host = "" # Add your host
db_user = "" # Add your user
db_password = "" # Add your password
db_name = "" # Add your database name
db_table = "" # Add your table name

for line in data:
   person = line.split(',')
   guests.append([person[0].strip(), person[1].strip()])

print("Number of guests {}\n".format(str(len(guests))))

db = MySQLdb.connect(db_host, db_user, db_password, db_name)
cursor = db.cursor()

for person, status in guests:
   print("Adding " + person + " status: " + status)
   cursor.execute("INSERT INTO {} (name, status)\
         VALUES (\"{}\", \"{}\")".format(db_table, person, status))

# Clean up
cursor.close()
db.commit()
db.close()
