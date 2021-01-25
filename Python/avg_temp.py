file = open("temperatures.txt", "r")
#print(file.read())
averageFile = open("average_temperatures.txt", "w+")


def readData(file):
    dataDict = {}
    for lines in file:
        time=lines[0:2]
        date=lines[2:8]
        stationNumber=lines[8:14]
        temperature=float(lines[14:19])

        if stationNumber not in dataDict.keys():
            dataDict[stationNumber] = [temperature,1,date]
        else:
            dataDict[stationNumber][0] = dataDict[stationNumber][0] + temperature
            dataDict[stationNumber][1] = dataDict[stationNumber][1] + 1   
    return dataDict


def calcAvg(dataDict):
    for station,data in dataDict:
        average = data[0]/data[1]
        dataformat=data[2] + station
        averageFile.write
        


print(readData(file))

