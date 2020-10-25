from apscheduler.schedulers.background import BackgroundScheduler
from apscheduler.schedulers.blocking import BlockingScheduler
from apscheduler.events import EVENT_JOB_EXECUTED, EVENT_JOB_ERROR, JobExecutionEvent
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


#TWORZENIE POLACZENIA
cursor = cnx.cursor(buffered=True)
print (cnx.is_connected())

#POBIERANIE ID Z BAZY DANYCH Z TABELI DEVICES
query2 = ("SELECT id FROM devices")
cursor.execute(query2)

records2 = cursor.fetchmany(size=1)
print (cursor.rowcount)

def Srednia(cursor, records2):
    try:
        cnx = mysql.connector.connect(user = "zephyrior", password = "6bny2B0&", host = "szkolnewybory.pl", database = "admin_zephyrior")
    except mysql.connector.Error as err:
        print(err)
    cursor = cnx.cursor(buffered=True)
    
    #start
    start = datetime.datetime.now().strftime('%Y-%m-%d %H:00:00')
    start = datetime.datetime.strptime(start, '%Y-%m-%d %H:%M:%S')
    start = start - datetime.timedelta(hours=1)
    start = datetime.datetime.strftime(start, '%Y-%m-%d %H:%M:%S')
    #end
    end = datetime.datetime.now().strftime('%Y-%m-%d %H:59:59')
    end = datetime.datetime.strptime(end, '%Y-%m-%d %H:%M:%S')
    end = end - datetime.timedelta(hours=1)
    end = datetime.datetime.strftime(end, '%Y-%m-%d %H:%M:%S')
    #date
    date = datetime.datetime.now().strftime('%Y-%m-%d %H:00:00')
    date = datetime.datetime.strptime(date, '%Y-%m-%d %H:%M:%S')
    date = date - datetime.timedelta(hours=1)
    date = datetime.datetime.strftime(date, '%Y-%m-%d %H:%M:%S')
    print ('start : ' + start)
    print ('end : ' + end)
    print ('date : ' + date)

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

            #PRZESZYLANIE SREDNICH DO BAZY DANYCH DO TABELKI HOURS
            addAverage = ("INSERT INTO hours"
                            "(id, date, pm1, pm25, pm10, humidity, temp, pressure)"
                            "VALUES (%s, %s, %s, %s, %s, %s, %s, %s)" )
            AverageData = (device_id, date, Averagepm1, Averagepm25, Averagepm10, AverageHumidity, AverageTemp, AveragePressure)
            cursor.execute(addAverage, AverageData)    
            cnx.commit() 


sched.add_job(Srednia, trigger='cron', hour='*/1', args=[cursor, records2])
sched.start()


cursor.close()
cnx.close() 