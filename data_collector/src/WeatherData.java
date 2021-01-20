import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.HashMap;

public class WeatherData extends Thread {

    private DateTimeFormatter dtf;
    private LocalDateTime ldt;
    private byte year;
    private byte month;
    private byte day;
    private byte hour;
    private byte minute;
    private byte second;

    private HashMap<Integer, WeatherStationWrapper> data;

    private Thread thread = null;

    public WeatherData() {
        data = new HashMap<Integer, WeatherStationWrapper>();
        dtf = DateTimeFormatter.ofPattern("yyyy/MM/dd HH:mm:ss");
        GetCurrentTime();
    }

    public void start() {
        System.out.println("Thread starting");
        if (thread == null) {
            thread = new Thread(this);
            thread.start();
        }
    }

    public void run() {
        while (true) {
            try {
                Thread.sleep(1000*(((59-minute)*60)+(60-second)));

                // TODO: Send the data to the data handler

                GetCurrentTime();
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
        }
    }

    public void AddData(WeatherStation ws) {
        WeatherStationWrapper wsw = null;
        if ((wsw = data.get(ws.id)) == null) {
            wsw = new WeatherStationWrapper();
            data.put(ws.id, wsw);
        }
        wsw.Add(ws);
    }

    private void GetCurrentTime() {
        ldt = LocalDateTime.now();
        year = Byte.parseByte(Integer.toString(ldt.getYear()).substring(2, 4));
        month = Byte.parseByte(Integer.toString(ldt.getMonthValue()));
        day = Byte.parseByte(Integer.toString(ldt.getDayOfMonth()));
        hour = Byte.parseByte(Integer.toString(ldt.getHour()));
        minute = Byte.parseByte(Integer.toString(ldt.getMinute()));
        second = Byte.parseByte(Integer.toString(ldt.getSecond()));
        System.out.println("Year: " + year);
        System.out.println("month: " + month);
        System.out.println("day: " + day);
        System.out.println("hour: " + hour);
        System.out.println("minute: " + minute);
        System.out.println("second: " + second);
    }
}
