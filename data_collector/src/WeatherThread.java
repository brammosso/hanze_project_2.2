import javax.xml.crypto.Data;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.Socket;

public class WeatherThread extends Thread {

    private Socket socket = null;
    private Thread thread = null;
    private WeatherData weatherData = null;

    public WeatherThread(Socket s, WeatherData wd) {
        socket = s;
        weatherData = wd;
    }

    public void run() {
        System.out.println("Thread running");
        while (true) {
            try {
                // Read all data from the client
                BufferedReader in = new BufferedReader(new InputStreamReader(socket.getInputStream()));
                String line = null;
                String data = "";
                WeatherStation ws = new WeatherStation();
                while ((line = in.readLine()) != null) {
                    line = line.trim(); //  Removes all whitespaces
                    data = line.substring(1, 5); // Get the tag of the data
                    switch (data) {
                        case "STN>": // Station number, goes up to a million
                            data = ExtractValue(line.substring(5));
                            // If it is invalid data set it to -128
                            if (data.equals("")) {
                                ws.id = -128;
                                continue;
                            }
                            ws.id = Integer.parseInt(data);
                            //System.out.println("Station: " + data);
                            break;
                        case "TEMP": // Temperatuur in ceclius, can range from -9999.9 to 9999.9 with 1 decimal
                            data = ExtractValue(line.substring(6));
                            if (data.equals("")) {
                                ws.temperature = -128;
                                continue;
                            }
                            ws.temperature = Float.parseFloat(data);
                            //System.out.println("Temperature: " + data);
                            break;
                        case "DEWP": // Dew point in celcius, can range from -9999.9 to 9999.9 with 1 decimal
                            data = ExtractValue(line.substring(6));
                            if (data.equals("")) {
                                ws.dewPoint = -128;
                                continue;
                            }
                            ws.dewPoint = Float.parseFloat(data);
                            //System.out.println("Dew point: " + data);
                            break;
                        case "PRCP": // Rainfaill in centimeters, can range from 0.00 to 999.99 with 2 decimal
                            data = ExtractValue(line.substring(6));
                            if (data.equals("") || ws.id == -128 || ws.temperature == -128 || ws.dewPoint == -128) {
                                continue;
                            }
                            ws.rainfall = Float.parseFloat(data);
                            //System.out.println("Rainfall cm: " + data);
                            weatherData.AddData(ws);
                            break;
                    }
                }
            }
            catch (IOException e) {
                System.out.println("Error in reading the data from the client");
            }
        }
    }

    public void start() {
        System.out.println("Thread starting");
        if (thread == null) {
            thread = new Thread(this);
            thread.start();
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
