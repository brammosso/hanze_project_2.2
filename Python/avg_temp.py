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

            


temperatureFile = open("temperatures.txt", "r")
averageFile = open("average_temperatures.txt", "w+")

parser = dataParser(temperatureFile, averageFile)
parser.calcAvg()