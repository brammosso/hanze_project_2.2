import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.ServerSocket;
import java.net.Socket;

public class DataHandlerServer {

    private ServerSocket ss = null;
    private Thread thread = null;
    private FolderRemover fr = null;

    public DataHandlerServer(int port) {
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
                System.out.println("Connection established");
                BufferedReader in = new BufferedReader(new InputStreamReader(soc.getInputStream()));
                String line = null;
                while ((line = in.readLine()) != null) {
                    System.out.println(line);
                    DataHandlerThread dht = new DataHandlerThread(line);
                    dht.start();
                }

            } catch (IOException e) {
                e.printStackTrace();
            }

        }
    }
}
