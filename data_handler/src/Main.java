import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;

public class Main {

    public static void main(String[] args) {
        System.out.println("Waiting for clients");

        DataHandlerServer dhs = new DataHandlerServer(7790);
        dhs.run();

    }
}
