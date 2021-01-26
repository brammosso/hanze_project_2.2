from datetime import date

class dataParser:
    def __init__(self, input_file, output_file):
        self.input_file = input_file
        self.output_file = output_file

    def readData(self):
        dataDict = {}
        for lines in self.input_file:
            timeStamp=lines[0:8]
            stationNumber=lines[8:14]
            humidity=float(lines[14:])
            if stationNumber not in dataDict.keys():
                dataDict[stationNumber] = [(humidity,timeStamp)]
            else:
                dataDict[stationNumber].append((humidity,timeStamp))

        # Remove all the data in the file because we no longer need it
        self.input_file.seek(0)
        self.input_file.truncate()

        return dataDict

    def calcPeakValue(self):
        dataDict = self.readData()
        for station, data in dataDict.items():
            peakValue = data[0]
            for d in data:
                if d[0] > peakValue[0]:
                    peakValue = d
            dataformat = peakValue[1] + str(station) + f'{peakValue[0]:06.2f}' + "\n"
            self.output_file.write(dataformat)
            

            
def removeOld():
    # Remove any data from humidity_peak_values.txt that is older then 28 days.
    today = date.today()
    humidityPeakValuesFile = open("humidity_peak_values.txt", "r+")
    saveLines = []
    for lines in humidityPeakValuesFile:
        day = lines[2:4]
        month = lines[4:6]
        year = "20" + lines[6:8]
        if (today - date(int(year), int(month), int(day))).days <= 28:
            saveLines.append(lines)
    # Clear the file
    humidityPeakValuesFile.seek(0)
    humidityPeakValuesFile.truncate()
    # Write all the saved lines back
    humidityPeakValuesFile = open("humidity_peak_values.txt", "r+")
    for line in saveLines:
        humidityPeakValuesFile.write(line)

removeOld()

humidityFile = open("humidity.txt", "r+") # Open in read/write mode
humidityPeakValuesFile = open("humidity_peak_values.txt", "a+") # Open in append mode

parser = dataParser(humidityFile, humidityPeakValuesFile)
parser.calcPeakValue()


def createTop10():
    humidityPeakValuesFile = open("humidity_peak_values.txt", "r+")
    top10 = []
    for lines in humidityPeakValuesFile:
        data=lines[0:14]
        humidity=float(lines[14:])
        # Get the first 10 values from the humidity_peak_values.txt file and sort them
        if len(top10) < 10:
            top10.append((data, humidity))
            top10.sort(key=lambda tup: tup[1])
            continue
        # Check if the current value is greather then any of the top10 values and replace them
        i = -1
        for index in range(10):
            if humidity > top10[index][1]:
                i = index
        if i != -1:
            top10[i] = (data, humidity)

    # Write the top10 to the humidity_top10.txt
    humidityTop10File = open("humidity_top10.txt", "w+")
    for data in top10:
        humidityTop10File.write(data[0] + f'{data[1]:06.2f}' + '\n')

createTop10()