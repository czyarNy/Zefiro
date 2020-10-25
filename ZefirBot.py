import mysql.connector
from datetime import date, datetime, timedelta
from mysql.connector import errorcode
import string
import random
import time
import os

#laczenie sie z baza danych
try:
    cnx = mysql.connector.connect(user = "zephyrior", password = "6bny2B0&", host = "szkolnewybory.pl", database = "admin_zephyrior")
except mysql.connector.Error as err:
    print(err)



cursor = cnx.cursor()

#dodawanie urzadzen
# for i in range (1):
#     id=''
#     for i in range (5):
#         id+= random.choice(string.ascii_letters + string.digits + string.digits + string.digits + string.digits + string.digits + string.digits + string.digits)
#     print (id)
#     password='$2y$10$nDsR/AAZBbBc33PBj151LuvKeHvDwux09qwUUzF/YLfom8N.ZPBey'
#     color='ffffff'

#     addUser = ("INSERT INTO devices"
#                 "(id, password, color)"
#                 "VALUES (%s, %s, %s)" )
#     userData = (id, password, color)
#     cursor.execute(addUser, userData)

#petla logs

starttime=0
trwa=True

f = open("boty_name.txt")

while trwa:
    for i in range (1500):
        id = f.readline()
        print (id)
        #LOSOWANIE
        piTemp = 0
        pm1 = random.randint(10, 40)
        pm25 = random.randint(10, 40)
        pm10 = random.randint(10, 300)
        humidity = random.randint(10, 90)
        temp = random.randint(16, 26)
        pressure = random.randint(980, 1040)
        #piTemp = os.system("vcgencmd measure_temp")
        print(pm1, pm25, pm10, humidity, temp, pressure, id)
        addLogs = ("INSERT INTO logs"
                "(id, error, pm1, pm25, pm10, humidity, temp, pressure)"
                "VALUES (%s, %s, %s, %s, %s, %s, %s, %s)" )
        logsData = (id, piTemp, pm1, pm25, pm10, humidity, temp, pressure)
        cursor.execute(addLogs, logsData)
        #time.sleep(0.01 - ((time.time() - starttime) % 0.01))
        cnx.commit()
    f.seek(0)


#cnx.commit()
cursor.close()
cnx.close() 
