import json

countries_list = open('./european_countries.txt')
station_data = open('./stations.json')

stations_europa_japan = open('./stations_europa_japan.txt', 'w+')
stations_europa = open('./stations_europa.txt', 'w+')

countries = countries_list.read().upper().strip().split('\n')
stations = json.loads(station_data.read())

print(countries)

for station in stations[2]['data']:
	stn_nr = int(station['stn'])
	stn_name = station['name']
	stn_country = station['country']
	stn_latitude = station['latitude']
	stn_longitude = station['longitude']

	if stn_country in countries:
		stn_data = f'{stn_nr:06d};{stn_name};{stn_country};{stn_longitude};{stn_latitude}'
		stations_europa_japan.write(stn_data + '\n')
		if stn_country == 'JAPAN':
			pass
		else:
			stations_europa.write(stn_data + '\n')