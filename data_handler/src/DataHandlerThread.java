
public class DataHandlerThread extends Thread {

    private Thread thread = null;
    private DataHandlerServer dhs;
    private String data = null;

    public DataHandlerThread(DataHandlerServer dhs, String data) {
        this.dhs = dhs;
        this.data = data;
    }

    public void run() {
        String year = data.substring(0, 2);
        String month = data.substring(2, 4);
        String day = data.substring(4, 6);
        String hour = data.substring(6, 8);
        String nr = data.substring(8, 14);
        String temperature = data.substring(14, 19);
        String humidity = data.substring(19, 25);
        String rainfall = data.substring(25, 31);
        dhs.WriteData(year, month, day, hour, nr, temperature, humidity, rainfall);
    }

    public void start() {
        if (thread == null) {
            thread = new Thread(this);
            thread.start();
        }
    }

}
