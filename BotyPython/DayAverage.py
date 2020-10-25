from apscheduler.schedulers.background import BackgroundScheduler
from apscheduler.schedulers.blocking import BlockingScheduler
import mysql.connector
from mysql.connector import errorcode
import datetime
import os

sched = BlockingScheduler()


#LACZENIE SIE Z BAZA DANYCH
try:
    cnx = mysql.connector.connect(user = "zephyrior", password = "6bny2B0&", host = "szkolnewybory.pl", database = "admin_zephyrior")
except mysql.connector.Error as err:
    print(err)

#USTAWIANIE DAT
#start
start = datetime.datetime.now().strftime('%Y-%m-%d 00:00:00')
#end
end = datetime.datetime.now().strftime('%Y-%m-%d 23:59:59')
date = datetime.date.today()
print (start)
print (end)




#TWORZENIE POLACZENIA
cursor = cnx.cursor(buffered=True)
print (cnx.is_connected())

#POBIERANIE ID Z BAZY DANYCH Z TABELI DEVICES
query2 = ("SELECT id FROM devices")
cursor.execute(query2)

records2 = cursor.fetchmany(size=1)
print (cursor.rowcount)

def Srednia(start, end, cursor, records2, date):
    try:
        cnx = mysql.connector.connect(user = "zephyrior", password = "6bny2B0&", host = "szkolnewybory.pl", database = "admin_zephyrior")
    except mysql.connector.Error as err:
        print(err)
    cursor = cnx.cursor(buffered=True)
    
    #WYKONA SIE TYLE RAZY ILE JEST URZADZEN W TABELI DEVICES
    for row2 in records2:
        
        #ZEROWANIE SREDNICH
        Averagepm1=0
        Averagepm25=0
        Averagepm10=0
        AverageHumidity=0
        AverageTemp=0
        AveragePressure=0
        count = 0

        #USTAWIANIE NAZWY (ID) URZADZENIA
        device_id = row2
        device_id = str(device_id)
        print (device_id)
        device_id = device_id.strip("(")
        device_id = device_id.strip(")")
        device_id = device_id.strip(",")
        device_id = device_id.strip("'")
        print (device_id)
        
        #POBIERANIE DANYCH DLA DANEGO URZADZENIA
        query1 = ("SELECT id, date, error, pm1, pm25, pm10, humidity, temp, pressure FROM `logs` WHERE `id` = %s AND `date` > %s AND `date` < %s")
        cursor.execute(query1, (device_id, start, end))

        records1 = cursor.fetchall()
        print (cursor.rowcount)

        #JEZELI SA DANE DLA DANEGO URZADZENIA
        if (cursor.rowcount != 0):
            for row1 in records1:
                #ZLICZANIE WSZYSTKICH DANYCH
                Averagepm1 += row1[3]
                Averagepm25 += row1[4]
                Averagepm10 += row1[5]
                AverageHumidity += row1[6]
                AverageTemp += row1[7]
                AveragePressure += row1[8]
                count+=1

            #OBLICZANIE SREDNICH
            Averagepm1/=count
            Averagepm25/=count
            Averagepm10/=count
            AverageHumidity/=count
            AverageTemp/=count
            AveragePressure/=count

            #PRZESZYLANIE SREDNICH DO BAZY DANYCH DO TABELKI DAYS
            addAverage = ("INSERT INTO days"
                            "(id, date, pm1, pm25, pm10, humidity, temp, pressure)"
                            "VALUES (%s, %s, %s, %s, %s, %s, %s, %s)" )
            AverageData = (device_id, date, Averagepm1, Averagepm25, Averagepm10, AverageHumidity, AverageTemp, AveragePressure)
            cursor.execute(addAverage, AverageData)    
            cnx.commit()
    start = datetime.datetime.strptime(start, '%Y-%m-%d %H:%M:%S')
    start = start + datetime.timedelta(days=1)
    start = datetime.datetime.strftime(start, '%Y-%m-%d %H:%M:%S')

    end = datetime.datetime.strptime(end, '%Y-%m-%d %H:%M:%S')
    end = end + datetime.timedelta(days=1)
    end = datetime.datetime.strftime(end, '%Y-%m-%d %H:%M:%S')
    print (start)
    print (end)

sched.add_job(Srednia, trigger='cron', day='*/1', args=[start, end, cursor, records2, date])
sched.start()

cursor.close()
cnx.close()