

public class WeatherStationWrapper {

    public WeatherStation weatherStation = null;
    public int size; // The amount of times new data has been added this hour

    public WeatherStationWrapper() {
        weatherStation = new WeatherStation();
        size = 0;
    }

    public void Add(WeatherStation ws) {
        size++;
        weatherStation.temperature = weatherStation.temperature + ((ws.temperature- weatherStation.temperature) / size);
        weatherStation.dewPoint = weatherStation.dewPoint + ((ws.dewPoint- weatherStation.dewPoint) / size);
        weatherStation.rainfall = weatherStation.rainfall + ((ws.rainfall- weatherStation.rainfall) / size);
    }

    public void Reset() {
        size = 0;
        weatherStation.temperature = 0;
        weatherStation.dewPoint = 0;
        weatherStation.rainfall = 0;
    }

}
