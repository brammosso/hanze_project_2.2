

public class DataHandlerThread extends Thread {

    private Thread thread = null;
    private String data = null;

    public DataHandlerThread(String data) {
        this.data = data;
    }

    public void run() {

    }

    public void start() {
        System.out.println("Thread starting");
        if (thread == null) {
            thread = new Thread(this);
            thread.start();
        }
    }

}
