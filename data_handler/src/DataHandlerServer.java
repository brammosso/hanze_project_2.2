import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.ServerSocket;
import java.net.Socket;

public class DataHandlerServer {

    private ServerSocket ss = null;
    private Thread thread = null;
    private FolderRemover fr = null;

    private Temperature temperatureWriter;
    private Humidity humidityWriter;
    private Rainfall rainfallWriter;

    public DataHandlerServer(int port) {
        temperatureWriter = new Temperature();
        humidityWriter = new Humidity();
        rainfallWriter = new Rainfall();

        fr = new FolderRemover();
        fr.start();
        try {
            ss = new ServerSocket(port);
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void run() {
        while (true) {
            try {
                Socket soc = ss.accept();
                //System.out.println("Connection established");
                BufferedReader in = new BufferedReader(new InputStreamReader(soc.getInputStream()));
                String line = null;
                while ((line = in.readLine()) != null) {
                    //System.out.println(line);
                    DataHandlerThread dht = new DataHandlerThread(this, line);
                    dht.start();
                }

            } catch (IOException e) {
                e.printStackTrace();
            }

        }
    }

    public void WriteData(String year, String month, String day, String hour, String nr, String temperature, String humidity, String rainfall) {
        temperatureWriter.Write(hour, day, month, year, nr, temperature);
        humidityWriter.Write(hour, day, month, year, nr, humidity);
        rainfallWriter.Write(hour, day, month, year, nr, rainfall);
    }
}
