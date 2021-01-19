import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.ServerSocket;
import java.net.Socket;

public class DataCollector {

    private ServerSocket ss;

    public DataCollector(int port) {
        System.out.println("Waiting for clients");

        // Create the serversocket
        ss = null;
        try {
            ss = new ServerSocket(port);
        }
        catch (IOException e) {
            System.out.println("IOException in serversocket: " + e);
            return;
        }
    }

    public void Listen() {
        while (true) {
            try {
                // Wait for a connection
                Socket soc = ss.accept();
                System.out.println("Client connected");
                // Read all data from the client
                BufferedReader in = new BufferedReader(new InputStreamReader(soc.getInputStream()));
                String line = null;
                while ((line = in.readLine()) != null) {
                    line = line.trim(); //  Removes all whitespaces
                    String data = line.substring(1, 5);
                    switch (data) {
                        case "STN>": // Station number
                            System.out.println("Station: " + ExtractValue(line.substring(5)));
                            break;
                        case "TEMP": // Temperatuur in ceclius, can range from -9999.9 to 9999.9 with 1 decimal
                            System.out.println("Temperature: " + ExtractValue(line.substring(6)));
                            break;
                        case "DEWP": // Dew point in celcius, can range from -9999.9 to 9999.9 with 1 decimal
                            System.out.println("Dew point: " + ExtractValue(line.substring(6)));
                            break;
                        case "PRCP": // Rainfaill in centimeters, can range from 0.00 to 999.99 with 2 decimal
                            System.out.println("Rainfall cm: " + ExtractValue(line.substring(6)));
                            break;
                    }
                }
            }
            catch (IOException e) {
                System.out.println("Error in socket creation");
            }
        }
    }

    private String ExtractValue(String data) {
        String newData = "";
        int i = 0;
        char c = 0;
        while ((c = data.charAt(i)) != '<') {
            newData += c;
            i++;
        }

        return newData;
    }
}
