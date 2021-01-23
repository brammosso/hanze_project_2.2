import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;

public class DataCollector {

    private WeatherData weatherData;
    private ServerSocket ss;

    public DataCollector(int port) {
        // Initialize and start the weatherData
        weatherData = new WeatherData();
        weatherData.start();
        // Create the serversocket
        System.out.println("Data collector started");
        ss = null;
        try {
            ss = new ServerSocket(port);
        }
        catch (IOException e) {
            System.out.println("IOException in serversocket: " + e);
        }
    }

    public void Listen() {
        while (true) {
            try {
                // Wait for a connection
                Socket soc = ss.accept();
                // Create a new weatherstation and add the socket as a parameter
                WeatherThread wt = new WeatherThread(soc, weatherData);
                wt.start();
            }
            catch (IOException e) {
                System.out.println("Error in socket creation");
            }
        }
    }
}
