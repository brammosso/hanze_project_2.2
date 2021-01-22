import java.net.Socket;
import java.io.PrintWriter;
import java.io.IOException;

public class SocketClient {
    private String socket_host;
    private Integer socket_port;
    private PrintWriter socket_out;
    private Socket socket;

    public SocketClient(String HOST, Integer PORT){
        socket_host = HOST;
        socket_port = PORT;
    }

    private void OpenSocket(){
        try {
            socket = new Socket(socket_host, socket_port);
            socket_out = new PrintWriter(socket.getOutputStream(), true);
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void SendData(String data) throws IOException{
        this.OpenSocket();
        socket_out.println(data);
        socket.close();
    }
}
