import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;

public class DataCollector {

    private WeatherData weatherData;
    private ServerSocket ss;

    public DataCollector(String host, int port) {
        // Initialize and start the weatherData
        weatherData = new WeatherData(host);
        weatherData.start();
        // Create the serversocket
        System.out.println("[+] Data collector started on " + host + ":" + port);
        ss = null;
        try {
            ss = new ServerSocket(port);
            System.out.println("[+] Succesfully created socket object");
        }
        catch (IOException e) {
            System.out.println("[x] IOException during socket object creation: " + e);
        }
    }

    public void Listen() {
        while (true) {
            try {
                // Wait for a connection
                Socket soc = ss.accept();
                
                // command line logging
                System.out.println("[+] Accepted a connection");

                // Create a new weatherstation and add the socket as a parameter
                WeatherThread wt = new WeatherThread(soc, weatherData);
                wt.start();
            }
            catch (IOException e) {
                System.out.println("[x] IOException during socket accepting: " + e);
            }
        }
    }
}
