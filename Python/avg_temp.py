from datetime import date

class dataParser:
    def __init__(self, input_file, output_file):
        self.input_file = input_file
        self.output_file = output_file

    def readData(self):
        dataDict = {}
        for lines in self.input_file:
            time=lines[0:2]
            date=lines[2:8]
            stationNumber=lines[8:14]
            temperature=float(lines[14:19])

            if stationNumber not in dataDict.keys():
                dataDict[stationNumber] = [temperature,1,date]
            else:
                dataDict[stationNumber][0] = dataDict[stationNumber][0] + temperature
                dataDict[stationNumber][1] = dataDict[stationNumber][1] + 1

        # Remove all the data in the file because we no longer need it
        self.input_file.seek(0)
        self.input_file.truncate()

        return dataDict

    def calcAvg(self):
        dataDict = self.readData()
        for station, data in dataDict.items():
            # calculate and format the averages
            # total length of 6 characters, 2 decimals
            average = f'{data[0]/data[1]:+06.2f}'

            # create formatted string
            dataformat= data[2] + station + average

            self.output_file.write(dataformat + '\n')

            
def removeOld():
    # Remove any data from average_temperatures.txt that is older then 2 days.
    today = date.today()
    averageFile = open("average_temperatures.txt", "r+")
    saveLines = []
    for lines in averageFile:
        day = lines[0:2]
        month = lines[2:4]
        year = "20" + lines[4:6]
        if (today - date(int(year), int(month), int(day))).days <= 2:
            saveLines.append(lines)
    # Clear the file
    averageFile.seek(0)
    averageFile.truncate()
    # Write all the saved lines back
    averageFile = open("average_temperatures.txt", "r+")
    for line in saveLines:
        averageFile.write(line)

removeOld()

temperatureFile = open("temperatures.txt", "r+") # Open in read/write mode
averageFile = open("average_temperatures.txt", "a+") # Open in append mode

parser = dataParser(temperatureFile, averageFile)
parser.calcAvg()


def validStations():
    dataDict = {}
    averageFile = open("average_temperatures.txt", "r+")
    # Place the average_temperature.txt inside of a dictionary
    for lines in averageFile:
        stationNumber=lines[6:12]
        temperature=float(lines[12:])
        if stationNumber not in dataDict.keys():
            dataDict[stationNumber] = [temperature,1]
        else:
            dataDict[stationNumber][0] = dataDict[stationNumber][0] + temperature
            dataDict[stationNumber][1] = dataDict[stationNumber][1] + 1
    # Write all station numbers to valid_stations.txt that are under 13.9 degrees Celsius
    validStationFile = open("valid_stations.txt", "w+")
    for station, data in dataDict.items():
        average = data[0]/data[1]
        if average < 13.9:
            validStationFile.write("" + station + '\n')

validStations()