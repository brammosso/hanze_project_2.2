import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.ServerSocket;
import java.net.Socket;

public class Main {

    public static void main(String[] args) {
        System.out.println("Waiting for clients");

        // Maak de serversocket met poort 7789
        ServerSocket ss = null;
        try {
            ss = new ServerSocket(7789);
        }
        catch (IOException e) {
            System.out.println("IOException in serversocket: " + e);
            return;
        }

        // Luister naar clients
        while (true) {
            try {
                // Er wordt hier gewacht op een connectie
                Socket soc = ss.accept();
                System.out.println("Client connected");
                // Lees alle data van de client
                BufferedReader in = new BufferedReader(new InputStreamReader(soc.getInputStream()));
                String line = null;
                while ((line = in.readLine()) != null) {
                    System.out.println("Client message: " + line);
                }
            }
            catch (Exception e) {
                System.out.println("Error in socket creation");
            }
        }

    }
}
