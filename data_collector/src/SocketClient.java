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

    public void OpenSocket(){
        try {
            socket = new Socket(socket_host, socket_port);
            socket_out = new PrintWriter(socket.getOutputStream(), true);
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void SendData(String data){
            socket_out.println(data);
    }

    public void CloseSocket(){
        try{
            socket.close();
        }catch(IOException e){
            e.printStackTrace();
        }
    }
}
